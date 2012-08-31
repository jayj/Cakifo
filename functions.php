<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and
 * sets up the supported features, default actions, and default filters.  If making customizations, users
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let
 * friends modify parent theme files ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package Cakifo
 * @subpackage Functions
 * @version 1.5-dev
 * @author Jesper Johansen <kontakt@jayj.dk>
 * @copyright Copyright (c) 2011-2012, Jesper Johansen
 * @link http://wpthemes.jayj.dk/cakifo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 */

/* Load the core theme framework */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook */
add_action( 'after_setup_theme', 'cakifo_theme_setup', 10 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters
 *
 * @since Cakifo 1.0
 */
function cakifo_theme_setup() {

	/* Get action/filter hook prefix */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	//add_theme_support( 'hybrid-core-template-hierarchy' );

	// Add Hybrid Core SEO if the (All in One SEO || HeadSpace2 SEO) plugin isn't activated (WordPress SEO is checked for in Hybrid Core)
	if ( ! class_exists( 'All_in_One_SEO_Pack' ) && ! class_exists( 'Headspace_Plugin' ) )
		add_theme_support( 'hybrid-core-seo' );

	/* Add theme support for framework extensions */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r', '3c-l', '3c-r', '3c-c' ) );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'dev-stylesheet' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	add_theme_support( 'custom-field-series' );
	//add_theme_support( 'cleaner-caption' );
	//add_theme_support( 'featured-header' );

	/* Add theme support for theme functions */
	add_theme_support( 'cakifo-sidebars', array( 'primary', 'secondary', 'subsidiary', 'subsidiary-two', 'subsidiary-three', 'after-single', 'after-singular', 'error-page' ) );
	add_theme_support( 'cakifo-shortcodes' );
	add_theme_support( 'cakifo-colorbox' );
	add_theme_support( 'cakifo-twitter-button' );

	/* Load Theme Settings */
	if ( is_admin() ) {
		require( trailingslashit( get_template_directory() ) . 'functions/admin.php' );
	}

	/* Load the customizer functions */
	require( trailingslashit( get_template_directory() ) . 'functions/customize.php' );

	/* Add theme support for WordPress features */
	add_theme_support( 'post-formats', array( 'aside', 'video', 'gallery', 'quote', 'link', 'image', 'status', 'chat' ) );
	add_theme_support( 'automatic-feed-links' );
	add_editor_style();

	/* Custom background */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e3ecf2',
		'default-image' => get_template_directory_uri() . '/images/bg.png'
	) );

	/* Set $content_width */
	hybrid_set_content_width( 630 );

	/* Set embed width/height defaults and $content_width for non-default layouts */
	add_filter( 'embed_defaults', 'cakifo_content_width' );

	/*
	 * Set new image sizes
	 *
	 * Small: For use in archives and searches
	 * Slider: For use in the slider
	 * Recent: For use in the recent posts
	 */
	add_image_size( 'small', apply_filters( 'small_thumb_width', 100 ), apply_filters( 'small_thumb_height', 100 ), true );
	add_image_size( 'slider', apply_filters( 'slider_image_width', 500 ), apply_filters( 'slider_image_height', 230 ), true );
	add_image_size( 'recent', apply_filters( 'recent_image_width', 190 ), apply_filters( 'recent_image_height', 130 ), true );

	/* Load JavaScript and CSS styles */
	add_action( 'wp_enqueue_scripts', 'cakifo_enqueue_script', 1 );
	add_action( 'wp_enqueue_scripts', 'cakifo_enqueue_style' );

	/* Link color from Theme Options */
	add_action( 'wp_head', 'cakifo_print_link_color_style' );

	/* Filter the body class */
	add_filter( 'body_class', 'cakifo_body_class' );

	/* Topbar search form */
	add_action( "{$prefix}_close_menu_primary", 'get_search_form' );

	/**
	 * If you want the old RSS and Twitter link, do this in your child theme:
	 * 		remove_action( "{$prefix}_close_menu_primary", 'get_search_form' );
	 * 		add_action( "{$prefix}_close_menu_primary", 'cakifo_topbar_rss' );
	*/

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'cakifo_disable_sidebars' );
	add_action( 'template_redirect', 'cakifo_one_column' );

	/* Add the breadcrumb trail just after the container is open */
	if ( current_theme_supports( 'breadcrumb-trail' ) ) {
		add_action( "{$prefix}_open_main", 'breadcrumb_trail' );
		add_filter( 'breadcrumb_trail_args', 'cakifo_breadcrumb_trail_args' );
	}

	/* Frontpage javascript loading */
	add_action( 'template_redirect', 'cakifo_front_page' );
	add_action( 'wp_footer', 'cakifo_slider_javascript', 100 );

	/* Change entry meta for certain post formats */
	add_filter( "{$prefix}_entry_meta_quote", 'cakifo_quote_entry_meta' );
	add_filter( "{$prefix}_entry_meta_aside", 'cakifo_aside_entry_meta' );
	add_filter( "{$prefix}_entry_meta_link", 'cakifo_link_entry_meta' );
	add_filter( "{$prefix}_entry_meta_image", 'cakifo_image_entry_meta' );

	/* Hide byline and/or entry meta for certain post formats */
	add_filter( "{$prefix}_byline_quote", '__return_false' );
	add_filter( "{$prefix}_byline_aside", '__return_false' );
	add_filter( "{$prefix}_byline_link", '__return_false' );
	add_filter( "{$prefix}_byline_status", '__return_false' );
	add_filter( "{$prefix}_entry_meta_status", '__return_false' );
	add_filter( "{$prefix}_byline_image", '__return_false' );

	/* Excerpt read more link */
	add_filter( 'excerpt_more', 'cakifo_excerpt_more' );

	/* Add Custom Field Series */
	if ( current_theme_supports( 'custom-field-series' ) )
		add_action( "{$prefix}_after_singular", 'custom_field_series' );

	/* Add an author box after singular posts */
	add_action( 'init', 'cakifo_place_author_box' );

	/* Get the Image arguments */
	add_filter( 'get_the_image_args', 'cakifo_get_the_image_arguments' );

	/* wp_list_comments() arguments */
	add_filter( "{$prefix}_list_comments_args" , 'cakifo_change_list_comments_args' );

	/* Filter default options */
	add_filter( "{$prefix}_default_theme_settings", 'cakifo_filter_default_theme_settings' );

	/* Filter the content of chat posts. */
	add_filter( 'the_content', 'cakifo_format_chat_content' );

	/* Auto-add paragraphs to the chat text. */
	add_filter( 'cakifo_post_format_chat_text', 'wpautop' );

	/* Filter the content of status posts */
	add_filter( 'the_content', 'cakifo_format_status_content' );

	/**
	 * Custom header for logo upload
	 */
	add_theme_support( 'custom-header', array(
		'width'                  => 400,
		'height'                 => 60,
		'flex-width'             => true,
		'flex-height'            => true,
		'default-text-color'     => apply_filters( 'cakifo_header_textcolor', cakifo_get_default_link_color_no_hash() ),
		'wp-head-callback'       => 'cakifo_header_style',
		'admin-head-callback'    => 'cakifo_admin_header_style',
		'admin-preview-callback' => 'cakifo_admin_header_image',
	) );

	// Register the logo from the parent theme images folder as the default logo
	register_default_headers( array(
		'logo' => array(
			'url'           => get_template_directory_uri() . '/images/logo.png',
			'thumbnail_url' => get_template_directory_uri() . '/images/logo.png',
			'description'   => __( 'Logo.png from the Cakifo images folder', 'cakifo' ),
			'width'         => 300,
			'height'        => 59
		)
	) );

	// If the user is using a child theme, register the logo.png from that as well
	if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/images/logo.png' ) ) {
		register_default_headers( array(
			'childtheme_logo' => array(
				'url'           => get_stylesheet_directory_uri() . '/images/logo.png',
				'thumbnail_url' => get_stylesheet_directory_uri() . '/images/logo.png',
				'description'   => __( 'Logo.png from the Cakifo child theme images folder', 'cakifo' ),
			)
		) );
	}
}

