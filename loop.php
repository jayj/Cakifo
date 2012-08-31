<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.1
 */
?>
<?php do_atomic( 'before_entry' ); //cakifo_before_entry ?>

	<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

		<?php do_atomic( 'open_entry' ); //cakifo_open_entry ?>

		<header class="entry-header">
			<?php if ( get_post_format() ) : ?>
				<hgroup>
					<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
					<?php echo apply_atomic_shortcode( 'entry_format', '<h3 class="entry-format">[post-format-link]</h3>' ); ?>
				</hgroup>
			<?php else : ?>
				<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
			<?php endif; ?>

			<?php
				/**
				 * Get the default post format. Normal posts return false
				 * @var string|bool
				 */
				$format = get_post_format();

				if ( false === $format )
					$format = 'standard';

				/**
				 * Byline
				 */
				if ( 'post' == get_post_type() )
					echo apply_atomic_shortcode( "byline_{$format}", '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'cakifo' ) . '</div>' );
			?>
		</header> <!-- .entry-header -->

		<?php
			/**
			 * Get the thumbnail
			 */
			if ( current_theme_supports( 'get-the-image' ) )
				get_the_image( array(
					'size'       => 'thumbnail',
					'attachment' => false
				) );
		?>

		<?php
			/**
			 * Only display Excerpts for Archives and Search pages
			 * Excerpt for post format archives
			 */
			if ( ( is_archive() || is_search() ) && ! get_post_format() ) :
		?>

			<div class="entry-summary">
				<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
			</div> <!-- .entry-summary -->

		<?php
			/**
			 * Show the normal content
			 *
			 * The loop for the different post formats, can be
			 * overwritten in a Child theme via:
			 *
			 * {loop-quote.php}, {loop-image.php}, {loop-gallery.php}
			 * {loop-link.php}, {loop-aside.php}, {loop-video.php},
			 * {loop-chat.php}, {loop-status}, or {loop-audio.php}
			 */
			else :
		?>

			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'cakifo' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'cakifo' ), 'after' => '</p>' ) ); ?>
			</div> <!-- .entry-content -->

		<?php endif; ?>

		<?php
			/**
			 * Entry meta
			 */
			if ( 'post' == get_post_type() )
				echo apply_atomic_shortcode( "entry_meta_{$format}", '<footer class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'cakifo' ) . '</footer>' );
		?>

		<div class="clear"></div>

		<?php
			// Loads the sidebar-after-single.php template
			if ( is_single() )
				get_sidebar( 'after-single' );
		?>

		<?php
			// Loads the sidebar-after-singular.php template
			if ( is_singular() ) {
				get_sidebar( 'after-singular' );
				do_atomic( 'after_singular' ); // cakifo_after_singular
			}
		?>

		<?php
			// Loads the loop-nav.php template
			if ( is_single() )
				get_template_part( 'loop-nav' );
		?>

		<?php comments_template( '/comments.php', true ); // Loads the comments.php template ?>

		<?php do_atomic( 'close_entry' ); //cakifo_close_entry ?>
	</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); //cakifo_after_entry ?>
