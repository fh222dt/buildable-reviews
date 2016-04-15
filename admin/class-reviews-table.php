<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
//['ID', 'Företag', 'Samlat betyg', 'Datum', 'Status', 'Användare', 'Detaljer'];
/**
 *
 */
class BR_reviews_table extends WP_List_Table {

    /**
	 * Constructor
	 */
	function __construct() {

		parent::__construct( array(
				'singular'=> __( 'Review', 'textdomain' ),
				'plural' => __( 'Reviews', 'textdomain' ),
				'ajax'	=> false
		) );
	}

    /**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
	  $columns = [
	    'review_id'    => __( 'ID', 'textdomain' ),
	    'employer' => __( 'Företag', 'textdomain' ),
	    'rating'    => __( 'Samlat betyg', 'textdomain' ),
		'created_at'    => __( 'Datum', 'textdomain' ),
        'status'    => __( 'Status', 'textdomain' ),
        'user_id'    => __( 'Användare', 'textdomain' ),
        'details'    => __( 'Detaljer', 'textdomain' )
	  ];

	  return $columns;
	}
}