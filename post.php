<?php
/**
 * Singular Template
 *
 * This is the default singular template.  It is used when a more specific template can't be found to display
 * singular views of posts (any post type).
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

    <div id="main">

        <?php do_atomic( 'open_main' ); // cakifo_open_main ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'post-format', get_post_format() ); ?>

            <?php do_atomic( 'after_singular' ); // cakifo_after_singular ?>

            <?php comments_template( '/comments.php', true ); // Loads the comments.php template ?>

        <?php endwhile; ?>

        <?php do_atomic( 'close_main' ); // retro-fitted_close_main ?>

        <?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

    </div> <!-- #main -->

    <?php do_atomic( 'after_main' ); // retro-fitted_after_main ?>

<?php get_footer(); // Loads the footer.php template. ?>