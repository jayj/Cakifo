<?php
/**
 * The template for displaying headlines from categories
 * in the template-front-page.php page template
 *
 * Child Themes can replace this template part file via {section-headlines.php}
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

<?php do_atomic( 'before_headlines' ); // cakifo_before_headlines ?>

	<section id="headlines" class="clearfix">

		<?php do_atomic( 'open_headlines' ); // cakifo_open_headlines ?>

		<?php
			$i = 0;
			
			// Get the number of posts to show
			$number = ( hybrid_get_setting( 'headlines_num_posts' ) ) ? hybrid_get_setting( 'headlines_num_posts' ) : 4;

			// Loop through each selected category
			foreach ( hybrid_get_setting( 'headlines_category' ) as $category ) :
		?>
				<?php
					/**
					 * Create the loop for each selected category
					 *
					 * @uses $GLOBALS['cakifo_do_not_duplicate'] Exclude posts from the 'Recent Posts' section
					 */
					$headlines = get_posts( array(
						'numberposts'  => $number,
						'post__not_in' => $GLOBALS['cakifo_do_not_duplicate'], // Don't show posts that are showing in the 'Recent Posts' section
						'category'     => $category,
						'post_status'  => 'publish',
						'tax_query'    => array(
							array(
								// Exclude posts with the Aside, Link, Quote, and Status format
								'taxonomy'     => 'post_format',
								'terms'        => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
								'field'        => 'slug',
								'operator'     => 'NOT IN',
							),
						),
					) );
				?>

				<?php if ( ! empty( $headlines ) ) : ?>

					<div class="headline-list <?php echo ( $i++ % 3 == 2 ) ? 'last' : ''; // 'Last' class for every 3rd category ?>">

						<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

						<?php $cat = get_category( $category ); ?>

						<h2 class="section-title alternative-section-title"><a href="<?php echo get_category_link( $category ); ?>" title="<?php echo esc_attr( $cat->name ); ?>"><?php echo $cat->name; ?></a></h2>

						<ol>
							<?php foreach ( $headlines as $post ) : $GLOBALS['cakifo_do_not_duplicate'][] = $post->ID; ?>
								<li>
									<?php do_atomic( 'open_headline_list_item' ); // cakifo_open_headline_list_item ?>

									<?php if ( current_theme_supports( 'get-the-image' ) ) { ?>
										<div class="image">
											<?php
												// Get the thumbnail
												get_the_image( array(
													'meta_key'      => 'Thumbnail',
													'size'          => 'small',
													'image_class'   => 'thumbnail',
													'default_image' => THEME_URI . '/images/default-thumb-mini.gif'
												) );
											?>
										</div>
									<?php } ?>

									<div class="details">
										<?php
											/* Entry title */
											echo apply_atomic( 'headline_entry_title', the_title( '<h3 class="' . esc_attr( $post->post_type ) . '-title entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h3>', false ) );
											
											/* Entry meta */
											echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published pubdate="no"] by [entry-author]', 'cakifo' ) . '</span>' );
										?>
									</div> <!-- .details -->

									<?php do_atomic( 'close_headline_list_item' ); // cakifo_close_headline_list_item ?>
								</li>
							<?php endforeach; ?>
						</ol>

						<?php do_atomic( 'close_headline_list' ); // cakifo_close_headline_list ?>

					</div> <!-- .headline-list -->

				<?php endif; ?>

		<?php endforeach; ?>

		<?php unset( $GLOBALS['cakifo_do_not_duplicate'] ); // Kill the variable ?>

		<?php do_atomic( 'close_headlines' ); // cakifo_close_headlines ?>

	</section> <!-- #headlines -->

<?php do_atomic( 'after_headlines' ); // cakifo_after_headlines ?>