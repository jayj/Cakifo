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
 * @version 1.2
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
	$domain = hybrid_get_textdomain();

	$theme_data = get_theme_data( trailingslashit( TEMPLATEPATH ) . 'style.css' );
	$child_data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );

	/* Add theme support for core framework features */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-post-meta-box' );
	add_theme_support( 'hybrid-core-theme-settings' );
	add_theme_support( 'hybrid-core-meta-box-footer' );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	//add_theme_support( 'hybrid-core-drop-downs' );

	// Add Hybrid Core SEO if the (WordPress SEO || All in One SEO || HeadSpace2 SEO ) plugin isn't activated
	if ( ! class_exists( 'Yoast_WPSEO_Plugin_Admin' ) && ! class_exists( 'All_in_One_SEO_Pack' ) && ! class_exists( 'Headspace_Plugin' ) )
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

	/* Load shortcodes file. */
	require_once( THEME_DIR . '/functions/shortcodes.php' );

	/* Load Theme Settings */
	if ( is_admin() )
		require_once( trailingslashit( TEMPLATEPATH ) . 'functions/admin.php' );

	/* Add theme support for WordPress features */
	add_theme_support( 'post-formats', array( 'aside', 'video', 'gallery', 'quote', 'link', 'audio', 'image', 'status', 'chat' ) );
	add_theme_support( 'automatic-feed-links' );
	add_custom_background();
	add_editor_style();

	/*
	 * Set new image sizes 
	 *
	 * Add a smaller image for use in archives and searches
	 * Add a image size for use in the slider
	 */
	add_image_size( 'small', apply_filters( 'small_thumb_width', '100' ), apply_filters( 'small_thumb_height', '100' ), true );
	add_image_size( 'slider', apply_filters( 'slider_image_width', '500' ), apply_filters( 'slider_image_height', '230' ), true );
	add_image_size( 'recent', apply_filters( 'recent_image_width', '190' ), apply_filters( 'recent_image_height', '130' ), true );

	/* Register shortcodes. */
	add_action( 'init', 'cakifo_register_shortcodes', 15 );

	/* Load JavaScript and CSS styles */
	add_action( 'wp_enqueue_scripts', 'cakifo_enqueue_script', 1 );
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
	add_action( 'wp_footer', 'cakifo_slider_javascript' );

	/* Change entry meta for certain post formats */
	add_filter( "{$prefix}_entry_meta_quote", 'cakifo_quote_entry_meta' );
	add_filter( "{$prefix}_entry_meta_aside", 'cakifo_aside_entry_meta' );
	add_filter( "{$prefix}_entry_meta_link", 'cakifo_link_entry_meta' );

	/* Hide byline and/or entry meta for certain post formats */
	add_filter( "{$prefix}_byline_quote", '__return_false' );
	add_filter( "{$prefix}_byline_aside", '__return_false' );
	add_filter( "{$prefix}_byline_link", '__return_false' );
	add_filter( "{$prefix}_byline_status", '__return_false' );
	add_filter( "{$prefix}_entry_meta_status", '__return_false' );

	/* Excerpt read more link */
	add_filter( 'excerpt_more', 'cakifo_excerpt_more' );

	/* Add Custom Field Series */
	if ( current_theme_supports( 'custom-field-series' ) )
		add_action( "{$prefix}_after_singular", 'custom_field_series' );

	/* Add an author box after singular posts */
	add_action( "{$prefix}_before_sidebar_single", 'cakifo_author_box' );

	/* Get the Image arguments */
	add_filter( 'get_the_image_args', 'cakifo_get_the_image_arguments' );

	/* Theme update check */
	add_action( 'admin_notices', 'cakifo_update_notice' );
	add_action( 'network_admin_notices', 'cakifo_update_notice' );

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
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'cakifo_header_image_width', 500 ) ); // Could be cool with flexible width and heights (@http://core.trac.wordpress.org/ticket/17242)
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'cakifo_header_image_height', 500 ) );

	// Load the logo from the parent theme images folder and the childtheme image folder 
	register_default_headers( array(
		'logo' => array(
			'url' => '%s/images/logo.png',
			'thumbnail_url' => '%s/images/logo.png',
			'description' => sprintf( __( 'Logo.png from the %1$s images folder', $domain ), $theme_data['Title'] )
		)
	) );

	// If the user is using a child theme, add the logo.png from that as well
	if ( TEMPLATEPATH != STYLESHEETPATH && file_exists( CHILD_THEME_DIR . '/images/logo.png' ) ) {
		register_default_headers( array(
			'childtheme_logo' => array(
				'url' => CHILD_THEME_URI . '/images/logo.png',
				'thumbnail_url' => CHILD_THEME_URI . '/images/logo.png',
				'description' => sprintf( __( 'Logo.png from the %1$s images folder', $domain ), $child_data['Title'] )
			)
		) );
	}

}

