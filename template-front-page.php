<?php
/**
 * Template Name: Front Page
 *
 * This page template is used to display a custom front page
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

	<div id="main">

		<?php do_atomic( 'open_main' ); // cakifo_open_main ?>

			<?php
				/**
				 * If we have content for this page, let's display it.
				 */
				if ( post_format_tools_post_has_content() )
					cakifo_get_loop_template( 'intro' );
			?>

			<?php
				/**
				 * Get the section-recentposts.php template file
				 */
				get_template_part( 'section', 'recentposts' );
			?>

			<?php
				/**
				 * Get the section-headlines.php template file
				 */
				if ( hybrid_get_setting( 'headlines_category' ) )
					get_template_part( 'section', 'headlines' );
			?>

		<?php do_atomic( 'close_main' ); // cakifo_close_main ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

	</div> <!-- #main -->

	<?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>
