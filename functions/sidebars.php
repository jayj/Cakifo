<?php
/**
 * Sets up the default theme sidebars if the child theme supports them.
 * These are a replacement of the default framework sidebars because
 * we're using HTML5
 *
 * Default sidebars: 'Primary', 'secondary', 'subsidiary', 'subsidiary-two',
 * 'subsidiary-three', 'after-single', 'after-singular', 'error-page'
 *
 * Themes may choose to use or not use these sidebars, create new sidebars, or
 * unregister individual sidebars.  A theme must register support for 'cakifo-sidebars' to use them
 *
 * @since Cakifo 1.2.0
 * @package Cakifo
 * @subpackage Functions
 */

/* Register widget areas */
add_action( 'widgets_init', 'cakifo_register_sidebars' );

/* Register the widgets */
add_action( 'widgets_init', 'cakifo_register_widgets' );

/**
 * Registers the default theme sidebars.  Child theme developers may optionally choose to support
 * these sidebars within their themes or add more custom sidebars to the mix.
 *
 * @since Cakifo 1.2.0
 * @uses register_sidebar() Registers a sidebar with WordPress.
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function cakifo_register_sidebars() {

	/* Get theme-supported sidebars */
	$supported_sidebars = get_theme_support( 'cakifo-sidebars' );

	/* If there is no array of sidebars IDs, return */
	if ( ! is_array( $supported_sidebars[0] ) )
		return;

	/* Set up some default sidebar arguments. */
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	);

	/* Set up an array of sidebars. */
	$theme_sidebars = array(
		'primary' => array(
			'name'        => _x( 'Primary', 'sidebar', 'cakifo' ),
			'description' => __( 'The main (primary) widget area, most often used as a sidebar.', 'cakifo' )
		),
		'secondary' => array(
			'name'        => _x( 'Secondary', 'sidebar', 'cakifo' ),
			'description' => __( 'The second most important widget area, most often used as a secondary sidebar.', 'cakifo' ),
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		),
		'subsidiary' => array(
			'name'        => _x( 'Footer Area One', 'sidebar', 'cakifo' ),
			'description' => __( 'A widget area loaded in the footer of the site.', 'cakifo' ),
		),
		'subsidiary-two' => array(
			'name'        => _x( 'Footer Area Two', 'sidebar', 'cakifo' ),
			'description' => __( 'A widget area loaded in the footer of the site.', 'cakifo' ),
		),
		'subsidiary-three' => array(
			'name'        => _x( 'Footer Area Three', 'sidebar', 'cakifo' ),
			'description' => __( 'A widget area loaded in the footer of the site.', 'cakifo' ),
		),
		'header' => array(
			'name'        => _x( 'Header', 'sidebar', 'cakifo' ),
			'description' => __( "Displayed within the site's header area.", 'cakifo' ),
		),
		'before-content' => array(
			'name'        => _x( 'Before Content', 'sidebar', 'cakifo' ),
			'description' => __( "Loaded before the page's main content area.", 'cakifo' ),
		),
		'after-content' => array(
			'name'        => _x( 'After Content', 'sidebar', 'cakifo' ),
			'description' => __( "Loaded after the page's main content area.", 'cakifo' ),
		),
		'after-singular' => array(
			'name'        => _x( 'After Singular', 'sidebar', 'cakifo' ),
			'description' => __( 'Loaded on singular post (page, attachment, etc.) views before the comments area.', 'cakifo' ),
		),
		'after-single' => array(
			'name'        => _x( 'After Single', 'sidebar', 'cakifo' ),
			'description' => __( 'Loaded on single post views, before the comments area.', 'cakifo' ),
		),
		'error-page' => array(
			'name'        => _x( 'Error Page', 'sidebar', 'cakifo' ),
			'description' => __( 'Loaded on 404 (Not found) error pages.', 'cakifo' ),
		)
	);

	/* Allow developers to filter the sidebars. */
	$theme_sidebars = apply_filters( hybrid_get_prefix() . '_theme_sidebars', $theme_sidebars );

	/* Loop through the supported sidebars. */
	foreach ( $supported_sidebars[0] as $sidebar ) {

		/* Make sure the given sidebar is one of the theme sidebars. */
		if ( isset( $theme_sidebars[ $sidebar ] ) ) {

			/* Allow developers to filter the default sidebar arguments. */
			$defaults = apply_filters( hybrid_get_prefix() . '_sidebar_defaults', $defaults, $sidebar );

			/* Parse the sidebar arguments and defaults. */
			$args = wp_parse_args( $theme_sidebars[ $sidebar ], $defaults );

			/* If no 'id' was given, use the $sidebar variable and sanitize it. */
			$args['id'] = ( isset( $args['id'] ) ? sanitize_key( $args['id'] ) : sanitize_key( $sidebar ) );

			/* Allow developers to filter the sidebar arguments. */
			$args = apply_filters( hybrid_get_prefix() . '_sidebar_args', $args, $sidebar );

			/* Register the sidebar. */
			register_sidebar( $args );
		}
	}
}

