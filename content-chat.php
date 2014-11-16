<?php
/**
 * Chat Content Template
 *
 * Template used to show posts with the 'chat' post format.
 *
 * This can be overridden in child themes with `content-chat.php`
 *
 * @package    Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_chat', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_chat', __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

		<?php do_atomic( 'in_singular' ); ?>

	<?php else : ?>

		<header class="entry-header">
			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
			<?php echo apply_atomic_shortcode( 'byline_chat', '<div class="byline">' . __( 'Published on [entry-published] by [entry-author] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' ); ?>
			<?php echo apply_atomic_shortcode( 'post_format_link', '[post-format-link]' ); ?>
		</header> <!-- .entry-header -->

		<?php if ( has_excerpt() ) : ?>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div> <!-- .entry-summary -->

		<?php else : ?>

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

		<?php endif; ?>

		<footer class="entry-meta">
			<?php echo apply_atomic_shortcode( 'entry_meta_chat', __( '[post-format-link] [entry-terms taxonomy="category" before="posted in "] [entry-terms before="| Tagged "] [entry-comments-link before="| "]', 'cakifo' ) ); ?>
		</footer> <!-- .entry-meta -->

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
