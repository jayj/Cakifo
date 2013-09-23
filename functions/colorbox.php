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
 * @uses  wp_enqueue_script()
 * @uses  wp_enqueue_style()
 */
function cakifo_colorbox_script() {
	wp_enqueue_script( 'colorbox', THEME_URI . '/js/jquery.colorbox-min.js', array( 'jquery' ), '1.4.29', true );
	wp_enqueue_style( 'colorbox', THEME_URI . '/css/colorbox.css', array(), '1.4.29', 'screen' );
}

/**
 * Prints the Colorbox script in the footer
 *
 * @since Cakifo 1.3.0
 */
function cakifo_colorbox() {

	// All arguments @link http://jacklmoore.com/colorbox/
	$defaults = array(
		'selector'       => '.colorbox',
		'maxWidth'       => '90%',
		'maxHeight'      => '90%',
		'opacity'        => '0.6',
		'fixed'          => true,
		'slideshowStart' => '&#9654;', // Play symbol
		'slideshowStop'  => 'll', // Stop symbol
		'current'        => esc_js( sprintf( _x( 'Image %1$s of %2$s',  'colorbox', 'cakifo' ), '{current}', '{total}' ) ),
		'previous'       => esc_js( _x( 'Previous',                     'colorbox', 'cakifo' ) ),
		'next'           => esc_js( _x( 'Next',                         'colorbox', 'cakifo' ) ),
		'close'          => esc_js( _x( 'Close Lightbox',               'colorbox', 'cakifo' ) ),
		'xhrError'       => esc_js( _x( 'This content failed to load.', 'colorbox', 'cakifo' ) ),
		'imgError'       => esc_js( _x( 'This image failed to load.',   'colorbox', 'cakifo' ) ),
	);

	$args = array();

	/**
	 * Allows child themes to filter the arguments.
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
	 * @param array $args The Colorbox arguments
	 */
	$args = apply_filters( 'cakifo_colorbox_args', $args );

	// Parse incoming $args into an array and merge it with $defaults.
	$args = wp_parse_args( $args, $defaults );

	// Get the CSS selector and remove it from the arguments
	$selector = $args['selector'];
	unset( $args['selector'] );

	// JSON encode the arguments
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
