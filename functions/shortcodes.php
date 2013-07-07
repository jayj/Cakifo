<?php
/**
 * Shortcodes bundled for use within the theme.  These shortcodes are not meant to be used with the post content
 * editor.  Their purpose is to make it easier for users to filter hooks without having to know too much PHP code
 * and to provide access to specific functionality in other (non-post content) shortcode-aware areas.
 *
 * @package Cakifo
 * @subpackage Functions
 */

/**
 * Registers new shortcodes
 *
 * @since Cakifo 1.0.0
 */
function cakifo_register_shortcodes() {
	add_shortcode( 'entry-format', 'cakifo_entry_format_shortcode' );

	/* Replace some Hybrid Core shortcodes */
	remove_shortcode( 'entry-published' );
	add_shortcode( 'entry-published', 'cakifo_entry_published_shortcode' );

	remove_shortcode( 'comment-published' );
	add_shortcode( 'comment-published', 'cakifo_comment_published_shortcode' );
}

add_action( 'init', 'cakifo_register_shortcodes', 15 );

/**
 * Displays the published date of an individual post in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array $attr
 * @since Cakifo 1.1.0
 */
function cakifo_entry_published_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'format' => get_option( 'date_format' ),
		'before' => '',
		'after'  => '',
	), $attr, 'entry-published' );

	$published = '<time class="published" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . get_the_date( $attr['format'] ) . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the published date of an individual comment in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array $attr
 * @since Cakifo 1.1.0
 */
function cakifo_comment_published_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr, 'comment-published' );

	$published = '<time class="published" datetime="' . esc_attr( get_comment_date( 'c' ) ) . '">' . get_comment_date() . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the post format of the current post
 *
 * @param array $attr
 * @since Cakifo 1.3.0
 */
function cakifo_entry_format_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr, 'entry-format' );

	return $attr['before'] . get_post_format() . $attr['after'];
}

?>
