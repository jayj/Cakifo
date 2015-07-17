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
 * Gets the footer text from the settings.
 *
 * Provides backward compatibility with earlier versions of Cakifo by replacing
 * the standard Cakifo shortcodes with HTML alternatives.
 *
 * @since Cakifo 1.7.0
 * @return string The footer text
 */
function cakifo_footer_content() {
	$text = apply_atomic_shortcode( 'footer_content', hybrid_get_setting( 'footer_insert' ) );

	// Don't do anything if we've previously determined that the shortcodes has been replaced
	// or the Cakifo Compatibility plugins is activated
	if ( ! hybrid_get_setting( 'footer_has_shortcodes' ) || function_exists( 'cakifo_combat_add_shortcodes' ) ) {
		echo $text;
		return;
	}

	// Only replace shortcodes from the default footer text.
	$text = str_replace( '[the-year]', date( 'Y' ), $text );
	$text = str_replace( '[site-link]', cakifo_get_site_link(), $text );
	$text = str_replace( '[wp-link]', cakifo_get_wp_link(), $text );
	$text = str_replace( '[theme-link]', cakifo_get_theme_link(), $text );
	$text = str_replace( '[child-link]', cakifo_get_child_theme_link(), $text );

	// Update the footer text in the database
	$settings = get_option( hybrid_get_prefix() . '_theme_settings', hybrid_get_default_theme_settings() );

	$settings['footer_insert'] = $text;
	$settings['footer_has_shortcodes'] = false;

	update_option( hybrid_get_prefix() . '_theme_settings', $settings );

	echo apply_atomic_shortcode( 'footer_content', $text );
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

/**
 * Returns a link back to the site.
 *
 * @since  Cakifo 1.7.0
 * @author Justin Tadlock
 *
 * @return string
 */
function cakifo_get_site_link() {
	return sprintf( '<a class="site-link" href="%s" rel="home">%s</a>', esc_url( home_url() ), get_bloginfo( 'name' ) );
}

/**
 * Returns a link to WordPress.org.
 *
 * @since  Cakifo 1.7.0
 * @author Justin Tadlock
 *
 * @return string
 */
function cakifo_get_wp_link() {
	return sprintf( '<a class="wp-link" href="http://wordpress.org" title="%s">%s</a>', esc_attr__( 'State-of-the-art semantic personal publishing platform', 'cakifo' ), __( 'WordPress', 'cakifo' ) );
}

/**
 * Returns a link to the parent theme URI.
 *
 * @since  Cakifo 1.7.0
 * @author Justin Tadlock
 *
 * @return string
 */
function cakifo_get_theme_link() {
	$theme = wp_get_theme( get_template() );
	$uri   = $theme->get( 'ThemeURI' );
	$name  = $theme->display( 'Name', false, true );

	/* translators: Theme name. */
	$title = sprintf( __( '%s WordPress Theme', 'cakifo' ), $name );

	return sprintf( '<a class="theme-link" href="%s" title="%s">%s</a>', esc_url( $uri ), esc_attr( $title ), $name );
}

/**
 * Returns a link to the child theme URI.
 *
 * @since  Cakifo 1.7.0
 * @author Justin Tadlock
 *
 * @return string
 */
function cakifo_get_child_theme_link() {
	if ( ! is_child_theme() ) {
		return '';
	}

	$theme = wp_get_theme();
	$uri   = $theme->get( 'ThemeURI' );
	$name  = $theme->display( 'Name', false, true );

	/* translators: Theme name. */
	$title = sprintf( __( '%s WordPress Theme', 'cakifo' ), $name );

	return sprintf( '<a class="child-link" href="%s" title="%s">%s</a>', esc_url( $uri ), esc_attr( $title ), $name );
}
