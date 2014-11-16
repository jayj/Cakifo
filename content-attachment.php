<?php
/**
 * Attachment Content Template
 *
 * Template used to show the post content of the attachment post type.
 *
 * This can be overridden in child themes with content-attachment.php
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<header class="entry-header">
		<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
		<?php echo apply_atomic_shortcode( 'byline_attachment', '<div class="byline">' . __( 'Uploaded on [entry-published] [entry-edit-link before="| "]', 'cakifo' ) . '</div>' ); ?>
	</header> <!-- .entry-header -->

	<?php cakifo_post_thumbnail(); ?>

	<div class="entry-content">
		<?php the_content(); ?>

		<?php hybrid_attachment(); // Function for handling non-image attachments. ?>

		<?php wp_link_pages(); ?>
	</div> <!-- .entry-content -->

	<?php echo apply_atomic_shortcode( 'entry_meta_attachment', '' ); ?>

	<aside class="attachment-meta clearfix">
		<div class="attachment-info">
			<h3><?php _e( 'Attachment Info', 'cakifo' ) ?></h3>

			<?php hybrid_media_meta(); ?>
		</div> <!-- .attachment-info -->

		<?php if ( $post->post_parent ) : ?>
			<?php $gallery = do_shortcode( sprintf( '[gallery id="%1$s" exclude="%2$s" columns="5" size="small"]', $post->post_parent, get_the_ID() ) ); ?>

			<?php if ( ! empty( $gallery ) ) : ?>
				<div class="attachment-gallery">
					<h3><?php _e( 'Gallery', 'cakifo' ); ?></h3>
					<?php echo $gallery; ?>
				</div> <!-- .attachment-gallery -->
			<?php endif; ?>

		<?php endif; ?>

	</aside> <!-- .attachment-meta -->

	<?php do_atomic( 'in_singular' ); ?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
