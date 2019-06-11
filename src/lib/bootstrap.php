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
 * Process the Bootsrap classes added in other parts of the template.
 *
 * And add a generic class row to the main content div.
 *
 * Other classes that may are required:
 *
 * 'archive-pagination' => 'clearfix',
 * 'entry-content' => 'clearfix',
 * 'entry-pagination' => 'clearfix',
 * 'sidebar-secondary' => '',
 */

/**
 * Adds the main row to content-sidebar-wrap (this is the main wrapper in Genesis).
 *
 * @return array
 */
function shq_genestrap_add_main_wrapper_classes():array {
	return shq_genestrap_add_html_classes( 'content-sidebar-wrap', [ 'content-sidebar-wrap', 'row' ] );
}
add_filter( 'shq_genestrap_add_genesis_attr', 'shq_genestrap_add_main_wrapper_classes' );

/**
 * Adds the Bootstrap "container" class to genesis_structural_wrap-*.
 *
 * @param string $output The output string.
 * @param string $original_output The original output string.
 *
 * @return string
 */
function shq_genestrap_add_container_to_wrapper( string $output, string $original_output ):string {
	if ( 'open' === $original_output ) {
		$output = shq_genestrap_add_html_structural_class( $output, 'container' );
	}

	return $output;
}

/**
 * Adds the Bootstrap "row" class to genesis_structural_wrap-*.
 *
 * @param string $output The output string.
 * @param string $original_output The original output string.
 *
 * @return string
 */
function shq_genestrap_add_row_to_wrapper( string $output, string $original_output ):string {
	if ( 'open' === $original_output ) {
		$output = shq_genestrap_add_html_structural_class( $output, 'row' );
	}

	return $output;
}

/**
 * Adds filters to the genesis_attr_* hook.
 */
function shq_genestrap_process_genesis_attr() {
	$filters = [];

	// Get the filters to apply.
	if ( has_filter( 'shq_genestrap_add_genesis_attr' ) ) {
		$filters = apply_filters( 'shq_genestrap_add_genesis_attr', $filters );
	}

	foreach ( $filters as $context => $attrs ) {
		$context = "genesis_attr_$context";
		add_filter( $context, 'apply_classes', 10, 2 );
	}
}
add_action( 'genesis_before', 'shq_genestrap_process_genesis_attr' );
