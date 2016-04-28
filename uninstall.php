<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
//delete options

//drop tables
global $wpdb;

$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION_RELATION );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_TYPE );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_OPTION );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE_TYPE );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_VOTE);
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_STATUS );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_COMMENT );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW );