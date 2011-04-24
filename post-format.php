<?php
/**
 * Post format Template
 *
 * This is the loops for each post format. Each section is seperated by
 * if ( has_post_format( 'foo' ) ) : 
 * elseif ( has_post_format( 'bar' ) ) : 
 * endif;
 * Loops can be overwrited in your child theme by creating 
 * the file post-format-formatname.php (ex post-format-aside.php for aside posts)
 *
 * @package Cakifo
 * @subpackage Template
 */

?>

<?php do_atomic( 'before_entry' ); //cakifo_before_entry ?>

    <article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
    
		<?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>
        
        <header class="entry-header">
        	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
        
        	<?php echo apply_atomic_shortcode( 'byline_' . get_post_format(), '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>
        </header> <!-- .entry-header --> 
        
        <?php
			if ( current_theme_supports( 'get-the-image' ) )
				get_the_image( array(
					'meta_key' => 'Thumbnail',
					'size' => 'thumbnail',
					'attachment' => false
				) );
        ?>

		<?php if ( is_archive() || is_search() ) : // Only display Excerpts for Archives and Search ?>
        	<div class="entry-summary <?php if ( has_post_format( 'status' ) ) echo 'note'; ?>">
        		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
                <?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        	</div> <!-- .entry-summary -->
        <?php else : ?>
        	<div class="entry-content <?php if ( has_post_format( 'status' ) ) echo 'note'; ?>">
        		<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
        		<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        	</div> <!-- .entry-content -->
        <?php endif; ?>
        
        <?php
			/* Entry meta */
			echo apply_atomic_shortcode( 'entry_meta_' . get_post_format(), '<footer class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</footer>' );
		?>
        
        <div class="clear"></div>
        
    <?php do_atomic( 'close_entry' ); //cakifo_close_entry ?>
    
    </article> <!-- #post-<?php the_ID(); ?> -->
	
<?php do_atomic( 'after_entry' ); //cakifo_after_entry ?>