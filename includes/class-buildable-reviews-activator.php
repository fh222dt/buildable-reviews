<?php
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Buildable_reviews_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		//Set up REVIEW_STATUS table
		$sql_review_status_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS . ' (
			status_id bigint(20) NOT NULL AUTO_INCREMENT,
			status_name varchar(50) NOT NULL,
			PRIMARY KEY  (status_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_status_table);

		//Set up REVIEW_VOTE_TYPE table
		$sql_review_vote_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE_TYPE . ' (
			vote_type_id bigint(20) NOT NULL AUTO_INCREMENT,
			vote_name varchar(50) NOT NULL,
			PRIMARY KEY  (vote_type_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_vote_table);

		//Set up REVIEW_VOTE table
		$sql_review_vote_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE . ' (
			vote_id bigint(20) NOT NULL AUTO_INCREMENT,
			vote_type_id bigint(20) NOT NULL,
			PRIMARY KEY  (vote_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_vote_table);

		//cant set foregin key with dbDelta...
		$fq_review_vote_table = 'ALTER TABLE ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE . '
			ADD FOREIGN KEY (vote_type_id)
			REFERENCES ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE_TYPE . '(vote_type_id)
    		ON DELETE CASCADE';
 		$wpdb->query($fq_review_vote_table);

		//Set up REVIEW_QUESTION_TYPE table
		$sql_review_type_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_TYPE . ' (
			question_type_id bigint(20) NOT NULL AUTO_INCREMENT,
			question_type_name varchar(50) NOT NULL,
			PRIMARY KEY  (question_type_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_type_table);

		//Set up REVIEW_QUESTION_OPTION table
		$sql_review_question_option_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION . ' (
			option_id bigint(20) NOT NULL AUTO_INCREMENT,
			is_term bool NOT NULL DEFAULT false,
			option_name varchar(50) NOT NULL,
			PRIMARY KEY  (option_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_question_option_table);

		//Set up REVIEW_QUESTION_OPTION_RELATION table
		$sql_review_question_option_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION . ' (
			question_id bigint(20) NOT NULL,
			option_id bigint(20) NOT NULL
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		$wpdb->query($sql_review_question_option_table);

		//Set up REVIEW_QUESTION table
		$sql_review_question_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION . ' (
			question_id bigint(20) NOT NULL AUTO_INCREMENT,
			type_id bigint(20) NOT NULL,
			question_name varchar(150) NOT NULL,
			question_desc varchar(350),
			PRIMARY KEY  (question_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_question_table);

		//cant set foregin key with dbDelta...
		$fq_review_question_table = 'ALTER TABLE ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION . '
			ADD FOREIGN KEY (type_id)
			REFERENCES ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_TYPE . '(question_type_id)
    		ON DELETE CASCADE';
 		$wpdb->query($fq_review_question_table);

		//Set up REVIEW_COMMENT table
		$sql_review_comment_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_COMMENT . ' (
			comment_id bigint(20) NOT NULL AUTO_INCREMENT,
			comment_text varchar(500) NOT NULL,
			comment_owner bigint(20) NOT NULL,
			PRIMARY KEY  (comment_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_comment_table);

		//Set up REVIEW table
		$sql_review_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW . ' (
			review_id bigint(20) NOT NULL AUTO_INCREMENT,
			status_id bigint(20) NOT NULL DEFAULT 1,
			vote_id bigint(20),
			question_id bigint(20),
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (review_id)
		)ENGINE=InnoDB AUTO_INCREMENT=1;';
		dbDelta($sql_review_table);

		//cant set foregin key with dbDelta and only one query at a time so...
		$fq_review_table_status = 'ALTER TABLE ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW . '
			ADD FOREIGN KEY (status_id)
			REFERENCES ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS . '(status_id)
    		ON DELETE CASCADE';
 		$wpdb->query($fq_review_table_status);

		$fq_review_table_vote = 'ALTER TABLE ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW . '
			ADD FOREIGN KEY (vote_id)
			REFERENCES ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE . '(vote_id)
    		ON DELETE CASCADE';
 		$wpdb->query($fq_review_table_vote);

		$fq_review_table_question = 'ALTER TABLE ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW . '
			ADD FOREIGN KEY (question_id)
			REFERENCES ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION . '(question_id)
    		ON DELETE CASCADE';
 		$wpdb->query($fq_review_table_question);
	}


		//user role to be able to edit reviews
		//sample review???
}