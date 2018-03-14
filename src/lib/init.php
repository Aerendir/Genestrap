<?php

add_action('genesis_setup', 'shq_gestrap_init');

/**
 * Initializes this child theme.
 */
function shq_gestrap_init() {
	// Require configuration from this child theme
	$config = require_once( get_stylesheet_directory() . '/config.php' );

	// Initialize the genesis framework calling the parent theme (Genesis)
	require_once( get_template_directory() . '/lib/init.php' );

	// Define this child theme information
	define( 'CHILD_THEME_NAME', $config['CHILD_THEME_NAME'] );
	define( 'CHILD_THEME_URL', $config['CHILD_THEME_URL'] );
	define( 'CHILD_THEME_VERSION', $config['CHILD_THEME_VERSION'] );

	// Add Bootstrap
	function shqgb_enqueue_scripts() {
		$bootstrapFile = get_stylesheet_directory_uri() . '/js/bootstrap.bundle.js';
		// Add the Bootstrap scripts
		wp_register_script( 'bootstrap', $bootstrapFile, ['jquery'], '4.0', true );
		wp_enqueue_script('bootstrap');
	};
	add_action( 'wp_enqueue_scripts', 'shqgb_enqueue_scripts', 11 );

	// Add HTML5 markup structure from Genesis
	add_theme_support( 'html5' );

	// Add HTML5 responsive recognition
	add_theme_support( 'genesis-responsive-viewport' );

	//* Unregister secondary navigation menu
	add_theme_support( 'genesis-menus', [ 'primary' => __( 'Header Navigation Menu', 'genesis' ) ] );

	add_theme_support('custom-header', [
		'header_image'    => '',
		'header-selector' => '.site-title a',
		'header-text'     => false,
		'height'          => 19,
		'width'           => 139,
	]);

	// Add support for structural wraps required to add Bootstrap additional classes
	add_theme_support( 'genesis-structural-wraps', [
		'header',
		'site-inner',
		'footer',
		'footer-widgets'
	] );

	add_theme_support( 'genesis-footer-widgets', 2 );

	// Load all the files required to customize Genesis
	foreach ( glob( get_stylesheet_directory() . '/lib/*.php' ) as $file ) {
		// Do not include this file
		if (false === strpos($file, 'init.php')) {
			require_once($file);
		};
	}
}
