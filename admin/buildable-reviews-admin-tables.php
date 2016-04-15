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
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
	  $columns = [
	    'review_id'    => __( 'Id', 'textdomain' ),
	    //'employer' => __( 'Företag', 'textdomain' ),
	    //'rating'    => __( 'Samlat betyg', 'textdomain' ),
		'created_at'    => __( 'Datum', 'textdomain' )
	  ];

	  return $columns;
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

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

	  $this->_column_headers = $this->get_column_info();

	  /** Process bulk action */
	  //$this->process_bulk_action();

	  $per_page     = $this->get_items_per_page( 'reviews_per_page', 25 );
	  $current_page = $this->get_pagenum();
	  $total_items  = self::record_count();

	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );


	  $this->items = self::get_reviews( $per_page, $current_page );
	}

}