<?php
/**
 * Template Name: Front Page
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); ?>

	<?php do_atomic( 'before_main' ); ?>

	<main id="main" class="site-main" role="main">

		<?php do_atomic( 'open_main' ); ?>

		<?php
			/*
			 * If we have content for this page, let's display it.
			 */
			while ( have_posts() ) : the_post();

				if ( hybrid_post_has_content() ) {
					cakifo_get_loop_template( 'intro' );
				}

			endwhile;
		?>

		<?php
			get_template_part( 'section', 'recentposts' );
		?>

		<?php
			if ( hybrid_get_setting( 'headlines_category' ) ) {
				get_template_part( 'section', 'headlines' );
			}
		?>

		<?php do_atomic( 'close_main' ); ?>

		<?php get_template_part( 'loop-nav' ); ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); ?>

<?php get_footer(); ?>
