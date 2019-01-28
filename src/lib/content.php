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

/**
 * Adds bootstrap classes.
 *
 * @throws \RuntimeException Il the selected layout is not supported.
 * @return array
 */
function shq_genestrap_add_content_classes():array {
	$classes = [];

	$layout = genesis_site_layout();

	switch ( $layout ) {
		case 'full-width-content':
			$classes = [ 'col-lg-12' ];
			break;
		case 'content-sidebar':
			$classes = [ 'col-lg-8', 'col-md-12' ];
			break;
		case 'sidebar-content':
		case 'content-sidebar-sidebar':
		case 'sidebar-sidebar-content':
		case 'sidebar-content-sidebar':
			throw new \RuntimeException( sprintf( 'Layout "%s" is not currently supported.', $layout ) );
	}

	return shq_genestrap_add_html_classes( 'content', $classes );
}
add_filter( 'shq_genestrap_add_genesis_attr', 'shq_genestrap_add_content_classes' );

/**
 * On full-width-content removes the automatic inserting of "p" tags by WordPress.
 *
 * This is to prevent that the layout of pages built in hand-written code is changed in unwanted ways.
 */
function prepare_full_width_content() {
	if ( genesis_site_layout() === 'full-width-content' ) {
		remove_filter( 'the_content', 'wpautop' );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
	}
}
add_action( 'genesis_before_content', 'prepare_full_width_content' );

/**
 * Opens a wrap for the categories, tags or taxnomies lists.
 */
function wrap_categories_list_open() {
	if ( is_category() ) {
		$is = 'category';
	} elseif ( is_tag() ) {
		$is = 'tag';
	} elseif ( is_tax() ) {
		$is = 'tax';
	} else {
		return;
	}

	genesis_markup(
		[
			'open'    => '<div %s>',
			'context' => "genestrap-{$is}-wrap",
		]
	);
}
add_action( 'genesis_before_loop', 'wrap_categories_list_open' );

/**
 * Closes a wrap for the categories, tags or taxnomies lists.
 */
function wrap_categories_list_close() {
	if ( is_category() ) {
		$is = 'category';
	} elseif ( is_tag() ) {
		$is = 'tag';
	} elseif ( is_tax() ) {
		$is = 'tax';
	} else {
		return;
	}

	genesis_markup(
		[
			'close'   => '</div>',
			'context' => "genestrap-{$is}-wrap",
		]
	);
}
add_action( 'genesis_after_loop', 'wrap_categories_list_close' );