/**
 * Registers the theme widgets.
 *
 * @since Cakifo 1.3.0
 * @uses register_widget() Registers individual widgets with WordPress
 * @link http://codex.wordpress.org/Function_Reference/register_widget
 * @return void
 */
function cakifo_register_widgets() {

	/* Load the Related Posts widget class. */
	require_once( trailingslashit( get_template_directory() ) . 'functions/widget-related-posts.php' );

	/* Register the Related Posts widget. */
	register_widget( 'Cakifo_Widget_Related_Posts' );
}

/**
 * Change the default widgets in the Monster Widgets plugin
 * Otherwise the plugin results in error because Hybrid Core
 * deregisters the default WordPress widgets.
 *
 * @since Cakifo 1.4.0
 * @link http://wordpress.org/extend/plugins/monster-widget/ Monster Widgets
 */
function cakifo_monster_widgets() {

	$monster = new Monster_Widget();

	$widgets = array(
		array(
			'Hybrid_Widget_Archives',
			array(
				'title'    => __( 'Archives List', 'monster-widget' ),
				'limit'    => 5,
				'dropdown' => 0,
				'format'   => 'html',
			)
		),
		array(
			'Hybrid_Widget_Archives',
			array(
				'title'  => __( 'Archives Dropdown', 'monster-widget' ),
				'type'   => 'monthly',
				'format' => 'option',
			)
		),
		array(
			'Hybrid_Widget_Calendar',
			array(
				'title' => __( 'Calendar', 'monster-widget' ),
			)
		),
		array(
			'Hybrid_Widget_Categories',
			array(
				'title'        => __( 'Categories List', 'monster-widget' ),
				'number'       => 5,
				'hierarchical' => 1,
				'style'        => 'list'
			)
		),
		array(
			'Hybrid_Widget_Pages',
			array(
				'title'   => __( 'Pages', 'monster-widget' ),
				'sortby'  => 'menu_order',
				'exclude' => '',
			)
		),
		array(
			'Hybrid_Widget_Bookmarks',
			array(
				'title'            => __( 'Links', 'monster-widget' ),
				'show_description' => 1,
				'show_name'        => 1,
				'show_rating'      => 1,
				'show_images'      => 1,
			)
		),
		array(
			'WP_Widget_Meta',
			array(
				'title'   => __( 'Meta', 'monster-widget' ),
			)
		),
		array(
			'WP_Widget_Recent_Comments',
			array(
				'title'  => __( 'Recent Comments', 'monster-widget' ),
				'number' => 7,
			)
		),
		array(
			'Hybrid_Widget_Archives',
			array(
				'title'  => __( 'Recent Posts', 'monster-widget' ),
				'limit'  => 5,
				'type'   => 'postbypost',
				'format' => 'html',
			)
		),
		array(
			'WP_Widget_RSS',
			array(
				'title'        => __( 'RSS', 'monster-widget' ),
				'url'          => 'http://jayj.dk/feed',
				'items'        => 5,
				'show_author'  => true,
				'show_date'    => true,
				'show_summary' => true,
			)
		),
		array(
			'Hybrid_Widget_Search',
			array(
				'title'         => __( 'Search', 'monster-widget' ),
				'theme_search'  => 1,
				'search_submit' => esc_attr__( 'Search', 'cakifo' ),
			)
		),
		array(
			'WP_Widget_Text',
			array(
				'title'  => __( 'Text', 'monster-widget' ),
				'text'   => $monster->get_text(),
				'filter' => true,
			)
		),
		array(
			'Hybrid_Widget_Tags',
			array(
				'title'    => __( 'Tag Cloud', 'monster-widget' ),
				'taxonomy' => array( 'post_tag' ),
				'format' => 'flat'
			)
		),
	);

	if ( $menu = $monster->get_nav_menu() ) {
		$widgets[] = array(
			'Hybrid_Widget_Nav_Menu',
			array(
				'title'    => __( 'Nav Menu', 'monster-widget' ),
				'nav_menu' => $menu,
			)
		);
	}

	return $widgets;
}

add_filter( 'monster-widget-config', 'cakifo_monster_widgets' );

?>
