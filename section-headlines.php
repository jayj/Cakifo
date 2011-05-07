<?php
/**
 * The template for displaying headlines from categories in the template-front-page.php page template

 * @package Cakifo
 * @subpackage Template
 */
?>

<?php do_atomic( 'before_headlines' ); // cakifo_before_headlines ?>

    <section id="headlines" class="clearfix">

		<?php do_atomic( 'open_headlines' ); // cakifo_open_headlines ?>

        <?php
        	$i = 0;
        	foreach ( hybrid_get_setting( 'headlines_category' ) as $category ) :
        ?>

				<?php
                	// Create the loop for each selected category, ignoring Aside, Link, Quote and Status posts
					$headlines = get_posts( array(
						'numberposts' => ( hybrid_get_setting( 'headlines_num_posts' ) ) ? hybrid_get_setting( 'headlines_num_posts' ) : 4,
						'post__not_in' => $GLOBALS['cakifo_do_not_duplicate'],
						'tax_query' => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'category',
									'field' => 'id',
									'terms' => $category,
									'operator' => 'IN',
								),
								array(
									'taxonomy' => 'post_format',
									'terms' => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
									'field' => 'slug',
									'operator' => 'NOT IN',
								),
						),
					) );
                ?>

                <?php if ( ! empty( $headlines ) ) : ?>

                    <div class="headline-list <?php echo ( $i++ % 3 == 2 ) ? 'last' : ''; // 'Last' class for every 3rd category ?>">

						<?php do_atomic( 'open_headline_list' ); // cakifo_open_headline_list ?>

                        <?php $cat = get_category( $category ); ?>

                        <h3 class="section-title"><a href="<?php echo get_category_link( $category ); ?>" title="<?php echo esc_attr( $cat->name ); ?>"><?php echo $cat->name; ?></a></h3>

                        <ul>
							<?php foreach ( $headlines as $post ) : $GLOBALS['cakifo_do_not_duplicate'][] = $post->ID; ?>
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
                                    	<?php echo apply_atomic_shortcode( 'headline_meta', '<span class="headline-meta">' . __( '[entry-published pubdate="no"] by [entry-author]', hybrid_get_textdomain() ) . '</span>' ); ?>
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

    </section> <!-- #headlines -->

<?php do_atomic( 'after_headlines' ); // cakifo_after_headlines ?>