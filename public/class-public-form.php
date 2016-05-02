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

        $usable_questions = $sql->get_all_questions();                      //question_id, question_name, question_desc, question_type_name
        $question_options = $sql->get_all_answer_option_relations();        //question_id, name

        $array = [];
        foreach ($usable_questions as &$q) {

            foreach ($question_options as &$option) {

                if($q['question_id'] == $option['question_id']) {

                    $name = $option['name'];
                    array_push($array, $name);
                }
            }
            $options = ['options' => $array];
            $q = $q + $options;
            $array = [];

        }

        // $benefits = get_terms(array(
        //     'taxonomy' => 'benefit',
        //     'orderby' => '',
        //     'hide_empty' => 'false',
        //     'meta_value' => '',
        // ));

        $form = '<h2>Lämna din recension</h2>';

        $output;
        foreach ($usable_questions as $question) {
            $output.= $question_templates->render_question($question);
        }

        $form .= $output;

        return $form;

    }
}
