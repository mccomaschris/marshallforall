<?php
/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package mfa
 */

namespace App;

use Timber\Timber;

$context = Timber::context();

if ( isset( $context['author'] ) ) {
	$context['title'] = 'Archive of ' . $context['author']->name();
}

Timber::render( array( 'templates/author.twig', 'templates/archive.twig' ), $context );
