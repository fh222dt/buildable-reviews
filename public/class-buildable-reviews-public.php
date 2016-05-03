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

		if (isset($_POST['action']) && $_POST['action'] == 'br_submit_review') {
			$max_lenght = 250;		//TODO: what is maxlenght??
			$answers = [];
			foreach ($_POST as $question_id => &$answer) {
				//validera
				if(empty($answer)) {		//all frågor kommer ej va obligatoriska
					//skicka ut felmeddelanden
				}
				if(strlen($answer) > $max_lenght) {
					//skicka ut felmeddelanden
				}
				//remove all non question answers from POST by only store POST-keys with question_id:s
				//Sanitize input from user & store in new array
				if(is_numeric($question_id)) {
					$answers[$question_id] = sanitize_text_field($answer);
				}


			}

			//saves review to review db table
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			$post_id = $_POST['post_id'];
			$status_id = get_option('br_standard_status'); //returns status_id from plugin settings
			$now = date("Y-m-d H:i:s");
			
			$wpdb->insert($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW,
				array('user_id' => $user_id, 'posts_id' => $post_id, 'status_id' => $status_id, 'created_at' => $now),
				array('%d', '%d', '%d', '%s'));

			//saves all answers to answers db table
			$review_id = (int)$wpdb->insert_id;

			foreach ($answers as $q => $a) {
				$wpdb->insert($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_ANSWER,
					array('review_id' => $review_id, 'question_id' => $q, 'answer' => $a),
					array('%d', '%d', '%s'));
			}

			//TODO:ge inlämningsbesked
			wp_redirect(home_url());
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