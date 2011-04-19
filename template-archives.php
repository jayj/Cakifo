<?php
/**
 * Template Name: Archives
 *
 * A custom page template for displaying blog archives.
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

    <div id="main">

        <?php do_atomic( 'open_main' ); // cakifo_open_main ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php do_atomic( 'before_entry' ); //cakifo_before_entry ?>

                <div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

					<?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>

                    <?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

                    <?php
						if ( current_theme_supports( 'get-the-image' ) )
							get_the_image( array(
								'meta_key' => 'Thumbnail',
								'size' => 'thumbnail',
								'attachment' => false
							) );
					?>

					<div class="entry-content">
						<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>

						<?php do_atomic( 'before_archives' ); //cakifo_before_archives ?>

						<h2><?php _e( 'Archives by category', hybrid_get_textdomain() ); ?></h2>

						<ul class="xoxo category-archives">
							<?php
								wp_list_categories( array(
									'show_count' => true,
									'use_desc_for_title' => false,
									'title_li' => false
								) );
							?>
						</ul> <!-- .xoxo .category-archives -->

						<h2><?php _e( 'Archives by month', hybrid_get_textdomain() ); ?></h2>

						<ul class="xoxo monthly-archives">
							<?php
								wp_get_archives( array(
									'show_post_count' => true,
									'type' => 'monthly'
								) );
							?>
						</ul> <!-- .xoxo .monthly-archives -->

						<?php do_atomic( 'after_archives' ); //cakifo_after_archives ?>

						<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
					</div> <!-- .entry-content -->

                    <?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-edit-link]', hybrid_get_textdomain() ) . '</div>' ); ?>

                    <div class="clear"></div>

                    <?php do_atomic( 'close_entry' ); //cakifo_close_entry ?>

                </div> <!-- #post-<?php the_ID(); ?> -->

            <?php do_atomic( 'after_entry' ); //cakifo_after_entry ?>

            <?php do_atomic( 'after_singular' ); // cakifo_after_singular ?>

			<?php
				$display = apply_filters( 'show_singular_comments', true ); // To disable in child theme: add_filter( 'show_singular_comments', '__return_false' ); 

				if ( $display )
					comments_template( '/comments.php', true ); // Loads the comments.php template
            ?>

        <?php endwhile; ?>

        <?php do_atomic( 'close_main' ); // cakifo_close_main ?>

        <?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

    </div> <!-- #main -->

    <?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>