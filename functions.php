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
 * @version 1.3
 * @author Jayj.dk <kontakt@jayj.dk>
 * @copyright Copyright (c) 2011, Jesper J
 * @link http://wpthemes.jayj.dk/cakifo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
$theme = new Hybrid();

if ( ! isset( $content_width ) )
	$content_width = 630;

/* Do theme setup on the 'after_setup_theme' hook */
add_action( 'after_setup_theme', 'cakifo_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters
 *
 * @since 1.0
 */
function cakifo_theme_setup() {

	/* Get action/filter hook prefix */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	//add_theme_support( 'hybrid-core-drop-downs' ); // @todo Test if it works

	// Add Hybrid Core SEO if the (All in One SEO || HeadSpace2 SEO) plugin isn't activated (WordPress SEO is checked for in Hybrid Core)
	if ( ! class_exists( 'All_in_One_SEO_Pack' ) && ! class_exists( 'Headspace_Plugin' ) )
		add_theme_support( 'hybrid-core-seo' );

	/* Load the sidebars if supported */
	//add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'secondary', 'subsidiary' ) );
	add_theme_support( 'cakifo-sidebars', array( 'primary', 'secondary', 'subsidiary', 'after-single', 'after-singular', 'error-page' ) );
	require_if_theme_supports( 'cakifo-sidebars', trailingslashit( TEMPLATEPATH ) . 'functions/sidebars.php' );

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
	
	/* Load Cakifo shortcodes if supported */
	add_theme_support( 'cakifo-shortcodes' );
	require_if_theme_supports( 'cakifo-shortcodes', trailingslashit( THEME_DIR ) . 'functions/shortcodes.php' );

	/* Load the Colorbox Script extention if supported. */
	add_theme_support( 'cakifo-colorbox' );
	require_if_theme_supports( 'cakifo-colorbox', trailingslashit( THEME_DIR ) . 'functions/colorbox.php' );
	
	/* Load the Twitter Button extention if supported */
	add_theme_support( 'cakifo-twitter-button' );
	require_if_theme_supports( 'cakifo-twitter-button', trailingslashit( THEME_DIR ) . 'functions/tweet_button.php' );

	/* Load Theme Settings and upgrade functionality */
	if ( is_admin() ) {
		require_once( trailingslashit( TEMPLATEPATH ) . 'functions/admin.php' );
		require_once( trailingslashit( TEMPLATEPATH ) . 'functions/upgrade.php' );
	}

	/* Add theme support for WordPress features */
	add_theme_support( 'post-formats', array( 'aside', 'video', 'gallery', 'quote', 'link', 'image', 'status', 'chat' ) );
	add_theme_support( 'automatic-feed-links' );
	add_custom_background( 'cakifo_custom_background_callback' );
	add_editor_style();

	/*
	 * Set new image sizes 
	 *
	 * Small: For use in archives and searches
	 * Slider: For use in the slider
	 * Recent: For use in the recent posts
	 */
	add_image_size( 'small', apply_filters( 'small_thumb_width', '100' ), apply_filters( 'small_thumb_height', '100' ), true );
	add_image_size( 'slider', apply_filters( 'slider_image_width', '500' ), apply_filters( 'slider_image_height', '230' ), true );
	add_image_size( 'recent', apply_filters( 'recent_image_width', '190' ), apply_filters( 'recent_image_height', '130' ), true );

	/* Register shortcodes. */
	add_action( 'init', 'cakifo_register_shortcodes', 15 );
	
	/* Set $content_width */
	add_action( 'init', 'cakifo_content_width' );

	/* Load JavaScript and CSS styles */
	add_action( 'wp_enqueue_scripts', 'cakifo_enqueue_script' );
	add_action( 'wp_print_styles', 'cakifo_enqueue_style' );

	/* Topbar RSS link */
	add_action( "{$prefix}_close_menu_primary", 'cakifo_topbar_rss' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'cakifo_disable_sidebars' );
	add_action( 'template_redirect', 'cakifo_one_column' );

	/* Add the breadcrumb trail just after the container is open */
	add_action( "{$prefix}_open_main", 'breadcrumb_trail' );
	add_filter( 'breadcrumb_trail_args', 'cakifo_breadcrumb_trail_args' );

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

	/* Custom logo */
	add_filter( 'cakifo_site_title', 'cakifo_logo' );
	
	/*
	 * Custom header for logo upload
	 *
	 * @todo Improve this
	 */
	add_custom_image_header( 'cakifo_header_style', 'cakifo_admin_header_style' );

	/**
	 * The color, height and width of your custom logo
	 * Add a filter to cakifo_header_textcolor, cakifo_header_image_width and cakifo_header_image_height 
	 * to change these values in your child theme
	 */
	define( 'HEADER_TEXTCOLOR', apply_filters( 'cakifo_header_textcolor', '54a8cf' ) ); // #54a8cf is the link color from style.css
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'cakifo_header_image_width', 500 ) ); // Could be cool with flexible width and heights (@link http://core.trac.wordpress.org/ticket/17242)
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'cakifo_header_image_height', 500 ) );

	// Load the logo from the parent theme images folder
	register_default_headers( array(
		'logo' => array(
			'url' => '%s/images/logo.png',
			'thumbnail_url' => '%s/images/logo.png',
			'description' => __( 'Logo.png from the Cakifo images folder', 'cakifo' )
		)
	) );

	// If the user is using a child theme, add the logo.png from that as well
	if ( is_child_theme() && file_exists( CHILD_THEME_DIR . '/images/logo.png' ) ) {
		register_default_headers( array(
			'childtheme_logo' => array(
				'url' => CHILD_THEME_URI . '/images/logo.png',
				'thumbnail_url' => CHILD_THEME_URI . '/images/logo.png',
				'description' => __( 'Logo.png from the Cakifo child theme images folder', 'cakifo' )
			)
		) );
	}

}

