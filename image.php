<?php
/**
 * Attachment Template
 *
 * This is the image attachment template. It is used when visiting the singular view of an image attachment
 * page.
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

					<header class="entry-header">
						<h1 class="entry-title post-title"><?php single_post_title(); ?></h1>

						<div class="byline"><?php cakifo_posted_on(); ?></div>
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
						<?php wp_link_pages(); ?>
					</div> <!-- .entry-content -->

					<aside class="attachment-meta clearfix">

						<div class="attachment-info image-info">
							<h3><?php _e( 'Image Info', 'cakifo' ) ?></h3>

							<?php hybrid_media_meta(); ?>
						</div> <!-- .attachment-info -->

						<?php if ( $post->post_parent ) : ?>
							<?php $gallery = do_shortcode( sprintf( '[gallery id="%1$s" exclude="%2$s" columns="5" size="small"]', $post->post_parent, get_the_ID() ) ); ?>

							<?php if ( ! empty( $gallery ) ) : ?>
								<div class="attachment-gallery image-gallery">
									<h3><?php _e( 'Gallery', 'cakifo' ); ?></h3>
									<?php echo $gallery; ?>
								</div> <!-- .attachment-gallery -->
							<?php endif; ?>

						<?php endif; ?>
					</aside> <!-- .attachment-meta -->

					<?php do_atomic( 'in_singular' ); ?>

				<?php do_atomic( 'close_entry' ); ?>
			</article> <!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; ?>

		<?php do_atomic( 'close_main' ); ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); ?>

<?php get_footer(); ?>
