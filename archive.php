<?php
/**
 * Archive Template
 *
 * The archive template is the default template used for archives pages without a more specific template.
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); ?>

	<?php do_atomic( 'before_main' ); ?>

	<main id="main" class="site-main" role="main">

		<?php do_atomic( 'open_main' ); ?>

		<?php get_template_part( 'loop-meta' ); ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php cakifo_get_loop_template( get_post_format() ); ?>

		<?php endwhile; ?>

		<?php get_template_part( 'loop-nav' ); ?>

		<?php do_atomic( 'close_main' ); ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); ?>

<?php get_footer(); ?>