/**
 * Loads the theme functions if the theme/child theme syupports them.
 *
 * @since Cakifo 1.3
 */
function cakifo_load_theme_support() {
	$template_directory = trailingslashit( get_template_directory() );

	/* Load the Cakifo sidebars if supported */
	require_if_theme_supports( 'cakifo-sidebars', $template_directory . 'functions/sidebars.php' );

	/* Load Cakifo shortcodes if supported */
	require_if_theme_supports( 'cakifo-shortcodes', $template_directory . 'functions/shortcodes.php' );

	/* Load the Colorbox Script extention if supported. */
	require_if_theme_supports( 'cakifo-colorbox', $template_directory . 'functions/colorbox.php' );

	/* Load the Twitter Button extention if supported */
	require_if_theme_supports( 'cakifo-twitter-button', $template_directory . 'functions/tweet_button.php' );
}

add_action( 'after_setup_theme', 'cakifo_load_theme_support', 12 );

/**
 * Loads the theme JavaScript files
 *
 * It loads jQuery, Modernizr, and the Javascript
 * needed for this theme
 *
 * @since Cakifo 1.0
 */
function cakifo_enqueue_script() {
	/**
	 * Modernizr enables HTML5 elements & feature detects
	 *
	 * For more/fewer features and optimal performance in your child theme,
	 * use a custom Modernizr build: www.modernizr.com/download/
	 *
	 * Use wp_deregister_script( 'modernizr' ); and
	 * 	wp_enqueue_script( 'modernizr', CHILD_THEME_URI . '/js/modernizr-2.x.min.js', '', '2.x' );
	 * in your child theme functions.php
	 */
	wp_enqueue_script( 'modernizr', THEME_URI . '/js/modernizr.js', array(), '2.5.3' );

	/* jQuery */
	wp_enqueue_script( 'jquery' );

	/**
	 * Loads the theme javascript
	 */
	wp_enqueue_script( 'cakifo-theme', THEME_URI . '/js/script.js', array( 'jquery' ), '1.4', true );
}

/**
 * Loads fonts from the Google Font API
 *
 * Adds a bbPress stylesheet as well if the plugin is active
 *
 * @since Cakifo 1.0
 */
