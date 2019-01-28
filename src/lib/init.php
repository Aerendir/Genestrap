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
 * @param      $pattern
 * @param int     $flags
 * @param bool    $traversePostOrder
 *
 * @return array|false
 *
 * @link http://php.net/manual/en/function.glob.php#119231
 */
function genestrap_rglob( $pattern, $flags = 0, $traversePostOrder = false ) {
	// Keep away the hassles of the rest if we don't use the wildcard anyway
	if ( strpos( $pattern, '/**/' ) === false ) {
		return glob( $pattern, $flags );
	}

	$patternParts = explode( '/**/', $pattern );

	// Get sub dirs
	$dirs = glob( array_shift( $patternParts ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

	// Get files for current dir
	$files = glob( $pattern, $flags );

	foreach ( $dirs as $dir ) {
		$subDirContent = genestrap_rglob( $dir . '/**/' . implode( '/**/', $patternParts ), $flags, $traversePostOrder );

		if ( ! $traversePostOrder ) {
			$files = array_merge( $files, $subDirContent );
		} else {
			$files = array_merge( $subDirContent, $files );
		}
	}

	return $files;
}

/**
 * @return bool true if this WordPress installation is running on localhost.
 */
function genestrap_is_localhost() {
	return substr( $_SERVER['REMOTE_ADDR'], '127.' ) === 0 || $_SERVER['REMOTE_ADDR'] === '::1';
}

/**
 * Returns the real version of the theme on production,
 * while returns the current datetime in development to bypass caching.
 *
 * The theme version is always passed to function that register
 * stylesheets and javascript to avoid adding the current version of
 * WordPress to querystring: this will make more difficult to spot it
 * and is one more security layer against attackers.
 *
 * @return string
 */
function genestrap_get_current_version() {
	return genestrap_is_localhost() ? date( 'h:i:s' ) : CHILD_THEME_VERSION;
}

add_action( 'genesis_setup', 'shq_gestrap_init' );

/**
 * Initializes this child theme.
 */
function shq_gestrap_init() {
	// Require configuration from this child theme
	$config = require_once get_stylesheet_directory() . '/config.php';

	// Initialize the genesis framework calling the parent theme (Genesis)
	require_once get_template_directory() . '/lib/init.php';

	// Define this child theme information
	define( 'CHILD_THEME_NAME', $config['CHILD_THEME_NAME'] );
	define( 'CHILD_THEME_URL', $config['CHILD_THEME_URL'] );
	define( 'CHILD_THEME_VERSION', $config['CHILD_THEME_VERSION'] );

	// Add Bootstrap
	function shqgb_enqueue_scripts() {
		$scriptsFile = get_stylesheet_directory_uri() . '/js/scripts.js';
		// Add the Bootstrap scripts
		wp_register_script( 'scripts', $scriptsFile, [ 'jquery' ], genestrap_get_current_version(), true );
		wp_enqueue_script( 'scripts' );
	};
	add_action( 'wp_enqueue_scripts', 'shqgb_enqueue_scripts', 11 );

	// Add HTML5 markup structure from Genesis
	add_theme_support( 'html5' );

	// Add HTML5 responsive recognition
	add_theme_support( 'genesis-responsive-viewport' );

	// * Unregister secondary navigation menu
	add_theme_support( 'genesis-menus', [ 'primary' => __( 'Header Navigation Menu', 'genesis' ) ] );

	add_theme_support(
		'custom-header',
		[
			'header_image'    => '',
			'header-selector' => '.site-title a',
			'header-text'     => false,
			'height'          => 19,
			'width'           => 139,
		]
	);

	// Add support for structural wraps required to add Bootstrap additional classes
	add_theme_support(
		'genesis-structural-wraps',
		[
			'header',
			'site-inner',
			'footer',
			'footer-widgets',
		]
	);

	add_theme_support( 'genesis-footer-widgets', 2 );

	// Load all the files required to customize Genesis
	foreach ( glob( get_stylesheet_directory() . '/lib/*.php' ) as $file ) {
		// Do not include this file
		if ( false === strpos( $file, 'init.php' ) ) {
			require_once $file;
		};
	}

	// Load all the files required to customize Gutenberg
	foreach ( genestrap_rglob( get_stylesheet_directory() . '/blocks/**/*.php' ) as $file ) {
		// Do not include this file
		if ( false === strpos( $file, 'init.php' ) ) {
			require_once $file;
		};
	}
}
