<?php

class BR_SQL_Quieries {
    /**
     * Get all reviews
     */
     public static function get_reviews($per_page = 25, $page_number = 1) {
         global $wpdb;
    //$sql_review_status_table = 'CREATE TABLE '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS . ' (



         $sql = 'SELECT R.review_id, R.created_at, R.updated_at, S.status_name, U.user_email AS user FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW.' R';
         $sql .= 'LEFT JOIN '.$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS.' S ON R.status_id = S.status_id';
         $sql .= 'LEFT JOIN '.$wpdb->prefix . 'users U ON R.user_id = U.ID';

         if ( ! empty( $_REQUEST['orderby'] ) ) {
             $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
             $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
         }

         $sql .= " LIMIT $per_page";

         $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


         $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }

    /**
     * Delete a review TODO implement soft del?
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
     public static function delete_review( $id ) {
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW,
            [ 'ID' => $id ],
            [ '%d' ]
        );
    }

    /**
    * Returns the count of records in the database.
    *
    * @return null|string
    */
    public static function record_count() {
        global $wpdb;

        $sql = 'SELECT COUNT(*) FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW;

        return $wpdb->get_var($sql);
    }
}

