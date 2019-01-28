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

// Remove filters and actions added by Genesis
remove_filter( 'genesis_post_meta', 'do_shortcode', 20 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

/**
 * Adds the featured image to the header of the entry.
 */
function shq_genestrap_entry_featured_image() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	genesis_markup(
		[
			'open'    => '<div %s>',
			'context' => 'entry-featured-image',
		]
	);

	the_post_thumbnail( 'post-image' );

	genesis_markup(
		[
			'close'   => '</div>',
			'context' => 'entry-featured-image',
		]
	);
}
add_action( 'genesis_entry_header', 'shq_genestrap_entry_featured_image', 13 );

/**
 * Echo the categories in which the post is filed.
 *
 * By default, does post meta on all public post types except page.
 *
 * It uses a Genesis' shortcode to get the categories and the whole output is filtered via
 * `shq_genestrap_post_meta_categories` before echoing.
 *
 * @return void
 */
function shq_genestrap_post_meta_categories() {

	$filtered = apply_filters( 'shq_genestrap_post_meta_categories', '[post_categories]' );

	if ( false == trim( $filtered ) ) {
		return;
	}

	// Remove the label and commas
	$filtered = str_replace( [ __( 'Filed Under: ', 'genesis' ), ', ' ], '', $filtered );

	genesis_markup(
		[
			'open'    => '<p %s>',
			'close'   => '</p>',
			'content' => genesis_strip_p_tags( $filtered ),
			'context' => 'entry-meta-categories',
		]
	);
}
add_filter( 'shq_genestrap_post_meta_categories', 'do_shortcode', 20 );
add_action( 'genesis_entry_header', 'shq_genestrap_post_meta_categories', 9 );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

/**
 * Echo the tags.
 *
 * By default, does post meta on all public post types except page.
 *
 * It uses a Genesis' shortcode to get the tags and the whole output is filtered via
 * `shq_genestrap_post_meta_tags` before echoing.
 *
 * @return void
 */
function shq_genestrap_post_meta_tags() {

	$filtered = apply_filters( 'shq_genestrap_post_meta_tags', '[post_tags]' );

	if ( false == trim( $filtered ) ) {
		return;
	}

	// Remove the label and commas
	$filtered = str_replace( [ __( 'Tagged With: ', 'genesis' ), ', ' ], '', $filtered );

	genesis_markup(
		[
			'open'    => '<p %s>',
			'close'   => '</p>',
			'content' => genesis_strip_p_tags( $filtered ),
			'context' => 'entry-meta-tags',
		]
	);
}
add_filter( 'shq_genestrap_post_meta_tags', 'do_shortcode', 20 );
add_action( 'genesis_entry_footer', 'shq_genestrap_post_meta_tags' );
