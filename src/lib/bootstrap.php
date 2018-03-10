<?php

/*
 * Process the Bootsrap classes added in other parts of the template.
 *
 * And add a generic class row to the main content div.
 *
 * Other classes that may are required:
 *
 *		'archive-pagination'   => 'clearfix',
 *		'entry-content'        => 'clearfix',
 *		'entry-pagination'     => 'clearfix',
 *		'sidebar-secondary'    => '',
 */

/**
 * Adds the main row to content-sidebar-wrap (this is the main wrapper in Genesis).
 *
 * @return array
 */
function shq_genestrap_add_main_wrapper_classes():array {
	return shq_genestrap_add_html_class('content-sidebar-wrap', 'row');
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_add_main_wrapper_classes' );

/**
 * Adds the Bootstrap "container" class to the site-header inner wrap.
 *
 * @param string $output
 * @param string $original_output
 *
 * @return string
 */
function shq_genestrap_add_inner_wrap_classes(string $output, string $original_output):string
{
	if ('open' === $original_output) {
		$output = str_replace('">', ' container">', $output);
	}

	return $output;
}
add_filter("genesis_structural_wrap-site-inner", 'shq_genestrap_add_inner_wrap_classes', 10, 2);

/**
 * Adds filters to the genesis_attr_* hook.
 */
function shq_genestrap_process_genesis_attr()
{
	$filters = [];

	// Get the filters to apply
	if (has_filter('shq_genestrap_add_genesis_attr')) {
		$filters = apply_filters('shq_genestrap_add_genesis_attr', $filters);
	}

	foreach($filters as $context => $attrs) {
		$context = "genesis_attr_$context";
		add_filter($context, 'apply_classes', 10, 2);
	}
}
add_action('genesis_before', 'shq_genestrap_process_genesis_attr' );
