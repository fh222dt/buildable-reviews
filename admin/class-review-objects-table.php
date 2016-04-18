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
                    $employer_link = esc_html(get_the_title($id));
                    if (current_user_can('edit_post', $id)) {
                        $employer_link = '<a href="'.esc_url(get_edit_post_link($id)).'">'.esc_html(get_the_title($id)) .'</a>';
                    }
                    echo $employer_link;
                    break;

            case 'rating':             //TODO
                    echo "3,9 av 5";
                    break;

            case 'most_recent':
                    echo $item['most_recent'];
                    break;

            case 'total_no':
                    echo $item['total_no'];
                    break;

            case 'view_all':             //TODO: link to list of all reviews
                    $edit_link = '<a href="#">Se alla</a>';
                    echo $edit_link;
                    break;

            default:
				return print_r( $item, true ) ;

        }
    }

    /**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
      $columns = $this->get_columns();
      $hidden = [];
      $sortable = [];
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