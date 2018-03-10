<?php

/**
 * @return array
 */
function shq_genestrap_add_content_classes():array {
	return shq_genestrap_add_html_classes('content', ['col-xs-12', 'col-sm-8', 'col-lg-8']);
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_add_content_classes' );
