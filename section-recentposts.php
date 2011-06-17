<?php
/**
 * The template for displaying recent posts in the template-front-page.php page template

 * @package Cakifo
 * @subpackage Template
 */
?>

<?php do_atomic( 'before_recent_posts' ); // cakifo_before_recent_posts ?>

    <section id="recent-posts" class="clearfix">

		<?php do_atomic( 'open_recent_posts' ); // cakifo_open_recent_posts ?>

        <h1 class="section-title"><?php _e( 'Recent Posts', hybrid_get_textdomain() ); ?></h1>

		<?php
            // Display our recent posts, ignoring Aside, Link, Quote and Status posts
            $recent_args = array(
                'showposts' => 4,
                'ignore_sticky_posts' => 1,
                'order' => 'DESC',
				'no_found_rows' => true,
                'tax_query' => array( array(
                        'taxonomy' => 'post_format',
                        'terms' => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
                        'field' => 'slug',
                        'operator' => 'NOT IN',
                    ),
                ),
            );

            // Our query for the Recent Posts section
            $recent = new WP_Query( $recent_args );
			
			$i = 0;
			
            while ( $recent->have_posts() ) : $recent->the_post();
			
                $GLOBALS['cakifo_do_not_duplicate'][] = $post->ID; // Put the post ID in an array to make sure it's only showing once (this array is used in the headline lists as well)
        ?>

            <article class="recent-post">
				<?php do_atomic( 'open_recent_posts_item' ); // cakifo_open_recent_posts_item ?>
    
                    <?php if ( current_theme_supports( 'get-the-image' ) ) { ?>
                        <div class="image">
                            <?php
                                get_the_image( array(
                                    'meta_key' => 'Thumbnail',
                                    'size' => 'recent',
                                    'image_class' => 'thumbnail',
									'height' => apply_filters( 'recent_image_height', '130' ),
                                    'default_image' => THEME_URI . '/images/default-thumb-190-130.gif',
                                ) );
                            ?>
                        </div>
                    <?php } ?>
    
                    <div class="details">
                        <?php echo apply_atomic_shortcode( 'recent_post_entry_title', '[entry-title heading="h1"]' ); ?>
    
                        <?php echo apply_atomic_shortcode( 'recent_posts_meta', '<span class="recentposts-meta">' . __( '[entry-published] by [entry-author]', hybrid_get_textdomain() ) . '</span>' ); ?>
    
                        <div class="entry-summary">
                            <?php cakifo_the_excerpt( 20 ); ?>
                        </div>
                    </div> <!-- .details -->
    
                <?php do_atomic( 'close_recent_posts_item' ); // cakifo_close_recent_posts_item ?>
            </article>

        <?php endwhile; wp_reset_query(); ?>

		<?php do_atomic( 'close_recent_posts' ); // cakifo_close_recent_posts ?>

    </section> <!-- #recent-posts -->

<?php do_atomic( 'after_recent_posts' ); // cakifo_after_recent_posts ?>