function cakifo_enqueue_style() {
	$scheme = is_ssl() ? 'https' : 'http';

	wp_enqueue_style( 'PT-Serif', $scheme . '://fonts.googleapis.com/css?family=PT+Serif:regular,italic,bold' );

	// Add a new bbPress stylesheet, if the plugin is active
	if ( class_exists( 'bbPress' ) ) :
		wp_dequeue_style( 'bbpress-style' );
		wp_enqueue_style( 'bbp-cakifo-bbpress', THEME_URI . '/css/bbpress.css', array(), '1.3', 'screen' );
	endif;
}

/**
 * Front Page stuff
 *
 * Adds JavaScript to the frontpage and
 * removes the breadcrumb menu.
 *
 * @since Cakifo 1.0
 */
function cakifo_front_page() {
	$prefix = hybrid_get_prefix();

	/* If we're not looking at the front page, return */
	if ( ! is_home() && ! is_front_page() )
		return;

	/* Load the Flexslider jQuery Plugin */
	if ( hybrid_get_setting( 'featured_show' ) )
		wp_enqueue_script( 'flexslider', THEME_URI . '/js/jquery.flexslider.js', array( 'jquery' ), '2.0', true );

	/* Remove the breadcrumb trail */
	remove_action( "{$prefix}_open_main", 'breadcrumb_trail' );
}

/**
 * Add the javascript needed for the slider
 *
 * @uses apply_filters() The cakifo_flexslider_args filter allows you to change the default values.
 * @since Cakifo 1.0
 */
function cakifo_slider_javascript() {

	/* If we're not looking at the front page, return */
	if ( ! is_home() && ! is_front_page() )
		return;

	//* If slider is disabled, return */
	if ( ! hybrid_get_setting( 'featured_show' ) )
		return;

	/**
	 * Default arguments
	 * @link http://www.woothemes.com/flexslider/
	 */
	$defaults = array(
		'selector'            => '.slides-container > .slide',	// Selector: Must match a simple pattern. '{container} > {slide}' -- Ignore pattern at your own peril
		'animation'           => 'slide',		// String: Select your animation type, commonly "fade" or "slide." jQuery easing is supported!
		'slideshow'           => true,			// Boolean: Animate slider automatically
		'slideshowSpeed'      => 4000,			// Integer: Set the speed of the slideshow cycling, in milliseconds
		'pauseOnHover'        => true,			// Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
		'video'               => true,			// Boolean: If using video in the slider, will prevent CSS3 3D Transforms to avoid graphical glitches
		'prevText'            => esc_js( _x( 'Previous', 'slide', 'cakifo' ) ),		// String: Set the text for the "previous" directionNav item
		'nextText'            => esc_js( _x( 'Next',     'slide', 'cakifo' ) ),			// String: Set the text for the "next" directionNav item
		'pauseText'           => esc_js( _x( 'Pause',    'slide', 'cakifo' ) ),			// String: Set the text for the "pause" pausePlay item
		'playText'            => esc_js( _x( 'Play',     'slide', 'cakifo' ) ),			// String: Set the text for the "play" pausePlay item
		//'namespace'         => 'flex-',		// String: Prefix string attached to the class of every element generated by the plugin
		//'direction'         => 'horizontal',	// Select the sliding direction, "horizontal" or "vertical"
		//'reverse'           => false,			// Boolean Reverse the animation direction
		//'animationLoop'     => true,			// Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
		//'smoothHeight'      => false,			// Boolean: Allow height of the slider to animate smoothly in horizontal mode
		//'startAt'           => 0,				// Integer: The slide that the slider should start on. Array notation (0 = first slide)
		//'animationSpeed'    => 600,			// Integer: Set the speed of animations, in milliseconds
		//'initDelay'         => 0,				// Integer: Set an initialization delay, in milliseconds
		//'randomize'         => false,			// Boolean: Randomize slide order
		//'pauseOnAction'     => true,			// Boolean: Pause the slideshow when interacting with control elements, highly recommended.
		//'useCSS'            => true,			// Boolean: Slider will use CSS3 transitions if available
		//'touch'             => true,			// Boolean: Allow touch swipe navigation of the slider on touch-enabled devices
		//'controlNav'        => true,			// String|Boolean: Create navigation for paging control of each clide? 'thumbnails' will create a thumbnail pagination. Note: Leave true for manualControls usage
		//'directionNav'      => true,			// Boolean: Create navigation for previous/next navigation? (true/false)
		//'keyboard'          => true,			// Boolean: Allow slider navigating via keyboard left/right keys
		//'multipleKeyboard'  => false,			// Boolean: Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.
		//'mousewheel'        => false,			// Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
		//'pausePlay'         => false,			// Boolean: Create pause/play dynamic element
		//'controlsContainer' => '',			// Selector: USE CLASS SELECTOR. Declare which container the navigation elements should be appended too. Default container is the FlexSlider element. Example use would be ".flexslider-container". Property is ignored if given element is not found.
		//'manualControls'    => '',			// Selector: Declare custom control navigation. Examples would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
		//'sync'              => '',			// Selector: Mirror the actions performed on this slider with another slider. Use with care.
		//'asNavFor'          => '',			// Selector: Internal property exposed for turning the slider into a thumbnail navigation for another slider
		//'itemWidth'         => 0,				// Integer: Box-model width of individual carousel items, including horizontal borders and padding.
		//'itemMargin'        => 0,				// Integer: Margin between carousel items.
		//'minItems'          => 0,				// Integer: Minimum number of carousel items that should be visible. Items will resize fluidly when below this.
		//'maxItems'          => 0,				// Integer: Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.
		//'move'              => 0,				// Integer: Number of carousel items that should move on animation. If 0, slider will move all visible items.
	);

	$args = array();

	/**
	 * Use the cakifo_flexslider_args filter to filter the defaults
	 * You can't change the Flexslider callbacks
	 *
	 * For more information about the arguments, see:
	 *
	 * @link https://github.com/jayj/Cakifo/wiki/Child-themes
	 * @link http://www.woothemes.com/flexslider/
	 */
	$args = apply_filters( 'cakifo_flexslider_args', $args );

	// Parse incoming $args into an array and merge it with $defaults
	$args = wp_parse_args( $args, $defaults );

	// JSON encode the arguments
	$json = json_encode( $args );

	echo "<script>
			jQuery(document).ready(function($) {
				$('#slider').flexslider(
					{$json}
				);
			});
		</script>";
}

