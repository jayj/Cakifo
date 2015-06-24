<?php
/**
 * Media template functions. These functions are meant to handle various features needed in theme templates
 * for media and attachments.
 *
 * @package    Cakifo
 * @subpackage Functions
 */

/**
 * Get the values of all registered image sizes. Both the customs and the defaults.
 *
 * @since  Cakifo 1.3.0
 * @return array  All image sizes
 */
function cakifo_get_image_sizes() {
	global $_wp_additional_image_sizes;

	$builtin_sizes = array(
		'large'	=> array(
			'width'  => get_option( 'large_size_w' ),
			'height' => get_option( 'large_size_h' )
		),
		'medium' => array(
			'width'  => get_option( 'medium_size_w' ),
			'height' => get_option( 'medium_size_h' )
		),
		'thumbnail'	=> array(
			'width'  => get_option( 'thumbnail_size_w' ),
			'height' => get_option( 'thumbnail_size_h' ),
			'crop'   => (boolean) get_option( 'thumbnail_crop' )
		)
	);

	if ( $_wp_additional_image_sizes ) {
		return array_merge( $builtin_sizes, $_wp_additional_image_sizes );
	}

	return $builtin_sizes;
}


/**
 * Get the values of a specific image size
 *
 * @since  Cakifo 1.3.0
 * @param  string  $name  The unique name for the image size or a WP default.
 * @return array          Array containing 'width', 'height', 'crop'
 */
function cakifo_get_image_size( $name ) {
	$image_sizes = cakifo_get_image_sizes();

	if ( isset( $image_sizes[$name] ) ) {
		return $image_sizes[$name];
	}

	return false;
}


/**
 * Returns a set of image attachment links based on size.
 *
 * @since  Cakifo 1.5.0
 * @author Justin Tadlock
 * @link   http://justintadlock.com
 * @return string  Links to various image sizes for the image attachment.
 */
function cakifo_get_image_size_links() {

	// If not viewing an image attachment page, return.
	if ( ! wp_attachment_is_image( get_the_ID() ) ) {
		return;
	}

	// Set up an empty array for the links.
	$links = array();

	// Get the intermediate image sizes and add the full size to the array.
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	// Loop through each of the image sizes.
	foreach ( $sizes as $size ) {

		// Get the image source, width, height, and whether it's intermediate.
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		// Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size.
		if ( ! empty( $image ) && ( true === $image[3] || 'full' == $size ) ) {

			/* Translators: Media dimensions - 1 is width and 2 is height. */
			$label = sprintf( __( '%1$s &#215; %2$s', 'cakifo' ), absint( $image[1] ), absint( $image[2] ) );

			$links[] = array(
				'link'   => sprintf( '<a class="image-size-link" href="%s">%s</a>', esc_url( $image[0] ), $label ),
				'width'  => $image[1],
			);
		}
	}

	// Sort by width
	function sort_by_width( $a, $b ) {
	    return $a['width'] - $b['width'];
	}

	uasort( $links, 'sort_by_width' );

	// Join the links in a string and return.
	return join( ' <span class="sep">/</span> ', wp_list_pluck( $links, 'link' ) );
}
