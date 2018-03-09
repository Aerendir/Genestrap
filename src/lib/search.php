<?php
/**
 * Search Form
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://rotsenacob.com
 * @author       Rotsen Mark Acob <rotsenacob.com>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_filter( 'get_search_form', 'bfg_search_form' );
function bfg_search_form( $form ) {
    $form = '<form class="form-inline my-2 my-lg-0" role="search" method="get" id="searchform" action="' . home_url('/') . '" >
	<input class="form-control mr-sm-2" type="text" value="' . get_search_query() . '" placeholder="' . esc_attr__('Search', 'bootstrap-for-genesis') . '..." name="s" id="s" />
	<button type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'bootstrap-for-genesis') .'" class="btn btn-outline-success my-2 my-sm-0">Search</button>
    </form>';
    return $form;
}
