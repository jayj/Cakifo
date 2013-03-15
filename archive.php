<?php
/**
 * Archive Template
 *
 * The archive template is the default template used for archives pages without a more specific template.
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

	<div id="main">

		<?php do_atomic( 'open_main' ); // cakifo_open_main ?>

		<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php cakifo_get_loop_template( get_post_format() ); ?>

		<?php endwhile; ?>

		<?php do_atomic( 'close_main' ); // cakifo_close_main ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

	</div> <!-- #main -->

	<?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>