/**
 * Change the thumbnail size to 'small' for archives and search pages.
 *
 * @param  array  $args The 'Get the Image' arguments
 * @return array        The filtered arguments
 * @since  Cakifo 1.1
 */
function cakifo_get_the_image_arguments( $args ) {

	if ( is_archive() || is_search() ) {
		$args['size'] = 'small';
		$args['image_class'] = 'thumbnail';
	}

	return $args;
}

/**
 * Change the arguments of wp_list_comments()
 *
 * @param  array  $args The wp_list_comments() arguments
 * @return array        The filtered wp_list_comments() arguments
 * @since  Cakifo 1.3
 */
function cakifo_change_list_comments_args( $args ) {
	$args['avatar_size'] = 48;
	return $args;
}

/**
 * Edit the "More link" for archive excerpts.
 *
 * @param  string  $more The default more link
 * @return string        The changed more link with a more descriptive text
 * @since  Cakifo 1.0
 */
function cakifo_excerpt_more( $more ) {
	global $post;

	if ( is_archive() )
		$more = '<p><a href="'. get_permalink( $post->ID ) . '" class="more-link">' .  __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) . '</a></p>';

	return $more;
}

/**
 * Custom breadcrumb trail arguments.
 *
 * @param  array  $args The 'Breadcrumb' arguments
 * @return array        The filtered 'Breadcrumb' arguments
 * @since  Cakifo 1.0
 */
function cakifo_breadcrumb_trail_args( $args ) {
	$args['before'] = __( 'You are here:', 'cakifo' ); // Change the text before the breadcrumb trail
	return $args;
}

/**
 * Change entry meta for the Quote post format.
 *
 * @param  string  $meta The normal entry meta
 * @return string        The changed entry meta
 * @since  Cakifo 1.1
 */
function cakifo_quote_entry_meta( $meta ) {
	if ( is_single() )
		return do_shortcode( '<footer class="entry-meta">' . __( 'Posted by [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );

	return do_shortcode( '<footer class="entry-meta">' . __( '[entry-permalink] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
}

/**
 * Change entry meta for the Aside post format.
 *
 * @param  string  $meta The normal entry meta
 * @return string        The changed entry meta
 * @since  Cakifo 1.1
 */
function cakifo_aside_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( '[entry-permalink after=" | "] By [entry-author] on [entry-published] [entry-terms taxonomy="category" before="in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
}

/**
 * Change entry meta for the Link post format.
 *
 * @param  string  $meta The normal entry meta
 * @return string        The changed entry meta
 * @since  Cakifo 1.1
 */
function cakifo_link_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( 'Link recommended by [entry-author] on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
}

/**
 * Change entry meta for the Image post format.
 *
 * @param  string  $meta The normal entry meta
 * @return string        The changed entry meta
 * @since  Cakifo 1.1
 */
function cakifo_image_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( '<div>[entry-published] by [entry-author] [entry-edit-link before="<br/>"]</div> <div>[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="<br />Tagged "] [entry-comments-link before="<br />"]</div>', 'cakifo' ) . '</footer>' );
}

/**
 * Display RSS feed link in the topbar.
 *
 * No longer showed by default in version 1.3
 * If you still want it, use this in your child theme:
 *	<code>
 * 		remove_action( "{$prefix}_close_menu_primary", 'get_search_form' );
 * 		add_action( "{$prefix}_close_menu_primary", 'cakifo_topbar_rss' );
 *	</code>
 *
 * @return string The RSS feed and maybe a Twitter link
 * @since  Cakifo 1.0
 */
function cakifo_topbar_rss() {
	echo apply_atomic_shortcode( 'rss_subscribe', '<div id="rss-subscribe">' . __( 'Subscribe by [rss-link] [twitter-username before="or "]', 'cakifo' ) . '</div>' );
}

/**
 * Add a search form to the topbar.
 *
 * @since Cakifo 1.3
 * @deprecated 1.4
 */
function cakifo_topbar_search() {
	_deprecated_function( __FUNCTION__, '1.4', 'get_search_form()' );
	get_search_form();
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since Cakifo 1.0
 */
function cakifo_one_column() {

	if ( ! is_active_sidebar( 'primary' ) && ! is_active_sidebar( 'secondary' ) )
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );

	elseif ( is_front_page() && ! is_home() ) // Static frontpage
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );

	elseif ( is_attachment() && 'layout-default' == theme_layouts_get_layout() )
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );
}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @param  string  $layout Not used.
 * @return string          Returns 'layout-1c'
 * @since  Cakifo 1.0
 */
