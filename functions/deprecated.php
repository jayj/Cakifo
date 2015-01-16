<?php
/**
 * Deprecated functions that should be avoided in favor of newer functions.
 * These functions should not be used in child themes as they will all be
 * removed at some point.
 *
 * @since      Cakifo 1.6.0
 * @package    Cakifo
 * @subpackage Functions
 */

/**
 * Custom Background callback
 * @deprecated Cakifo 1.4.0
 */
function cakifo_custom_background_callback() {
	_deprecated_function( __FUNCTION__, 'Cakifo 1.4.0' );
	_custom_background_cb();
	return;
}

/**
 * Display RSS feed link in the topbar.
 *
 * No longer showed by default in version 1.3.
 * If you still want it, you should create your own
 * function
 *
 * @deprecated Cakifo 1.6.0
 * @return string  The RSS feed
 */
function cakifo_topbar_rss() {
	_deprecated_function( __FUNCTION__, 'Cakifo 1.6.0' );

	$rss = '<a href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '" class="rss-link">' . __( 'RSS', 'cakifo' ) . '</a>';

	echo '<div id="rss-subscribe">' . sprintf( __( 'Subscribe by %s', 'cakifo' ), $rss ) . '</div>';
}

/**
 * @deprecated Cakifo 1.4.0
 */
function cakifo_topbar_search() {
	_deprecated_function( __FUNCTION__, 'Cakifo 1.4.0', 'get_search_form()' );
	get_search_form();
}

/**
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

/**
 * Displays an attachment image's metadata and exif data while viewing a singular attachment page.
 *
 * @deprecated Cakifo 1.7.0 Use hybrid_media_meta() instead.
 */
function cakifo_image_info() {
	_deprecated_function( __FUNCTION__, 'Cakifo 1.7.0', 'hybrid_media_meta()' );

	echo '<div class="attachment-info image-info clearfix">';
		echo '<h3>' . __( 'Image Info', 'cakifo' ) . '</h3>';
		hybrid_media_meta();
	echo '</div> <!-- .attachment-info -->';
}

/**
 * @deprecated Cakifo 1.7.0
 */
function cakifo_load_customize_controls() {}

/**
 * @deprecated Cakifo 1.7.0
 */
function cakifo_admin_header_image() {}

/**
 * @deprecated Cakifo 1.7.0
 */
function cakifo_admin_header_style() {}
