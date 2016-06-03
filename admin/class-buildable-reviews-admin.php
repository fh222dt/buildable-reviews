<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Buildable_reviews_admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
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
	 * @param      string    $buildable_reviews       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $buildable_reviews, $version ) {

		$this->buildable_reviews = $buildable_reviews;
		$this->version = $version;

	}

	/**
	 * Admin menus
	 * using this solution for parameter free callback:
	 * http://wordpress.stackexchange.com/questions/16415/passing-arguments-to-a-admin-menu-page-callback
	 *
	 */


	public function add_admin_menus() {
		$views = [];

		$view_hook_name = add_menu_page(
		'Manage Reviews',
		'Reviews',
		'br_edit_reviews',
		$this->buildable_reviews,
		array($this, 'load_admin_page_content'),
		'',
		'3.99');
		$this->views[$view_hook_name] = 'buildable-reviews-admin-display';

		$view_hook_name = add_submenu_page(
		$this->buildable_reviews,
		'Add Question',
		'Add Question',
		'br_edit_reviews',
		$this->buildable_reviews.'-add-question',
		array($this, 'load_admin_page_content')
		);
		$this->views[$view_hook_name] = 'buildable-reviews-admin-add-question';

		$view_hook_name = add_submenu_page(
		$this->buildable_reviews,
		'Settings',
		'Settings',
		'br_edit_reviews',
		$this->buildable_reviews.'-settings',
		array($this, 'load_admin_page_content')
		);
		$this->views[$view_hook_name] = 'buildable-reviews-settings';

		//this page is not displayed in the menu using null as slug
		$view_hook_name = add_submenu_page(
		null,
		'Details',
		'Details',
		'br_edit_reviews',
		$this->buildable_reviews.'-details',
		array($this, 'load_admin_page_content')
		);
		$this->views[$view_hook_name] = 'buildable-reviews-admin-review-details';

		//this page is not displayed in the menu using null as slug
		$view_hook_name = add_submenu_page(
		null,
		'List all by',
		'List all by',
		'br_edit_reviews',
		$this->buildable_reviews.'-list',
		array($this, 'load_admin_page_content')
		);
		$this->views[$view_hook_name] = 'buildable-reviews-admin-list';

	}

	function br_settings_page_callback() {
		require_once plugin_dir_path( __FILE__ ). 'partials/buildable-reviews-settings.php';
	}

	//Load the view for menu item
	public function load_admin_page_content() {
		$current_views = $this->views[current_filter()];
    	require_once plugin_dir_path( __FILE__ ). 'partials/'.$current_views. '.php';
	}
	/**
	 * Takes the answer algorithm setting
	 * to set the % of total score each q should have.
	 *
	 * Anwser "yes" gives value 5
	 * Checkboxes gives total no of boxes diveded by answered ones as value
	 * Textfields doesn't count
	 *
	 * @param  [int] $id review_id
	 * @return [int]     total score ie 3.2 (between 0-5)
	 */
	public static function get_total_score_of_review($id) {
		$settings = get_option('br_question_algorithm');		//from settings  return q_id => % of score  Array ( [1] => 90 [2] => 10 [3] => 0 [4] => 0 [5] => 0 [6] => 0 [7] => 0 )
		$sql = new BR_SQL_Quieries();
		$answers = $sql->get_review_answers($id);				//returns all questions with its answers by review_id
		$total_score = 0;
		$no = 0;												//counter for no of q:s with used answer

		foreach ($answers as $answer) {
			$score = 0;
			//get the % that the answer should weight in the total score
			$answer_weight = (int)$settings[$answer['question_id']];

			//only count q:s with weight to them
			if($answer_weight > 0) {
				$no++;

				if(is_numeric($answer['answer'])) {
					$score = (int)$answer['answer'] * ($answer_weight/100);
				}
				else if($answer['answer'] === 'Ja') {
					$score = 5;
				}
				else if($answer['question_type_name'] === 'Radio' || $answer['question_type_name'] === 'Checkbox') {

					//turn answer into array
					$multiple_answers = array_map('intval', explode(',', $answer['answer']));

					 $total_answers = count($multiple_answers);
					 $total_options = count($sql->get_question_options($answer['question_id']));

					 if(is_int($total_answers) && is_int($total_options)) {
					 	$score = $total_answers / $total_options;
					 }
				}

				$total_score += $score;
			}
		}
		$total_score = $total_score / $no;
		$total_score = round( $total_score, 1, PHP_ROUND_HALF_UP);
		return $total_score;
	}

	/**
	 * Returns total score of object.
	 * @param  [int] $id [the object to be scored]
	 * @return [float]     [total score of object]
	 */
 	public static function get_total_score_of_object($id) {
		//get all reviews of a specific object
		$sql = new BR_SQL_Quieries();
		$all_review_ids = $sql->get_all_review_ids($id);

		$total_score = 0;

		foreach ($all_review_ids as $review) {
			$score = Buildable_reviews_admin::get_total_score_of_review($review['review_id']);

			$total_score += $score;
		}

		$no_of_review = count($all_review_ids);

		if($total_score == 0) {
			return $total_score;
		}

		$total_score = $total_score / $no_of_review;

		$total_score = round( $total_score, 1, PHP_ROUND_HALF_UP);

		return $total_score;
	}


	/**
	 * Updates or delete review from user
	 */
	public function br_update_review() {
        global $wpdb;
        $sql = new BR_SQL_Quieries();

        if(isset($_POST['review-id'])) {
            $review_id = $_POST['review-id'];
        }

		if(isset($_POST['delete'])) {
			$sql->delete_review($review_id);

			wp_redirect('admin.php?page=buildable-reviews');
		}

		else {

	        $answers = $sql->get_review_answers($review_id);

			//update textfield-answers of questions in db
	        foreach ($answers as $answer) {
	            if($answer['question_type_name'] == 'Textfield'){
	                $answer['answer'] = sanitize_text_field($_POST['answer-id-'. $answer['answer_id']]);

	                $wpdb->update($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_ANSWER, array('answer' => $answer['answer']),
	                array('answer_id' => $answer['answer_id']));
	            }
	        }
			//update status in db
			if(isset($_POST['status'])) {
				$status = (int)$_POST['status'];
				$wpdb->update($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW, array('status_id' => $status),
					array('review_id' => $review_id));
			}

	        wp_redirect('admin.php?page=buildable-reviews-details&review-id='.$review_id);
		}
    }

	/**
	 * Adds new question to be answered in the review
	 */
	public function br_add_new_question() {
        global $wpdb;

		//insert new question & description
        $type_id = (int)$_POST['type'];
		$question_name = sanitize_text_field($_POST['question-name']);
		$question_desc = sanitize_text_field($_POST['desciption']);
		$required = sanitize_text_field($_POST['required']);


        $wpdb->insert($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION,
			array('type_id' => $type_id, 'question_name' => $question_name,
				'question_desc' => $question_desc, 'required' => $required),
			array('%d', '%s', '%s'));

		//insert question options if any
		$options = $_POST['options'];
		$q_id = (int)$wpdb->insert_id;

		if (!empty($options)) {
			foreach ($options as $option_id) {
				$wpdb->insert($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION,
					array('question_id' => $q_id, 'option_id' => $option_id),
					array('%d', '%d'));
			}
		}
        wp_redirect('admin.php?page=buildable-reviews-settings');
    }
	/**
	 * Update a question, change q-type, is_required and so on
	 */
	public function br_update_question() {
		$type_id = (int)$_POST['type'];
		$question_name = sanitize_text_field($_POST['question-name']);
		$question_desc = sanitize_text_field($_POST['desciption']);
		$required = sanitize_text_field($_POST['required']);
		$question_id = $_POST['question-id'];

		global $wpdb;

		$wpdb->update($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION,
			array('type_id' => $type_id, 'question_name' => $question_name,
				'question_desc' => $question_desc, 'required' => $required),
			array( 'question_id' => $question_id ));

		//insert question options if any
		$options = $_POST['options'];

		if (!empty($options)) {

			$options_as_string = implode(",", $options);

			//delete all unselected options
			$delete = 'DELETE FROM '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION .
					  ' WHERE option_id NOT IN ('. $options_as_string .') AND question_id = '.$question_id;

			$wpdb->query($delete);

			foreach ($options as $option_id) {

				//insert new options
				$update = 'INSERT IGNORE INTO '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION .
						  ' (question_id, option_id ) VALUES ('. $question_id.','.$option_id.');';
				$wpdb->query($update);
			}
		}

		wp_redirect('admin.php?page=buildable-reviews-add-question&question-id='.$question_id);

	}


	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->buildable_reviews, plugin_dir_url( __FILE__ ) . 'css/buildable-reviews-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->buildable_reviews, plugin_dir_url( __FILE__ ) . 'js/buildable-reviews-admin.js', array( 'jquery' ), $this->version, false );

	}

}