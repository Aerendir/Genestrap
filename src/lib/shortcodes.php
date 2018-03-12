<?php

/**
 * Adds link to the Genestrap Github Repository.
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string).
 *
 * Output passes through `shq_genestrap_genestrap_link_shortcode` filter before returning.
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Output for `shq_genestrap_link` shortcode.
 */
function shq_genestrap_genestrap_link_shortcode( $atts ) {

	$defaults = [
		'after'  => '',
		'before' => '',
		'url'    => 'https://serendipityhq.com',
	];
	$atts     = shortcode_atts( $defaults, $atts, 'shq_genestrap_link' );

	$output = $atts['before'] . '<a href="' . esc_url( $atts['url'] ) . '">Genestrap</a>' . $atts['after'];

	return apply_filters( 'genesis_footer_genesis_link_shortcode', $output, $atts );

}
add_shortcode( 'shq_genestrap_link', 'shq_genestrap_genestrap_link_shortcode' );
