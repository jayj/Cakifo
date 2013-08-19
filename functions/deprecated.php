<?php
/**
 * Deprecated functions that should be avoided in favor of newer functions.
 * These functions should not be used in child themes as they will all be
 * removed at some point.
 *
 * @package Cakifo
 * @subpackage Functions
 * @since Cakifo 1.6.0
 */

/**
 * Custom Background callback
 *
 * @since Cakifo 1.3.0
 * @deprecated Cakifo 1.4.0
 */
function cakifo_custom_background_callback() {
	_deprecated_function( __FUNCTION__, '1.4.0' );
	_custom_background_cb();
	return;
}

/**
 * Display RSS feed link in the topbar.
 *
 * No longer showed by default in version 1.3.
 * If you still want it, you should create your own
 * functiion
 *
 * @since Cakifo 1.0.0
 * @deprecated 1.6.0
 * @return string The RSS feed
 */
function cakifo_topbar_rss() {
	_deprecated_function( __FUNCTION__, '1.6.0' );

	$rss = '<a href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '" class="rss-link">' . __( 'RSS', 'cakifo' ) . '</a>';

	echo '<div id="rss-subscribe">' . sprintf( __( 'Subscribe by %s', 'cakifo' ), $rss ) . '</div>';
}

/**
 * @since Cakifo 1.3.0
 * @deprecated 1.4.0
 */
function cakifo_topbar_search() {
	_deprecated_function( __FUNCTION__, '1.4.0', 'get_search_form()' );
	get_search_form();
}

/**
 * @since Cakifo 1.0.0
 * @deprecated Cakifo 1.3.0 Use wp_trim_words() instead.
 */
function cakifo_the_excerpt( $length = 55, $echo = true ) {
	_deprecated_function( __FUNCTION__, 'Cakifo 1.3.0', 'wp_trim_words()' );

	$more_link = apply_filters( 'excerpt_more', '...' ) . '<br /> <a href="' . get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) . '</a>';

	if ( $echo )
		echo wp_trim_words( get_the_excerpt(), $length, $more_link );
	else
		return wp_trim_words( get_the_excerpt(), $length, $more_link );
}
