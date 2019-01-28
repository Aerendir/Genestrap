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
 * @return array
 */
function shq_genestrap_add_sidebar_primary_classes():array {
	return shq_genestrap_add_html_classes( 'sidebar-primary', [ 'sidebar', 'hidden-xs', 'col-md-12', 'col-lg-4' ] );
}
add_filter( 'shq_genestrap_add_genesis_attr', 'shq_genestrap_add_sidebar_primary_classes' );
