<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and
 * sets up the supported features, default actions, and default filters.  If making customizations, users
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let
 * friends modify parent theme files ;)
 *
 * Child themes should do their setup on the `after_setup_theme` hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
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
 * @version 1.6-dev
 * @author Jesper Johansen <kontakt@jayj.dk>
 * @copyright Copyright (c) 2011-2013, Jesper Johansen
 * @link http://wpthemes.jayj.dk/cakifo
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 */

/* Load the core theme framework */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the `after_setup_theme` hook */
add_action( 'after_setup_theme', 'cakifo_theme_setup', 10 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters
 *
 * @since Cakifo 1.0.0
 */
function cakifo_theme_setup() {

	/* Get action/filter hook prefix */
	$prefix = hybrid_get_prefix();

	/* Load Cakifo theme includes. */
	require_once( trailingslashit( THEME_DIR ) . 'functions/custom-header.php' );
	require_once( trailingslashit( THEME_DIR ) . 'functions/custom-fonts.php' );
	require_once( trailingslashit( THEME_DIR ) . 'functions/customize.php' );
	require_once( trailingslashit( THEME_DIR ) . 'functions/deprecated.php' );

	/* Add theme support for core framework features */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'secondary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-styles', array( 'style' ) );
	add_theme_support( 'hybrid-core-scripts' );

	/* Add theme support for framework extensions */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r', '3c-l', '3c-r', '3c-c' ) );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	add_theme_support( 'custom-field-series' );

	/* Add theme support for theme functions */
	add_theme_support( 'cakifo-sidebars', array(
		'primary',
		'secondary',
		'subsidiary',
		'subsidiary-two',
		'subsidiary-three',
		'after-single',
		'after-singular',
		'error-page'
	));

	add_theme_support( 'cakifo-colorbox' );

	/* Load Theme Settings */
	if ( is_admin() ) {
		require_once( trailingslashit( THEME_DIR ) . 'functions/admin.php' );
	}

	/* Add theme support for HTML5. */
	add_theme_support( 'html5', array(
		'comment-list', 'comment-form', 'search-form'
	));

	/**
	 * This theme supports all available post formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	));

	/* Adds RSS feed links to <head> for posts and comments. */
	add_theme_support( 'automatic-feed-links' );

	/* Add theme support for editor styles. */
	add_editor_style();

	/* Add theme support for custom backgrounds. */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e3ecf2',
		'default-image' => get_template_directory_uri() . '/images/bg.png'
	));

	/* Add theme support for custom colors. */
	add_theme_support( 'color-palette', array(
		'callback' => 'cakifo_register_colors'
	));

	/* Ignore some selectors for the Color Palette extension in the theme customizer. */
	add_filter( 'color_palette_preview_js_ignore', 'cakifo_cp_preview_js_ignore', 10, 3 );

	/* Set $content_width */
	hybrid_set_content_width( 650 );

	/**
	 * Set new image sizes
	 */
	add_image_size(
		'small',
		apply_filters( 'small_thumb_width', 100 ),
		apply_filters( 'small_thumb_height', 100 ),
		true
	);

	add_image_size(
		'slider',
		apply_filters( 'slider_image_width', 500 ),
		apply_filters( 'slider_image_height', 230 ),
		true
	);

	add_image_size(
		'recent',
		apply_filters( 'recent_image_width', 220 ),
		apply_filters( 'recent_image_height', 150 ),
		true
	);

	/* Enqueue theme scripts and styles */
	add_action( 'wp_enqueue_scripts', 'cakifo_enqueue_script', 1 );
	add_action( 'wp_footer', 'cakifo_slider_javascript', 100 );

	/* Filter the body class */
	add_filter( 'body_class', 'cakifo_body_class' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'cakifo_disable_sidebars' );
	add_action( 'template_redirect', 'cakifo_theme_layout' );

	/* Set embed width/height defaults and $content_width for non-default layouts */
	add_filter( 'embed_defaults', 'cakifo_content_width' );

	/* Search Form in the topbar */
	add_action( "{$prefix}_close_menu_primary", 'get_search_form' );

	/* Excerpt "Read More" link */
	add_filter( 'excerpt_more', 'cakifo_excerpt_more' );

	/* Add Custom Field Series */
	if ( current_theme_supports( 'custom-field-series' ) ) {
		add_action( "{$prefix}_after_singular", 'custom_field_series' );
	}

	/* Add an Author Box after singular posts */
	add_action( 'init', 'cakifo_place_author_box' );

	/* Load all necessary files and hooks for singular pages  */
	add_filter( "{$prefix}_in_singular", 'cakifo_load_in_singular' );

	/* Backward compatibility for the 'show_singular_comments' filter */
	add_action( 'init', 'cakifo_compat_show_singular_comments' );

	/* Filter Get the Image arguments */
	add_filter( 'get_the_image_args', 'cakifo_get_the_image_arguments' );

	/* Filter default theme options */
	add_filter( "{$prefix}_default_theme_settings", 'cakifo_filter_default_theme_settings' );

	/* Filter the arguments for wp_link_pages(), used in the loop files */
 	add_filter( 'wp_link_pages_args', 'cakifo_link_pages_args' );
}

