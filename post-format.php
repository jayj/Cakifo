<?php
/**
 * Post format Template
 *
 * This is the loops for each post format. Each section is seperated by
 * if ( has_post_format( 'foo' ) ) : 
 * elseif ( has_post_format( 'bar' ) ) : 
 * endif;
 * Loops can be overwrited in your childtheme by creating 
 * the file post-format-formatname.php (ex post-format-aside.php for aside posts)
 *
 * @package Cakifo
 * @subpackage Template
 */

?>

<?php do_atomic( 'before_entry' ); //cakifo_before_entry ?>

    <div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
    
    <?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>
    
    <?php if ( has_post_format( 'aside' ) ) : ?>


		<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // Will be hided with CSS ?>
        
		<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail', 'attachment' => false ) ); ?>
        
        <div class="entry-content">
        	<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
        	<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        </div> <!-- .entry-content -->
        
        <?php echo apply_atomic_shortcode( 'entry_meta_aside', '<div class="entry-meta">' . __( 'By [entry-author] on [entry-published] [entry-terms taxonomy="category" before="in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>
    
    
    <?php elseif ( has_post_format( 'quote' ) ) : // Quotes ?>
    
    	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // Will be hided with CSS ?>
        
        <div class="entry-content">
        	<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
        	<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        </div> <!-- .entry-content -->
        
        <?php echo apply_atomic_shortcode( 'entry_meta_quote', '<div class="entry-meta">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>
    
    
    <?php elseif ( has_post_format( 'link' ) ) : // Links ?>
    
    
    	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
        
		<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail', 'attachment' => false ) ); ?>
        
        <div class="entry-content">
        	<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
        	<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        </div> <!-- .entry-content -->

		 <?php echo apply_atomic_shortcode( 'entry_meta_link', '<div class="entry-meta">' . __( 'Recommended by [entry-author] on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>
    
    
    <?php elseif ( has_post_format( 'status' ) ) : // Statuses ?>
    
    	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // Will be hided with CSS ?>
    
        <div class="entry-content note">
        	<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
        	<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        </div> <!-- .entry-content -->
    
    
	<?php else : // Any other post format (standard) ?>

		<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
        
        <?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>
        
		<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail', 'attachment' => false ) ); ?>
        
        <div class="entry-content">
        	<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', hybrid_get_textdomain() ) ); ?>
        	<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
        </div> <!-- .entry-content -->

		 <?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', hybrid_get_textdomain() ) . '</div>' ); ?>

	<?php endif; ?>

    <div class="clear"></div>
    
    <?php do_atomic( 'close_entry' ); //cakifo_close_entry ?>
    
    </div> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); //cakifo_after_entry ?>