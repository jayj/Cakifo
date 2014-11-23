<?php
/**
 * Featured Content (slider) Template
 *
 * This template file is used for the slider on the home and blog page.
 * Child Themes can replace it via {section-slider.php}
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

<?php
	/*
	 * Set up the slider query
	 */
	$feature_query = array(
		'posts_per_page' => (int) hybrid_get_setting( 'featured_posts' ),
		'post_status'    => 'publish',
		'no_found_rows'  => true,
		'tax_query'      =>
			array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    =>
						array(
							'post-format-aside',
							'post-format-audio',
							'post-format-chat',
							'post-format-quote',
							'post-format-status'
						),
					'operator' => 'NOT IN'
				)
			)
	);

	/*
	 * Select posts from the selected categories
	 * or use Sticky Posts
	 */
	if ( hybrid_get_setting( 'featured_category' ) ) {
		$feature_query['cat'] = hybrid_get_setting( 'featured_category' );
		$feature_query['ignore_sticky_posts'] = 1;
	} else {
		$feature_query['post__in'] = get_option( 'sticky_posts' );
	}

	/*
	 * Fire up the query.
	 *
	 * Filter it with the `cakifo_slider_query` filter
	 */
	$loop = new WP_Query( apply_filters( 'cakifo_slider_query', $feature_query ) );
?>

<?php if ( $loop->have_posts() ) : ?>

	<?php do_atomic( 'before_slider' ); ?>

	<section id="slider" class="cakifo-slider">

		<h2 class="screen-reader-text"><?php _e( 'Featured Posts', 'cakifo' ); ?></h2>

		<div class="slides-container clearfix">

		<?php do_atomic( 'open_slider' ); ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<?php do_atomic( 'before_slide' ); ?>

				<article class="slide">

					<?php do_atomic( 'open_slide' ); ?>

					<?php
						if ( current_theme_supports( 'get-the-image' ) ) :

							// Get the post thumbnail with the slider image size
							$thumbnail = get_the_image(
								array(
									'size'        => 'slider',
									'image_class' => 'thumbnail',
									'attachment'  => false,
									'meta_key'    => null,
									'echo'        => false
								)
							);

							// There's an image thumbnail.
							if ( $thumbnail ) :

								echo $thumbnail;

							// Try to embed a video from the post content.
							elseif ( has_post_format( 'video' ) && current_theme_supports( 'hybrid-core-media-grabber' ) ) :

								$thumbnail_size = cakifo_get_image_size( 'slider' );

								echo hybrid_media_grabber( array(
									'type'   => 'video',
									'width'  => $thumbnail_size['width'],
									'before' => '<div class="slider-video">',
									'after'  => '</div>'
								) );

							endif;

						endif;
					?>

					<div class="entry-summary">
						<?php echo apply_atomic_shortcode( 'slider_entry_title', '[entry-title tag="h2"]' ); ?>
						<?php the_excerpt(); ?>
						<a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ); ?></a>
					</div> <!-- .entry-summary -->

					<?php do_atomic( 'close_slide' ); ?>

				</article> <!-- .slide -->

				<?php do_atomic( 'after_slide' ); ?>

			<?php endwhile; ?>

			<?php do_atomic( 'close_slider' ); ?>

		</div> <!-- .slides-container -->

	</section> <!-- .cakifo-slider -->

	<?php do_atomic( 'after_slider' ); ?>

<?php endif; wp_reset_query(); ?>
