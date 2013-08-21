<?php
/**
 * Status Content Template
 *
 * Template used to show posts with the 'status' post format.
 *
 * This can be overridden in child themes with `content-status.php`
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.6.0
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<header class="entry-header clearfix">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_atomic( 'status_avatar', 48 ) ); ?>

		<?php
			echo hybrid_entry_author_shortcode( array(
				'before' => '<h1 class="entry-author">',
				'after'  => '</h1>'
			) );
		?>

		<a href="<?php the_permalink(); ?>" class="entry-date">
			<?php echo hybrid_entry_published_shortcode( array() ); ?>
		</a>

		<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) ); ?>
		<?php wp_link_pages(); ?>
	</div> <!-- .entry-content -->

	<?php if ( ! get_option( 'show_avatars' ) ) : ?>
		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_aside', __( '[post-format-link] published on [entry-published] by [entry-author] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->
	<?php endif; ?>

	<?php
		if ( is_singular() ) {
			do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular)
		}
	?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
