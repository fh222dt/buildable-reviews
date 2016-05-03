<?php
/**
 * This is were the review-form is put together and outputed to the user
 * Use shortcode [br-review-form] to display the review-form
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/class-question-templates.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

class BR_public_review_form {

    public function br_review_form() {

        if(!is_user_logged_in()){

            return '<p>Du måste vara inloggad för att kunna lämna en recension</p>';    //TODO: omformulera o styla
        }


        $question_templates = new BR_question_templates();
        $sql = new BR_SQL_Quieries();

        $usable_questions = $sql->get_all_questions();                      //question_id, question_name, question_desc, question_type_name
        $question_options = $sql->get_all_answer_option_relations();        //question_id, name

        $array = [];
        foreach ($usable_questions as &$q) {

            foreach ($question_options as &$option) {

                //ugly way of findning benefits question
                if($q['question_name'] == 'Förmåner') {
                    $benefits = BR_public_review_form::do_benefits();
                    $q['options'] = $benefits;
                }

                else if($q['question_id'] == $option['question_id']) {
                    $name = $option['name'];
                    array_push($array, $name);
                }
            }
            $options = ['options' => $array];
            $q = $q + $options;
            $array = [];
        }

        $form = '<h2>Lämna din recension</h2>
                <form method="post" action="">';

        $output;
        foreach ($usable_questions as $question) {
            $output.= $question_templates->render_question($question);
        }

        $form .= $output;
        $form .= '<input type="hidden" name="action" value="br_submit_review" />
        <input value="Lämna recension" type="submit" />
        </form>';

        return $form;

    }
    //add all benefits as options to the question
    private function do_benefits() {
        $raw_benefits = get_terms(array(
            'taxonomy' => 'benefit',
            'orderby' => 'meta_value',
            'hide_empty' => false,
            //'meta_value' => 'Försäkringar & Hälsa',        //search by category name
        ));
        $benefits = [];
        foreach ($raw_benefits as &$benefit) {
            $name = $benefit->name;
            array_push($benefits, $name);
        }
        return $benefits;
    }
}
