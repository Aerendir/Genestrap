<?php
/**
 * Server-side rendering for the latest posts Gutenberg block.
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
 * @param array $attributes
 *
 * @return string
 */
function shq_genestrap_render_block_latest_post( $attributes ) {
	$args = array(
		'numberposts' => $attributes['numberOfItems'],
		'post_status' => 'publish',
		'order'       => $attributes['order'],
		'orderby'     => $attributes['orderBy'],
	);

	if ( isset( $attributes['selectedCategoryId'] ) ) {
		$args['category'] = $attributes['selectedCategoryId'];
	}

	$recent_posts = wp_get_recent_posts( $args );

	$list_items_markup = '';

	foreach ( $recent_posts as $post ) {
		$list_items_markup .= '<li>';
		$post_id            = $post['ID'];

		$post_featured_image = '';
		if ( isset( $attributes['displayPostFeaturedImage'] ) && true === $attributes['displayPostFeaturedImage'] ) {
			$post_featured_image_url = get_the_post_thumbnail_url( $post_id );

			if (false !== $post_featured_image_url) {
				$post_featured_image = sprintf( '<img src="%1$s" />', $post_featured_image_url );
			}
		}

		$title = get_the_title( $post_id );
		if ( ! $title ) {
			$title = __( '(Untitled)' );
		}

		$list_items_markup .= sprintf(
			'<a href="%1$s">%2$s<span class="post-title">%3$s</span></a>',
			esc_url( get_permalink( $post_id ) ),
			$post_featured_image,
			esc_html( $title )
		);

		if ( isset( $attributes['displayPostDate'] ) && true === $attributes['displayPostDate'] ) {
			$list_items_markup .= sprintf(
				'<time datetime="%1$s" class="wp-block-latest-posts__post-date">%2$s</time>',
				esc_attr( get_the_date( 'c', $post_id ) ),
				esc_html( get_the_date( '', $post_id ) )
			);
		}

		$list_items_markup .= "</li>\n";
	}

	$class = 'wp-block-latest-posts';
	if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

	if ( isset( $attributes['postLayout'] ) && 'grid' === $attributes['postLayout'] ) {
		$class .= ' is-grid';
	}

	if ( isset( $attributes['columns'] ) && 'grid' === $attributes['postLayout'] ) {
		$class .= ' columns-' . $attributes['columns'];
	}

	if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
		$class .= ' has-dates';
	}

	if ( isset( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}

	$block_content = sprintf(
		'<ul class="%1$s">%2$s</ul>',
		esc_attr( $class ),
		$list_items_markup
	);

	return $block_content;
}

function shq_genestrap_register_block_latest_post() {
	wp_register_script(
		'shq-genestrap-latest-posts-block',
		get_stylesheet_directory_uri() . '/blocks/latest-posts/block.js',
		[ 'wp-api-fetch', 'wp-blocks', 'wp-components', 'wp-date', 'wp-editor', 'wp-element', 'wp-html-entities', 'wp-i18n', 'wp-url' ],
		genestrap_get_current_version()
	);

	wp_register_style(
		'shq-genestrap-latest-posts-block',
		get_stylesheet_directory_uri() . '/blocks/latest-posts/editor.css',
		[ 'wp-edit-blocks' ],
		genestrap_get_current_version()
	);

	register_block_type(
		'genestrap/latest-posts',
		[
			'editor_script'   => 'shq-genestrap-latest-posts-block',
			'editor_style'    => 'shq-genestrap-latest-posts-block',
			'render_callback' => 'shq_genestrap_render_block_latest_post',
			'attributes'      => [
				'selectedCategoryId'       => [
					'type' => 'string',
				],
				'className'                => [
					'type' => 'string',
				],
				'numberOfItems'            => [
					'type'    => 'number',
					'default' => 10,
				],
				'displayPostDate'          => [
					'type'    => 'boolean',
					'default' => false,
				],
				'displayPostFeaturedImage' => [
					'type'    => 'boolean',
					'default' => true,
				],
				'postLayout'               => [
					'type'    => 'string',
					'default' => 'list',
				],
				'columns'                  => [
					'type'    => 'number',
					'default' => 3,
				],
				'align'                    => [
					'type' => 'string',
				],
				'order'                    => [
					'type'    => 'string',
					'default' => 'desc',
				],
				'orderBy'                  => [
					'type'    => 'string',
					'default' => 'date',
				],
			],
		]
	);
}
add_action( 'init', 'shq_genestrap_register_block_latest_post' );
