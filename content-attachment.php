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
 * @since Cakifo 1.6.0
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
		</header> <!-- .entry-header -->

		<?php
			/**
			 * Get the thumbnail
			 */
			if ( current_theme_supports( 'get-the-image' ) )
				get_the_image(
					array(
						'size'       => 'thumbnail',
						'attachment' => false
					)
				);
		?>

		<div class="entry-content">
			<?php hybrid_attachment(); // Function for handling non-image attachments. ?>

			<p class="download">
				<a href="<?php echo esc_url( wp_get_attachment_url() ); ?>" type="<?php echo esc_attr( get_post_mime_type() ); ?>">
					<?php printf( __( 'Download %s', 'cakifo' ), the_title( '<span class="fn">&quot;', '&quot;</span>', false ) ); ?>
				</a>
			</p> <!-- .download -->

			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<?php echo apply_atomic_shortcode( 'entry_meta_attachment', '<footer class="entry-meta">' . __( 'Published on [entry-published] [entry-edit-link before="| "]', 'cakifo' ) . '</footer> <!-- .entry-meta -->' ); ?>

		<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

	<?php else : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
		</header> <!-- .entry-header -->

		<?php
			/**
			 * Get the thumbnail
			 */
			if ( current_theme_supports( 'get-the-image' ) )
				get_the_image(
					array(
						'size'       => 'thumbnail',
						'attachment' => false
					)
				);
		?>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-summary -->

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