function cakifo_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @param  array  $sidebars_widgets Array with all the widgets for all the sidebars
 * @return array                    Same array but with the primary and secondary sidebar removed
 * @since Cakifo 1.0
 */
function cakifo_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;

	if ( current_theme_supports( 'theme-layouts' ) ) {
		if ( 'layout-1c' == theme_layouts_get_layout() || is_404() ) {
			$sidebars_widgets['primary']   = false;
			$sidebars_widgets['secondary'] = false;
		}
	}

	return $sidebars_widgets;
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @uses hybrid_get_content_width()
 * @uses hybrid_set_content_width()
 * @uses theme_layouts_get_layout()
 * @param array $args Array with default embed settings
 * @since Cakifo 1.3
 */
function cakifo_content_width( $args ) {

	$args['width'] = hybrid_get_content_width();

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$layout = theme_layouts_get_layout();

		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout )
			$args['width'] = 490;
		elseif ( 'layout-3c-c' == $layout )
			$args['width'] = 500;
		elseif ( 'layout-1c' == $layout )
			$args['width'] = 940;

		/* Set $content_width */
		hybrid_set_content_width( $args['width'] );
	}

	return $args;
}

/**
 * Styles the header text displayed on the blog
 *
 * @since Cakifo 1.0
 */
function cakifo_header_style() {
	/* Get default text color */
	$text_color = get_theme_support( 'custom-header', 'default-text-color' );

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: get_theme_support( 'custom-header', 'default-text-color' ) is default, hide text (returns 'blank') or any hex value
	if ( $text_color == get_header_textcolor() )
		return;

	// If we get this far, we have custom styles. Let's do this. ?>

	<style type="text/css">
		<?php
			// Has the text been hidden?
			if ( ! display_header_text() && ! get_header_image() ) :
		?>
			#site-title,
			#site-description {
				position: absolute !important;
				clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that
			elseif ( 'blank' != get_header_textcolor() ) :
		?>
			#site-title a,
			#site-description {
				color: #<?php echo get_header_textcolor(); ?> !important;
			}
		<?php endif; ?>
	</style>

	<?php
}

/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @since Cakifo 1.4
 */
function cakifo_admin_header_image() { ?>

	<div id="headimg">
		<?php
			if ( display_header_text() )
				$style = ' style="color:#' . get_header_textcolor() . ';"';
			else
				$style = ' style="display:none;"';

			$header_image = get_header_image();
		?>

		<h1>
			<a id="name" onclick="return false;" href="<?php echo esc_url( home_url() ); ?>">
				<?php if ( ! empty( $header_image ) ) : ?>
					<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
				<?php endif; ?>
				<span class="displaying-header-text" <?php echo $style; ?>><?php bloginfo( 'name' ); ?></span>
			</a>
		</h1>

		<h2 id="desc" class="displaying-header-text"><?php bloginfo( 'description' ); ?></h2>

		<br class="clear" />
	</div>
<?php }

/**
 * Styles the header image and text on the Header admin screen
 *
 * @since Cakifo 1.0
 */
function cakifo_admin_header_style() { ?>

	<style type="text/css">
		.appearance_page_custom-header #headimg {
			border: none;
			max-width: 980px;
			margin-bottom: 25px;
		}

		#headimg h1 {
			font-family: Georgia, "Times New Roman", Times, serif;
			float: left;
			margin: 0;
		}

		#headimg h1 a {
			font-size: 46px;
			font-weight: normal;
			line-height: 36px;
			text-decoration: none;
			letter-spacing: -2px;
		}

		#desc {
			color: #666;
			font: 21px 'PT Serif', Georgia, serif;
			float: right;
			margin: 15px 0 0 0;
			color: #7a7a7a;
			padding: 4px 6px;
		}
	</style> <?php
}

/**
 * Custom Background callback
 *
 * @since Cakifo 1.3
 * @deprecated Cakifo 1.4
 */
function cakifo_custom_background_callback() {
	_deprecated_function( __FUNCTION__, '1.4' );
	_custom_background_cb();
	return;
}

/**
 * Allow the user to upload a new logo or change between image and text
 * using the WordPress header function
 *
 * @return string The site title. Either as text, as an image or both.
 * @since  Cakifo 1.0
 */
function cakifo_logo() {

	/* Get the site title */
	$title = get_bloginfo( 'name' );

	/* Check if there's a header image, else return the blog name */
	$maybe_image = ( get_header_image() ) ? '<img src="' . get_header_image() . '" alt="' . esc_attr( $title ) . '" /><span class="assistive-text">' . $title . '</span>' : '<span>' . $title . '</span>';

	/* If 'Show header text with your image' is checked, add the 'display-header-text' to the heading */
	$heading_class = ( display_header_text() ) ? 'display-header-text' : '';

	/* Format the output */
	$output = '<h1 id="site-title" class="' . esc_attr( $heading_class ) . '"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home">' . $maybe_image . '</a></h1>';

	/* Display the site title and allow child themes to overwrite the final output */
	echo apply_atomic( 'site_title', $output );
}

