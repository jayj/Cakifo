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
	$sidebars = get_theme_support( 'cakifo-sidebars' );

	/* If there is no array of sidebars IDs, return */
	if ( ! is_array( $sidebars[0] ) )
		return;

	/* Set up the primary sidebar arguments */
	$primary = array(
		'id'            => 'primary',
		'name'          => _x( 'Primary', 'sidebar name', 'cakifo' ),
		'description'   => __( 'The main (primary) widget area, most often used as a sidebar.', 'cakifo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	);

	/* Set up the secondary sidebar arguments */
	$secondary = array(
		'id'            => 'secondary',
		'name'          => _x( 'Secondary', 'sidebar name', 'cakifo' ),
		'description'   => __( 'The second most important widget area, most often used as a secondary sidebar.', 'cakifo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the first footer sidebar arguments */
	$subsidiary_one = array(
		'id'            => 'subsidiary',
		'name'          => _x( 'Footer Area One', 'sidebar name', 'cakifo' ),
		'description'   => __( 'An optional widget area for your site footer.', 'cakifo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the second footer sidebar arguments */
	$subsidiary_two = array(
		'id'            => 'subsidiary-two',
		'name'          => _x( 'Footer Area Two', 'sidebar name', 'cakifo' ),
		'description'   => __( 'An optional widget area for your site footer.', 'cakifo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the third footer sidebar arguments */
	$subsidiary_three = array(
		'id'            => 'subsidiary-three',
		'name'          => _x( 'Footer Area Three', 'sidebar name', 'cakifo' ),
		'description'   => __( 'An optional widget area for your site footer.', 'cakifo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the header sidebar arguments */
	$header = array(
		'id'            => 'header',
		'name'          => _x( 'Header', 'sidebar name', 'cakifo' ),
		'description'   => __( 'Displayed within the site\'s header area.', 'cakifo' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the before content sidebar arguments */
	$before_content = array(
		'id'            => 'before-content',
		'name'          => _x( 'Before Content', 'sidebar name', 'cakifo' ),
		'description'   => __( 'Loaded before the page\'s main content area.', 'cakifo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the after content sidebar arguments */
	$after_content = array(
		'id'            => 'after-content',
		'name'          => _x( 'After Content', 'sidebar name', 'cakifo' ),
		'description'   => __( 'Loaded after the page\'s main content area.', 'cakifo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the after singular sidebar arguments */
	$after_singular = array(
		'id'            => 'after-singular',
		'name'          => _x( 'After Singular', 'sidebar name', 'cakifo' ),
		'description'   => __( 'Loaded on singular post (page, attachment, etc.) views before the comments area.', 'cakifo' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the after single sidebar arguments */
	$after_single = array(
		'id'            => 'after-single',
		'name'          => _x( 'After Single', 'sidebar name', 'cakifo' ),
		'description'   => __( 'Loaded on single post views before the comments area.', 'cakifo' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	/* Set up the 404 error page arguments */
	$error_page = array(
		'id'            => 'error-page',
		'name'          => _x( 'Error Page', 'sidebar name', 'cakifo' ),
		'description'   => __( 'Loaded on 404 error pages', 'cakifo' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	);

	/* Register the primary sidebar */
	if ( in_array( 'primary', $sidebars[0] ) )
		register_sidebar( $primary );

	/* Register the secondary sidebar */
	if ( in_array( 'secondary', $sidebars[0] ) )
		register_sidebar( $secondary );

	/* Register the first footer sidebar */
	if ( in_array( 'subsidiary', $sidebars[0] ) || in_array( 'footer', $sidebars[0] ) )
		register_sidebar( $subsidiary_one );

	/* Register the second footer sidebar */
	if ( in_array( 'subsidiary-two', $sidebars[0] ) || in_array( 'footer-two', $sidebars[0] ) )
		register_sidebar( $subsidiary_two );

	/* Register the third footer sidebar */
	if ( in_array( 'subsidiary-three', $sidebars[0] ) || in_array( 'footer-three', $sidebars[0] ) )
		register_sidebar( $subsidiary_three );

	/* Register the header sidebar */
	if ( in_array( 'header', $sidebars[0] ) )
		register_sidebar( $header );

	/* Register the before content sidebar */
	if ( in_array( 'before-content', $sidebars[0] ) )
		register_sidebar( $before_content );

	/* Register the after content sidebar */
	if ( in_array( 'after-content', $sidebars[0] ) )
		register_sidebar( $after_content );

	/* Register the after singular sidebar */
	if ( in_array( 'after-singular', $sidebars[0] ) )
		register_sidebar( $after_singular );

	/* Register the after singular sidebar */
	if ( in_array( 'after-single', $sidebars[0] ) )
		register_sidebar( $after_single );

	/* Register the error page sidebar */
	if ( in_array( 'error-page', $sidebars[0] ) )
		register_sidebar( $error_page );
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
