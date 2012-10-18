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
	/**
	 * Set up the slider query
	 */
	$feature_query = array(
		'showposts'     => (int) hybrid_get_setting( 'featured_posts' ),
		'post_status'   => 'publish',
		'no_found_rows' => true,
		'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array(
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

	/**
	 * Select posts from the selected categories
	 * or use Sticky Posts
	 */
	if ( hybrid_get_setting( 'featured_category' ) ) {
		$feature_query['cat'] = hybrid_get_setting( 'featured_category' );
		$feature_query['ignore_sticky_posts'] = 1;
	} else {
		$feature_query['post__in'] = get_option( 'sticky_posts' );
	}

	/* Fire the query */
	$loop = new WP_Query( $feature_query );
?>

<?php if ( $loop->have_posts() ) : ?>

	<?php do_atomic( 'before_slider' ); // cakifo_before_slider ?>

	<section id="slider">

		<h2 class="assistive-text"><?php _e( 'Featured Posts', 'cakifo' ); ?></h2>

		<div class="slides-container clearfix">

		<?php do_atomic( 'open_slider' ); // cakifo_open_slider ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<?php do_atomic( 'before_slide' ); // cakifo_before_slide ?>

				<article class="slide">

					<?php do_atomic( 'open_slide' ); // cakifo_open_slide ?>

					<?php
						if ( current_theme_supports( 'get-the-image' ) ) :

							/**
							 * Get the post thumbnail with the slider image size
							 */
							$thumbnail = get_the_image( array(
								'size'        => 'slider',
								'attachment'  => false,
								'meta_key'    => null, // Don't allow to set thumbnail with custom field. That way you can have 2 thumbnails. One for the post and one for the slider
								'image_class' => 'thumbnail',
								'echo'        => false
							) );

							/* Get the size for the 'slider' image size */
							$thumbnail_size = cakifo_get_image_size( 'slider' );

							/* There's a thumbnail! */
							if ( $thumbnail ) {

								echo $thumbnail;

							/* Try to embed a video from the post content */
							} elseif ( has_post_format( 'video' ) ) {
								echo '<div class="slider-video">' . cakifo_get_video_embed( $thumbnail_size['width'] ) . '</div>';
							}

						endif;
					?>

					<div class="entry-summary">
						<?php echo apply_atomic_shortcode( 'slider_entry_title', '[entry-title tag="h2"]' ); ?>
						<?php the_excerpt(); ?>
						<a class="more-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ); ?></a>
					</div> <!-- .entry-summary -->

					<?php do_atomic( 'close_slide' ); // cakifo_close_slide ?>

				</article> <!-- .slide -->

				<?php do_atomic( 'after_slide' ); // after_close_slide ?>

			<?php endwhile; ?>

			<?php do_atomic( 'close_slider' ); // cakifo_close_slider ?>

		</div> <!-- .slides-container -->

	</section> <!-- #slider -->

	<?php do_atomic( 'after_slider' ); // cakifo_after_slider ?>

<?php endif; wp_reset_query(); ?>
