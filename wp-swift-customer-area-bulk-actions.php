<?php
/*
Plugin Name: WP Swift: Customer Area Bulk Actions
Plugin URI: https://github.com/wp-swift-wordpress/wp-swift-customer-area-bulk-actions
Description: Adds new bulk actions to Customer Area private files.
Version: 1
Author: Gary Swift
Author URI: https://github.com/wp-swift-wordpress-plugins
License: GPL2
*/
require_once plugin_dir_path( __FILE__ ) . '_hook-javascript.php';
require_once plugin_dir_path( __FILE__ ) . '_ajax.php';
add_action( 'admin_footer', 'wp_swift_hook_javascript' );
add_action( 'wp_ajax_wp_swift_submit_bulk_action', 'wp_swift_submit_bulk_action_callback' );