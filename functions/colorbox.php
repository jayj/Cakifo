<?php
/**
 * Colorbox script
 *
 * Adds the Colorbox jQuery lightbox script to the theme
 * if it supports it
 * Supported by default, remove it in a child theme with
 * remove_theme_support( 'cakifo-colorbox' );
 *
 * @package Cakifo
 * @subpackage Functions
 * @link http://colorpowered.com/colorbox/
 * @since 1.3
 */

add_action( 'wp_enqueue_scripts', 'cakifo_colorbox_script' );
add_action( 'wp_print_styles', 'cakifo_colorbox_style' );
add_action( 'wp_footer', 'cakifo_colorbox', 100 );

/**
 * Load the script
 */
function cakifo_colorbox_script() {
	wp_enqueue_script( 'colorbox', THEME_URI . '/js/jquery.colorbox-min.js', array( 'jquery' ), '1.3.17.2', true );
}

/**
 * Load the style
 */
function cakifo_colorbox_style() {
	wp_enqueue_style( 'colorbox', THEME_URI . '/css/colorbox.css', array(), '1.3', 'screen' );
}

/**
 * Prints the script in the footer
 */
function cakifo_colorbox() {

	/**
	 * See all arguments at http://colorpowered.com/colorbox/
	 */
	$defaults = array(
		'selector' => '.colorbox',
		'maxWidth' => '80%',
		'maxHeight' => '80%',
		'opacity' => '0.6', // For modern browsers that support CSS3 gradients this will be 1
		'fixed' => true,
		'slideshowStart' => '\u25B6',
		'slideshowStop' => 'll',
	);

	$args = array();

	/**
	 * Allow child themes to filter the arguments
	 * Use it like this:
	 *
	 * add_filter( 'cakifo_colorbox_args', 'my_child_colorbox_args' );
	 * function my_child_colorbox_args( $args ) {
	 * 		$args['selector'] = '.colorbox, .my-new-selector';
     *		return $args;
	 *	}
	 */
	$args = apply_filters( 'cakifo_colorbox_args', $args );

	/**
	 * Parse incoming $args into an array and merge it with $defaults
	 */
	$args = wp_parse_args( $args, $defaults );

	echo "<script type='text/javascript'>
			jQuery(document).ready(function($) {
				$('" . $args['selector'] . "').colorbox({ ";

				foreach ( $args as $arg => $val ) {

					// Don't add the selector argument to the list
					if ( $arg == 'selector' )
						continue;
	
					// Make sure true and false aren't encoded in quotes
					if ( $val === true )
						echo $arg . ' : true,' . "\n";
					elseif ( $val === false )
						echo $arg . ' : false,' . "\n";
					else
						echo $arg . ' : "' . $val . '",' . "\n";
				}

					echo 'foo: "bar" '; // IE7 fix

	echo "		});
			});
		</script>";
}

?>