/**
 * Load the Cakifo loop template
 *
 * It's modified version of get_template_part() to make sure the theme
 * stays compatible with child themes as the loop files were changed
 * from `loop.php` to `content.php` in Cakifo 1.6
 *
 * First it tries to get the old `loop-$name.php` then the new `content-$name.php`
 * If neither of those exists, it will try `loop.php` and `content.php`
 *
 * The template is included using require, not require_once, so you may include the
 * same template part multiple times.
 *
 * For the $name parameter, if the file is called "content-special.php" then specify
 * "special".
 *
 * @since Cakifo 1.6.0
 * @uses locate_template()
 * @uses do_action() Calls 'get_template_part_content' action.
 * @param string $name The name of the specialised template.
 */
function cakifo_get_loop_template( $name = null ) {
	do_action( 'get_template_part_content', 'content', $name );
	$templates = array();

	if ( isset( $name ) ) {
		$templates[] = "loop-{$name}.php";
		$templates[] = "content-{$name}.php";
	}

	$templates[] = 'loop.php';
	$templates[] = 'content.php';

	locate_template( $templates, true, false );
}

/**
 * Load the theme functions, if the theme/child theme supports them.
 *
 * @since Cakifo 1.3.0
 */
function cakifo_load_theme_support() {
	$template_directory = trailingslashit( get_template_directory() );

	/* Load the Cakifo sidebars if supported */
	require_if_theme_supports( 'cakifo-sidebars', $template_directory . 'functions/sidebars.php' );

	/* Load the Colorbox Script extention if supported. */
	require_if_theme_supports( 'cakifo-colorbox', $template_directory . 'functions/colorbox.php' );
}

add_action( 'after_setup_theme', 'cakifo_load_theme_support', 12 );

/**
 * Enqueue theme scripts, includes jQuery, Modernizr, and Flexslider.
 *
 * @since Cakifo 1.0.0
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

	/* Enqueue jQuery */
	wp_enqueue_script( 'jquery' );

	/* Enqueue the theme javascript */
	wp_enqueue_script( 'cakifo-theme', THEME_URI . '/js/script.js', array( 'jquery' ), '1.6', true );

	/* Enqueue the Flexslider jQuery Plugin */
	if ( hybrid_get_setting( 'featured_show' ) )
		wp_enqueue_script( 'flexslider', THEME_URI . '/js/jquery.flexslider.js', array( 'jquery' ), '2.0', true );
}

/**
 * Add Flexslider scripts
 *
 * @since Cakifo 1.0.0
 * @uses apply_filters() The `cakifo_flexslider_args` filter allows you to change the default values.
 */