/**
 * Loads the theme JavaScript files
 *
 * It loads jQuery, Modernizr, and the javascript 
 * needed for this theme
 *
 * @since 1.0
 */
function cakifo_enqueue_script() {

	/**
	 * Modernizr enables HTML5 elements & feature detects
	 *
     * For more/fewer features and optimal performance in your child theme,
	 * use a custom Modernizr build: www.modernizr.com/download/
	 *
	 * Use wp_deregister_script( 'modernizr' ); and
	 * wp_enqueue_script( 'modernizr', CHILD_THEME_URI . '/js/modernizr-2.x.min.js', '', '2.x' );
	 * in your child theme functions.php
	 */
	wp_enqueue_script( 'modernizr', THEME_URI . '/js/modernizr-2.0.6.min.js', '', '2.0.6' );

	// Make sure jQuery is loaded after Modernizr
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', includes_url( 'js/jquery/jquery.js' ), array( 'modernizr' ), null, true );

	/**
	 * Loads development script. Use this file for development purposes by adding this to your 'wp-config.php' file:
	 * define( 'SCRIPT_DEBUG', true );
	 */
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && current_theme_supports( 'dev-stylesheet' ) )
		wp_enqueue_script( 'cakifo-theme-dev', THEME_URI . '/js/script.dev.js', array( 'jquery' ), '1.3', true );
	else
		wp_enqueue_script( 'cakifo-theme', THEME_URI . '/js/script.js', array( 'jquery' ), '1.3', true );

}

/**
 * Loads fonts from the Google Font API
 *
 * Adds a bbPress stylesheet as well if the plugin is active
 *
 * @since 1.0
 */
function cakifo_enqueue_style() {
	wp_enqueue_style( 'PT-Serif', 'http://fonts.googleapis.com/css?family=PT+Serif:regular,italic,bold' );

	// Add a new bbPress stylesheet, if the plugin is active
	if ( class_exists( 'bbPress' ) ) :
		wp_dequeue_style( 'bbpress-style' );
		wp_enqueue_style( 'bbp-cakifo-bbpress', THEME_URI . '/css/bbpress.css', array(), '1.3', 'screen' );
	endif;
}

/**
 * Front Page stuff
 *
 * Adds JavaScript to the Front Page.
 * Removes the breadcrumb menu.
 *
 * @since 1.0
 */
function cakifo_front_page() {
	$prefix = hybrid_get_prefix();

	/* If we're not looking at the front page, return */
	if ( ! is_home() && ! is_front_page() )
		return;

	/* Load the Slides jQuery Plugin */
	if ( hybrid_get_setting( 'featured_show' ) )
		wp_enqueue_script( 'slides', THEME_URI . '/js/slides.js', array( 'jquery' ), '2.0-beta1', true );
		//wp_enqueue_script( 'slides', THEME_URI . '/js/slides.min.jquery.js', array( 'jquery' ), '1.1.8', true );

	/* Remove the breadcrumb trail */
	remove_action( "{$prefix}_open_main", 'breadcrumb_trail' );
}

