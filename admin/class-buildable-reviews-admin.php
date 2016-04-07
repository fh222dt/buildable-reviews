<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Buildable_reviews_admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $buildable_reviews;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $buildable_reviews       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $buildable_reviews, $version ) {

		$this->buildable_reviews = $buildable_reviews;
		$this->version = $version;

	}

	/**
	 * Admin menus
	 * using this solution for parameter free callback: 
	 * http://wordpress.stackexchange.com/questions/16415/passing-arguments-to-a-admin-menu-page-callback
	 *
	 */


	public function add_admin_menus() {
		$views = [];

		$view_hook_name = add_menu_page(
		'Manage Reviews',
		'Reviews',
		'br_edit_reviews',
		$this->buildable_reviews,
		array($this, 'load_admin_page_content'),	//array($this, 'load_admin_page_content(buildable-reviews-admin-display.php)'),   //plugins_url('buildable-reviews\admin\partials\buildable-reviews-admin-display.php'),
		'',
		'3.99');
		$this->views[$view_hook_name] = 'buildable-reviews-admin-display';

		$view_hook_name = add_submenu_page(
		$this->buildable_reviews,
		'Add Question',
		'Add Question',
		'br_edit_reviews',
		$this->buildable_reviews.'-add-question',
		array($this, 'load_admin_page_content')
		);
		$this->views[$view_hook_name] = 'buildable-reviews-admin-add-question';

		$view_hook_name = add_submenu_page(
		$this->buildable_reviews,
		'Settings',
		'Settings',
		'br_edit_reviews',
		$this->buildable_reviews.'-settings',
		array($this, 'load_admin_page_content')
		);
		$this->views[$view_hook_name] = 'buildable-reviews-admin-settings';

	}

	//Load the view for menu item
	public function load_admin_page_content() {
		$current_views = $this->views[current_filter()];
    	require_once plugin_dir_path( __FILE__ ). 'partials/'.$current_views. '.php';
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in buildable_reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The buildable_reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->buildable_reviews, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->buildable_reviews, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

}