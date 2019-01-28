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

/**
 * Creates a grid of all the categories.
 */
function shq_genestrap_categories_grid_shortcode() {
	$colNum     = 4;
	$currentCol = 1;
	$categories = get_categories();

	// Open the row
	$output = genesis_markup(
		[
			'open'    => '<div %s>',
			'context' => 'categories-grid',
			'echo'    => false,
		]
	);

	foreach ( $categories as $category ) {
		// Only print root categories but not the uncategorized one
		if ( 1 !== $category->term_id && 0 === $category->parent ) {
			if ( 1 === $currentCol ) {
				$output .= genesis_markup(
					[
						'open'    => '<div class="categories-row row">',
						'context' => 'categories-row',
						'echo'    => false,
					]
				);
			}

			$output .= genesis_markup(
				[
					'open'    => '<div class="category-box col-lg-3">',
					'context' => 'category-box',
					'echo'    => false,
				]
			);

			$description = false === empty( trim( $category->description ) )
				? sprintf( '<p class="category-description">%s</p>', $category->description )
				: null;
			$link        = sprintf( '<a href="%s"><h3 class="category-name">%s</h3>%s</a>', get_category_link( $category ), $category->name, $description );

			$output .= $link;

			$output .= genesis_markup(
				[
					'close'   => '</div>',
					'context' => 'category-box',
					'echo'    => false,
				]
			);

			if ( $colNum === $currentCol ) {
				$output    .= genesis_markup(
					[
						'close'   => '</div>',
						'context' => 'categories-row',
						'echo'    => false,
					]
				);
				$currentCol = 0;
			}

			$currentCol++;
		}
	}
	$output .= genesis_markup(
		[
			'close'   => '</div>',
			'context' => 'categories-grid',
			'echo'    => false,
		]
	);

	return $output;
}
add_shortcode( 'shq_genestrap_categories_grid', 'shq_genestrap_categories_grid_shortcode' );

/**
 * @param array $atts
 *
 * @return string
 */
function shq_genestrap_search_form_shortcode( array $atts ) {
	$default                  = [
		'show_label'       => 'true',
		'show_placeholder' => 'true',
		'input_data'       => '',
		'form_classes'     => '',
		'input_classes'    => 'form-control',
		'input_wrapper'    => 'col',
		'button_classes'   => 'btn btn-primary',
		'button_wrapper'   => 'col',
	];
	$atts                     = shortcode_atts( $default, $atts );
	$atts['show_label']       = 'false' === $atts['show_label'] ? false : true;
	$atts['show_placeholder'] = 'false' === $atts['show_placeholder'] ? false : true;

	$form = '<form class="' . $atts['form_classes'] . '" role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
   		<div class="form-group">';

	if ( $atts['show_label'] ) {
		$form .= '<label class="screen-reader-text" for="s">' . __( 'Search for:' ) . '</label>';
	}

	$form .= '<div class="' . $atts['input_wrapper'] . '"><input ' . $atts['input_data'] . 'class="' . $atts['input_classes'] . '" type="text" value="' . get_search_query() . '" name="s" id="s"';
	if ( $atts['show_placeholder'] ) {
		$form .= 'placeholder="' . __( 'Search for:' );
	}
	$form .= '" /></div>';

	$form .= '<div class="' . $atts['button_wrapper'] . '"><button type="submit" class="' . $atts['button_classes'] . '" id="searchsubmit">' . esc_attr__( 'Search' ) . '</button></div>
    	</div>
    </form>';

	return $form;
}
add_shortcode( 'shq_genestrap_search_form', 'shq_genestrap_search_form_shortcode' );
