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
 * Adds the Bootstrap classes for pagination to prev and next links.
 *
 * @return string
 */
function shq_genestrap_prev_next_link_add_bootstrap_class():string {
	return ' class="page-link"';
}
add_filter( 'previous_posts_link_attributes', 'shq_genestrap_prev_next_link_add_bootstrap_class' );
add_filter( 'next_posts_link_attributes', 'shq_genestrap_prev_next_link_add_bootstrap_class' );

remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
add_action( 'genesis_after_endwhile', 'shq_genestrap_posts_nav' );
/**
 * Conditionally echo archive pagination in a format dependent on chosen setting.
 *
 * From genesis/lib/structure/post.php
 * Used to overwrite the function until it will implement the Genesis markup.
 *
 * This is shown at the end of archives to get to another page of entries.
 *
 * @since 1.0.0
 */
function shq_genestrap_posts_nav() {

	if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
		shq_genestrap_numeric_posts_nav();
	} else {
		genesis_prev_next_posts_nav();
	}

}

/**
 * Echo archive pagination in page numbers format.
 *
 * From genesis/lib/structure/post.php
 * Used to overwrite the function until it will implement the Genesis markup.
 *
 * Applies the `genesis_prev_link_text` and `genesis_next_link_text` filters.
 *
 * The links, if needed, are ordered as:
 *
 * * previous page arrow,
 * * first page,
 * * up to two pages before current page,
 * * current page,
 * * up to two pages after the current page,
 * * last page,
 * * next page arrow.
 *
 * @since 1.0.0
 *
 * @global WP_Query $wp_query Query object.
 *
 * @return void Return early if on a single post or page, or only one page exists.
 */
function shq_genestrap_numeric_posts_nav() {

	if ( is_singular() ) {
		return;
	}

	global $wp_query;

	// Stop execution if there's only one page.
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = (int) $wp_query->max_num_pages;
	$links = [];

	// Add current page to the array.
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	// Add the pages around the current page to the array.
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	genesis_markup(
		[
			'open'    => '<div %s>',
			'context' => 'archive-pagination',
		]
	);

	$before_number = genesis_a11y() ? '<span class="screen-reader-text">' . __( 'Page ', 'genesis' ) . '</span>' : '';

	echo '<ul class="pagination">';

	// Previous Post Link.
	if ( get_previous_posts_link() ) {
		// phpcs:disable
		printf( '<li class="page-item pagination-previous">%s</li>' . "\n", get_previous_posts_link( apply_filters( 'genesis_prev_link_text', '&#x000AB; ' . __( 'Previous Page', 'genesis' ) ) ) );
		// phpcs:enable
	}

	// Link to first page, plus ellipses if necessary.
	if ( ! in_array( 1, $links, false ) ) {

		$class = 1 === $paged ? ' class="active page-item"' : ' class="page-item"';

		printf( '<li%s><a class="page-link" href="%s">%s</a></li>' . "\n", esc_attr( $class ), esc_url( get_pagenum_link() ), esc_attr( $before_number ) . '1' );

		if ( ! in_array( 2, $links, false ) ) {
			echo '<li class="pagination-omission">&#x02026;</li>' . "\n";
		}
	}

	// Link to current page, plus 2 pages in either direction if necessary.
	sort( $links );
	foreach ( $links as $link ) {
		$class = $paged === $link ? ' class="active page-item"' : ' class="page-item"';
		$aria  = $paged === $link ? ' aria-label="' . esc_attr__( 'Current page', 'genesis' ) . '"' : '';
		printf( '<li%s><a class="page-link" href="%s" %s>%s</a></li>' . "\n", esc_attr( $class ), esc_url( get_pagenum_link( $link ) ), esc_attr( $aria ), esc_attr( $before_number ) . $link );
	}

	// Link to last page, plus ellipses if necessary.
	if ( ! in_array( $max, $links, false ) ) {

		if ( ! in_array( $max - 1, $links, false ) ) {
			echo '<li class="pagination-omission">&#x02026;</li>' . "\n";
		}

		$class = $paged === $max ? ' class="active page-item"' : ' class="page-item"';
		printf( '<li%s><a class="page-link" href="%s">%s</a></li>' . "\n", esc_attr( $class ), esc_url( get_pagenum_link( $max ) ), esc_attr( $before_number ) . $max );

	}

	// Next Post Link.
	if ( get_next_posts_link() ) {
		printf( '<li class="page-item pagination-next">%s</li>' . "\n", get_next_posts_link( apply_filters( 'genesis_next_link_text', __( 'Next Page', 'genesis' ) . ' &#x000BB;' ) ) );
	}

	echo '</ul>';
	genesis_markup(
		[
			'close'   => '</div>',
			'context' => 'archive-pagination',
		]
	);

	echo "\n";

}
