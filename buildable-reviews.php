<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           buildable-reviews
 *
 * @wordpress-plugin
 * Plugin Name:       Buildable reviews
 * Plugin URI:
 * Description:       Build your own custom review form & display the result with diagrams & top lists.
 * Version:           1.0.0
 * Author:            Frida Holmström
 * Author URI:        http://fridaholmstrom.se/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buildable-reviews
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buildable-reviews-activator.php
 */
function activate_buildable_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buildable-reviews-activator.php';
	Buildable_reviews_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buildable-reviews-deactivator.php
 */
function deactivate_buildable_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buildable-reviews-deactivator.php';
	Buildable_reviews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_buildable_reviews' );
register_deactivation_hook( __FILE__, 'deactivate_buildable_reviews' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-buildable-reviews.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buildable_reviews() {

	$plugin = new Buildable_reviews();
	$plugin->run();

}
run_buildable_reviews();