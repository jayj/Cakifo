<?php
/**
 * General template functions.  These functions are for use throughout the theme's various template files.
 *
 * @package    Cakifo
 * @subpackage Functions
 */

/**
 * Determines if the current page has the front page template
 *
 * @since Cakifo 1.7.0
 *
 * @return bool
 */
function cakifo_is_front_page_template() {
	return is_page_template( 'template-front-page.php' );
}

/**
 * Determines if the slider is active
 *
 * @since  Cakifo 1.6.0
 *
 * @uses   apply_filters() Use the `cakifo_show_slider` filter to apply new logic to
 * whether the slider should load or not.
 * @return bool true|false
 */
function cakifo_is_active_slider() {
	$show_slider = hybrid_get_setting( 'featured_show' ) && cakifo_is_front_page_template();

	return (bool) apply_filters( 'cakifo_show_slider', $show_slider );
}

/**
 * Get the custom menu name by location.
 *
 * @since  Cakifo 1.7.0
 * @author David Chandra Purnama
 * @link   http://shellcreeper.com/
 *
 * @param  string    $location The location to get the menu name from.
 * @return string|bool         The menu name if it exists. False if not.
 */
function cakifo_get_menu_name( $location ) {

	$menus = get_registered_nav_menus();

	// If no menu available, bail early.
	if ( empty( $menus ) ) {
		return false;
	}

	// Check if menu is set.
	if ( has_nav_menu( $location ) ) {

		// Get menu locations.
		$locations = get_nav_menu_locations();

		// If location not set, return false.
		if ( ! isset( $locations[$location] ) ) {
			return false;
		}

		// Get the menu name.
		$menu_obj = get_term( $locations[$location], 'nav_menu' );

		return $menu_obj->name;
	}

	return false;
}

/**
 * Gets the permalink to the blog page.
 *
 * @since  Cakifo 1.7.0
 * @author Justin Tadlock
 *
 * @return string
 */
function cakifo_get_blog_page_url() {
	$blog_url = '';

	if ( 'posts' === get_option( 'show_on_front' ) ) {
		$blog_url = home_url();
	} elseif ( 0 < ( $page_for_posts = get_option( 'page_for_posts' ) ) ) {
		$blog_url = get_permalink( $page_for_posts );
	}

	return $blog_url;
}
}