if ( ! function_exists( 'cakifo_author_box' ) ) :
/**
 * Function to add an author box
 *
 * @since Cakifo 1.0
 */
function cakifo_author_box() { ?>

	<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>

		<?php do_atomic( 'before_author_box' ); // cakifo_before_author_box ?>

		<div class="author-profile vcard">

			<?php do_atomic( 'open_author_box' ); // cakifo_open_author_box ?>

			<h4 class="author-name fn n"><?php echo do_shortcode( __( 'Article written by [entry-author]', 'cakifo' ) ); ?></h4>

			<?php echo get_avatar( get_the_author_meta( 'user_email' ), '48' ); ?>

			<div class="author-description author-bio">
				<?php the_author_meta( 'description' ); ?>
			</div>

			<?php if ( get_the_author_meta( 'twitter' ) ) { ?>
				<p class="twitter-link">
					<a href="http://twitter.com/<?php the_author_meta( 'twitter' ); ?>" title="<?php printf( esc_attr__( 'Follow %1$s on Twitter', 'cakifo' ), get_the_author_meta( 'display_name' ) ); ?>">
						<?php printf( __( 'Follow %1$s on Twitter', 'cakifo' ), get_the_author_meta( 'display_name' ) ); ?>
					</a>
				</p>
			<?php } // End check for twitter ?>

			<?php do_atomic( 'close_author_box' ); // cakifo_close_author_box ?>

		</div> <!-- .author-profile -->

		<?php do_atomic( 'after_author_box' ); // cakifo_after_author_box

	endif;
}
endif; // cakifo_author_box

/**
 * Place the author box at the end of single posts
 *
 * @since Cakifo 1.3
 */
function cakifo_place_author_box() {
	$prefix = hybrid_get_prefix();

	if ( is_active_sidebar( 'after-single' ) )
		add_action( "{$prefix}_before_sidebar_single", 'cakifo_author_box' );
	else
		add_action( "{$prefix}_singular-post_after_singular", 'cakifo_author_box' );
}

/**
 * Displays an attachment image's metadata and exif data while viewing a singular attachment page.
 *
 * Note: This function will most likely be restructured completely in the future.  The eventual plan is to
 * separate each of the elements into an attachment API that can be used across multiple themes.  Keep
 * this in mind if you plan on using the current filter hooks in this function.
 *
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @since Cakifo 1.0
 */
function cakifo_image_info() {

	/* Set up some default variables and get the image metadata. */
	$meta  = wp_get_attachment_metadata( get_the_ID() );
	$items = array();
	$list  = '';

	// If there's no image meta, return
	if ( empty( $meta ) )
		return;

	/* Add the width/height to the $items array. */
	$items['dimensions'] = array(  _x( 'Dimensions', 'image dimensions', 'cakifo' ), '<a href="' . wp_get_attachment_url() . '">' . sprintf( _x( '%1$s &#215; %2$s pixels', 'image dimensions', 'cakifo' ), $meta['width'], $meta['height'] ) . '</a>' );

	/* If a timestamp exists, add it to the $items array */
	if ( ! empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = array(  _x( 'Date', 'image creation', 'cakifo' ), date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $meta['image_meta']['created_timestamp'] ) );

	/* If a camera exists, add it to the $items array */
	if ( ! empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = array(  __( 'Camera', 'cakifo' ), $meta['image_meta']['camera'] );

	/* If an aperture exists, add it to the $items array */
	if ( ! empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = array(  __( 'Aperture', 'cakifo' ), sprintf( __( 'f/%s', 'cakifo' ), $meta['image_meta']['aperture'] ) );

	/* If a focal length is set, add it to the $items array */
	if ( ! empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = array(  __( 'Focal Length', 'cakifo' ), sprintf( __( '%s mm', 'cakifo' ), $meta['image_meta']['focal_length'] ) );

	/* If an ISO is set, add it to the $items array */
	if ( ! empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = array(  __( 'ISO', 'cakifo' ), $meta['image_meta']['iso'] );

	/* If a shutter speed is given, format the float into a fraction and add it to the $items array */
	if ( ! empty( $meta['image_meta']['shutter_speed'] ) ) {

		if ( ( 1 / $meta['image_meta']['shutter_speed'] ) > 1 ) {
			$shutter_speed = '1/';

			if ( number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1 ) ==  number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0 ) )
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0, '.', '' );
			else
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1, '.', '' );
		} else {
			$shutter_speed = $meta['image_meta']['shutter_speed'];
		}

		$items['shutter_speed'] = array(  __( 'Shutter Speed', 'cakifo' ), sprintf( __( '%s sec', 'cakifo' ), $shutter_speed ) );
	}

	/**
	 * Allow child themes to overwrite the array of items
	 * @note Changed name to 'image_meta_items' in Version 1.3
	 */
	$items = apply_atomic( 'image_meta_items', $items );

	/* Loop through the items, wrapping the first item in the array in <dt> and the second in <dd> */
	foreach ( $items as $item ) {
		$list .= "<dt>{$item[0]}</dt>";
		$list .= "<dd>{$item[1]}</dd>";
	}

	/* Format the HTML output of the function */
	$output = '<div class="image-info">
					<h4>' . __( 'Image Info', 'cakifo' ) . '</h4>
					<dl>' . $list . '</dl>
				</div> <!-- .image-info -->';

	/* Display the image info and allow child themes to overwrite the final output */
	echo apply_atomic( 'image_info', $output );
}

