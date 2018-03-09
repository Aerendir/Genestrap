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
function shq_genestrap_header():void {
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

add_filter('genesis_seo_title', 'shq_genestrap_seo_title' , 10, 3);
/**
 * Adds the navbar-brand markup to the title.
 *
 * @param string $title
 *
 * @return string
 */
function shq_genestrap_seo_title( string $title):string {
	// Hardly add the navbr-brand class to the a tag in the title.
	// The a title currently doesn't receive any class, so it is safe to simply add the class as we don't risk to overwrite anything.
	// See header.php > genesis_seo_site_title() function for more info
	return str_replace('<a', '<a class="navbar-brand" ', $title);
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
