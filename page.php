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

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

	<main id="main" class="site-main" role="main">

		<?php do_atomic( 'open_main' ); // cakifo_open_main ?>

		<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php do_atomic( 'before_entry' ); // cakifo_before_entry ?>

				<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

					<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

					<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>

					<?php
						// Get the thumbnail
						if ( current_theme_supports( 'get-the-image' ) )
							get_the_image(
								array(
									'meta_key'   => 'Thumbnail',
									'size'       => 'thumbnail',
									'attachment' => false
								)
							);
					?>

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
					</div> <!-- .entry-content -->

					<?php edit_post_link( __( 'Edit', 'cakifo' ), '<div class="entry-meta">', '</div>' ); ?>

					<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

					<?php do_atomic( 'close_entry' ); //´cakifo_close_entry ?>

				</article> <!-- #post-<?php the_ID(); ?> -->

			<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>

		<?php endwhile; ?>

		<?php do_atomic( 'close_main' ); // cakifo_close_main ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>