/**
 * Get the values of all registered image sizes. Both the custom and the default
 *
 * @return array  An array of all the images sizes
 * @since Cakifo 1.3
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

	if ( $_wp_additional_image_sizes )
		return array_merge( $builtin_sizes, $_wp_additional_image_sizes );

	return $builtin_sizes;
}

/**
 * Get the values of a specific image size
 *
 * @param  string  $name The unique name for the image size or a WP default
 * @return array       	 Array containing 'width', 'height', 'crop'
 * @since Cakifo 1.3
 */
function cakifo_get_image_size( $name ) {

	$image_sizes = cakifo_get_image_sizes();

	if ( isset( $image_sizes[$name] ) )
		return $image_sizes[$name];

	return false;
}

if ( ! function_exists( 'cakifo_url_grabber' ) ) :
/**
 * Returns URLs found in the content
 *
 * 'href' will look for links inside anchor tags
 * 'http' will look for all text containing http:// or https:// in the content
 * 'all' will look for all links
 *
 * @param string $type http, href or all. Default is all
 * @param string $content The content to search for URLs in. Default is the post content.
 * @return array Array containing all links found in the content.
 * @since Cakifo 1.0
 */
function cakifo_url_grabber( $type = 'all', $content = null ) {

	/* Set default content to post content */
	if ( ! isset( $content ) )
		$content = get_the_content();

	/* Get all URLs from <a href=""> */
	if ( 'href' == $type ) {
		if ( ! preg_match_all( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', $content, $matches ) )
			return false;

		return array_map( 'esc_url_raw', $matches[1] );
	}

	/* Get all http:// URLs (excluding those in <a href="">) */
	elseif ( 'http' == $type ) {
		if ( ! preg_match_all( '/([^\"=\'>])((https?):\/\/[^\s<]+[^\s<\.)])/i', $content, $matches ) )
			return false;

		return array_map( 'esc_url_raw', $matches[0] );
	}

	/* Else, get all links */
	if ( ! preg_match_all( '/(\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i', $content, $matches ) )
		return false;

	return array_map( 'esc_url_raw', $matches[0] );
}
endif; // cakifo_url_grabber

/**
 * @since Cakifo 1.0
 * @deprecated Cakifo 1.3 Use the native WordPress function wp_trim_words() instead.
 * @ignore
 */
function cakifo_the_excerpt( $length = 55, $echo = true ) {
	_deprecated_function( __FUNCTION__, '1.3', 'wp_trim_words()' );

	$more_link = apply_filters( 'excerpt_more', '...' ) . '<br /> <a href="' . get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) . '</a>';

	if ( $echo )
		echo wp_trim_words( get_the_excerpt(), $length, $more_link );
	else
		return wp_trim_words( get_the_excerpt(), $length, $more_link );
}

if ( ! function_exists( 'cakifo_get_default_link_color' ) ) :
/**
 * Returns the default link color for Cakifo
 *
 * @return string The default color
 * @since Cakifo 1.4
 */
function cakifo_get_default_link_color() {
	return '#3083aa';
}
endif; // cakifo_get_default_link_color

if ( ! function_exists( 'cakifo_get_default_link_color_no_hash' ) ) :
/**
 * Returns the default link color for Cakifo with no hash
 *
 * @return string The default color with no hash
 * @since Cakifo 1.4
 */
function cakifo_get_default_link_color_no_hash() {
	return '3083aa';
}
endif; // cakifo_get_default_link_color_no_hash

/**
 * Filter the default theme settings
 *
 * @param  array  $settings Array with default settings
 * @return array            Array with the filtered default settings
 * @since Cakifo 1.4
 */
function cakifo_filter_default_theme_settings( $settings ) {
	$settings['link_color'] = cakifo_get_default_link_color();

	return $settings;
}

/**
 * Add a style block to the theme for the current link color.
 *
 * This function is attached to the wp_head action hook.
 *
 * @since Cakifo 1.4
 */
function cakifo_print_link_color_style() {
	$defaults   = hybrid_get_default_theme_settings();
	$link_color = hybrid_get_setting( 'link_color' );

	// Don't do anything if the current link color is the default color for the current scheme
	if ( $link_color == $defaults['link_color'] )
		return;
?>
	<style>
		/* Link color */
		a,
		.entry-title a {
			color: <?php echo esc_attr( $link_color ); ?>;
		}
	</style>
<?php
}

/**
 * Extends the default WordPress body class to denote:
 * 1. White or empty background color to change the layout and spacing.
 *
 * @since Cakifo 1.4.4
 */
function cakifo_body_class( $classes ) {
	$background_color = get_background_color();

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	return $classes;
}

/**
 * This function filters the post content when viewing a post with the "chat" post format.  It formats the
 * content with structured HTML markup to make it easy for theme developers to style chat posts.  The
 * advantage of this solution is that it allows for more than two speakers (like most solutions).  You can
 * have 100s of speakers in your chat post, each with their own, unique classes for styling.
 *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $content The content of the post.
 * @return string $chat_output The formatted content of the post.
 * @since Cakifo 1.5
 */
function cakifo_format_chat_content( $content ) {
	global $_post_format_chat_ids;

	/* If this is not a 'chat' post, return the content. */
	if ( ! has_post_format( 'chat' ) )
		return $content;

	/* Set the global variable of speaker IDs to a new, empty array for this chat. */
	$_post_format_chat_ids = array();

	/* Allow the separator (separator for speaker/text) to be filtered. */
	$separator = apply_filters( 'post_format_chat_separator', ':' );

	/* Open the chat transcript div and give it a unique ID based on the post ID. */
	$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';

	/* Split the content to get individual chat rows. */
	$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );

	/* Loop through each row and format the output. */
	foreach ( $chat_rows as $chat_row ) {

		/* If a speaker is found, create a new chat row with speaker and text. */
		if ( strpos( $chat_row, $separator ) ) {

			/* Split the chat row into author/text. */
			$chat_row_split = explode( ':', trim( $chat_row ), 2 );

			/* Get the chat author and strip tags. */
			$chat_author = strip_tags( trim( $chat_row_split[0] ) );

			/* Get the chat text. */
			$chat_text = trim( $chat_row_split[1] );

			/* Get the chat row ID (based on chat author) to give a specific class to each row for styling. */
			$speaker_id = cakifo_format_chat_row_id( $chat_author );

			/* Open the chat row. */
			$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

			/* Add the chat row author. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard"><cite class="fn">' . apply_filters( 'cakifo_post_format_chat_author', $chat_author, $speaker_id ) . '</cite>' . $separator . '</div>';

			/* Add the chat row text. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</div>';

			/* Close the chat row. */
			$chat_output .= "\n\t\t\t\t" . '</div> <!-- .chat-row -->';
		}

		/**
		 * If no author is found, assume this is a separate paragraph of text that belongs to the
		 * previous speaker and label it as such, but let's still create a new row.
		 */
		else {

			/* Make sure we have text. */
			if ( ! empty( $chat_row ) ) {

				/* Open the chat row. */
				$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

				/* Don't add a chat row author.  The label for the previous row should suffice. */

				/* Add the chat row text. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'post_format_chat_text', $chat_row, $chat_author, $speaker_id ) ) . '</div>';

				/* Close the chat row. */
				$chat_output .= "\n\t\t\t</div> <!-- .chat-row -->";
			}
		}
	}

	/* Close the chat transcript div. */
	$chat_output .= "\n\t\t\t</div> <!-- .chat-transcript -->\n";

	/* Return the chat content and apply filters for developers. */
	return apply_filters( 'cakifo_post_format_chat_content', $chat_output );
}

