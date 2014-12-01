<?php
/**
 * Setup the WordPress core custom header feature.
 *
 * @package    Cakifo
 * @subpackage Functions
 */

/*
 * Custom header for logo upload.
 */
add_theme_support( 'custom-header',
	array(
		'width'              => 400,
		'height'             => 60,
		'flex-width'         => true,
		'flex-height'        => true,
		'default-text-color' => cakifo_get_default_link_color_no_hash(),
		'wp-head-callback'   => 'cakifo_header_style',
	)
);

/*
 * Register the default logo.
 */
register_default_headers( array(
	'logo' =>
		array(
			'description'   => __( 'Logo.png from the Cakifo images folder', 'cakifo' ),
			'url'           => get_template_directory_uri() . '/images/logo.png',
			'thumbnail_url' => get_template_directory_uri() . '/images/logo.png',
			'width'         => 300,
			'height'        => 59
		)
	)
);

/*
 * Register child theme default logo if images/logo.png exists.
 */
if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/images/logo.png' ) ) :
	register_default_headers( array(
		'childtheme_logo' =>
			array(
				'description'   => __( 'Logo.png from the Cakifo child theme images folder', 'cakifo' ),
				'url'           => get_stylesheet_directory_uri() . '/images/logo.png',
				'thumbnail_url' => get_stylesheet_directory_uri() . '/images/logo.png',
			)
		)
	);
endif;


/**
 * Styles the header image and text displayed on the blog.
 *
 * @since Cakifo 1.0.0
 */
function cakifo_header_style() {

	$default_text_color = get_theme_support( 'custom-header', 'default-text-color' );
	$header_text_color  = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( $header_text_color == $default_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this. ?>

	<style type="text/css" id="custom-header-css">
		<?php
			// Has the text been hidden?
			if ( ! display_header_text() ) :
		?>
			.site-title span {
				clip: rect(1px, 1px, 1px, 1px);
				width: 1px;
				height: 1px;
				overflow: hidden;
				position: absolute;
			}
		<?php
			// If the user has set a custom color for the text, use that
			elseif ( 'blank' != $header_text_color ) :
		?>
			.site-title,
			.site-description {
				color: #<?php echo $header_text_color; ?>;
			}

			.site-header a[rel="home"]:hover .site-title {
				color: #555;
			}
		<?php endif; ?>

		<?php
			// Hide the description if there's no header image and the text has been hidden
			if ( ! get_header_image() && ! display_header_text() ) :
		?>
			.site-description {
				position: absolute;
				clip: rect(1px 1px 1px 1px); /* IE7 */
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php endif; ?>
	</style>

	<?php
}


/**
 * Display the site title as logo and/or name.	What this function
 * returns depends on what the user has choosen in `Apperance > Header`.
 *
 * @since  Cakifo 1.0.0
 * @return string  The site title. Either as text, as an image or both.
 */
function cakifo_logo() {

	// Get the site title.
	$title = get_bloginfo( 'name' );
	$maybe_image = '';

	// Get header image.
	if ( get_header_image() ) {
		$maybe_image = '<img src="' . esc_url( get_header_image() ) . '" alt="' . esc_attr( $title ) . '" />';
	}

	// Generate the markup.
	$output = sprintf( '<h1 class="site-title" id="site-title">%s<span>%s</span></h1>',
		$maybe_image,
		$title
	);

	// Display the site title and allow child themes to overwrite the final output.
	echo apply_atomic( 'site_title', $output );
}
