<?php

/**
 * @return array
 */
function shq_genestrap_add_content_classes():array {
	$classes = [];

	$layout = genesis_site_layout();

	switch ($layout) {
		case 'full-width-content':
			$classes = ['col-lg-12'];
			break;
		case 'content-sidebar':
			$classes = ['col-lg-12', 'col-sm-8', 'col-lg-8'];
			break;
		case 'sidebar-content':
		case 'content-sidebar-sidebar':
		case 'sidebar-sidebar-content':
		case 'sidebar-content-sidebar':
		throw new \RuntimeException(sprintf('Layout "%s" is not currently supported.', $layout));
	}

	return shq_genestrap_add_html_classes('content', $classes);
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_add_content_classes' );

/**
 * On full-width-content removes the automatic inserting of "p" tags by Wordpress.
 *
 * This is to prevent that the layout of pages built in hand-written code is changed in unwanted ways.
 */
function prepare_full_width_content() {
	if (genesis_site_layout() === 'full-width-content') {
		remove_filter( 'the_content', 'wpautop' );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
	}
}
add_action('genesis_before_content', 'prepare_full_width_content' );
