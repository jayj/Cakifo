<?php
/**
 * The template for displaying Recent Posts in the template-front-page.php page template
 *
 * Child Themes can replace this template part file via {section-recentposts.php}
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

<?php do_atomic( 'before_recent_posts' ); // cakifo_before_recent_posts ?>

	<section id="recent-posts" class="clearfix">

		<?php do_atomic( 'open_recent_posts' ); // cakifo_open_recent_posts ?>

		<?php
			/**
			 * Get the link to the Posts (blog) page
			 * @var string
			 */
			$posts_page = ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
		?>

		<h1 class="section-title"><a href="<?php echo esc_url( $posts_page ); ?>" title="<?php esc_attr_e( 'See more posts', 'cakifo' ); ?>"><?php _e( 'Recent Posts', 'cakifo' ); ?></a></h1>

		<?php
			// Display the Recent Posts
			$recent_args = array(
				'showposts'           => 4, // Show 4 posts
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
				'no_found_rows'       => true,
				'tax_query'           => array(
					array(
						// Exclude posts with the Aside, Link, Quote, and Status format
						'taxonomy' => 'post_format',
						'terms'    => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
						'field'    => 'slug',
						'operator' => 'NOT IN',
					)
				),
			);

			// Our query for the Recent Posts section
			$recent = new WP_Query( $recent_args );

			$i = 0;

			while ( $recent->have_posts() ) : $recent->the_post();

				 // Put the post ID in an array to make sure it's only showing once (this array is used in the headline lists as well)
				$GLOBALS['cakifo_do_not_duplicate'][] = $post->ID;
		?>

			<article class="recent-post">
				<?php do_atomic( 'open_recent_posts_item' ); // cakifo_open_recent_posts_item ?>

					<?php if ( current_theme_supports( 'get-the-image' ) ) { ?>
						<div class="image">
							<?php
								// Get the  thumbnail
								 get_the_image( array(
									'meta_key'      => 'Thumbnail',
									'size'          => 'recent',
									'image_class'   => 'thumbnail',
									'height'        => apply_filters( 'recent_image_height', '130' ),
									'default_image' => THEME_URI . '/images/default-thumb-190-130.gif'
								) );
							?>
						</div>
					<?php } ?>

					<div class="details">
						<?php
							/* Entry title */
							echo apply_atomic( 'recent_post_entry_title', the_title( '<h2 class="' . esc_attr( $post->post_type ) . '-title entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>', false ) );

							/* Entry meta */
							echo apply_atomic_shortcode( 'recent_posts_meta', '<span class="recent-posts-meta">' . __( '[entry-published] by [entry-author]', 'cakifo' ) . '</span>' );
						?>

						<div class="entry-summary">
							<?php
								$more_link = apply_filters( 'excerpt_more', '...' ) . '<br /> <a href="' . get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) . '</a>';

								echo wp_trim_words( get_the_excerpt(), 20, $more_link );
							?>
						</div>
					</div> <!-- .details -->

				<?php do_atomic( 'close_recent_posts_item' ); // cakifo_close_recent_posts_item ?>
			</article>

		<?php endwhile; wp_reset_query(); ?>

		<?php do_atomic( 'close_recent_posts' ); // cakifo_close_recent_posts ?>

	</section> <!-- #recent-posts -->

<?php do_atomic( 'after_recent_posts' ); // cakifo_after_recent_posts ?>
