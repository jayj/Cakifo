<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins. 
 *
 * @package Cakifo
 * @subpackage Template
 */
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php hybrid_document_title(); ?></title>

	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body class="<?php hybrid_body_class(); ?>">

	<?php do_atomic( 'open_body' ); // cakifo_open_body ?>

	<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template ?>

    <div id="wrapper">

    	<?php do_atomic( 'before_header' ); // cakifo_before_header ?>

    	<header id="branding" role="banner" class="clearfix">

        	<?php do_atomic( 'open_header' ); // cakifo_open_header ?>

			<hgroup>
				<?php
                    hybrid_site_title();
                    hybrid_site_description();
                ?>
            </hgroup>

			<?php do_atomic( 'header' ); // cakifo_header ?>

        </header> <!-- #branding -->

        <?php do_atomic( 'after_header' ); // cakifo_after_header ?>

	<?php do_atomic( 'before_content' ); // cakifo_before_content ?>

	<div id="content" class="clearfix">

    	<?php do_atomic( 'open_content' ); // cakifo_open_content ?>

        <?php
			 // Load the slider.php template
			if ( ( is_home() || is_front_page() ) && hybrid_get_setting( 'featured_show' ) )
				get_template_part( 'slider' );
		?>