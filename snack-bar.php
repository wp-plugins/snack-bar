<?php
/*
 Plugin Name: Snack Bar
 Plugin URI: http://wordpress.org/extend/plugins/snack-bar/
 Description: Adds a snack menu to the admin bar 
 Author: wpmuguru, westi, PeteMall
 Version: 0.1.1
 */

function snack_bar_menu() {
	global $wp_admin_bar, $wpdb, $wp_version;

	if ( ! is_super_admin() || ! is_admin_bar_showing() )
	return;

	$class = 'snack-bar';

	/* Add the main siteadmin menu item */
	$site_parent = $parent = 'snack';
	if( is_multisite() && substr( $wp_version, 0, 3 ) == '3.3' )
		$parent = 'network-admin';
	else	// TODO: Consider the pi symbol for snacks to go with the name - &#1083;
		$wp_admin_bar->add_menu( array( 'id' => $parent, 'title' => __('Snacks'), 'href' => '', 'meta' => array( 'class' => $class ) ) );


	if ( is_multisite() ) {
		/* add levels for site/network sub menus */
		$site_parent = 'snack_site';
		$net_parent = 'snack_network';
		$wp_admin_bar->add_menu( array( 'id' => $site_parent, 'parent' => $parent, 'title' => __('Site'), 'href' => admin_url(), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => $net_parent, 'parent' => $parent, 'title' => __('Network'), 'href' => network_admin_url(), 'meta' => array( 'class' => $class ) ) );

		/* add network menu items */
		$wp_admin_bar->add_menu( array( 'id' => 'net_sites', 'parent' => $net_parent, 'title' => __('Sites'), 'href' => network_admin_url( 'sites.php' ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'net_users', 'parent' => $net_parent, 'title' => __('Users'), 'href' => network_admin_url( 'users.php' ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'net_themes', 'parent' => $net_parent, 'title' => __('Themes'), 'href' => network_admin_url( 'themes.php' ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'net_plugins', 'parent' => $net_parent, 'title' => __('Plugins'), 'href' => network_admin_url( 'plugins.php' ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'net_settings', 'parent' => $net_parent, 'title' => __('Settings'), 'href' => network_admin_url( 'settings.php' ), 'meta' => array( 'class' => $class ) ) );

		/* add site menu items */
		$wp_admin_bar->add_menu( array( 'id' => 'site_edit', 'parent' => $site_parent, 'title' => __('Edit'), 'href' => network_admin_url( 'site-info.php?id=' . $wpdb->blogid ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'site_users', 'parent' => $site_parent, 'title' => __('Users'), 'href' => network_admin_url( 'site-users.php?id=' . $wpdb->blogid ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'site_themes', 'parent' => $site_parent, 'title' => __('Themes'), 'href' => network_admin_url( 'site-themes.php?id=' . $wpdb->blogid ), 'meta' => array( 'class' => $class ) ) );		
		$wp_admin_bar->add_menu( array( 'id' => 'site_plugins', 'parent' => $site_parent, 'title' => __('Plugins'), 'href' => admin_url( 'plugins.php' ), 'meta' => array( 'class' => $class ) ) );
		
		if( !is_main_site() ) {
			$items = array();
			$blogname = get_option( 'blogname' );
			if ( get_blog_status( $wpdb->blogid, 'deleted' ) == '1' )
				$items['activateblog'] = array( 'message' => sprintf( __( 'You are about to activate the site %s' ), $blogname ), 'title' => __( 'Activate' ) );
			else
				$items['deactivateblog'] = array( 'message' => sprintf( __( 'You are about to deactivate the site %s' ), $blogname ), 'title' => __( 'Deactivate' ) );

			if ( get_blog_status( $wpdb->blogid, 'archived' ) == '1' )
				$items['unarchiveblog'] = array( 'message' => sprintf( __( 'You are about to unarchive the site %s' ), $blogname ), 'title' => __( 'Unarchive' ) );
			else
				$items['archiveblog'] = array( 'message' => sprintf( __( 'You are about to archive the site %s' ), $blogname ), 'title' => __( 'Archive' ) );

			if ( get_blog_status( $wpdb->blogid, 'spam' ) == '1' )
				$items['unspamblog'] = array( 'message' => sprintf( __( 'You are about to unspam the site %s' ), $blogname ), 'title' => __( 'Not Spam' ) );
			else
				$items['spamblog'] = array( 'message' => sprintf( __( 'You are about to mark the site %s as spam.' ), $blogname ), 'title' => __( 'Spam' ) );

			if ( current_user_can( 'delete_site', $wpdb->blogid ) )
				$items['deleteblog'] = array( 'message' => sprintf( __( 'You are about to delete the site %s' ), $blogname ), 'title' => __( 'Delete' ) );

			foreach( $items as $action2 => $strings )
				$wp_admin_bar->add_menu( array( 'id' => 'site_' . $action2, 'parent' => $site_parent, 'title' => $strings['title'], 'href' => esc_url( wp_nonce_url( network_admin_url( 'edit.php?action=confirm&amp;action2=' . $action2 . '&amp;id=' . $wpdb->blogid . '&amp;msg=' . urlencode( $strings['message'] ) ), 'confirm' ) ), 'meta' => array( 'class' => $class ) ) );
		}
	} else {
		/* add snack menu items for single site installs */
		$wp_admin_bar->add_menu( array( 'id' => 'site_users', 'parent' => $site_parent, 'title' => __('Users'), 'href' => admin_url( 'site-users.php' ), 'meta' => array( 'class' => $class ) ) );
		$wp_admin_bar->add_menu( array( 'id' => 'site_plugins', 'parent' => $site_parent, 'title' => __('Plugins'), 'href' => admin_url( 'plugins.php' ), 'meta' => array( 'class' => $class ) ) );		
	}
}
add_action( 'admin_bar_menu', 'snack_bar_menu', 1000 );

function snack_bar_menu_init() {
	if ( ! is_super_admin() || ! is_admin_bar_showing() )
		return;

	$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
	wp_enqueue_style( 'snack-bar', WP_PLUGIN_URL . "/snack-bar/snack-bar$suffix.css", array(), '20101112' );
}
add_action('admin_bar_init', 'snack_bar_menu_init');