function cakifo_slider_javascript() {

	/* If we're not looking at the front page, return */
	if ( ! is_home() && ! is_front_page() )
		return;

	/* If slider is disabled, return */
	if ( ! hybrid_get_setting( 'featured_show' ) )
		return;

	/**
	 * Default arguments
	 * @link http://www.woothemes.com/flexslider/ All available arguments and descriptions
	 */
	$defaults = array(
		'selector'       => '.slides-container > .slide',
		'animation'      => 'slide',
		'slideshow'      => true,
		'slideshowSpeed' => 7000,
		'animationSpeed' => 450,
		'pauseOnHover'   => true,
		'video'          => true,
		'prevText'       => esc_js( _x( 'Previous', 'slide', 'cakifo' ) ),
		'nextText'       => esc_js( _x( 'Next',     'slide', 'cakifo' ) ),
		'pauseText'      => esc_js( _x( 'Pause',    'slide', 'cakifo' ) ),
		'playText'       => esc_js( _x( 'Play',     'slide', 'cakifo' ) ),

		// REMOVE BEFORE RELEASE?
		'slideshow'      => false,
	);

	$args = array();

	/**
	 * Use the `cakifo_flexslider_args` filter to filter the defaults
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
				$('.cakifo-slider').flexslider(
					{$json}
				);
			});
		</script>";
}

/**
 * Change the thumbnail size to 'small' for archives and search pages.
 *
 * @since Cakifo 1.1.0
 * @param array $args The 'Get the Image' arguments
 * @return array      The filtered arguments
 */
function cakifo_get_the_image_arguments( $args ) {

	if ( ( is_archive() || is_search() ) && ! has_post_format( 'image' ) ) {
		$args['size'] = 'small';
		$args['image_class'] = 'thumbnail';
	}

	return $args;
}

/**
 * Edit the "More link" for archive excerpts.
 *
 * @since Cakifo 1.0.0
 * @param string $more The default more link
 * @return string      The changed more link with a more descriptive text
 */
function cakifo_excerpt_more( $more ) {
	global $post;

	if ( is_archive() )
		$more = '<p><a href="'. get_permalink( $post->ID ) . '" class="more-link">' .
					__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) .
				'</a></p>';

	return $more;
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since Cakifo 1.5.0
 * @return void
 */
function cakifo_theme_layout() {

	// No active sidebars
	if ( ! is_active_sidebar( 'primary' ) && ! is_active_sidebar( 'secondary' ) ) {
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );

	// Front page template
	} elseif ( is_page_template( 'template-front-page.php' ) ) {
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );

	// Attachment or default layout
	} elseif ( is_attachment() && 'layout-default' == theme_layouts_get_layout() ) {
		add_filter( 'get_theme_layout', 'cakifo_theme_layout_one_column' );
	}
}

/**
 * Filters `get_theme_layout` by returning `layout-1c`.
 *
 * @since Cakifo 1.0.0
 * @param string $layout
 * @return string Returns 'layout-1c'
 */
function cakifo_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since Cakifo 1.0.0
 * @param array $sidebars_widgets Array with all the widgets for all the sidebars
 * @return array                  Same array but with the primary and secondary sidebar removed
 */
function cakifo_disable_sidebars( $sidebars_widgets ) {

	if ( current_theme_supports( 'theme-layouts' ) && ! is_admin() ) {

		if ( 'layout-1c' == theme_layouts_get_layout() || is_404() ) {
			$sidebars_widgets['primary']   = false;
			$sidebars_widgets['secondary'] = false;
		}
 	}

	return $sidebars_widgets;
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the `$content_width` variable handles
 * with context-based widths.
 *
 * @since Cakifo 1.3.0
 * @uses hybrid_get_content_width()
 * @uses hybrid_set_content_width()
 * @uses theme_layouts_get_layout()
 * @param array $args Array with default embed settings
 */
function cakifo_content_width( $args ) {

	$args['width'] = hybrid_get_content_width();

	if ( current_theme_supports( 'theme-layouts' ) ) :

		$layout = theme_layouts_get_layout();

		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout ) {
			$args['width'] = 490;
		} elseif ( 'layout-3c-c' == $layout ) {
			$args['width'] = 500;
		} elseif ( 'layout-1c' == $layout ) {
			$args['width'] = 940;
		}

		/* Set $content_width */
		hybrid_set_content_width( $args['width'] );

	endif;

	return $args;
}

if ( ! function_exists( 'cakifo_author_box' ) ) :

	/**
	 * Function to add an author box
	 *
	 * @since Cakifo 1.0.0
	 */
	function cakifo_author_box() { ?>

		<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : ?>

			<?php do_atomic( 'before_author_box' ); // cakifo_before_author_box ?>

			<div class="author-profile clearfix vcard">

				<?php do_atomic( 'open_author_box' ); // cakifo_open_author_box ?>

				<h4 class="author-name fn n">
					<?php echo do_shortcode( __( 'Article written by [entry-author]', 'cakifo' ) ); ?>
				</h4>

				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 96 ); ?>

				<div class="author-description author-bio">
					<?php echo wpautop( get_the_author_meta( 'description' ) ); ?>
				</div>

				<?php do_atomic( 'close_author_box' ); // cakifo_close_author_box ?>

			</div> <!-- .author-profile -->

			<?php do_atomic( 'after_author_box' ); // cakifo_after_author_box

		endif;
	}

