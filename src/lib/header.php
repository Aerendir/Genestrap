<?php

// Remove the Genesis header
remove_action( 'genesis_header', 'genesis_do_header' );

// Remove the primary menu from its current position: we'll add it later in shq_genestrap_header()
remove_action( 'genesis_after_header', 'genesis_do_nav' );

// Remove the nav-link parser as they are created by the WP_Bootstrap_Walker
remove_filter( 'nav_menu_link_attributes', 'genesis_nav_menu_link_attributes' );

// Add the container class to some wrappers
add_filter("genesis_structural_wrap-site-inner", 'shq_genestrap_add_container_to_wrapper', 10, 2);
add_filter("genesis_structural_wrap-header", 'shq_genestrap_add_container_to_wrapper', 10, 2);

/**
 * Rebuild the header to match the Bootstrap structure.
 */
function shq_genestrap_header():void {

	// Open the nav (classes will be added by genestrap_title_area())
	genesis_markup( [
		'open'    => '<nav %s>',
		'context' => 'title-area',
	] );

	// Add the site title
	do_action( 'genesis_site_title' );

	echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#genestrap-header-menu" aria-controls="genestrap-header-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>';

	// Now render the Primary Menu
	echo wp_nav_menu([
		'theme_location' => 'primary',
		'menu_class' => 'navbar-nav mr-auto',
		'container' => 'div',
		'container_class' => 'collapse navbar-collapse',
		'container_id' => 'genestrap-header-menu',
		'depth'				=> 2,
		'fallback_cb'		=> 'WP_Bootstrap_Navwalker::fallback',
		'walker'			=> new WP_Bootstrap_Navwalker()
	]);

	genesis_markup( [
		'close'    => '</nav>',
		'context' => 'title-area',
	] );
}
add_action('genesis_header', 'shq_genestrap_header');

/**
 * Adds the background to the header.
 *
 * @return array
 */
function shq_genestrap_add_header_classes():array {
	if (genesis_site_layout() === 'full-width-content') {
		shq_genestrap_add_html_class('site-header', 'mb-0');
	}

	return shq_genestrap_add_html_class('site-header', 'bg-dark');
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_add_header_classes');

/**
 * Adds the Bootstrap classes to the title-area markup.
 *
 * @return mixed
 */
function shq_genestrap_title_area_classes() {
	return shq_genestrap_add_html_classes('title-area', ['navbar', 'navbar-expand-lg', 'navbar-dark']);
}
add_filter('shq_genestrap_add_genesis_attr', 'shq_genestrap_title_area_classes');

/**
 * Adds the navbar-brand markup to the title.
 *
 * @param array ...$params
 *
 * @return string
 */
function shq_genestrap_seo_title(...$params):string {
	// Only use the linked title, withiut the wrap (h1 or p) to not break Bootstrap.
	// Hardly add the navbr-brand class to the a tag in the title.
	// The a title currently doesn't receive any class, so it is safe to simply add the class as we don't risk to overwrite anything.
	// See header.php > genesis_seo_site_title() function for more info
	$title = str_replace('<a', '<a class="navbar-brand" ', $params[1]);

	if (get_theme_support( 'custom-header' )) {
		$logo = get_header_image()
			? sprintf('<img src="%s" width="%s" height="%s" alt="%s" />', esc_url(get_header_image()), esc_attr( get_custom_header()->width) , esc_attr( get_custom_header()->height ) , get_bloginfo( 'name' ))
			: get_bloginfo( 'name' );

		// Remove the text from the $title
		$title = sprintf('<a href="%s" class="navbar-brand">%s</a>', home_url(), $logo);
	}

	return $title;
}
add_filter('genesis_seo_title', 'shq_genestrap_seo_title' , 10, 3);

/**
 * Puts the search widget inside the nav tag so it can be toggled on smaller screens.
 *
 * @param $menu
 *
 * @return string
 */
function build_header_navbar($menu) {
	global $wp_registered_sidebars;
	// Filter only the header menu
	if (false !== strpos($menu, 'genestrap-header-menu')) {
		$navbar = str_replace('</div>', '', $menu );

		if ( has_action( 'genesis_header_right' ) || isset( $wp_registered_sidebars['header-right'] ) ) {

			ob_start();
			/**
			 * Fires inside the header widget area wrapping markup, before the Header Right widget area.
			 *
			 * @since 1.5.0
			 */
			do_action( 'genesis_header_right' );
			dynamic_sidebar( 'header-right' );

			$navbar .= ob_get_contents();
			ob_end_clean();

		}

		$menu = $navbar . '</div>';
	}

	return $menu;
}
add_filter('wp_nav_menu', 'build_header_navbar' );
