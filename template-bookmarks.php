<?php
/**
 * Template Name: Bookmarks
 *
 * This page template is decrepated and only visible if
 * the `link_manager_enabled` option is true.
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); ?>

	<?php do_atomic( 'before_main' ); ?>

	<main id="main" class="site-main" role="main">

		<?php do_atomic( 'open_main' ); ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php do_atomic( 'before_entry' ); ?>

				<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

					<?php do_atomic( 'open_entry' ); ?>

					<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

					<?php cakifo_post_thumbnail(); ?>

					<div class="entry-content">
						<?php the_content(); ?>

						<?php do_atomic( 'before_bookmarks' ); ?>

						<?php
							$args = array(
								'title_li'         => false,
								'title_before'     => '<h2>',
								'title_after'      => '</h2>',
								'category_before'  => false,
								'category_after'   => false,
								'categorize'       => true,
								'show_description' => true,
								'between'          => '<br />',
								'show_images'      => false,
								'show_rating'      => false,
							);

							wp_list_bookmarks( $args );
						?>

						<?php do_atomic( 'after_bookmarks' ); ?>

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