function cakifo_slider_javascript() {

	/* If we're not looking at the front page, return */
	if ( ! is_home() && ! is_front_page() )
		return;

	//* If slider is disabled, return */
	if ( ! hybrid_get_setting( 'featured_show' ) )
		return;

	$loading_gif = ( file_exists( CHILD_THEME_DIR . '/images/loading.gif' ) ) ? CHILD_THEME_URI . '/images/loading.gif' : THEME_URI . '/images/loading.gif';

	/**
	 * Default args
	 */
	$defaults = array(
		'width' => 880, // [Number] Define the slide width
		'height' => 290, // [Number] Define the slide height
		'responsive' => false, // [Boolean] Slideshow will scale to its container
		'navigation' => false, // [Boolean] Auto generate the navigation, next/previous buttons
		'pagination' => true, // [Boolean] Auto generate the pagination
		'effects' => array(
			'navigation' =>  'fade',  // [String] Can be either "slide" or "fade"
			'pagination' =>  'fade' // [String] Can be either "slide" or "fade"
		),
		'direction' => 'down', // [String] Define the slide direction => "up", "right", "down", "left"
		'fade' => array(
			'interval' => 200, // [Number] Interval of fade in milliseconds
			'crossfade' => false, // [Boolean] TODO: add this feature. Crossfade the slides, great for images, bad for text
			'easing' => '' // [String] Dependency: jQuery Easing plug-in <http://gsgd.co.uk/sandbox/jquery/easing/>
		),
		'slide' => array(
			'interval' => 400, // [Number] Interval of fade in milliseconds
			'browserWindow' => false, // [Boolean] Slide in/out from browser window, bad ass
			'easing' => '' // [String] Dependency: jQuery Easing plug-in <http://gsgd.co.uk/sandbox/jquery/easing/>
		),
		'preload' => array(
			'active' => false, // [Boolean] Preload the slides before showing them, this needs some work
			'image' => esc_url( $loading_gif ) // [String] Define the path to a load .gif
		),
		'startAtSlide' => 1, // [Number] What should the first slide be?
		'playInterval' => 5000, // [Number] Time spent on each slide in milliseconds
		'pauseInterval' => 8000, // [Number] Time spent on pause, triggered on any navigation or pagination click
		'autoHeight' => true, // [Boolean] TODO: add this feature. Auto sets height based on each slide
	);

	$args = array();

	/**
	 * For more information about the arguments, see
	 *
	 * @link https://github.com/jayj/Cakifo/wiki/Child-themes
	 * @link http://slidesjs.com
	 *
	 * @notice The name of this filter changed in version 1.3
	 *	from cakifo_slider_args to cakifo_slider_arguments
	 */
	$args = apply_filters( 'cakifo_slider_arguments', $args ); 

	// Parse incoming $args into an array and merge it with $defaults
	$args = wp_parse_args( $args, $defaults );

	// Find the last argument in the array, and remove the comma after it
	$last_arg = end( array_keys( $args ) );

	echo "<script>
		jQuery(document).ready(function($) {
			$('#slider .inner-slider').slides({ ";

			foreach ( $args as $arg => $val ) :

				// Don't put a comma after the last argument
				$comma = ( $arg == $last_arg ) ? "\n" : ",\n";
	
				// Is the value an array?
				if ( is_array( $val ) ) :

					echo $arg . ': {' . "\n";

						// Find the last argument in the array
						$last_childarg = end( array_keys( $val ) );

						// Loop through the arguments in the child array
						foreach ( $val as $childarg => $childval ) {

							// Don't put a comma after the last argument
							$childcomma = ( $childarg == $last_childarg ) ? "\n" : ",\n";

							if ( $childval === true )
								echo $childarg . ': true' . $childcomma;
							elseif ( $val === false  )
								echo $childarg . ': false' . $childcomma;
							elseif ( is_int( $childval ) )
								echo $childarg . ': ' . $childval . $childcomma;
							else
								echo $childarg . ': "' . $childval . '"' . $childcomma;
						}

					echo '}' . $comma;

				// A true boolean?
				elseif( $val === true ) :
					echo $arg . ': true' . $comma;
	
				// A false when?
				elseif ( $val === false ) :
					echo $arg . ': false' . $comma;

				// A number?
				elseif ( is_int( $val ) ) :
					echo $arg . ': ' . $val . $comma;

				// Nope, it's just a regular string
				else :
					echo $arg . ': "' . $val . '"' . $comma;

				endif;

			endforeach;

	echo '}); });</script>';
}

/**
 * Change to small thumbnail for archives and search
 *
 * @since 1.1
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
 * Change avatar size to 48
 *
 * @since 1.3
 */
function cakifo_change_list_comments_args( $args ) {
	$args['avatar_size'] = 48;
	
	return $args;
}

/**
 * New excerpt function with the length as a parameter
 *
 * The ideal solution would be to change the excerpt_length filter,
 * but we need different excerpt lengths 
 *
 * @since 1.0
 * @param int $length Number of words. Default 55.
 */
function cakifo_the_excerpt( $length = 55, $echo = true ) {

	$text = get_the_excerpt();
	$words_array = preg_split( "/[\n\r\t ]+/", $text, $length + 1, PREG_SPLIT_NO_EMPTY );
	$more_link = '<br /> <a href="' . get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) . '</a>';

	if ( count( $words_array ) > $length ) {
		array_pop( $words_array );
		$text = implode( ' ', $words_array );
		$text = $text . apply_filters( 'excerpt_more', '...' );
	} else {
		$text = implode( ' ', $words_array ); 
	}
	
	if ( $echo )
		echo $text . $more_link;
	else
		return $text . $more_link;
}

function cakifo_excerpt_more( $more ) {
	global $post;

	if ( is_archive() )
		$more = '<p><a href="'. get_permalink( $post->ID ) . '" class="more-link">' .  __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) . '</a></p>';

	return $more;
}

/**
 * Custom breadcrumb trail arguments
 *
 * @since 1.0
 */
function cakifo_breadcrumb_trail_args( $args ) {
	$args['before'] = __( 'You are here:', 'cakifo' ); // Change the text before the breadcrumb trail 

	return $args;
}

/**
 * Change entry meta for different post formats
 *
 * @since 1.1
 */
function cakifo_quote_entry_meta( $meta ) {
	if ( is_single() )
		return do_shortcode( '<footer class="entry-meta">' . __( 'Posted by [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );

	return do_shortcode( '<footer class="entry-meta">' . __( '[entry-shortlink] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
}

function cakifo_aside_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( 'By [entry-author] on [entry-published] [entry-terms taxonomy="category" before="in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
}

function cakifo_link_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( 'Link recommended by [entry-author] on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
}

function cakifo_image_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( '<div>[entry-published] by [entry-author] [entry-edit-link before="<br/>"]</div> <div>[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="<br />Tagged "] [entry-comments-link before="<br />"]</div>', 'cakifo' ) . '</footer>' );
}


/**
 * Display RSS feed link in the topbar 
 *
 * @since 1.0
 */
function cakifo_topbar_rss() {
	echo apply_atomic_shortcode( 'rss_subscribe', '<div id="rss-subscribe">' . __( 'Subscribe by [rss-link] [twitter-username before="or "]', 'cakifo' ) . '</div>' );
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since 1.0
 */
function cakifo_one_column() {

	if ( !is_active_sidebar( 'primary' ) && !is_active_sidebar( 'secondary' ) )
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );

	elseif ( is_front_page() && ! is_home() ) // Static frontpage
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );

	elseif ( is_attachment() )
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );
}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since 1.0
 */
function cakifo_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since 1.0
 */
function cakifo_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;

	if ( current_theme_supports( 'theme-layouts' ) ) {

		if ( 'layout-1c' == theme_layouts_get_layout() || is_404() ) {
			$sidebars_widgets['primary'] = false;
			$sidebars_widgets['secondary'] = false;
		}
	}

	return $sidebars_widgets;
}

/**
 * Set $content_width based on the current post layout
 *
 * @since 1.3
 */
function cakifo_content_width() {

	$layout = theme_layouts_get_layout();
	
	if ( current_theme_supports( 'theme-layouts' ) ) {
		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout )
			hybrid_set_content_width( 490 );
		elseif ( 'layout-3c-c' == $layout )
			hybrid_set_content_width( 500 );
	}

}