/**
 * This function returns an ID based on the provided chat author name.  It keeps these IDs in a global
 * array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
 * that will be used in an HTML class for individual chat rows so they can be styled.  So, speaker "John"
 * will always have the same class each time he speaks.  And, speaker "Mary" will have a different class
 * from "John" but will have the same class each time she speaks.
 *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $chat_author Author of the current chat row.
 * @return int The ID for the chat row based on the author.
 * @since Cakifo 1.5
 */
function cakifo_format_chat_row_id( $chat_author ) {
	global $_post_format_chat_ids;

	/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
	$chat_author = strtolower( strip_tags( $chat_author ) );

	/* Add the chat author to the array. */
	$_post_format_chat_ids[] = $chat_author;

	/* Make sure the array only holds unique values. */
	$_post_format_chat_ids = array_unique( $_post_format_chat_ids );

	/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
	return absint( array_search( $chat_author, $_post_format_chat_ids ) ) + 1;
}

/**
 * This function filters the post content when viewing a post with the "status" post format.  It formats the
 * status with a .status-content wrapper and an avatar.  In case the content of the status is a embedded
 * Twitter status, it does nothing.
 *
 * @param string $content The content of the status.
 * @return string $content The formatted content of the status.
 * @since Cakifo 1.5
 */
function cakifo_format_status_content( $content ) {

	/* Check if we're displaying a 'status' post. */
	if ( has_post_format( 'status' ) ) :

		/* Match any Twitter embeds (<blockquote class="twitter-tweet") elements. */
		preg_match( '/<blockquote class=\"twitter.*?>/', $content, $matches );

		/* Store the post content in the $output variable */
		$output = $content;

		/* If no <blockquote class="twitter-tweet"> elements were found, wrap the entire content in a div and insert an avatar. */
		if ( empty( $matches ) ) {
			$content  = "\n\t\t\t\t" . '<div class="note status-content">';
			$content .= "\n\t\t\t\t\t" . '<a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '">' . get_avatar( get_the_author_meta( 'ID' ), apply_atomic( 'status_avatar', '48' ) ) . '</a>';
			$content .= "\n\t\t\t\t\t" . $output;
			$content .= "\n\t\t\t\t" . '</div> <!-- .status-content -->';
		}
	endif;

	return $content;
}


?>
