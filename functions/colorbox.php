<?php
/**
 * Colorbox script
 *
 * Adds the Colorbox jQuery lightbox script to the theme if supported.
 * Supported by default, remove it in a child theme with:
 * `remove_theme_support( 'cakifo-colorbox' );`
 *
 * The selector is '.colorbox'
 *
 * @package    Cakifo
 * @subpackage Functions
 * @since      Cakifo 1.3.0
 * @link       http://jacklmoore.com/colorbox/
 */

add_action( 'wp_enqueue_scripts', 'cakifo_colorbox_script' );
add_action( 'wp_footer', 'cakifo_colorbox', 100 );

/**
 * Load the Colorbox script and style
 *
 * @since Cakifo 1.3.0
 */
function cakifo_colorbox_script() {

	// Use the .min stylesheet if SCRIPT_DEBUG is turned off.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'colorbox', THEME_URI . '/js/jquery.colorbox-min.js', array( 'jquery' ), '1.6.2', true );
	wp_enqueue_style( 'colorbox', trailingslashit( THEME_URI ) . "css/colorbox{$suffix}.css", array(), '1.6.2', 'screen' );
}

/**
 * Prints the Colorbox script in the footer
 *
 * @since Cakifo 1.3.0
 */
function cakifo_colorbox() {

	// All arguments: @link http://jacklmoore.com/colorbox/
	$defaults = array(
		'selector'       => '.colorbox',
		'maxWidth'       => '98%',
		'maxHeight'      => '95%',
		'opacity'        => '0.6',
		'fixed'          => true,
		'current'        => esc_js( sprintf( _x( '%1$s of %2$s', 'colorbox. 1: image number, 2: image total ', 'cakifo' ), '{current}', '{total}' ) ),
		'previous'       => esc_js( _x( 'Previous',                     'colorbox', 'cakifo' ) ),
		'next'           => esc_js( _x( 'Next',                         'colorbox', 'cakifo' ) ),
		'close'          => esc_js( _x( 'Close Lightbox',               'colorbox', 'cakifo' ) ),
		'slideshowStart' => esc_js( _x( 'Start slideshow',              'colorbox', 'cakifo' ) ),
		'slideshowStop'  => esc_js( _x( 'Stop slideshow',               'colorbox', 'cakifo' ) ),
		'xhrError'       => esc_js( _x( 'This content failed to load.', 'colorbox', 'cakifo' ) ),
		'imgError'       => esc_js( _x( 'This image failed to load.',   'colorbox', 'cakifo' ) ),
	);

	$args = array();

	/**
	 * Allows child themes to filter the Colorbox arguments.
	 *
	 * Usage:
	 * <code>
	 * 	add_filter( 'cakifo_colorbox_args', 'my_child_colorbox_args' );
	 *  function my_child_colorbox_args( $args ) {
	 * 		$args['selector'] = '.colorbox, .my-new-awesome-selector';
	 *		return $args;
	 *	 }
	 * </code>
	 *
	 * @param array $args The default Colorbox arguments
	 */
	$args = apply_filters( 'cakifo_colorbox_args', $args );

	$args = wp_parse_args( $args, $defaults );

	// Add button texts as screen reader text
	foreach ( array( 'previous', 'next', 'close', 'slideshowStart', 'slideshowStop' ) as $key ) {
		$args[$key] = sprintf( '<span class="screen-reader-text">%s</span>', $args[$key] );
	}

	// Get the CSS selector and remove it from the arguments
	$selector = $args['selector'];
	unset( $args['selector'] );

	$json = json_encode( $args );

	echo "<script>
			jQuery(document).ready(function($) {
				$('" . esc_js( $selector ) . "').colorbox(
					{$json}
				);
			});
		</script>";
}

?>