/**
 * Custom Background callback
 *
 * Removes the background image from style.css if
 * the user has selected a custom background color
 *
 * @since 1.3
 */
function cakifo_custom_background_callback() {

	/* Get the background image */
	$image = get_background_image();

	/* If there's an image, just call the normal WordPress callback. We won't do anything here */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}

	/* Get the background color */
	$color = get_background_color();

	/* If no background color, return */
	if ( empty( $color ) )
		return;

	/* Use 'background' instead of 'background-color' */
	$style = "background: #{$color};";

?>
	<style type="text/css">body { <?php echo trim( $style ); ?> }</style>
<?php

}

/**
 * Allow the user to upload a new logo or change between image and text 
 * using the WordPress header function 
 *
 * @since 1.0
 */
function cakifo_logo( $title ) {

	//$tag = ( is_home() || is_front_page() ) ? 'h1' : 'h4';

	if ( $title = get_bloginfo( 'name' ) ) {

		// Check if there's a header image, else return the blog name
		$maybe_image = ( get_header_image() ) ? '<span class="assistive-text">' . $title . '</span><img src="' . get_header_image() . '" alt="' . esc_attr( $title ) . '" />' : '<span>' . $title . '</span>';

		//$title = '<' . $tag . ' id="site-title"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home">' . $maybe_image . '</a></' . $tag . '>';
		$title = '<h1 id="site-title"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home">' . $maybe_image . '</a></h1>';
	}

	return $title;
}

