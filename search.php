<?php
/**
 * Search Template
 *
 * The search template is loaded when a visitor uses the search form to search for something
 * on the site.
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

			<?php get_template_part( 'loop', get_post_format() ); ?>

		<?php endwhile; ?>

		<?php do_atomic( 'close_main' ); // cakifo_close_main ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

	</div> <!-- #main -->

	<?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>
