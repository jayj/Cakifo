<?php
/**
 * Setup the Hybrid Core Theme Fonts extention.
 *
 * @package    Cakifo
 * @subpackage Functions
 */

/**
 * Add theme support for theme fonts.
 */
add_theme_support( 'theme-fonts', array(
	'callback'   => 'cakifo_register_fonts',
	'customizer' => true
));

/* Load custom fonts on the editor pages and Appearance > Header. */
add_action( 'theme_fonts_register', 'cakifo_admin_fonts_extra' );
add_filter( 'tiny_mce_before_init', 'cakifo_editor_font_classes' );

/**
 * Registers custom fonts for the Theme Fonts extension.
 *
 * @since  Cakifo 1.6.0
 * @param  object  $theme_fonts
 * @return void
 */
function cakifo_register_fonts( $theme_fonts ) {

	/* Add the 'body' font setting. */
	$theme_fonts->add_setting(
		array(
			'id'        => 'body',
			'label'     => __( 'Body text', 'cakifo' ),
			'default'   => 'georgia-font-stack-400',
			'selectors' => 'body',
		)
	);

	/* Add the 'headings' font setting. */
	$theme_fonts->add_setting(
		array(
			'id'        => 'headings',
			'label'     => __( 'Headings', 'cakifo' ),
			'default'   => 'pt-serif-700',
			'selectors' => 'h1, h2, h3, h4, h5, h6, caption, .main-navigation .menu-item > a, .format-status .entry-date',
		)
	);

	/* Add the 'site title' font setting. */
	$theme_fonts->add_setting(
		array(
			'id'        => 'title',
			'label'     => __( 'Site title and description', 'cakifo' ),
			'default'   => 'pt-serif-400',
			'selectors' => '.site-title, .site-description',
		)
	);

	/* Register fonts that users can select for this theme. */
	$fonts = array(
		array(
			'handle'  => 'georgia-font-stack',
			'label'   => __( 'Georgia', 'cakifo' ),
			'stack'   => 'Georgia, Cambria, "Bitstream Charter", serif',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'arial-font-stack',
			'label'   => __( 'Arial', 'cakifo' ),
			'stack'   => 'Arial, Helvetica, sans-serif',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'helvetica-font-stack',
			'label'   => __( 'Helvetica', 'cakifo' ),
			'stack'   => '"Helvetica Neue", Helvetica, Arial, sans-serif',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'helvetica-neue-font-stack',
			'label'   => __( 'Helvetica Neue', 'cakifo' ),
			'stack'   => '"HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif',
			'weights' => array( '300' )
		),
		array(
			'handle'  => 'segoe-font-stack',
			'label'   => __( 'Segoe UI', 'cakifo' ),
			'stack'   => '"Segoe UI", "Trebuchet MS", Verdana, sans-serif',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'trebuchet-font-stack',
			'label'   => __( 'Trebuchet', 'cakifo' ),
			'stack'   => '"Trebuchet MS", Verdana, sans-serif',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'pt-serif',
			'label'   => __( 'PT Serif', 'cakifo' ),
			'family'  => 'PT Serif',
			'stack'   => "'PT Serif', Georgia, serif",
			'type'    => 'google',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'merriweather',
			'label'   => __( 'Merriweather', 'cakifo' ),
			'family'  => 'Merriweather',
			'stack'   => "'Merriweather', serif",
			'type'    => 'google',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'roboto',
			'label'   => __( 'Roboto', 'cakifo' ),
			'family'  => 'Roboto',
			'stack'   => "'Roboto', sans-serif",
			'type'    => 'google',
			'weights' => array( '400', '700' )
		),
		array(
			'handle'  => 'open-sans',
			'label'   => __( 'Open Sans', 'cakifo' ),
			'family'  => 'Open Sans',
			'stack'   => "'Open Sans', sans-serif",
			'type'    => 'google',
			'weights' => array( '300', '400', '600', '700' )
		),
		array(
			'handle'  => 'oswald',
			'setting' => 'headings',
			'label'   => __( 'Oswald', 'cakifo' ),
			'family'  => 'Oswald',
			'stack'   => "'Oswald', sans-serif",
			'type'    => 'google',
			'weights' => array( '300', '400', '700' )
		),
	);

	/* Add each font and font weight. */
	foreach( $fonts as $font ) :

		foreach( $font['weights'] as $weight ) {

			$theme_fonts->add_font( array(
				'handle'  => $font['handle'] . "-{$weight}",
				'label'   => sprintf( '%s [%s]', $font['label'], cakifo_convert_font_weight( $weight ) ),
				'stack'   => $font['stack'],
				'weight'  => $weight,
				'family'  => ( isset( $font['family'] ) )  ? $font['family']  : '',
				'style'   => ( isset( $font['style'] ) )   ? $font['style']   : '',
				'setting' => ( isset( $font['setting'] ) ) ? $font['setting'] : '',
				'type'    => ( isset( $font['type'] ) )    ? $font['type']    : '',
				'uri'     => ( isset( $font['uri'] ) )     ? $font['uri']     : '',
			));

		}

	endforeach;
}