/**
 * Styles the header image and text displayed on the blog
 *
 * @since 1.0
 */
function cakifo_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;

	// If we get this far, we have custom styles. Let's do this. ?>

	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() && !get_header_image() ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		elseif( !get_header_image() ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}

// Used on the header admin screen
function cakifo_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
		width: 980px;
		background-repeat: no-repeat;
	}
	#headimg h1,
	#desc {
		font-family: Georgia, "Times New Roman", Times, serif;
	}
	#headimg h1 {
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
		font-size: 18px;
		line-height: 23px;
		color: #909090;
		float: right;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>!important;
		}
	<?php endif; ?>
	#headimg {
		max-width: 1000px;
		height: auto!important;
		width: 100%;
	}
	</style>
<?php
}

/**
 * Debug function
 */
if ( ! function_exists( 'debug' ) ) { 
	function debug( $function, $var_dump = true ) {
		if ( $var_dump )
			var_dump( $function );
		else
			echo '<pre>' . print_r ( $function, true ) . '</pre>';	
	}
}
 
/**
 * Adds an author box at the end of single posts
 *
 * @since 1.0
 */
function cakifo_author_box() { ?>

    <?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>

        <div class="author-profile vcard">

            <h4 class="author-name fn n"><?php echo do_shortcode( __( 'Article written by [entry-author]', 'cakifo' ) ); ?></h4>

            <?php echo get_avatar( get_the_author_meta( 'user_email' ), '48' ); ?>

            <div class="author-description author-bio">
                <?php the_author_meta( 'description' ); ?>
            </div>

            <?php if ( get_the_author_meta( 'twitter' ) ) { ?>
                <p class="twitter-link clear">
                    <a href="http://twitter.com/<?php the_author_meta( 'twitter' ); ?>" title="<?php printf( esc_attr__( 'Follow %1$s on Twitter', 'cakifo' ), get_the_author_meta( 'display_name' ) ); ?>"><?php printf( __( 'Follow %1$s on Twitter', 'cakifo' ), get_the_author_meta( 'display_name' ) ); ?></a>
                </p>
            <?php } // End check for twitter ?>
        </div>  <!-- .author-profile --> <?php

	endif;
}

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
 * @since 1.0
 */
