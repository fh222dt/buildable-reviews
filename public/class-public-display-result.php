<?php
/**
 * This is were the review result is displayed
 */

require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-buildable-reviews-admin.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/partials/class-result-answer-templates.php' );

class BR_public_display_result {            //TODO footer area
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


        $output = '<div class="br-review">
            <h3>Betyg '. $score .'</h3>
            <div class="br-display-question">';

        foreach ($answers as $answer) {
            $output .= $display->render_answer($answer);

        }

        $output.='</div>
            <div class="review-footer">
                <p>Lämnad datum '.date_format($date, 'Y-m-d').'</p>
                <a href="#">Anmäl till granskning</a>
            </div>
        </div>';

        return $output;
    }
    /**
     * Returns a list of each individual review of an object
     * @param  [int] $object_id
     * @return [string]
     */
    function br_review_object_list() {
        $sql = new BR_SQL_Quieries();
        $object_id = get_the_ID();
        $all_review_ids = $sql->get_all_review_ids($object_id);    //returns all ids that has status = godkänd



        //TODO pagination
        $output = '';
        if(!empty($all_review_ids)) {
            foreach ($all_review_ids as $review) {
        		$output .= BR_public_display_result::br_review_single($review['review_id']);
        	}
        }
        else {                //TODO link
            $output =
            '<div class="no-reviews-yet">
            <p>Det finns inga recensioer ännu. Jobbar du här eller har gjort? <a href="#">
                Lämna en recension så andra kan läsa om den här arbetsgivaren</a></p>
            </div>';
        }

        return $output;

    }

    function br_review_object_summary() {
        $sql = new BR_SQL_Quieries();
        $object_id = get_the_ID();
        $min_no_of_reviews = get_option('br_summarize_min');        //minimum from setting
        $all_review_ids = $sql->get_all_review_ids($object_id);    //returns all reviews that has status = godkänd

        if(count($all_review_ids) >= $min_no_of_reviews) {
            $total_score = Buildable_reviews_admin::get_total_score_of_object($object_id);    //returns float
            $no_of_reviews= count($all_review_ids);
            //$summarized_questions = BR_public_display_result::summarize_question($object_id, $all_review_ids);
            $all_questions = array_map('intval', explode(',', get_option('br_question_order'))); //all q:s that is in the form

            $output = '<div class="br-review">
                <h3>Samlat betyg'. $total_score .'</h3><p>'. $no_of_reviews .' recensioner</p>
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
    //hämta alla svar per fråga
    //räkna ut medel per fråga
    //returnera ett htmlkod svar
    static function summarize_question($question, $object_id) {
        $sql = new BR_SQL_Quieries();
        $display = new BR_result_answer_templates();
        $all_answers = $sql->get_all_answers_to_question($question, $object_id);

        $output = '<h4>'. esc_attr($all_answers[0]['question_name']) .'</h4>';
        $no_of_answers = count($all_answers);

        //TODO benefits summary
        if($all_answers[0]['question_type_name'] === 'Benefits') {    //show benefit if all answers has it

        }
        if($all_answers[0]['question_type_name'] === 'Textfield') {    //display 3 random answers

            $i = 1;
            do {
                $rand = rand(0, $no_of_answers-1);
                $output .= '<p>'.$all_answers[$rand]['answer'] .'</p>';
                $i++;
            } while ($i <= 3);
        }

        if($all_answers[0]['question_type_name'] === 'Scale') {        //display average

            $i = 0;
            foreach ($all_answers as $answer) {
                $i += (int)$answer['answer'];
            }
            $sum = $i / $no_of_answers;
            //$all_answers[0]['answer'] = $sum;

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
                $output .= '<p>'.$option['name'].' '.$percentage.'%</p>';
            }
        }

        return $output;
    }

}