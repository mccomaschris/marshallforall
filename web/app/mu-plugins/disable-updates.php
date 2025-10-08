<?php
/**
 * Disable plugin and theme updates in production
 *
 * @package bedrock
 */

// Disable all automatic updates.
add_filter( 'automatic_updater_disabled', '__return_true' );

// Disable plugin updates.
remove_action( 'load-update-core.php', 'wp_update_plugins' );
add_filter( 'pre_site_transient_update_plugins', '__return_null' );

// Disable theme updates.
remove_action( 'load-update-core.php', 'wp_update_themes' );
add_filter( 'pre_site_transient_update_themes', '__return_null' );

// Hide update notices.
add_action(
	'admin_menu',
	function () {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
);

// Remove update menu items.
add_action(
	'admin_menu',
	function () {
		remove_submenu_page( 'index.php', 'update-core.php' );
	}
);
