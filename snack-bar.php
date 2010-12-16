<?php
/*
 Plugin Name: Snack Bar
 Plugin URI: http://wordpress.org/extend/plugins/snack-bar/
 Description: Adds a snack menu to the admin bar 
 Author: wordpressdotorg
 Version: 0.01-alpha-do-not-use-yet
 Author URI: http://wordpress.org/
 */

function snack_bar_menu() {
	global $wp_admin_bar, $wpdb;

	if ( ! is_super_admin() || ! is_admin_bar_showing() )
	return;

	$class = 'snack-bar';

	/* Add the main siteadmin menu item */
	$wp_admin_bar->add_menu( array( 'id' => 'snack', 'title' => __('Snacks'), 'href' => '', 'meta' => array( 'class' => $class ) ) );
}
add_action( 'admin_bar_menu', 'snack_bar_menu', 1000 );

function snack_bar_menu_init() {
	if ( ! is_super_admin() || ! is_admin_bar_showing() )
	return;

	$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
	wp_enqueue_style( 'snack-bar', WP_PLUGIN_URL . "/snack-bar/snack-bar$suffix.css", array(), '20101112' );
	wp_enqueue_script( 'snack-bar', WP_PLUGIN_URL . "/snack-bar/snack-bar$suffix.js", array(), '20101109' );
}
add_action('admin_bar_init', 'snack_bar_menu_init');
