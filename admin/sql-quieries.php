<?php

class BR_SQL_Quieries {
    /**
     * Get all reviews
     */
     public static function get_reviews($per_page = 25, $page_number = 1, $where) {
         global $wpdb;

         $sql = 'SELECT R.review_id, R.created_at, R.updated_at, S.status_name, U.user_email AS user, P.ID AS employer FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW.' R ';
         $sql .= 'LEFT JOIN '.$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS. ' S ON R.status_id = S.status_id ';
         $sql .= 'LEFT JOIN '.$wpdb->prefix . 'users U ON R.user_id = U.ID ';
         $sql .= 'LEFT JOIN '.$wpdb->prefix . 'posts P ON R.posts_id = P.ID ';

         if(! empty($where)) {
             //$sql .= 'WHERE R.review_id = '.$where.' ';
             $sql .= $where;
         }


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
     public static function delete_review($id) {
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW,
            [ 'review_id' => $id ],
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

      $sql = 'SELECT COUNT(*) FROM' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW;

      return $wpdb->get_var( $sql );
    }
    /**
     * Returns all questions with its answers by review_id
     */
    public static function get_review_answers($id) {
        global $wpdb;

        $sql = 'SELECT A.answer_id, Q.question_name, A.answer, T.question_type_name, Q.question_id FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_ANSWER.' A
        LEFT JOIN xpn4_br_review_question Q
        ON A.question_id = Q.question_id
        LEFT JOIN xpn4_br_review_question_type T
        ON Q.type_id = T.question_type_id
        WHERE A.review_id = '.$id.';';

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Get all employers
     */
     public static function get_employers($per_page = 25, $page_number = 1) {
         global $wpdb;

         $sql = 'SELECT R.posts_id AS employer_id, COUNT(*) AS total_no, MAX(R.created_at) AS most_recent FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW.' R ';
         $sql .= 'GROUP BY employer_id ';


         if ( ! empty( $_REQUEST['orderby'] ) ) {
             $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
             $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
         }

         $sql .= " LIMIT $per_page";

         $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


         $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }

     public static function get_all_review_ids($id) {
         //SELECT * FROM xpn4_br_review WHERE xpn4_br_review.posts_id = 125

         global $wpdb;

         $sql = 'SELECT * FROM '. $wpdb->prefix .Buildable_reviews::TABLE_NAME_REVIEW .
                 ' WHERE '. $wpdb->prefix .Buildable_reviews::TABLE_NAME_REVIEW .'.posts_id = '. $id;

         $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }

     public static function get_all_status_names() {
         global $wpdb;

         $sql = 'SELECT * FROM '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;

     }

     public static function get_all_questions() {
         global $wpdb;

         $sql = 'SELECT Q.question_id, Q.question_name, Q.question_desc, T.question_type_name, Q.required FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION. ' Q
         LEFT JOIN xpn4_br_review_question_type T
         ON Q.type_id = T.question_type_id';

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }

     public static function get_all_question_types() {
         global $wpdb;

         $sql = 'SELECT * FROM '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_TYPE;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }

     public static function get_all_answer_options() {
         global $wpdb;

         $sql = 'SELECT * FROM '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;

     }

     public static function get_all_answer_option_relations() {
         global $wpdb;

         $sql = 'SELECT R.question_id, O.option_name AS name FROM '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION .' R
         LEFT JOIN '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION .' O
         ON R.option_id = O.option_id';

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;

     }

     public static function get_question($id) {
         global $wpdb;

         $sql = 'SELECT Q.question_id, Q.type_id, Q.question_name, Q.question_desc, Q.required, T.question_type_name FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION .' Q
         LEFT JOIN '. $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_TYPE .' T
         ON T.question_type_id = Q.type_id
         WHERE question_id = '.$id;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }

     public static function get_question_options($id) {
         global $wpdb;

         $sql = 'SELECT * FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION .'
         WHERE question_id = '.$id;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

         return $result;
     }
}

