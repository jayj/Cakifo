<?php
/**
 * Link Content Template
 *
 * Template used to show posts with the 'link' post format.
 *
 * This can be overridden in child themes with content-link.php
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_link', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<?php cakifo_post_thumbnail(); ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_video', __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

		<?php do_atomic( 'in_singular' ); ?>

	<?php else : ?>

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( hybrid_get_the_post_format_url() ) . '">', '</a></h1>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<?php cakifo_post_thumbnail(); ?>

		<div class="entry-content">
			<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					esc_html__( 'Continue reading %s', 'cakifo' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				) );

				wp_link_pages();
			?>
		</div> <!-- .entry-content -->

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_link', __( '[post-format-link] published on [entry-published] by [entry-author] [entry-permalink before="| "] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
