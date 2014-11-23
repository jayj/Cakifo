<?php
/**
 * Functions for registering and setting theme settings that tie into the WordPress theme customizer.
 *
 * @package Cakifo
 * @subpackage Functions
 * @since Cakifo 1.6.0
 */

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'cakifo_customize_register' );
add_action( 'customize_preview_init', 'cakifo_customize_preview' );

/* Load custom control classes. */
add_action( 'customize_register', 'cakifo_load_customize_controls', 1 );

/**
 * Loads theme-specific customize control classes.  Customize control classes extend the WordPress
 * WP_Customize_Control class to create unique classes that can be used within the theme.
 *
 * @since Cakifo 1.5.0
 */
function cakifo_load_customize_controls() {
	/* Loads the multiple select customize control class. */
	require_once( trailingslashit( THEME_DIR ) . 'functions/customize-control-multiple-select.php' );
}

/**
 * Registers custom sections, settings, and controls for the $wp_customize instance.
 *
 * @since Cakifo 1.4.0
 * @param object $wp_customize
 */
function cakifo_customize_register( $wp_customize ) {

	$prefix = hybrid_get_prefix();

	// Get the default theme settings.
	$defaults = hybrid_get_default_theme_settings();


	/* Enable live preview for WordPress theme features. */
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_image' )->transport     = 'postMessage';


	/*
	 * Add the Headline Lists section.
	 */
	$wp_customize->add_section(
		'cakifo_customize_headlines_settings',
		array(
			'title'           => esc_html__( 'Headline Lists', 'cakifo' ),
			'priority'        => 125, // After the static front page section
			'capability'      => 'edit_theme_options',
			'active_callback' => 'cakifo_is_front_page_template',
		)
	);



	/* Add the 'headlines_category' setting. */
	$wp_customize->add_setting(
		"{$prefix}_theme_settings[headlines_category]",
		array(
			'default'    => $defaults['headlines_category'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);

	/* Add the multiple select control for the 'headlines_category' setting. */
	$wp_customize->add_control(
		new Cakifo_Customize_Control_Multiple_Select_Headlines(
			$wp_customize,
			'headlines_category',
			array(
				'settings' => "{$prefix}_theme_settings[headlines_category]",
				'label'    =>  esc_html__( 'Headline Terms', 'cakifo' ),
				'section'  => 'cakifo_customize_headlines_settings',
				'type'     => 'cakifo-headlines-multiple-select'
			)
		)
	);

	/* Add the 'headlines_num_posts' setting. */
	$wp_customize->add_setting(
		"{$prefix}_theme_settings[headlines_num_posts]",
		array(
			'default'    => $defaults['headlines_num_posts'],
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);

	/* Add the text control for the 'headlines_num_posts' setting. */
	$wp_customize->add_control(
		'headlines_num_posts',
		array(
			'settings' => "{$prefix}_theme_settings[headlines_num_posts]",
			'label'    =>  esc_html__( 'Number of Headline posts', 'cakifo' ),
			'section'  => 'cakifo_customize_headlines_settings',
			'type'     => 'number',
			'input_attrs' => array(
				'min'   => 1,
			),
		)
	);

	/* Add the 'featured_show' setting. */
	/*
	 * Add the Slider section
	 */
	$wp_customize->add_section(
		'cakifo_customize_slider_settings',
		array(
			'title'    => esc_html__( 'Slider', 'cakifo' ),
			'priority' => 125, // After the static front page section
		)
	);
	$wp_customize->add_setting(
		"{$prefix}_theme_settings[featured_show]",
		array(
			'default' => $defaults['featured_show'],
			'type'    => 'option',
		)
	);

	/* Add the checkbox control for the 'featured_show' setting. */
	$wp_customize->add_control(
		'featured_show',
		array(
			'settings' => "{$prefix}_theme_settings[featured_show]",
			'label'    => esc_html__( 'Show "Featured Content" slider?', 'cakifo' ),
			'type'     => 'checkbox',
			'section'     => 'cakifo_customize_slider_settings',
		)
	);

	/* Add the 'featured_category' setting. */
	$wp_customize->add_setting(
		"{$prefix}_theme_settings[featured_category]",
		array(
			'default' => $defaults['featured_category'],
			'type'    => 'option',
		)
	);

	/* Add the select control for the 'featured_category' setting. */
	$wp_customize->add_control(
		'featured_category',
		array(
			'settings' => "{$prefix}_theme_settings[featured_category]",
			'label'    => esc_html__( 'Featured Category', 'cakifo' ),
			'type'     => 'select',
			'section'         => 'cakifo_customize_slider_settings',
			'choices'         => _cakifo_customize_get_categories(),
		)
	);

	/* Add the 'featured_posts' setting. */
	$wp_customize->add_setting(
		"{$prefix}_theme_settings[featured_posts]",
		array(
			'default' => $defaults['featured_posts'],
			'type'    => 'option',
		)
	);

	/* Add the text control for the 'featured_posts' setting. */
	$wp_customize->add_control(
		'featured_posts',
		array(
			'settings' => "{$prefix}_theme_settings[featured_posts]",
			'label'    =>  esc_html__( 'Number of posts in the slider', 'cakifo' ),
			'type'     => 'text',
			'section'         => 'cakifo_customize_slider_settings',
			'type'            => 'number',
			'input_attrs'     => array(
				'min' => -1
			),
		)
	);


/**
 * Gets all categories ready to be added as choices in a select field.
 *
 * @since Cakifo 1.7.0
 *
 * @return array
 */
function _cakifo_customize_get_categories() {
	$categories = array( '' => '' ); // add empty option add the start

	foreach ( get_categories() as $cat ) {
		$categories[$cat->term_id] = $cat->name;
	}

	return $categories;
}

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Cakifo 1.4.0
 */
function cakifo_customize_preview() {
	wp_enqueue_script( 'cakifo-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '1.7', true );
}

?>
