<?php
/**
 * Plugin Name: Super Users
 * Description: Grants unconditional mu-auth access to specific MU Net IDs.
 */

add_filter( 'mu_auth_super_users', function( $users ) {
	return array( 'cmccomas', 'madden24' );
} );
