<?php
/**
 * Audio Content Template
 *
 * Template used to show posts with the 'audio' post format.
 *
 * This can be overridden in child themes with loop-audio.php
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
			<?php echo apply_atomic_shortcode( 'byline_audio', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
		</header> <!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<?php echo apply_atomic_shortcode( 'entry_meta_audio', '<footer class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "]', 'cakifo' ) . '</footer> <!-- .entry-meta -->' ); ?>

		<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

	<?php else : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<?php if ( has_excerpt() ) { ?>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div> <!-- .entry-summary -->

		<?php } else { ?>

			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) ); ?>
				<?php wp_link_pages(); ?>
			</div> <!-- .entry-content -->

		<?php } ?>

		<?php echo apply_atomic_shortcode( 'byline_audio', '<footer class="entry-meta">' . __( 'Published on [entry-published] by [entry-author] [entry-permalink before="| "] [entry-comments-link before="| "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer> <!-- .entry-meta -->' ); ?>

	<?php endif; ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
