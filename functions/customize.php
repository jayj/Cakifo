<?php
/**
 * Functions for registering and setting theme settings that tie into the WordPress theme customizer.
 *
 * @package Cakifo
 * @subpackage Functions
 * @since Cakifo 1.4
 */

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'cakifo_customize_register' );

/**
 * Registers custom sections, settings, and controls for the $wp_customize instance.
 *
 * @param object $wp_customize
 * @since Cakifo 1.4
 */
function cakifo_customize_register( $wp_customize ) {

	/* Get the theme prefix. */
	$prefix = hybrid_get_prefix();

	/* Get the default theme settings. */
	$defaults = hybrid_get_default_theme_settings();

	$wp_customize->get_setting('blogname')->transport = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport = 'postMessage';

	/* Add the Cakifo section */
	$wp_customize->add_section( 'cakifo_customize_settings', array(
		'title'      => esc_html__( 'Cakifo settings', 'cakifo' ),
		'priority'   => 35,
		'capability' => 'edit_theme_options'
	) );

	/**
	 * Link color
	 */
	$wp_customize->add_setting( "{$prefix}_theme_settings[link_color]", array(
		'default'           => cakifo_get_default_link_color(),
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'capability'        => 'edit_theme_options',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'    => esc_html__( 'Link Color', 'cakifo' ),
		'section'  => 'colors',
		'settings' => "{$prefix}_theme_settings[link_color]",
	) ) );

	/**
	 * Show slider?
	 */
	$wp_customize->add_setting( "{$prefix}_theme_settings[featured_show]", array(
		'default'    => false,
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'featured_show', array(
		'settings' => "{$prefix}_theme_settings[featured_show]",
		'label'    =>  esc_html__( 'Show "Featured Content" slider?', 'cakifo' ),
		'section'  => 'cakifo_customize_settings',
		'type'     => 'checkbox',
	) );

	/**
	 * Slider categories
	 */
	// Create category array
	foreach ( get_categories() as $cat ) {
		$categories[$cat->term_id] = $cat->name;
	}

	/* Add setting and control */
	$wp_customize->add_setting( "{$prefix}_theme_settings[featured_category]", array(
		'default'    => '',
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'featured_category', array(
		'settings' => "{$prefix}_theme_settings[featured_category]",
		'label'    =>  esc_html__( 'Featured Category:', 'cakifo' ),
		'section'  => 'cakifo_customize_settings',
		'type'     => 'select',
		'choices'  => $categories
	) );

	/**
	 * Number of posts in the slider
	 */
	$wp_customize->add_setting( "{$prefix}_theme_settings[featured_posts]", array(
		'default'    => 5,
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'featured_posts', array(
		'settings' => "{$prefix}_theme_settings[featured_posts]",
		'label'    =>  esc_html__( 'Number of posts', 'cakifo' ),
		'section'  => 'cakifo_customize_settings',
		'type'     => 'text',
	) );

	if ( $wp_customize->is_preview() && ! is_admin() )
		add_action( 'wp_footer', 'cakifo_customize_preview', 21 );
}

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 *
 * @since Cakifo 1.4
 */
function cakifo_customize_preview() {
	?>
	<script type="text/javascript">
		wp.customize('blogname',function( value ) {
			value.bind(function(to) {
				jQuery('#site-title span').html(to);
			});
		});
		wp.customize('blogdescription',function( value ) {
			value.bind(function(to) {
				jQuery('#site-description').html(to);
			});
		});
	</script>
	<?php
}

?>
