<?php
/**
 * Attachment Template
 *
 * This is used when visiting the singular view of a post attachment
 * page (videos, audio, etc.).
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, image.php for Image archives,
 * video.php for Video archives, and audio.php for Audio archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); ?>

	<?php do_atomic( 'before_main' ); ?>

	<main id="main" class="site-main" role="main">

		<?php do_atomic( 'open_main' ); ?>

		<?php get_template_part( 'loop-meta' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php cakifo_get_loop_template( 'attachment' ); ?>

		<?php endwhile; ?>

		<?php get_template_part( 'loop-nav' ); ?>

		<?php do_atomic( 'close_main' ); ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); ?>

<?php get_footer(); ?>
