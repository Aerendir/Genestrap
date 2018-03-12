<?php

// Put the footer widgets inside the <footer ...>...</footer> tags
remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
add_action('genesis_before_footer', 'genesis_footer_markup_open', 5);

// Add the container class to the footer wrapper
add_filter('genesis_structural_wrap-footer', 'shq_genestrap_add_container_to_wrapper', 10, 2);

// Add the row class to the footer wrapper
add_filter('genesis_structural_wrap-footer-widgets', 'shq_genestrap_add_row_to_wrapper', 10, 2);

function shq_genestrap_add_col_classes()
{
	return shq_genestrap_add_html_class('footer-widget-area', 'col-sm-6');
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_add_col_classes' );

/**
 * Adds the Genestrap credit to default footer.
 * @return string
 */
function shq_genestrap_footer_credits():string
{
	return sprintf( '[footer_copyright before="%s "] &#x000B7; [footer_childtheme_link before="" after=" %s"] [footer_genesis_link url="https://www.studiopress.com/" before="" after=" %s"] [shq_genestrap_link] &#x000B7; [footer_wordpress_link] &#x000B7; [footer_loginout]', __( 'Copyright', 'genesis' ), __( 'on', 'genesis' ), __( 'and', 'genesis' ) );
}
add_filter('genesis_footer_creds_text', 'shq_genestrap_footer_credits');
