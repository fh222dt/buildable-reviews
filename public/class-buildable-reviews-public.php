<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Buildable_reviews_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $buildable_reviews    The ID of this plugin.
	 */
	private $buildable_reviews;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $buildable_reviews       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $buildable_reviews, $version ) {

		$this->buildable_reviews = $buildable_reviews;
		$this->version = $version;

	}

	public function register_shortcodes() {
		require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/class-public-form.php' );
		add_shortcode('br-review-form', array('BR_public_review_form', 'br_review_form'));
	}

	public function handle_submited_review() {
		global $wpdb;
		session_start();
		if (isset($_POST['action']) && $_POST['action'] == 'br_submit_review') {

			$error = false; 		//will be true if validation failes
			$max_lenght = 250;		// TODO: what is max lenght??
			$validated_answers = [];			// Holds all validated answers
			$all_questions = array_map('intval', explode(',', get_option('br_question_order'))); //all q:s that is in the form

			//If a q is'nt answered, add it as an empty one to $_POST. This makes shore all q:s can be
			//checked & validated properly.
			foreach ($all_questions as $id) {
				if(!array_key_exists($id, $_POST)) {
					$_POST[$id] ='';
				}
			}


			//validate all answers
			foreach ($_POST as $question_id => $answer) {
				//remove all non question answers from POST by only store POST-keys with question_id:s
				if(is_numeric($question_id)) {

					//required q can't be empty
					$required_question = Buildable_reviews_Public::is_required_question($question_id);
					if($required_question == true) {
						if(empty($answer)) {
							//TODO skicka inte form
							$error = true;

							//add error message
							$error_msg[$question_id] = 'Får inte vara tomt';
							$_SESSION['br_form_error'] = $error_msg;
						}
						else {
							//remove error message
							unset($_SESSION['br_form_error'][$question_id]);
						}
					}

					//answer has only one value
					if(!is_array($answer)) {
						//remove chars after max_lenght if to long answer
						if(strlen($answer) > $max_lenght) {
							$answer = substr($answer, 0, $max_lenght);
						}

						//Sanitize input from user & store in new array
						$validated_answers[$question_id] = sanitize_text_field($answer);
					}
					//answer has multiple values, store all of them in new array in string format
					else {
						$answer = implode(', ', $answer);
						$validated_answers[$question_id] = sanitize_text_field($answer);
					}
				}
			}

			//remove all empty answers
			foreach ($validated_answers as $question_id =>$answer) {
				if(empty($answer)) {
					unset($validated_answers[$question_id]);
				}
			}
			//save if error is false
			if($error == false) {
				echo "save";
				unset($_SESSION['br_form_error']);
				//visa skickat meddelande

			}



			//saves review to review db table
			//$current_user = wp_get_current_user();

			// if($current_user->ID != 0) {			//user is logged in
			// 	$user_id = $current_user->ID;
			// }
			//
			// else {				//new user or not logged in
			// 	if (isset($_POST['17'])) {		//q-id of epost TODO: clean up
			// 		$email = $_POST['17'];
			// 	}
			// 	$user = Buildable_reviews_Public::check_user($email);
			//
			// 	$user_id = $user;
			// // }
			//
			//
			// $post_id = $_POST['post_id'];
			// $status_id = get_option('br_standard_status'); //returns status_id from plugin settings
			// $now = date("Y-m-d H:i:s");
			//
			// $wpdb->insert($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW,
			// 	array('user_id' => $user_id, 'posts_id' => $post_id, 'status_id' => $status_id, 'created_at' => $now),
			// 	array('%d', '%d', '%d', '%s'));
			//
			// //saves all answers to answers db table
			// $review_id = (int)$wpdb->insert_id;
			//
			// foreach ($answers as $q => $a) {
			// 	$wpdb->insert($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_ANSWER,
			// 		array('review_id' => $review_id, 'question_id' => $q, 'answer' => $a),
			// 		array('%d', '%d', '%s'));
			// }
			//
			// //TODO:ge inlämningsbesked
			// wp_redirect(home_url());
		}
	}
	/**
	 *
	 * @param  int  $id
	 * @return boolean
	 */
	public function is_required_question($id) {
		$sql = new BR_SQL_Quieries();
		$result = $sql->get_question($id);

		$require = $result[0]['required'];

		return $require;
	}

	/**
	 * Checks if user already exists in db, or creates new user from email
	 * @param  [string] $email
	 * @return [int]|[string] user_id or validation error
	 */
	public function check_user($email) {
		//validate email
		if (is_email($email)) {
			$safe_email = sanitize_email($email);
		}
		else {
			return "Ogiltig epost";
		}

		if(email_exists($safe_email) === false) {		//new user is created
			// Generate the password and create the user
			$password = wp_generate_password(12, false);
			$user_id = wp_create_user($safe_email, $password, $safe_email);

			// Set the role
			$user = new WP_User($user_id);
			$user->set_role('contributor');

			// Email the user 		TODO
			//wp_mail( $email_address, 'Welcome!', 'Your Password: ' . $password );

			return $user_id;
		}
		else {				//email exists in db
			$user_id = email_exists($safe_email);

			return $user_id;
		}
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in buildable_reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The buildable_reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->buildable_reviews, plugin_dir_url( __FILE__ ) . 'css/buildable-reviews-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in buildable_reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The buildable_reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->buildable_reviews, plugin_dir_url( __FILE__ ) . 'js/buildable-reviews.js', array( 'jquery' ), $this->version, false );

	}

}