<?php
/**
 * This is were the review result is displayed
 */

require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-buildable-reviews-admin.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/partials/class-result-answer-templates.php' );

class BR_public_display_result {
    /**
     * Returns output for one review
     * @param  int $review_id
     * @return string
     */
    static function br_review_single($review_id) {
        $sql = new BR_SQL_Quieries();
        $answers = $sql->get_review_answers($review_id);        //returns array

        $score = Buildable_reviews_admin::get_total_score_of_review($review_id);        //return float
        $display = new BR_result_answer_templates();

        $where = 'WHERE R.review_id = '.$review_id.' ';
        $review = $sql->get_reviews(1, 1, $where);
        $date = new DateTime($review[0]['created_at']);


        $output = '<div class="br-review" data-review="'. $review_id .'">
            <h3>Betyg <span class="score-icons" data-score='. $score .'></span></h3>
            <div class="br-display-question">';

        foreach ($answers as $answer) {
            $output .= $display->render_answer($answer);

        }

        $output.='</div>
            <div class="review-footer">
                <p>Lämnad '.date_format($date, 'Y-m-d').'</p>
                <a class="br-report-review" href="#">Anmäl för granskning</a>
            </div>
        </div>';

        return $output;
    }
    /**
     * Returns a list with all individual reviews of an object
     * @param  [int] $object_id
     * @return [string]
     */
    function br_review_object_list() {
        $sql = new BR_SQL_Quieries();
        $object_id = get_the_ID();
        $all_review_ids = $sql->get_all_review_ids($object_id);    //returns all ids that has status = godkänd

        $output = '';
        if(!empty($all_review_ids)) {
            foreach ($all_review_ids as $review) {
        		$output .= BR_public_display_result::br_review_single($review['review_id']);
        	}
            $output .= '<div id="results-pagination"></div>';
        }
        else {                //TODO link
            $output =
            '<div class="no-reviews-yet">
            <p>Det finns inga recensioer ännu. Jobbar du här eller har gjort? <a href="#review-form">
                Lämna en recension så andra kan läsa om den här arbetsgivaren</a></p>
            </div>';
        }

        return $output;

    }
    /**
     * Returns the complete summary of object if minimum from setting is reached
     * @return [string]
     */
    function br_review_object_summary() {
        $sql = new BR_SQL_Quieries();
        $object_id = get_the_ID();
        $min_no_of_reviews = get_option('br_summarize_min');        //minimum from setting
        $all_review_ids = $sql->get_all_review_ids($object_id);    //returns all reviews that has status = godkänd

        if(count($all_review_ids) >= $min_no_of_reviews) {
            $total_score = Buildable_reviews_admin::get_total_score_of_object($object_id);    //returns float
            $no_of_reviews= count($all_review_ids);
            $all_questions = array_map('intval', explode(',', get_option('br_question_order'))); //all q:s that is in the form

            $output = '<div class="br-review-summary">
                <h3>Samlat betyg<span class="score-icons" data-score='. $total_score .'></span></h3><p>'. $no_of_reviews .' recensioner</p>
                <div class="br-display-question">';
                    foreach ($all_questions as $question) {
                        $output .= BR_public_display_result::summarize_question($question, $object_id);
                    }

            $output .='</div>';
        }
        else {            //TODO link
            $output = '<div class="no-reviews-yet">
            <p>Det finns inte tillräckligt många recensioner för att en sammanställning ska kunna göras. <a href="#">
                Du kan titta på alla lämna recensioner individuellt istället.</a></p>
            </div>';
        }

        return $output;
    }
    /**
     * Return output for every summarized question
     * @param  [int] $question
     * @param  [int] $object_id
     * @return [string]
     */
    static function summarize_question($question, $object_id) {
        $sql = new BR_SQL_Quieries();
        $display = new BR_result_answer_templates();

        $all_answers = $sql->get_all_answers_to_question($question, $object_id);

        $output = '<h4>'. esc_attr($all_answers[0]['question_name']) .'</h4>';

        $no_of_answers = count($all_answers);

        if($all_answers[0]['question_type_name'] === 'Benefits') {    //shows benefit if all answers has it
            $output .= '<p>Visar förmåner som alla svarande erbjuds</p>';

            $display_form = new BR_public_display_form();

            //add each benefit from answers to one array
            $answered_benefits =[];
            foreach ($all_answers as $answer) {
                $answer = explode(', ', $answer['answer']);
                $answered_benefits = array_merge($answered_benefits, $answer);

            }
            //all benefits from database
            $all_benefits = $display_form->do_benefits();        //benefits => id, name, category
            //var for faking answer
            $sum_answer = '';

            //if all answers has checked a benefit, it is considered as a 'offered benefit'
            $counted = array_count_values($answered_benefits);
            foreach ($all_benefits as $benefit) {
                $term_id = 'term_id '.$benefit['id'];

                if($counted[$term_id] == $no_of_answers) {
                     $sum_answer .= $term_id.', ';    //faking a answer-string
                }
            }
            $render['answer'] = $sum_answer;    //faking a answer array

            $output .= $display->render_benefits($render);
        }

        if($all_answers[0]['question_type_name'] === 'Textfield') {    //display 3 random answers

            $i = 1;
            do {
                $rand = rand(0, $no_of_answers-1);
                $output .= '<p>'.esc_attr($all_answers[$rand]['answer']) .'</p>';
                $i++;
            } while ($i <= 3);
        }

        if($all_answers[0]['question_type_name'] === 'Scale') {        //display average

            $i = 0;
            foreach ($all_answers as $answer) {
                $i += (int)$answer['answer'];
            }
            $sum = $i / $no_of_answers;

            $output .= '<p>Genomsnitt '. $sum .' av 5</p>';
        }

        if($all_answers[0]['question_type_name'] === 'Radio') {        //display each option with %

            $all_options = $sql->get_question_options($question);

            foreach  ($all_options as $option) {
                $points = 0;
                foreach ($all_answers as $answer) {
                    if($answer['answer'] === $option['name']) {
                        $points++;
                    }
                }
                $percentage = ($points / $no_of_answers) * 100;
                $output .= '<p>'.esc_attr($option['name']).' '.$percentage.'%</p>';
            }
        }

        return $output;
    }

}