function cakifo_image_info() {

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( get_the_ID() );
	$items = array();
	$list = '';
	
	// If there's no image meta, return
	if ( empty( $meta ) )
		return;

	/* Add the width/height to the $items array. */
	$items['dimensions'] = sprintf( __( '<span class="prep">Dimensions:</span> %s', 'cakifo' ), '<span class="image-data"><a href="' . wp_get_attachment_url() . '">' . sprintf( __( '%1$s &#215; %2$s pixels', 'cakifo' ), $meta['width'], $meta['height'] ) . '</a></span>' );

	/* If a timestamp exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = sprintf( __( '<span class="prep">Date:</span> %s', 'cakifo' ), '<span class="image-data">' . date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $meta['image_meta']['created_timestamp'] ) . '</span>' );

	/* If a camera exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = sprintf( __( '<span class="prep">Camera:</span> %s', 'cakifo' ), '<span class="image-data">' . $meta['image_meta']['camera'] . '</span>' );

	/* If an aperture exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = sprintf( __( '<span class="prep">Aperture:</span> %s', 'cakifo' ), '<span class="image-data">' . sprintf( __( 'f/%s', 'cakifo' ), $meta['image_meta']['aperture'] ) . '</span>' );

	/* If a focal length is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = sprintf( __( '<span class="prep">Focal Length:</span> %s', 'cakifo' ), '<span class="image-data">' . sprintf( __( '%s mm', 'cakifo' ), $meta['image_meta']['focal_length'] ) . '</span>' );

	/* If an ISO is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = sprintf( __( '<span class="prep">ISO:</span> %s', 'cakifo' ), '<span class="image-data">' . $meta['image_meta']['iso'] . '</span>' );

	/* If a shutter speed is given, format the float into a fraction and add it to the $items array. */
	if ( !empty( $meta['image_meta']['shutter_speed'] ) ) {

		if ( ( 1 / $meta['image_meta']['shutter_speed'] ) > 1 ) {
			$shutter_speed = '1/';

			if ( number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1 ) ==  number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0 ) )
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0, '.', '' );
			else
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1, '.', '' );
		} else {
			$shutter_speed = $meta['image_meta']['shutter_speed'];
		}

		$items['shutter_speed'] = sprintf( __( '<span class="prep">Shutter Speed:</span> %s', 'cakifo' ), '<span class="image-data">' . sprintf( __( '%s sec', 'cakifo' ), $shutter_speed ) . '</span>' );
	}

	/* Allow child themes to overwrite the array of items. */
	$items = apply_atomic( 'image_info_items', $items );

	/* Loop through the items, wrapping each in an <li> element. */
	foreach ( $items as $item )
		$list .= "<li>{$item}</li>";

	/* Format the HTML output of the function. */
	$output = '<div class="image-info"><h4>' . __( 'Image Info', 'cakifo' ) . '</h4><ul>' . $list . '</ul></div>';

	/* Display the image info and allow child themes to overwrite the final output. */
	echo apply_atomic( 'image_info', $output );
}


/**
 * Get the values of image sizes
 *
 * @since 1.3
 * @return array An array of all the images sizes
 */
function cakifo_get_image_sizes() {
	global $_wp_additional_image_sizes;
	
	// $intermediate_sizes = get_intermediate_image_sizes();

	$builtin_sizes = array(
		'large'		=> array(
			'width' => get_option( 'large_size_w' ),
			'height' => get_option( 'large_size_h' )
		),
		'medium'	=> array(
			'width' => get_option( 'medium_size_w' ),
			'height' => get_option( 'medium_size_h' )
		),
		'thumbnail'	=> array(
			'width' => get_option( 'thumbnail_size_w' ),
			'height' => get_option( 'thumbnail_size_h' ),
			'crop' => (boolean) get_option( 'thumbnail_crop' )
		)
	);

	if ( $_wp_additional_image_sizes )
		return array_merge( $builtin_sizes, $_wp_additional_image_sizes );

	return $builtin_sizes;
}

/**
 * Get the values of a image size
 *
 * @since 1.3
 * @param string $name Your unique name for this image size or a WP default
 * @return array Array containing 'width', 'height', 'crop'.
 */

function cakifo_get_image_size( $name ) {

	$image_sizes = cakifo_get_image_sizes();

	if ( isset( $image_sizes[$name] ) )
		return $image_sizes[$name];

	return false;
}

/**
 * Returns URLs found in the content
 *
 * @since 1.0
 * @param string $type http|href Default is http
 * @param $content The content to find URLs in. Default is the post content
 * @return array Array containing URLs found in the content
 */
function cakifo_url_grabber( $type = 'http', $content = null ) {

	// Set default content to post content
	if ( ! isset( $content ) )
		$content = get_the_content();

	/* If $type == 'href', get all URLs from <a href=""> */
	if ( $type == 'href' ) {
		if ( ! preg_match_all( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', $content, $matches ) )
			return false;

		return array_map( 'esc_url_raw', $matches[1] );
	}

	/* Else, get all http:// URLs (including those in <a href="">) */
	if ( ! preg_match_all( '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $content, $matches ) )
		return false;

	return array_map( 'esc_url_raw', $matches[0] );
}

?>