<?php
/**
 * This is were the review result is displayed
 */

require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-buildable-reviews-admin.php' );
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/public/partials/class-result-answer-templates.php' );

class BR_public_display_result {            //TODO footer area

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

        //print_r($answers);
        //exit;

        foreach ($answers as $answer) {
            $output .= $display->render_answer($answer);
        }

        $output.='</div>
            <div class="review-footer">
                <p>Lämnad datum '.date_format($date, 'Y-m-d').'</p>
                <div>Voting</div>
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
    function br_review_object_list() {    //all reviews by object
        $sql = new BR_SQL_Quieries();
        $object_id = get_the_ID();
        $all_review_ids = $sql->get_all_review_ids($object_id);



        //TODO pagination
        $output = '';
        if(!empty($all_review_ids)) {
            foreach ($all_review_ids as $review) {
        		$output .= BR_public_display_result::br_review_single($review['review_id']);
        	}
        }
        else {
            $output =
            '<div class="no-reviews-yet">
            <p>Det finns inga recensioer ännu. Jobbar du här eller har gjort? <a href="#">
                Lämna en recension så andra kan läsa om den här arbetsgivaren</a></p>
            </div>';
        }

        // print_r($output);
        // exit;
        //TODO return type?
        return $output;

    }

    function br_review_object_summary() {
            //endast om antal recensioner är över x(från en setting)
            //räkna ut medel & välj 3 random för textsvar
        $object_id = get_the_ID();
        $total_score =
        $no_of_reviews=
        $summarized_questions = BR_public_display_result::summarize_review($object_id);

        $output = '<div class="br-review">
            <h3>Samlat betyg'. $total_score .'</h3><p>'. $no_of_reviews .' antal recensioner</p>
            <div class="br-display-question">';
                foreach ($summarized_questions as $question) {
                    $output .= '';//question template
                }

        $output .='</div>';

        return $output;
    }

    static function summarize_review($object_id) {
        //get all answers that belongs to a object
        $sql = new BR_SQL_Quieries();
        $review_ids = $sql->get_all_review_ids($object_id);    //returns array of review_ids
        $all_answers = [];            //a_id, q_id, q_type_name, answer(kommer va alla möjliga typer)
        foreach ($review_id as $review) {
            //get answers
            $answers = $sql->get_review_answers($review);    //returns array of answers to the review id
            foreach ($answers as $answer) {
                //add to all_answers
                $all_answers['answer_id'] = $answer['answer_id'];
            }

        }
        //sortera svar efter q_id gör om arrayen så att varje fråga blir ett element o alla svar en array i den

        foreach ($all_answers as $answer) {

            if($all_answers['question_type_name'] === 'Benefits') {
            //benefits
            //hur många har kryssat varje ruta? antal eller %
            }
            if($all_answers['question_type_name'] === 'Textfield') {
            //samla ihop 3 random sv textfrågor
            }
            if($all_answers['question_type_name'] === 'Scale') {
                //1-5 visa svar i medeltal (skala) tex 3.92
            }
            if($all_answers['question_type_name'] === 'Radio') {
            //räkna ut %
            }
        }

        return; //ett object som har alla svar i form av en sammanfattning
    }

}