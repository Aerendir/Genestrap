<?php

// Remove the Genesis header
remove_action( 'genesis_header', 'genesis_do_header' );

// Rebuild the header to match the Bootstrap structure
add_action('genesis_header', 'shq_genestrap_header');
/**
 * Renders the header in the Bootstrap format.
 *
 * Uses some functions overridden from Genesis.
 */
function shq_genestrap_header() {
	// Open the nav
	echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">';

	// Add the site title
	do_action( 'genesis_site_title' );

	// Now render the Primary Menu
	wp_nav_menu( [
		'menu' => 'Primary Navigation',
		'menu_class' => 'this-is-the-menu-class',
		'menu_id' => 'this-is-the-menu-id',
		'container' => 'div',
		'container_class' => 'this-is-the-container-class',
		'container_id' => 'this-is-the-container-ID',
	] );

	echo '</nav>';
}

remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
add_action( 'genesis_site_title', 'shq_genestrap_seo_site_title' );
/**
 * THIS IS COPIED FROM Genesis/header.php.
 *
 * We need to add the navbar-brand class.
 *
 * Echo the site title into the header.
 *
 * Depending on the SEO option set by the user, this will either be wrapped in an `h1` or `p` element.
 *
 * Applies the `genesis_seo_title` filter before echoing.
 *
 * @since 1.1.0
 */
function shq_genestrap_seo_site_title() {

	// Set what goes inside the wrapping tags.
	$inside = sprintf( '<a class="navbar-brand" href="%s">%s</a>', trailingslashit( home_url() ), get_bloginfo( 'name' ) );

	// Determine which wrapping tags to use.
	$wrap = genesis_is_root_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';

	// A little fallback, in case an SEO plugin is active.
	$wrap = genesis_is_root_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;

	// Wrap homepage site title in p tags if static front page.
	$wrap = is_front_page() && ! is_home() ? 'p' : $wrap;

	// And finally, $wrap in h1 if HTML5 & semantic headings enabled.
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	/**
	 * Site title wrapping element
	 *
	 * The wrapping element for the site title.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'genesis_site_title_wrap', $wrap );

	// Build the title.
	$title = genesis_markup( [
		'open'    => sprintf( "<{$wrap} %s>", genesis_attr( 'site-title' ) ),
		'close'   => "</{$wrap}>",
		'content' => $inside,
		'context' => 'site-title',
		'echo'    => false,
		'params'  => [
			'wrap' => $wrap,
		],
	] );

	echo apply_filters( 'genesis_seo_title', $title, $inside, $wrap );

}


//add_action('genesis_header', 'custom_do_nav', 5);

function custom_do_nav() {
	wp_nav_menu( [
		'menu' => 'Primary Navigation',
		'container' => 'nav',
		'container_class' => 'navbar navbar-expand-lg navbar-light bg-light',
		'menu_class' => 'navbar navbar-expand-lg navbar-light bg-light',
		'menu_id' => 'navigation',
		'items_wrap' => '  <div class="container-fluid">  <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mry-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div> <div class="collapse navbar-collapse" id="mry-navbar-collapse-1"><ul id="%1$s" class="%2$s">%3$s</ul></div></div>'
	] );

	echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>';
}