endif; // cakifo_author_box

/**
 * Place the author box at the end of single posts
 *
 * @since Cakifo 1.3.0
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
 * @since Cakifo 1.0.0
 * @author Justin Tadlock
 * @link http://justintadlock.com
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
	$items['dimensions'] = array( _x( 'Dimensions', 'image dimensions', 'cakifo' ), '<a href="' . wp_get_attachment_url() . '">' . sprintf( _x( '%1$s &#215; %2$s pixels', 'image dimensions', 'cakifo' ), $meta['width'], $meta['height'] ) . '</a>' );

	/* If a timestamp exists, add it to the $items array */
	if ( ! empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = array( _x( 'Date', 'image creation', 'cakifo' ), date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $meta['image_meta']['created_timestamp'] ) );

	/* If a camera exists, add it to the $items array */
	if ( ! empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = array( __( 'Camera', 'cakifo' ), $meta['image_meta']['camera'] );

	/* If an aperture exists, add it to the $items array */
	if ( ! empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = array( __( 'Aperture', 'cakifo' ), sprintf( _x( 'f/%s', 'exif: aperture', 'cakifo' ), $meta['image_meta']['aperture'] ) );

	/* If a focal length is set, add it to the $items array */
	if ( ! empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = array( __( 'Focal Length', 'cakifo' ), sprintf( _x( '%s mm', 'exif: focal length', 'cakifo' ), $meta['image_meta']['focal_length'] ) );

	/* If an ISO is set, add it to the $items array */
	if ( ! empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = array( __( 'ISO', 'cakifo' ), $meta['image_meta']['iso'] );

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

		$items['shutter_speed'] = array( __( 'Shutter Speed', 'cakifo' ), sprintf( _x( '%s sec', 'exif: shutter speed', 'cakifo' ), $shutter_speed ) );
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
	$output = '<div class="image-info clearfix">
					<h3>' . __( 'Image Info', 'cakifo' ) . '</h3>
					<dl>' . $list . '</dl>
				</div> <!-- .image-info -->';

	/* Display the image info and allow child themes to overwrite the final output */
	echo apply_atomic( 'image_info', $output );
}

/**
 * Get the values of all registered image sizes. Both the custom and the default
 *
 * @since Cakifo 1.3.0
 * @return array An array of all the images sizes
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
 * @since Cakifo 1.3.0
 * @param string $name The unique name for the image size or a WP default
 * @return array       Array containing 'width', 'height', 'crop'
 */
function cakifo_get_image_size( $name ) {
	$image_sizes = cakifo_get_image_sizes();

	if ( isset( $image_sizes[$name] ) )
		return $image_sizes[$name];

	return false;
}

/**
 * Returns a set of image attachment links based on size.
 *
 * @since Cakifo 1.5.0
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @return string Links to various image sizes for the image attachment.
 */
function cakifo_get_image_size_links() {

	/* If not viewing an image attachment page, return. */
	if ( ! wp_attachment_is_image( get_the_ID() ) )
		return;

	/* Set up an empty array for the links. */
	$links = array();

	/* Get the intermediate image sizes and add the full size to the array. */
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	/* Loop through each of the image sizes. */
	foreach ( $sizes as $size ) {

		/* Get the image source, width, height, and whether it's intermediate. */
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
		if ( ! empty( $image ) && ( true === $image[3] || 'full' == $size ) )
			$links[] = "<a class='image-size-link' href='" . esc_url( $image[0] ) . "'>{$image[1]} &times; {$image[2]}</a>";
	}

	/* Join the links in a string and return. */
	return join( ' <span class="sep">/</span> ', $links );
}

/**
 * Returns the default link color for Cakifo with no hash
 *
 * @since Cakifo 1.4.0
 * @return string The default color with no hash
 */
function cakifo_get_default_link_color_no_hash() {
	return apply_filters( 'cakifo_default_link_color_no_hash', '3083aa' );
}

/**
 * Filter the default theme settings
 *
 * @since Cakifo 1.4.0
 * @param array $settings The default theme settings.
 * @return array
 */
function cakifo_filter_default_theme_settings( $settings ) {
	$settings['link_color']          = '#' . cakifo_get_default_link_color_no_hash();
	$settings['featured_posts']      = 5;
	$settings['headlines_num_posts'] = 4;

	return $settings;
}

/**
 * Extends the default WordPress body class to denote:
 *   1. White or empty background color to change the layout and spacing.
 *
 * @since Cakifo 1.4.4
 */
function cakifo_body_class( $classes ) {
	$background_color = get_background_color();

	if ( empty( $background_color ) ) {
		$classes[] = 'custom-background-empty';
	} elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) ) {
		$classes[] = 'custom-background-white';
	}

	return $classes;
}

/**
 * Load all necessary files and hooks for singular pages.  This includes the sidebars, the loop-nav.php
 * template, comments template and the `after_singular` atomic hook.
 *
 * @since Cakifo 1.5.0
 */
function cakifo_load_in_singular() {
	// Load the sidebar-after-single.php template
	if ( is_single() )
		get_sidebar( 'after-single' );

	// Loads the sidebar-after-singular.php template
	get_sidebar( 'after-singular' );

	do_atomic( 'after_singular' ); // cakifo_after_singular

	// Loads the loop-nav.php template
	get_template_part( 'loop-nav' );

	// Loads the comments.php template
	if ( post_type_supports( get_post_type(), 'comments' ) ) {
		comments_template( '/comments.php', true );
	}
}

/**
 * Filter the arguments for the wp_link_pages(), used in the loop files.
 *
 * @since 1.5.0
 * @return array $args Arguments for the wp_link_pages() function.
 */
function cakifo_link_pages_args( $args ) {
	$args['before'] = '<p class="page-links">' . __( 'Pages:', 'cakifo' );
	$args['after']  = '</p>';

	return $args;
}

/**
 * Back compat for the 'show_singular_comments' filter
 *
 * @since Cakifo 1.5.0
 */
function cakifo_compat_show_singular_comments() {
	if ( false === apply_filters( 'show_singular_comments', true ) )
		remove_post_type_support( 'page', 'comments' );
}

/**
 * Returns the URL from the post.
 *
 * @uses hybrid_get_the_post_format_url() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Cakifo 1.6.0
 * @return string URL
 */
function cakifo_get_link_url() {
	$has_url = hybrid_get_the_post_format_url();

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Registers colors for the Color Palette extension.
 *
 * @since  Cakifo 1.6.0
 * @param  object  $color_palette
 * @return void
 */
function cakifo_register_colors( $color_palette ) {

	/* Get the link color from the Cakifo settings. */
	$default_link_color = ltrim( hybrid_get_setting( 'link_color' ), '#' );

	/* Add custom colors. */
	$color_palette->add_color( array(
		'id'      => 'link',
		'label'   => __( 'Link Color', 'cakifo' ),
		'default' => $default_link_color
	));

	$color_palette->add_color( array(
		'id'      => 'secondary',
		'label'   => __( 'Secondary Color', 'cakifo' ),
		'default' => 'ffc768'
	));

	$color_palette->add_color( array(
		'id'      => 'topbar',
		'label'   => __( 'Topbar', 'cakifo' ),
		'default' => '262626'
	));

	/* Add rule sets for colors. */
	$color_palette->add_rule_set(
		'link',
		array(
			'color' => 'a',
		)
	);

	$color_palette->add_rule_set(
		'topbar',
		array(
			'background-color' => '.topbar',
		)
	);

	$color_palette->add_rule_set(
		'secondary',
		array(
			'background-color' => '.flex-control-paging li a, .flex-direction-nav a:focus',
			'border-top-color' => '.site-content, .site-footer, .comment-respond',
			'border-bottom-color' => '.loop-meta-home'
		)
	);
}

/**
 * Filters the 'color_palette_preview_js_ignore' hook with some selectors that should be ignored on the
 * live preview because they don't need to be overwritten.
 *
 * @since  Cakifo 1.6.0
 * @param  string  $selectors
 * @param  string  $color_id
 * @param  string  $property
 * @return string
 */
function cakifo_cp_preview_js_ignore( $selectors, $color_id, $property ) {

	if ( 'color' === $property && 'link' === $color_id )
		$selectors = '.site-title, .menu a, .section-title a, .widget-title a, .intro-post .post-edit-link';

	return $selectors;
}

?>
