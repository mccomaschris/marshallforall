<?php
/**
 * Plugin Name: Health Check Endpoint
 * Description: Health check endpoint for monitoring
 * Version: 1.0.0
 */

add_action(
	'init',
	function () {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( '/health' === $_SERVER['REQUEST_URI'] || '/health/' === $_SERVER['REQUEST_URI'] ) {
			header( 'Content-Type: application/json' );

			$status    = array( 'status' => 'ok' );
			$http_code = 200;

			// Check database connection.
			global $wpdb;
			if ( ! $wpdb->check_connection( false ) ) {
				$http_code = 503;
				$status    = array(
					'status'  => 'error',
					'message' => 'Database connection failed',
				);
			}

			http_response_code( $http_code );
			echo wp_json_encode( $status );
			exit;
		}
	},
	1
);
