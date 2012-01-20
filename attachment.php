<?php
/**
 * Attachment Template
 *
 * This is the default attachment template.  It is used when visiting the singular view of a post attachment 
 * page (images, videos, audio, etc.).
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

        <article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

			<?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>

            <header class="entry-header">
				<?php
					echo apply_atomic_shortcode( "attachment_metadeta", '<div class="entry-format">' . sprintf( 
						__( 'Published on [entry-published] in <a href="%1$s" title="Return to %2$s" rel="gallery">%2$s</a> [entry-edit-link before=" | "]', 'cakifo' ), 
							esc_url( get_permalink( $post->post_parent ) ),
							get_the_title( $post->post_parent )
					) . '</div>' );
                ?>
                <?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
            </header> <!-- .entry-header -->

            <div class="entry-content">
                <?php
                	/* Image attachments */
					if ( wp_attachment_is_image( get_the_ID() ) ) :
				?>
                        <p class="attachment-image">
                            <a href="<?php echo wp_get_attachment_url( get_the_ID() ); ?>" title="<?php esc_attr_e( 'View full size', 'cakifo' ); ?>">
                            	<?php echo wp_get_attachment_image( get_the_ID(), 'full', false, array( 'class' => 'aligncenter' ) ); ?>
                            </a>
                        </p> <!-- .attachment-image -->

                        <?php if ( ! empty( $post->post_excerpt ) ) : ?>
                            <div class="entry-caption">
                            	<?php the_excerpt(); ?>
                            </div>
                		<?php endif; ?>
                <?php else : ?>

					<?php hybrid_attachment(); // Function for handling non-image attachments ?>

                    <p class="download">
                    	<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>" rel="enclosure" type="<?php echo get_post_mime_type(); ?>"><?php printf( __( 'Download &quot;%1$s&quot;', 'cakifo' ), the_title( '<span class="fn">', '</span>', false) ); ?></a>
                    </p> <!-- .download -->

                <?php endif; ?>
            </div> <!-- .entry-content -->

            <?php if ( wp_attachment_is_image( get_the_ID() ) ) : ?>

                <div id="image-info">
  					<?php cakifo_image_info(); // Get image meta data ?>

                    <div id="attachment-gallery" class="headline-list last">
                        <h4><?php _e( 'Gallery', 'cakifo' ); ?></h4>
                        <?php echo apply_atomic_shortcode( 'attachment_gallery', sprintf( '[gallery id="%1$s" exclude="%2$s" columns="5" size="small"]', $post->post_parent, get_the_ID() ) ); ?>
                    </div> <!-- #attachment-gallery -->
                </div> <!-- #image-info -->	

            <?php endif; ?>

            <div class="clear"></div>

            <?php do_atomic( 'close_entry' ); //cakifo_close_entry ?>

        </article> <!-- #post-<?php the_ID(); ?> -->

        <?php get_sidebar( 'after-singular' ); // Loads the sidebar-after-singular.php template ?>

        <?php do_atomic( 'after_singular' ); // cakifo_after_singular ?>

        <?php comments_template( '/comments.php', true ); // Loads the comments.php template ?>

	<?php endwhile; ?>

        <?php do_atomic( 'close_main' ); // cakifo_close_main ?>

        <?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template ?>

    </div> <!-- #main -->

	<?php do_atomic( 'after_main' ); // cakifo_after_main ?>

<?php get_footer(); // Loads the footer.php template ?>