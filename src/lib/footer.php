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

// Put the footer widgets inside the <footer ...>...</footer> tags.
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
add_action( 'genesis_before_footer', 'genesis_footer_markup_open', 5 );

// Add the container class to the footer wrapper.
add_filter( 'genesis_structural_wrap-footer', 'shq_genestrap_add_container_to_wrapper', 10, 2 );

// Add the row class to the footer wrapper.
add_filter( 'genesis_structural_wrap-footer-widgets', 'shq_genestrap_add_row_to_wrapper', 10, 2 );

/**
 * Adds the bootstrap classes for columns.
 *
 * @return array
 */
function shq_genestrap_add_col_classes() {
	$col_zie = 12 / get_theme_support('genesis-footer-widgets')[0];
	return shq_genestrap_add_html_class( 'footer-widget-area', 'col-sm-' . $col_zie );
}
add_filter( 'shq_genestrap_add_genesis_attr', 'shq_genestrap_add_col_classes' );

/**
 * Adds the Genestrap credit to default footer.
 *
 * @return string
 */
function shq_genestrap_footer_credits():string {
	return sprintf( '[footer_copyright before="%s "] &#x000B7; [footer_childtheme_link before="" after=" %s"] [footer_genesis_link url="https://www.studiopress.com/" before="" after=" %s"] [shq_genestrap_link] &#x000B7; [footer_wordpress_link] &#x000B7; [footer_loginout]', __( 'Copyright', 'genesis' ), __( 'on', 'genesis' ), __( 'and', 'genesis' ) );
}
add_filter( 'genesis_footer_creds_text', 'shq_genestrap_footer_credits' );

/**
 * Adds the background to the header.
 *
 * @return array
 */
function shq_genestrap_add_footer_classes():array {
	if ( genesis_site_layout() === 'full-width-content' ) {
		return shq_genestrap_add_html_class( 'site-footer', 'mt-0' );
	}

	return [];
}
add_filter( 'shq_genestrap_add_genesis_attr', 'shq_genestrap_add_footer_classes' );
