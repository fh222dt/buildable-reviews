<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

/**
 *
 */
class BR_review_objects_table extends WP_List_Table {
    /*
     * Quire the db
     */
     private $sql;

    /**
	 * Constructor
	 */
	function __construct() {

		parent::__construct( array(
				'singular'=> __( 'Review', 'textdomain' ),
				'plural' => __( 'Reviews', 'textdomain' ),
				'ajax'	=> false
		) );
        $this->sql = new BR_SQL_Quieries();
	}

    /**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
	  $columns = [
	    'employer_id'    => __( 'ID', 'textdomain' ),
	    'employer' => __( 'Företag', 'textdomain' ),
	    'rating'    => __( 'Samlat betyg', 'textdomain' ),
		'most_recent'    => __( 'Senast recenserad', 'textdomain' ),
        'total_no'    => __( 'Antal', 'textdomain' ),
        'view_all'    => __( 'Visa alla', 'textdomain' )
	  ];

	  return $columns;
	}

    /**
	 * Column default
	 *
	 * @object $item
	 * @string $column_name
	 * @return
	 */
	function column_default( $item, $column_name ) {

		switch($column_name) {
            case 'employer_id':
                    $id = $item['employer_id'];
                    echo $id;
                    break;

            case 'employer':
					$id = $item['employer_id'];
					$employer_link = '<a href="?page=buildable-reviews-list&by-employer='.$id.'">'.esc_html(get_the_title($id)) .'</a>';
                    echo $employer_link;
                    break;

            case 'rating':
					$id = $item['employer_id'];
					$score = Buildable_reviews_admin::get_total_score_of_object($id);
                    echo $score.' av 5';
                    break;

            case 'most_recent':
                    echo $item['most_recent'];
                    break;

            case 'total_no':
                    echo $item['total_no'];
                    break;

            case 'view_all':
					$id = $item['employer_id'];
					$employer_link = '<a href="?page=buildable-reviews-list&by-employer='.$id.'">Se alla</a>';
					echo $employer_link;
					break;

            default:
				return print_r( $item, true ) ;

        }
    }

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
	  $sortable_columns = array(
		'employer_id' => array( 'employer_id', true ),
		'employer' => array( 'employer', false ),
		//'rating' => array( 'rating', false ),  //TODO
		'most_recent' => array( 'most_recent', false ),
		'total_no' => array( 'total_no', false )
	  );

	  return $sortable_columns;
	}

    /**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
      $columns = $this->get_columns();
      $hidden = [];
      $sortable = $this->get_sortable_columns();
	  $this->_column_headers = array($columns, $hidden, $sortable);

	  /** Process bulk action */
	  //$this->process_bulk_action();

	  $per_page     = $this->get_items_per_page( 'reviews_per_page', 25 );
	  $current_page = $this->get_pagenum();
	  $total_items  = $this->sql->record_count();

	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );


	  $this->items = $this->sql->get_employers( $per_page, $current_page );
	}
}