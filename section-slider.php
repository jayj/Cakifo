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
							'post-format-status',
							'post-format-video'
						),
					'operator' => 'NOT IN'
				)
			)
	);

	/*
	 * Select posts from the selected category
	 * or use Sticky Posts
	 */
	if ( hybrid_get_setting( 'featured_category' ) ) {
		$feature_query['cat'] = hybrid_get_setting( 'featured_category' );
		$feature_query['ignore_sticky_posts'] = 1;
	} else {
		$feature_query['post__in'] = get_option( 'sticky_posts' );
	}

	/**
	 * Filter the featured posts query.
	 *
	 * @param array $feature_query An array of valid WP_Query arguments.
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

					<?php cakifo_post_thumbnail( 'slider' ); ?>

					<div class="entry-summary">
						<?php echo apply_atomic_shortcode( 'slider_entry_title', '[entry-title tag="h2"]' ); ?>
						
						<?php the_excerpt(); ?>

						<?php
							printf( '<a href="%1$s" class="more-link">%2$s</a>',
								esc_url( get_permalink() ),
								/* translators: %s: Name of current post */
								sprintf( esc_html__( 'Continue reading %s', 'cakifo' ), '<span class="screen-reader-text">' . get_the_title() . '</span>' )
								);
						?>
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
