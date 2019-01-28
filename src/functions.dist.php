<?php
/**
 * Configures Genesis to use Bootstrap classes.
 *
 * @package Genestrap
 */

/*
 * This file is part of the Genestrap Genesis WordPress theme.
 *
 * (c) Adamo Crespi <hello@Aerendir.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Include the child theme init file
require_once get_stylesheet_directory() . '/lib/init.php';

// Add the author box under posts
add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );

/**
 * Imports the Google font to use on the site.
 */
function google_fonts() {
	$query_args = [
		'family' => 'Pacifico|Raleway:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i',
	];
	wp_enqueue_style( 'google_fonts', add_query_arg( $query_args, '//fonts.googleapis.com/css' ), array(), null );
}
add_action( 'wp_enqueue_scripts', 'google_fonts' );

// !!! Be sure the actions are added onlly in DEBUG MODE!!!
if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
	add_action( 'wp_footer', 'shq_genestrap_debug_genesis_structural_markup_filters' );
	add_action( 'wp_footer', 'shq_genestrap_debug_genesis_attr_filters' );
}
