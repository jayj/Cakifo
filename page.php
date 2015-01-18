<?php
/**
 * Page Template
 *
 * This is the default page template.  It is used when a more specific template can't be found to display
 * singular views of pages.
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

			<?php do_atomic( 'before_entry' ); ?>

				<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

					<?php do_atomic( 'open_entry' ); ?>

					<?php the_title( '<h1 class="entry-title post-title">', '</h1>' ); ?>

					<?php cakifo_post_thumbnail(); ?>

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
					</div> <!-- .entry-content -->

					<?php edit_post_link( __( 'Edit', 'cakifo' ), '<div class="entry-meta">', '</div>' ); ?>

					<?php do_atomic( 'in_singular' ); ?>

					<?php do_atomic( 'close_entry' ); ?>

				</article> <!-- #post-<?php the_ID(); ?> -->

			<?php do_atomic( 'after_entry' ); ?>

		<?php endwhile; ?>

		<?php get_template_part( 'loop-nav' ); ?>

		<?php do_atomic( 'close_main' ); ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); ?>

<?php get_footer(); ?>
