<?php
/**
 * This is were the review result is displayed
 */

require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

class BR_public_display_result {

    function br_review_single($review_id) {    //one review by review id
        $sql = new BR_SQL_Quieries();
        $review = $sql->get_all_review_ids($review_id);

        '<div class="br-review">
            <h3></h3>
            <div class="br-display-question">
                <h4>Frågans namn</h4>
                <div>Resultat, olika beroende på fråge-typ</div>
            </div>
            <div class="review-footer">
                <p>Lämnad datum</p>
                <div>Voting</div>
                <a href="#">Anmäl till granskning</a>
            </div>
        </div>'

        return $output;
    }
    /**
     * Returns a list of each individual review of an object
     * @param  [int] $object_id
     * @return [string]
     */
    function br_review_object_list($object_id) {    //all reviews by object
        $sql = new BR_SQL_Quieries();
        $all_review_ids = $sql->get_all_review_ids($object_id);

        //TODO pagination
        $output = '';
        foreach ($all_review_ids as $review) {
			$output .= BR_public_display_review::br_review_single($review['review_id']);
		}

        //TODO return type?
        return $output;

    }

    function br_review_object_summary($object_id) {
            //endast om antal recensioner är över x(från en setting)
    }

}