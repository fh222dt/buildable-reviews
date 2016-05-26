<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

/**
 *
 */
class BR_reviews_table extends WP_List_Table {
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
	    'review_id'    => __( 'ID', 'textdomain' ),
	    'employer' => __( 'Företag', 'textdomain' ),
	    'rating'    => __( 'Samlat betyg', 'textdomain' ),
		'created_at'    => __( 'Datum', 'textdomain' ),
        'status_name'    => __( 'Status', 'textdomain' ),
        'user'    => __( 'Lämnad av', 'textdomain' ),
        'details'    => __( 'Detaljer', 'textdomain' )
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
            case 'review_id':
                    $id = $item['review_id'];
                    echo $id;
                    break;

            case 'employer':
                    $id = $item['employer'];
					$employer_link = '<a href="?page=buildable-reviews-list&by-employer='.$id.'">'.esc_html(get_the_title($id)) .'</a>';
                    echo $employer_link;
                    break;

            case 'rating':
					$id = $item['review_id'];
					$score = Buildable_reviews_Admin::get_total_score_of_review($id);
                    echo $score .' av 5';
                    break;

            case 'created_at':
                    echo $item['created_at'];
                    break;

            case 'status_name':             //TODO: onclick change status
                    echo $item['status_name'];
                    break;

            case 'user':
                    $user_email = $item['user'];
					$user_id = get_user_by( 'email', $user_email );
                    $user_link = '<a href="?page=buildable-reviews-list&by-user='.$user_id->id.'">'.$user_email.'</a>';
                    echo $user_link;
                    break;

            case 'details':
                    $edit_link = '<a href="?page=buildable-reviews-details&review-id='.$item['review_id'].'">Se hela</a>';
                    echo $edit_link;
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
	    'review_id' => array( 'review_id', true ),
	    'employer' => array( 'employer', false ),
		//'rating' => array( 'rating', false ), 	//TODO
		'created_at' => array( 'created_at', false ),
        'status_name' => array( 'status_name', false ),
        'user' => array( 'user', false )
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


	  $this->items = $this->sql->get_reviews( $per_page, $current_page, null );
	}
}