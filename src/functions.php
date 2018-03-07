<?php

// Require configuration
require_once( './config.php' );

// Initialize the genesis framework
include_once( get_template_directory() . '/lib/init.php' );

// Define this child theme information
define( 'CHILD_THEME_NAME', 'TrustBack.Me' );
define( 'CHILD_THEME_URL', 'http://www.trustback.me/' );
define( 'CHILD_THEME_VERSION', '0.1' );

// Cleanup WP Head
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'start_post_rel_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// Add HTML5 markup structure from Genesis
add_theme_support( 'html5' );

// Add HTML5 responsive recognition
add_theme_support( 'genesis-responsive-viewport' );
