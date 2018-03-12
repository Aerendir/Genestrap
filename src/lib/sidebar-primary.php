<?php

/**
 * @return array
 */
function shq_genestrap_add_sidebar_primary_classes():array {
	return shq_genestrap_add_html_classes('sidebar-primary', ['sidebar', 'hidden-xs', 'col-sm-4', 'col-lg-4']);
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_add_sidebar_primary_classes' );
