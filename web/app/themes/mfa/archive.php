<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mfa
 */

namespace App;

use Timber\Timber;

$templates = array( 'templates/archive.twig', 'templates/index.twig' );

$title = 'Archive'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
if ( is_day() ) {
	$title = 'Archive: ' . get_the_date( 'D M Y' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
} elseif ( is_month() ) {
	$title = 'Archive: ' . get_the_date( 'M Y' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
} elseif ( is_year() ) {
	$title = 'Archive: ' . get_the_date( 'Y' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
} elseif ( is_tag() ) {
	$title = single_tag_title( '', false ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
} elseif ( is_category() ) {
	$title = single_cat_title( '', false ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
} elseif ( is_post_type_archive() ) {
	$title = post_type_archive_title( '', false ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	array_unshift( $templates, 'templates/archive-' . get_post_type() . '.twig' );
}

$context = Timber::context(
	array(
		'title' => $title,
	)
);

Timber::render( $templates, $context );
