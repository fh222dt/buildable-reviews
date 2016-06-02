<?php
/**
 * This is were the review-form is put together and outputed to the user
 * Use shortcode [br-review-form] to display the review-form
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/partials/class-form-question-templates.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

class BR_public_display_form {

    public function br_review_form() {
        if(isset($_POST['br-review-submit'])) {

            $output = '<h2>Tack</h2><p>Tack för din recension! Efter kontroll kommer vi snart att publicera den.</p>';
            return $output;
        }

        $question_templates = new BR_form_question_templates();
        $sql = new BR_SQL_Quieries();
        $usable_questions = $sql->get_all_questions();                      //question_id, question_name, question_desc, question_type_name
        $question_options = $sql->get_all_answer_option_relations();        //question_id, name
        $array = [];

        //prepair all questions with it's answer options
        foreach  ($usable_questions as &$q) {
            foreach ($question_options as &$option) {
                //VIP treatment for benefits question (benefits is a custom taxonomy)
                if($q['question_type_name'] == 'Benefits') {
                    $benefits = BR_public_display_form::do_benefits();
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
                <form id="br-review-form" method="post" action="">';

        //sort questions based on setting
        $order_from_setting = array_map('intval', explode(',', get_option('br_question_order'))); //from string to array
        $sorted_questions = [];        //holds q:s in sorted order
        $usable_questions = array_column($usable_questions, null, 'question_id');

        //do the sorting
        foreach ($order_from_setting as $id) {
            $sorted_questions[] = $usable_questions[$id];
        }

        //print each question
        $output ='';
        foreach ($sorted_questions as $question) {
            $output.= $question_templates->render_question($question);
        }

        //finish the form
        $form .= $output;
        $form .= '<input type="checkbox" name="policy" value="policy" required/><label>Jag godkänner villkoren</label>
                <input type="hidden" name="action" value="br_submit_review">
                <input type="hidden" name="post_id" value="'. get_the_ID().'">
                <input name="br-review-submit" value="Lämna recension" type="submit">
                </form>';
        return $form;
    }

    //add all benefits as options to the question
    public static function do_benefits() {
        $raw_benefits = get_terms(array(
            'taxonomy' => 'benefit',
            'orderby' => 'meta_value',
            'hide_empty' => false,
        ));
        $benefits = [];
        foreach ($raw_benefits as &$benefit) {
            $name = $benefit->name;
            $id = $benefit->term_id;
            $category = get_term_meta($benefit->term_id, 'benefit-category', $single = true);

            $entry = [];
            $entry['id'] = $id;
            $entry['name'] = $name;
            $entry['category'] = $category;

            array_push($benefits, $entry);
            //sort array by category for nice looking fieldsets in output
            usort($benefits, function($a, $b) {
                return $a['category'] <=> $b['category'];
            });
        }

        return $benefits;
    }
}