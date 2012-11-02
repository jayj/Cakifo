<?php
/**
 * Aside Content Template
 *
 * Template used to show posts with the 'aside' post format.
 *
 * This can be overridden in child themes with loop-aside.php
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.5.0
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<?php if ( is_singular() && is_main_query() ) : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_aside', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
		</header> <!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
		</div> <!-- .entry-content -->

		<?php
			echo apply_atomic_shortcode( 'entry_meta_aside',
				'<footer class="entry-meta">' . __(
					'[entry-terms taxonomy="category" before="Posted in "]
					 [entry-terms before="| Tagged "]', 'cakifo' ) .
				'</footer> <!-- .entry-meta -->'
			);
		?>

		<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

	<?php else : ?>

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
		</div> <!-- .entry-content -->

		<?php echo apply_atomic_shortcode( 'byline_aside', '<footer class="entry-meta">' . __( '[post-format-link] published on [entry-published] [entry-permalink before="| "] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' ); ?>

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
