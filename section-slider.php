<?php
/**
 * Featured Content (slider) Template
 *
 * This template file is used for the slider on the home and blog page.
 * Child Themes can replace it via {section-slider.php}
 *
 * @package		Cakifo
 * @subpackage	Template
 */
?>

<?php
	/**
	 * Select posts from the selected categories
	 */
	if ( hybrid_get_setting( 'featured_category' ) ) :
		$feature_query = array( 
			'cat' => hybrid_get_setting( 'featured_category' ),
			'showposts' => hybrid_get_setting( 'featured_posts' ),
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish',
			'no_found_rows' => true,
		);
	// No selected categories, use Sticky Posts
	else :
		$feature_query = array(
			'post__in' => get_option( 'sticky_posts' ),
			'showposts' => hybrid_get_setting( 'featured_posts' ),
			'post_status' => 'publish',
			'no_found_rows' => true,
		);
	endif;
?>

<?php $loop = new WP_Query( $feature_query ); ?>

<?php if ( $loop->have_posts() ) : ?>

	<?php do_atomic( 'before_slider' ); // cakifo_before_slider ?>

	<section id="slider">

    	<h3 class="assistive-text"><?php _e( 'Featured Posts', 'cakifo' ); ?></h3>

		<div class="slides_container">

		<?php do_atomic( 'open_slider' ); // cakifo_open_slider ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<?php do_atomic( 'before_slide' ); // cakifo_before_slide ?>

				<article class="slide">

					<?php do_atomic( 'open_slide' ); // cakifo_open_slide ?>

					<?php
						if ( current_theme_supports( 'get-the-image' ) ) :

							/**
							 * Get the post thumbnail with the slider image size
							 *
							 * Either from a custom field, the featured image function,
							 * or an embed video (video post format only)
							 */
							$thumbnail = get_the_image( array(
								'size' => 'slider',
								'attachment' => false,
								'meta_key' => null, // Don't allow to set thumbnail with custom field. That way you can have 2 thumbnails. One for the post and one for the slider
								'image_class' => 'thumbnail',
								'echo' => false
							) );

							$thumbnail_size = cakifo_get_image_size( 'slider' );

							// There's a thumbnail!
							if ( $thumbnail ) :

								echo $thumbnail;

							/**
							 * Try to embed a video from the post content
							 */
							elseif ( has_post_format( 'video' ) ) :

								// oEmbed stores the video HTML in a custom field that starts with '_oembed_'
								$post_metas = get_post_custom_keys();

								if ( ! empty( $post_metas ) ) {
									foreach( $post_metas as $post_meta_key ) {
										if ( '_oembed_' == substr( $post_meta_key, 0, 8 ) )
											$video = get_post_meta( get_the_ID(), $post_meta_key, true );
									}
								}

								// There's a video!
								if ( isset( $video ) ) {

									// Get the original width and height
									preg_match('/width="(\d+)(px)?" height="(\d+)(px)?"/', $video, $matches);

									$width = intval( $matches[1] );
									$height = intval( $matches[3] );

									// Calculate the new height to maintain the right aspect ratio
									$new_width = $thumbnail_size['width'];
									$new_height = intval( $new_width * $height / $width );

									// Change the width and height attributes
									$video = preg_replace('/width="(\d+)(px)?" height="(\d+)(px)?"/', 'width="' . $new_width . '" height="' . $new_height . '"', $video);

									echo '<div class="slider-video">' . $video . '</div>';
								}

							endif;

							unset( $thumbnail, $video );

						endif;
					?>

					<div class="entry-summary">
						<?php echo apply_atomic_shortcode( 'slider_entry_title', '[entry-title]' ); ?>
						<?php the_excerpt(); ?>
						<a class="more-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ); ?></a>
					</div> <!-- .entry-summary -->

					<?php do_atomic( 'close_slide' ); // cakifo_close_slide ?>

				</article> <!-- .slide -->

				<?php do_atomic( 'after_slide' ); // after_close_slide ?>

			<?php endwhile; ?>

			<?php do_atomic( 'close_slider' ); // cakifo_close_slider ?>

		</div> <!-- .slides_container -->

	</section> <!-- #slider -->

	<?php do_atomic( 'after_slider' ); // cakifo_after_slider ?>

<?php endif; wp_reset_query(); ?>