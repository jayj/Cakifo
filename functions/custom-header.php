<?php
/**
 * Setup the WordPress core custom header feature.
 *
 * @package Cakifo
 * @subpackage Functions
 */

/**
 * Custom header for logo upload.
 */
add_theme_support( 'custom-header',
	array(
		'width'                  => 400,
		'height'                 => 60,
		'flex-width'             => true,
		'flex-height'            => true,
		'default-text-color'     => cakifo_get_default_link_color_no_hash(),
		'wp-head-callback'       => 'cakifo_header_style',
		'admin-head-callback'    => 'cakifo_admin_header_style',
		'admin-preview-callback' => 'cakifo_admin_header_image',
	)
);

/**
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

/**
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
endif; // is_child_theme() && file_exists()

/**
 * Styles the header image and text displayed on the blog.
 *
 * @since Cakifo 1.0.0
 */
function cakifo_header_style() {

	$default_text_color = get_theme_support( 'custom-header', 'default-text-color' );
	$header_text_color  = get_header_textcolor();

	/* If no custom options for text are set, let's bail. */
	if ( $header_text_color == $default_text_color )
		return;

	// If we get this far, we have custom styles. Let's do this. ?>

	<style type="text/css" id="custom-header-css">
		<?php
			// Has the text been hidden?
			if ( ! display_header_text() ) :
		?>
			.site-title span {
				position: absolute;
				clip: rect(1px 1px 1px 1px); /* IE7 */
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text, use that
			elseif ( 'blank' != $header_text_color ) :
		?>
			.site-title,
			.site-description {
				color: #<?php echo $header_text_color; ?>;
			}
		<?php endif; ?>

		<?php
			// Hide the description if there's no header image and the text has been hidden
			if ( ! get_header_image() && ! display_header_text() ) :
		?>
			.home-link {
				position: absolute;
				clip: rect(1px 1px 1px 1px); /* IE7 */
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php endif; ?>
	</style>

	<?php
}

/**
 * Custom header markup displayed on the Appearance > Header admin panel.
 *
 * @since Cakifo 1.4.0
 */
function cakifo_admin_header_image() { ?>

	<div id="headimg">
		<?php
			/* Get header information. */
			$header_image  = get_header_image();
			$default_color = get_theme_support( 'custom-header', 'default-text-color' );
			$text_color    = get_header_textcolor();

			/* Set up variables. */
			$style = $span = $desc = $class = '';
		?>

		<?php
			/* Set the styling for the individual elements. */
			if ( display_header_text() && $default_color != $text_color ) {
				$style = "color: #{$text_color}; ";
				$desc  = "color: #{$text_color}; ";
			}

			if ( ! display_header_text() ) {
				$span = 'display: none; ';
				$desc = 'display: none; ';
			}

			if ( ! empty( $header_image ) && ! display_header_text() ) {
				$desc = 'display: block !important;';
			}
		?>

		<a id="name" onclick="return false;" href="<?php echo esc_url( home_url() ); ?>" style="<?php echo $style; ?>">
			<h1>

					<?php
						if ( ! empty( $header_image ) )
							echo '<img src="' . $header_image . '" alt="" />';
					?>

					<span class="displaying-header-text" style="<?php echo $span; ?>">
						<?php bloginfo( 'name' ); ?>
					</span>
			</h1>

			<?php
				if ( empty( $header_image ) )
					$class = 'displaying-header-text';
			?>
			<h2 id="desc" class="<?php echo $class; ?>" style="<?php echo $desc; ?>"><?php bloginfo( 'description' ); ?></h2>
		</a>

		<br class="clear" />
	</div>
<?php }

/**
 * Styles the header styles displayed on the Appearance > Header admin panel.
 *
 * @since Cakifo 1.0.0
 * @todo  Get font from Theme Customizer
 */
function cakifo_admin_header_style() { ?>

	<?php
		/* Get the font in the 'title' setting. */
		$font = cakifo_get_font_info( get_theme_mod( 'theme_font_title' ) );
	?>

	<style type="text/css">
		.appearance_page_custom-header #headimg {
			max-width: 1000px;
			margin-bottom: 25px;
			border: none;
		}

		#headimg h1,
		#desc {
			font-family: <?php echo esc_attr( $font['name'] ); ?>, Georgia, serif;
			font-weight: <?php echo esc_attr( $font['weight'] ); ?>;
		}

		#headimg h1 {
			float: left;
			margin: 10px 0 0;
		}

		#headimg h1 span {
			font-size: 46px;
			line-height: 1.8;
			letter-spacing: -2px;
			text-decoration: none;
		}

		#desc {
			color: #555;
			font-size: 21px;
			letter-spacing: -1px;

			float: right;
			margin: 20px 0 0 0;
			padding: 4px 6px;
		}
	</style> <?php
}

/**
 * Display the site title as logo and/or name.	What this function
 * returns depends on what the user has choosen in `Apperance > Header`.
 *
 * @since Cakifo 1.0.0
 * @return string The site title. Either as text, as an image or both.
 */
function cakifo_logo() {

	/* Get the site title. */
	$title = get_bloginfo( 'name' );
	$maybe_image = '';

	if ( get_header_image() ) {
		$maybe_image = '<img src="' . get_header_image() . '" alt="' . esc_attr( $title ) . '" />';
	}

	$output = sprintf( '<h1 class="site-title" id="site-title">%s<span>%s</span></h1>',
		$maybe_image,
		$title
	);

	/* Display the site title and allow child themes to overwrite the final output. */
	echo apply_atomic( 'site_title', $output );
}
