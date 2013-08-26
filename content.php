<?php
/**
 * The loop that displays posts.
 *
 * Template used to show post content when a more specific template cannot be found.
 *
 * This can be overridden in child themes with `loop.php` or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package    Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_standard', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
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
			<?php echo apply_atomic_shortcode( 'entry_meta_standard', __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

		<?php do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular) ?>

	<?php else : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_standard', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
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

		<?php if ( is_archive() || is_search() ) : ?>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
				<?php wp_link_pages(); ?>
			</div> <!-- .entry-summary -->

		<?php else : ?>

			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) ); ?>
				<?php wp_link_pages(); ?>
			</div> <!-- .entry-content -->

		<?php endif; // is_archive() || is_search() ?>

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_standard', __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-comments-link before="| "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

	<?php endif; // is_singular() ?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
