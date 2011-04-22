<?php
/**
 * Archive Template
 *
 * The archive template is the default template used for archives pages without a more specific template. 
 *
 * @package Cakifo
 * @subpackage Template
 */

get_header(); // Loads the header.php template ?>

	<?php do_atomic( 'before_main' ); // cakifo_before_main ?>

    <div id="main">

        <?php do_atomic( 'open_main' ); // cakifo_open_main ?>

		<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template ?>

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

        <?php do_atomic( 'before_entry' ); //cakifo_before_entry ?>

    	<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

    	<?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>

        	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

            <?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>

			<?php
				if ( current_theme_supports( 'get-the-image' ) )
					get_the_image( array(
						'meta_key' => 'Thumbnail',
						'size' => 'small',
						'image_class' => 'thumbnail',
						'attachment' => false
					) );
            ?>

            <div class="entry-content">
				<?php the_excerpt(); ?>
                <?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
            </div> <!-- .entry-content -->

             <?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>

    <div class="clear"></div>

    <?php do_atomic( 'close_entry' ); //cakifo_close_entry ?>

    </div> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); //cakifo_after_entry ?>

        <?php endwhile; ?>

        <?php do_atomic( 'close_main' ); // cakifo_close_main ?>

        <?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

    </div> <!-- #main -->

    <?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>