<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 *
 */
class Buildable_reviews_admin_tables extends WP_List_Table {

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
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
	  switch ( $column_name ) {
	    case 'review_id':
	    //case 'employer':
		//case 'rating':
		case 'created_at':
	      return $item[ $column_name ];
	    default:
	      return print_r( $item, true ); //Show the whole array for troubleshooting purposes
	  }
	}

	

}