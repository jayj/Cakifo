<?php
/**
 * Attachment Template
 *
 * This is the image attachment template.  It is used when visiting the singular view of an image attachment
 * page.
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.5.0
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

	<div id="main">

		<?php do_atomic( 'open_main' ); // cakifo_open_main ?>

		<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php do_atomic( 'before_entry' ); // cakifo_before_entry ?>

			<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

				<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

					<header class="entry-header">
						<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

						<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
						<?php echo apply_atomic_shortcode( 'byline_attachment_image', '<div class="byline">' . sprintf( __( 'Sizes: %s', 'cakifo' ), cakifo_get_image_size_links() ) . '</div>' ); ?>
					</header> <!-- .entry-header -->

					<div class="entry-content">
						<?php
							if ( has_excerpt() ) {
								$src = wp_get_attachment_image_src( get_the_ID(), 'full' );
								echo do_shortcode( sprintf( '[caption align="aligncenter" width="%1$s"]%3$s %2$s[/caption]', esc_attr( $src[1] ), get_the_excerpt(), wp_get_attachment_image( get_the_ID(), 'full', false ) ) );
							} else {
								echo wp_get_attachment_image( get_the_ID(), 'full', false, array( 'class' => 'aligncenter' ) );
							}
						?>

						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'unique' ), 'after' => '</p>' ) ); ?>
					</div> <!-- .entry-content -->

					<aside class="attachment-meta clearfix">
						<?php cakifo_image_info(); // Get image meta data ?>

						<?php $gallery = do_shortcode( sprintf( '[gallery id="%1$s" exclude="%2$s" columns="5" size="small" numberposts="20" orderby="rand"]', $post->post_parent, get_the_ID() ) ); ?>

						<?php if ( ! empty( $gallery ) ) { ?>
							<div class="image-gallery">
								<h3><?php _e( 'Gallery', 'cakifo' ); ?></h3>
								<?php echo $gallery; ?>
							</div> <!-- .image-gallery -->
						<?php } ?>
					</aside> <!-- .attachment-meta -->

					<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

				<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; ?>

		<?php do_atomic( 'close_main' ); // cakifo_close_main ?>

	</div> <!-- #main -->

	<?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>
