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

		<h1 class="entry-author">
			<?php echo do_shortcode( '[entry-author]' ); ?>
		</h1>

		<h2 class="entry-date">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php echo do_shortcode( '[entry-published]' ); ?>
			</a>
		</h2>

		<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div> <!-- .entry-content -->

	<footer class="entry-meta">
		<?php echo apply_atomic_shortcode( 'entry_meta_status', '[entry-edit-link]' ); ?>
	</footer> <!-- .entry-meta -->

	<?php
		if ( is_singular() )
			do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular)
	?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
