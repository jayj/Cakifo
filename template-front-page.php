<?php
/**
 * Template Name: Front Page
 *
 * This is the frontpage page template
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

    <div id="main">

	<?php do_atomic( 'open_main' ); // cakifo_open_main ?>

    <h3 class="section-title"><?php _e( 'Recent Posts', hybrid_get_textdomain() ); ?></h3>

    <?php do_atomic( 'before_recent_posts' ); // cakifo_before_recent_posts ?>

        <div id="recent-posts">
            <?php do_atomic( 'open_recent_posts' ); // cakifo_open_recent_posts ?>

            <ul>
                <?php
                    // Create Recent Posts loop
                    $loop = new WP_Query( array( 'showposts' => 4, 'ignore_sticky_posts' => 1 ) );

                    while ( $loop->have_posts() ) : $loop->the_post();
                        $do_not_duplicate[] = $post->ID; // Put the post ID in an array so make sure it only shows once (this array is used in the headline lists as well)
                ?>
                    <li>
                        <?php do_atomic( 'open_recent_posts_item' ); // cakifo_open_recent_posts_item ?>

                        <?php if ( current_theme_supports( 'get-the-image' ) ) { ?>
                            <div class="image">
								<?php 
									get_the_image( array(
										'meta_key' => 'Thumbnail',
										'size' => 'recent',
										'image_class' => 'thumbnail',
										'default_image' => THEME_URI . '/images/default-thumb-190-130.gif'
									) );
								?>
							</div>
                        <?php } ?>

                        <div class="details">
                            <h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>

                            <?php echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', hybrid_get_textdomain() ) . '</span>' ); ?>

                            <div class="entry-summary">
								<?php cakifo_the_excerpt( 20 ); ?>
                            </div>
                        </div> <!-- .details -->

                        <?php do_atomic( 'close_recent_posts_item' ); // cakifo_close_recent_posts_item ?>
                    </li>
                <?php endwhile; ?>

                <?php do_atomic( 'close_recent_posts' ); // cakifo_close_recent_posts ?>
            </ul>

        </div> <!-- #recent-posts -->

    <?php do_atomic( 'after_recent_posts' ); // cakifo_after_recent_posts ?>

	<?php if ( hybrid_get_setting( 'headlines_category' ) ) : ?>

		<?php do_atomic( 'before_headlines' ); // cakifo_before_headlines ?>

        <div id="headlines">
			<?php do_atomic( 'open_headlines' ); // cakifo_open_headlines ?>

            <?php
				$i = 0;
				foreach ( hybrid_get_setting( 'headlines_category' ) as $category ) :
			?>

				<?php
					// Create the loop for each selected category
					$headlines = get_posts( array(
						'numberposts' => ( hybrid_get_setting( 'headlines_num_posts' ) ) ? hybrid_get_setting( 'headlines_num_posts' ) : 4,
						'category' => $category,
						'post__not_in' => $do_not_duplicate
					) );
                ?>

                <?php if ( !empty( $headlines ) ) : ?>

                    <div class="headline-list <?php echo ( $i++ % 3 == 2 ) ? 'last' : ''; // 'Last' class for every 3rd category ?>">
						<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

                        <?php $cat = get_category( $category ); ?>

                        <h3 class="section-title"><a href="<?php echo get_category_link( $category ); ?>" title="<?php echo esc_attr( $cat->name ); ?>"><?php echo $cat->name; ?></a></h3>

                        <ul>
							<?php foreach ( $headlines as $post ) : $do_not_duplicate[] = $post->ID; ?>
                            <li>
								<?php do_atomic( 'open_headline_list_item' ); // cakifo_open_headline_list_item ?>

                                <?php if ( current_theme_supports( 'get-the-image' ) ) { ?>
									<div class="image">
										<?php 
											get_the_image( array(
												'meta_key' => 'Thumbnail',
												'size' => 'small',
												'image_class' => 'thumbnail',
												'default_image' => THEME_URI . '/images/default-thumb-mini.gif'
											) );
										?>
									</div>
                                <?php } ?>

                                <div class="details">
									<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
									<?php echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published] by [entry-author]', hybrid_get_textdomain() ) . '</span>' ); ?>
                                </div> <!-- .details -->

                                <?php do_atomic( 'close_headline_list_item' ); // cakifo_close_headline_list_item ?>
							</li>
                            <?php endforeach; ?>
                        </ul>

                        <?php do_atomic( 'close_headline_list' ); // cakifo_close_headline_list ?>
                    </div> <!-- .headline-list -->

                <?php endif; ?>

            <?php endforeach; ?>

            <?php do_atomic( 'close_headlines' ); // cakifo_close_headlines ?>
        </div> <!-- #headlines -->

        <?php do_atomic( 'after_headlines' ); // cakifo_after_headlines ?>

	<?php endif; // End check if headline categories were selected ?>

	<?php do_atomic( 'close_main' ); // cakifo_close_main ?>

    <?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

    </div> <!-- #main -->

    <?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>