/**
 * Loads the theme JavaScript files
 * It loads jQuery, Modernizr and the javascript needed for this theme
 *
 * @since 1.0
 */
function cakifo_enqueue_script() {

	/**
	 * Modernizr enables HTML5 elements & feature detects
	 *
     * For optimal performance in your child theme, use a custom Modernizr build: www.modernizr.com/download/
	 * use wp_deregister_script( 'modernizr' ); and
	 * wp_enqueue_script( 'modernizr', CHILD_THEME_URI . '/js/modernizr-2.0.min.js', '', '2.0' );
	 * in your child theme functions.php
	 */
	wp_enqueue_script( 'modernizr', THEME_URI . '/js/modernizr-2.0.min.js', '', '2.0' );

	// Make sure jQuery is loaded after Modernizr
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', includes_url( 'js/jquery/jquery.js' ), array( 'modernizr' ), null, true );

	wp_enqueue_script( 'cakifo-theme', THEME_URI . '/js/script.js', array( 'jquery' ), '1.0', true );

}

/**
 * Loads fonts from the Google Font API
 *
 * @since 1.0
 */
function cakifo_enqueue_style() {
	wp_enqueue_style( 'PT-Serif', 'http://fonts.googleapis.com/css?family=PT+Serif:regular,italic,bold,bolditalic' );
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
	if ( !is_home() && !is_front_page() )
		return;

	/* Load the Slides jQuery Plugin */
	wp_enqueue_script( 'slides', THEME_URI . '/js/slides.min.jquery.js', array( 'jquery' ), '1.1.8', true );

	/* Remove the breadcrumb trail */
	remove_action( "{$prefix}_open_main", 'breadcrumb_trail' );
}