/**
 * Convert numeric font weights to more user-friendly names.
 *
 * @since  Cakifo 1.6.0
 * @param  string  $weight Numeric font weight
 * @return string          Font weight name
 */
function cakifo_convert_font_weight( $weight ) {
	$convert = array(
		'100'     => _x( 'Ultra light', 'font weight', 'cakifo' ),
		'200'     => _x( 'Thin',        'font weight', 'cakifo' ),
		'300'     => _x( 'Light',       'font weight', 'cakifo' ),
		'400'     => _x( 'Normal',      'font weight', 'cakifo' ),
		'500'     => _x( 'Medium',      'font weight', 'cakifo' ),
		'600'     => _x( 'Semi bold',   'font weight', 'cakifo' ),
		'700'     => _x( 'Bold',        'font weight', 'cakifo' ),
		'800'     => _x( 'Extra bold',  'font weight', 'cakifo' ),
		'900'     => _x( 'Ultra bold',  'font weight', 'cakifo' ),
		'normal'  => _x( 'Normal',      'font weight', 'cakifo' ),
		'bold'    => _x( 'Bold',        'font weight', 'cakifo' ),
		'bolder'  => _x( 'Bolder',      'font weight', 'cakifo' ),
		'lighter' => _x( 'Lighter',     'font weight', 'cakifo' ),
	);

	return $convert[$weight];
}

/**
 * Get the font name and weight from handle.
 *
 * The handles can be strings like:
 *    roboto-700
 *    pt-serif-400
 *    arial-normal
 *
 * @since  Cakifo 1.6.0
 * @param  string  $font_handle The font handle
 * @return array                Font name and weight
 */
function cakifo_get_font_info( $font_handle ) {
	$font = explode( '-', $font_handle );

	// The weight is the last element in the array.
	$weight = array_pop( $font );

	// Combine the rest of the array again into the name.
	$name = implode( '-', $font );

	return array(
		'name'   => $name,
		'weight' => $weight
	);
}

/**
 * Enqueue fonts selected in the theme customizer.
 *
 * @since  Cakifo 1.6.0
 * @param  object  $theme_fonts Theme_Fonts class
 */
function cakifo_admin_fonts_extra( $theme_fonts ) {
	global $cakifo_theme_fonts;

	$cakifo_theme_fonts = $theme_fonts;
	add_action( 'admin_enqueue_scripts', 'cakifo_admin_header_enqueue_fonts' );
}

/**
 * Enqueue the fonts on the post editor and header screens.
 *
 * @since Cakifo 1.6.0
 */
function cakifo_admin_header_enqueue_fonts( $hook_suffix ) {
	global $cakifo_theme_fonts;

	if ( in_array( $hook_suffix, array( 'appearance_page_custom-header', 'post-new.php', 'post.php' ) ) ) {
		$cakifo_theme_fonts->enqueue_styles();
	}
}

/**
 * Add font classes to the editor body class
 *
 * @since  Cakifo 1.6.0
 * @param  array  $settings
 * @return array
 */
function cakifo_editor_font_classes( $settings ) {

	// Get fonts.
	$body_font    = cakifo_get_font_info( get_theme_mod( 'theme_font_body' ) );
	$heading_font = cakifo_get_font_info( get_theme_mod( 'theme_font_headings' ) );

	// Set the body classes.
	$settings['body_class'] .= ' body-' . $body_font['name'];
	$settings['body_class'] .= ' heading-' . $heading_font['name'];

	$settings['body_class'] .= ' body-weight-' . $body_font['weight'];
	$settings['body_class'] .= ' heading-weight-' . $heading_font['weight'];

    return $settings;
}
