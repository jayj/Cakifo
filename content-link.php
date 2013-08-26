<?php
/**
 * Link Content Template
 *
 * Template used to show posts with the 'link' post format.
 *
 * This can be overridden in child themes with content-link.php
 *
 * @package    Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( hybrid_get_the_post_format_url() ) . '">', ' <span class="meta-nav">&rarr;</span></a></h1>' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_link', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<?php
			// Get the thumbnail.
			if ( current_theme_supports( 'get-the-image' ) ) {
				get_the_image( array(
					'size'       => 'thumbnail',
					'attachment' => false
				));
			}
		?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_video', __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

		<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

	<?php else : ?>

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( hybrid_get_the_post_format_url() ) . '">', ' <span class="meta-nav">&rarr;</span></a></h1>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<?php
			// Get the thumbnail.
			if ( current_theme_supports( 'get-the-image' ) ) {
				get_the_image( array(
						'size'       => 'thumbnail',
						'attachment' => false
				));
			}
		?>

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) ); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_link', __( '[post-format-link] published on [entry-published] by [entry-author] [entry-permalink before="| "] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
