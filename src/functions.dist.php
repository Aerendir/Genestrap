<?php

// Include the child theme init file
require_once(get_stylesheet_directory() . '/lib/init.php');

// !!! Be sure the actions are added onlly in DEBUG MODE!!!
if (defined('WP_DEBUG') && true === WP_DEBUG) {
	add_action( 'wp_footer', 'shq_genestrap_debug_genesis_structural_markup_filters' );
	add_action( 'wp_footer', 'shq_genestrap_debug_genesis_attr_filters' );
}
