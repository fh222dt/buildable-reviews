<?php
/**
 * This is were the review-form is put together and outputed to the user
 * Use shortcode [br-review-form] to display the review-form
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/class-question-templates.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

class BR_public_review_form {

    public function br_review_form() {
        $question_templates = new BR_question_templates();
        $sql = new BR_SQL_Quieries();

        $usable_questions = $sql->get_all_questions();
        $question_options = $sql->get_all_answer_option_relations();

        print_r($question_options);
        exit;

        // $benefits = get_terms(array(
        //     'taxonomy' => 'benefit',
        //     'orderby' => '',
        //     'hide_empty' => 'false',
        //     'meta_value' => '',
        // ));

        $form = '<h2>Lämna din recension</h2>';

        $questions;
        foreach ($usable_questions as $q) {
            $questions.= $this->question_templates->render_question($q);
        }

        $form .= $questions;

        return $form;

    }
}
