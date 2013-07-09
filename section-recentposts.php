<?php
/**
 * The template for displaying Recent Posts in the template-front-page.php page template
 *
 * Child Themes can replace this template part file via {section-recentposts.php}
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_recent_posts' ); // cakifo_before_recent_posts ?>

<section class="recent-post-columns clearfix">

	<?php do_atomic( 'open_recent_posts' ); // cakifo_open_recent_posts ?>

	<?php
		/* Get the link to the Posts (blog) page */
		$posts_page = ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
	?>

	<h1 class="section-title">
		<a href="<?php echo esc_url( $posts_page ); ?>" title="<?php esc_attr_e( 'See more posts', 'cakifo' ); ?>"><?php _e( 'Recent Posts', 'cakifo' ); ?></a>
	</h1>

	<?php
		// Create the Recent Posts query
		$recent_args = array(
			'showposts'           => 4, // Show 4 posts
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish',
			'no_found_rows'       => true,
			'tax_query'           => array(
				array(
					// Only show posts with the standard post format
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array(
						'post-format-aside',
						'post-format-audio',
						'post-format-chat',
						'post-format-link',
						'post-format-quote',
						'post-format-status',
						'post-format-video'
					),
					'operator' => 'NOT IN'
				)
			),
		);

		// Fire the Recent Posts query
		$recent = new WP_Query( apply_filters( 'cakifo_recent_posts_query', $recent_args ) );

		while ( $recent->have_posts() ) : $recent->the_post();

			/**
			 * Put the post ID in an array to make sure it's only showing once
			 * on the page as this array is used in the headline lists as well.
			 */
			$GLOBALS['cakifo_do_not_duplicate'][] = get_the_ID();
	?>

		<article class="recent-post">
			<?php do_atomic( 'open_recent_posts_item' ); // cakifo_open_recent_posts_item ?>

				<?php
					/* Get the thumbnail */
					if ( current_theme_supports( 'get-the-image' ) )
						get_the_image(
							array(
							'meta_key'      => 'Thumbnail',
							'size'          => 'recent',
							'image_class'   => 'thumbnail',
							'default_image' => THEME_URI . '/images/default-thumb-220-150.png',
							)
						);
				?>

				<header class="entry-header">
					<?php
						echo apply_atomic_shortcode( 'recent_posts_entry_title', '[entry-title tag="h2"]' );
						echo apply_atomic_shortcode( 'recent_posts_meta', '<span class="recent-posts-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' );
					?>
				</header> <!-- .details -->

				<div class="entry-summary">
					<?php
						$more_link = apply_filters( 'excerpt_more', '...' ) . '<br /> <a href="' . get_permalink() . '" class="more-link">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) . '</a>';

						echo wp_trim_words( get_the_excerpt(), 20, $more_link );
					?>
				</div> <!-- .entry-summary -->

			<?php do_atomic( 'close_recent_posts_item' ); // cakifo_close_recent_posts_item ?>
		</article> <!-- .recent-post -->

	<?php endwhile; wp_reset_query(); ?>

	<?php do_atomic( 'close_recent_posts' ); // cakifo_close_recent_posts ?>

</section> <!-- .recent-posts -->

<?php do_atomic( 'after_recent_posts' ); // cakifo_after_recent_posts ?>
