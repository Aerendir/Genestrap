<?php
/**
 * Activates the debug mode.
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
 * Debug function to see all the filters applied to render the current page.
 *
 * @See https://gist.github.com/bryanwillis/16f1755f07e507f2b3b6
 */

/**
 * Add the action to your functions.php file
 * add_action( 'wp_footer', 'shq_genestrap_debug_genesis_attr_filters' );
 *
 * @author Bryan Willis and Adamo Aerendir Crespi
 */
function shq_genestrap_debug_genesis_attr_filters() {
	// current_filter() might be a better way to do this.
	global $wp_filter;
	$genesis_attr_filters = [];
	$h1                   = '<h1>Current Page Genesis Attribute Filters</h1>';
	$out                  = '';
	$ul                   = '<ul>';
	foreach ( $wp_filter as $key => $val ) {
		if ( false !== strpos( $key, 'genesis_attr' ) ) {
			// phpcs:disable
			$genesis_attr_filters[ $key ][] = var_export( $val, true );
			// phpcs:enable
		}
	}
	foreach ( $genesis_attr_filters as $name => $attr_vals ) {
		$out .= "<h2 id=$name>$name</h2><pre>" . implode( "\n\n", $attr_vals ) . '</pre>';
		$ul  .= "<li><a href='#$name'>$name</a></li>";
	}
	// phpcs:disable
	print "$h1$ul</ul>$out";
	// phpcs:enable
}

/**
 * Add the action to your functions.php file
 * add_action( 'wp_footer', 'shq_genestrap_debug_genesis_structural_markup_filters' );
 *
 * @author Bryan Willis and Adamo Aerendir Crespi
 */
function shq_genestrap_debug_genesis_structural_markup_filters() {
	// current_filter() might be a better way to do this.
	global $wp_filter;
	$genesis_structural_markup_filters = [];
	$h1                                = '<h1>Current Page Genesis Structural Markup Filters</h1>';
	$out                               = '';
	$ul                                = '<ul>';
	foreach ( $wp_filter as $key => $val ) {
		if ( false !== strpos( $key, 'genesis_structural_markup' ) ) {
			// phpcs:disable
			$genesis_structural_markup_filters[ $key ][] = var_export( $val, true );
			// phpcs:enable
		}
	}
	foreach ( $genesis_structural_markup_filters as $name => $attr_vals ) {
		$out .= "<h2 id=$name>$name</h2><pre>" . implode( "\n\n", $attr_vals ) . '</pre>';
		$ul  .= "<li><a href='#$name'>$name</a></li>";
	}
	// phpcs:disable
	print "$h1$ul</ul>$out";
	// phpcs:enable
}
