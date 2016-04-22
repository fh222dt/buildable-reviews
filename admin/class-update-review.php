<?php
/**
 *	Update review
 */
 require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

class BR_update_review {


    function br_update_review() {
        global $wpdb;
        $sql = new BR_SQL_Quieries();

        if(isset($_GET['review-id'])) {        //TODO: do i need post??
            $review_id = $_GET['review-id'];
        }
        else if(isset($_POST['review-id'])) {
            $review_id = $_POST['review-id'];
        }
        //echo $review_id;
        $answers = $sql->get_review_answers($review_id);

        foreach ($answers as $answer) {
            $answer['answer'] = !empty($_POST['answer-id-'. $answer['answer_id']]);

            //$print_r($answer);

            $wpdb->update($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_ANSWER, array('answer' => $answer['answer']),
            array('answer_id' => $answer['answer_id']));         //TODO: kankse ange datatyper?
        }
        //wp_redirect( 'admin.php?page=buildable-reviews' );
        //TODO: update status
    }




}