function cakifo_slider_javascript() {

	/* If we're not looking at the front page, return */
	if ( !is_home() && !is_front_page() )
		return;

	$loading_gif = ( file_exists( CHILD_THEME_DIR . '/images/loading.gif' ) ) ? CHILD_THEME_URI . '/images/loading.gif' : THEME_URI . '/images/loading.gif';

	/**
	 * Default args
	 */
	$defaults = array(
		'play' => '3500', // number, Autoplay slideshow, a positive number will set to true and be the time between slide animation in milliseconds
		'hoverPause' => true, // boolean, Set to true and hovering over slideshow will pause it
		'generatePagination' => true, // boolean, Auto generate pagination
		'autoHeight' => true, // boolean, Set to true to auto adjust height
		'effect' => 'fade', // string, '[next/prev], [pagination]', e.g. 'slide, fade' or simply 'fade' for both
		'fadeSpeed' => 50, // number, Set the speed of the fading animation in milliseconds
		'slideSpeed' => 150, // number, Set the speed of the sliding animation in milliseconds
		'paginationClass' => 'slider-pagination',
		'preload' => false, // boolean, Set true to preload images in an image based slideshow
		'preloadImage' => esc_url( $loading_gif ),
		'randomize' => false, // boolean, Set to true to randomize slides
		// 'currentClass' => 'current'
		// 'container => 'slides_container', // string, Class name for slides container. Default is "slides_container"
		// 'generateNextPrev' => false, // boolean, Auto generate next/prev buttons
		// 'next' => 'next', // string, Class name for next button
		// 'prev '=> 'prev', // string, Class name for previous button
		// 'pagination' => 'true', // boolean, If you're not using pagination you can set to false, but don't have to
		// 'start' => 1, // number, starting slide
		// 'crossfade' => 'false', // boolean, Crossfade images in a image based slideshow
		// 'pause' => 0, // number, Pause slideshow on click of next/prev or pagination. A positive number will set to true and be the time of pause in milliseconds
		// 'autoHeightSpeed'=> 350, // number, Set auto height animation time in milliseconds
		// 'bigTarget' => 'false', // boolean, Set to true and the whole slide will link to next slide on click
		// 'animationStart'=> 'function(){}', // Function called at the start of animation
		// 'animationComplete'=> 'function(){}' // Function called at the completion of animation
	);

	$args = '';

	/* @link http://slidesjs.com for more info */
	$args = apply_atomic( 'slider_args', $args ); 

	/**
	 * Parse incoming $args into an array and merge it with $defaults
	 */ 
	$args = wp_parse_args( $args, $defaults );

	echo "<script type='text/javascript'>
			jQuery(document).ready(function($) {
				$('#slider').slides({ ";

				foreach ( $args as $arg => $val ) {

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

				// Add display block if there's only 1 slide
				if ( $('.slide').length == 1 )
					$('.slide').css( 'display', 'block' );
			});
		</script>";
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
 * New excerpt function with the length as a parameter
 * The ideal solution would be to change the excerpt_length filter but we need different excerpt lengths 
 * Props: http://wordpress.stackexchange.com/questions/6310/howto-control-manual-excerpt-length#answer-6316
 *
 * @since 1.0
 * @param int $length The length of the excerpt
 */
function cakifo_the_excerpt( $length = 55 ) {
	global $post;

	$limit = $length + 1;
    $excerpt = explode( ' ', get_the_excerpt(), $limit );
    array_pop( $excerpt );

    $excerpt = implode( " ", $excerpt ) . apply_filters( 'excerpt_more', '...' ) . '<br /> <a href="' . get_permalink( $post->ID ) . '">' . __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) . '</a>';

    echo $excerpt;
}

function cakifo_excerpt_more( $more ) {
	global $post;

	if ( is_archive() )
		$more = '<p><a href="'. get_permalink( $post->ID ) . '" class="more-link">' .  __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) . '</a></p>';

	return $more;
}

/**
 * Custom breadcrumb trail arguments
 *
 * @since 1.0
 */
function cakifo_breadcrumb_trail_args( $args ) {

	$args['before'] = __( 'You are here:', hybrid_get_textdomain() ); // Change the text before the breadcrumb trail 

	return $args;
}

/**
 * Change entry meta for different post formats
 *
 * @since 1.1
 */
function cakifo_quote_entry_meta( $meta ) {

	if ( is_single() )
		return do_shortcode( '<footer class="entry-meta">' . __( 'Posted by [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</footer>' );

	return;
}

function cakifo_aside_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( 'By [entry-author] on [entry-published] [entry-terms taxonomy="category" before="in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</footer>' );
}


function cakifo_link_entry_meta( $meta ) {
	return do_shortcode( '<footer class="entry-meta">' . __( 'Link recommended by [entry-author] on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</footer>' );
}

/**
 * Display RSS feed link in the topbar 
 *
 * @since 1.0
 */
function cakifo_topbar_rss() {
	echo apply_atomic_shortcode( 'rss_subscribe', '<div id="rss-subscribe">' . __( 'Subscribe by [rss-link] [twitter-username before="or "]', hybrid_get_textdomain() ) . '</div>' );
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
 * Allow the user to upload a new logo or change between image and text 
 * using the WordPress header function 
 *
 * @since 1.0
 */
function cakifo_logo( $title ) {

	$tag = ( is_home() || is_front_page() ) ? 'h1' : 'h4';

	if ( $title = get_bloginfo( 'name' ) ) {

		// Check if there's a header image, else return the blog name
		$maybe_image = ( get_header_image() ) ? '<span class="assistive-text">' . $title . '</span><img src="' . get_header_image() . '" alt="' . esc_attr( $title ) . '" />' : '<span>' . $title . '</span>';

		$title = '<' . $tag . ' id="site-title"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home">' . $maybe_image . '</a></' . $tag . '>';
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
if ( ! function_exists('debug')) { 
	function debug( $function ) {
		echo '<pre>' . print_r ( $function, true ) . '</pre>';	
	}
}
 
/**
 * Adds an author box at the end of single posts
 *
 * @since 1.0
 */
function cakifo_author_box() { ?>

    <?php if ( get_the_author_meta( 'description' ) && ( function_exists( 'is_multi_author' ) && is_multi_author() ) ) : ?>

        <div class="author-profile vcard">

            <h4 class="author-name fn n"><?php echo do_shortcode( __( 'Article written by [entry-author]', hybrid_get_textdomain() ) ); ?></h4>

            <?php echo get_avatar( get_the_author_meta( 'user_email' ), '48' ); ?>

            <div class="author-description author-bio">
                <?php the_author_meta( 'description' ); ?>
            </div>

            <?php if ( get_the_author_meta( 'twitter' ) ) { ?>
                <p class="twitter-link clear">
                    <a href="http://twitter.com/<?php the_author_meta( 'twitter' ); ?>" title="<?php printf( esc_attr__( 'Follow %1$s on Twitter', hybrid_get_textdomain() ), get_the_author_meta( 'display_name' ) ); ?>"><?php printf( __( 'Follow %1$s on Twitter', hybrid_get_textdomain() ), get_the_author_meta( 'display_name' ) ); ?></a>
                </p>
            <?php } // End check for twitter ?>
        </div>  <!-- .author-profile --> <?php

	endif;
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

	/* Add the width/height to the $items array. */
	$items['dimensions'] = sprintf( __( '<span class="prep">Dimensions:</span> %s', hybrid_get_textdomain() ), '<span class="image-data"><a href="' . wp_get_attachment_url() . '">' . sprintf( __( '%1$s &#215; %2$s pixels', hybrid_get_textdomain() ), $meta['width'], $meta['height'] ) . '</a></span>' );

	/* If a timestamp exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = sprintf( __( '<span class="prep">Date:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $meta['image_meta']['created_timestamp'] ) . '</span>' );

	/* If a camera exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = sprintf( __( '<span class="prep">Camera:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . $meta['image_meta']['camera'] . '</span>' );

	/* If an aperture exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = sprintf( __( '<span class="prep">Aperture:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . sprintf( __( 'f/%s', hybrid_get_textdomain() ), $meta['image_meta']['aperture'] ) . '</span>' );

	/* If a focal length is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = sprintf( __( '<span class="prep">Focal Length:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . sprintf( __( '%s mm', hybrid_get_textdomain() ), $meta['image_meta']['focal_length'] ) . '</span>' );

	/* If an ISO is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = sprintf( __( '<span class="prep">ISO:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . $meta['image_meta']['iso'] . '</span>' );

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

		$items['shutter_speed'] = sprintf( __( '<span class="prep">Shutter Speed:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . sprintf( __( '%s sec', hybrid_get_textdomain() ), $shutter_speed ) . '</span>' );
	}

	/* Allow child themes to overwrite the array of items. */
	$items = apply_atomic( 'image_info_items', $items );

	/* Loop through the items, wrapping each in an <li> element. */
	foreach ( $items as $item )
		$list .= "<li>{$item}</li>";

	/* Format the HTML output of the function. */
	$output = '<div class="image-info"><h4>' . __( 'Image Info', hybrid_get_textdomain() ) . '</h4><ul>' . $list . '</ul></div>';

	/* Display the image info and allow child themes to overwrite the final output. */
	echo apply_atomic( 'image_info', $output );
}


/**
 * Check for theme update
 * 
 * Displays a update notice if there's a new version of the theme
 * 
 * @since 1.2
 */
function cakifo_update_notice() {
	
	if ( current_user_can( 'update_themes' ) ) :

		include_once( ABSPATH . WPINC . '/feed.php' );
		$theme_data = get_theme_data( trailingslashit( TEMPLATEPATH ) . 'style.css' );

		// Get the update feed
		$rss = fetch_feed( 'http://wpthemes.jayj.dk/themerss/cakifo.xml' );

		if ( ! is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity(1); // We only want the latest
			$rss_items = $rss->get_items(0, 1);
		endif;

		if ( $maxitems != 0 ) :

			foreach ( $rss_items as $item ) {
				// Compare feed version to theme version
				if ( version_compare( $item->get_title(), $theme_data['Version'] ) > 0 )
					echo '<div id="update-nag">Version ' . esc_html( $item->get_title() ) .' for the Cakifo theme is available! <a href="' . esc_url( $item->get_permalink() ) .'">Click here to download the update</a>. ' . esc_html( $item->get_description() ) .'</div>';
			}

		endif;

	endif; // current_user_can('update_themes')
}

?>