<?php

// Require configuration
require_once( __DIR__ . '/../config.php' );

// Initialize the genesis framework
require_once( get_template_directory() . '/lib/init.php' );

// Define this child theme information
define( 'CHILD_THEME_NAME', $config['CHILD_THEME_NAME'] );
define( 'CHILD_THEME_URL', $config['CHILD_THEME_URL'] );
define( 'CHILD_THEME_VERSION', $config['CHILD_THEME_VERSION'] );

// Unregister JQuery (only for frontend) and enqueue the compiled scripts
if (!is_admin()) {
	add_action('wp_enqueue_scripts', 'shqgb_enqueue_scripts', 11);
	function shqgb_enqueue_scripts() {
		wp_deregister_script('jquery');
		wp_register_script('scripts', '../scripts.js', [],true, true);
		wp_enqueue_script('scripts');
	};
}

// Add HTML5 markup structure from Genesis
add_theme_support( 'html5' );

// Add HTML5 responsive recognition
add_theme_support( 'genesis-responsive-viewport' );
