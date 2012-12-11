<?php
/**
 * Gallery Content Template
 *
 * Template used to show posts with the 'gallery' post format.
 *
 * This can be overridden in child themes with `loop-gallery.php`
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.5.0
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_gallery', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<?php echo apply_atomic_shortcode( 'entry_meta_gallery', '<footer class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer> <!-- .entry-meta -->' ); ?>

		<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

	<?php else : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_gallery', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
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
			<?php
				if ( has_excerpt() ) {
					the_excerpt();
					wp_link_pages();
				}
			?>

			<?php $count = cakifo_get_image_attachment_count(); ?>

			<p class="image-count">
				<?php printf( _n( 'This gallery contains %s image.', 'This gallery contains %s images.', $count, 'cakifo' ), $count ); ?>
			</p>
		</div> <!-- .entry-summary -->

		<?php echo apply_atomic_shortcode( 'entry_meta_gallery', '<footer class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer> <!-- .entry-meta -->' ); ?>

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
