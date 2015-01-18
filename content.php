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
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title post-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>

		<div class="byline"><?php cakifo_posted_on(); ?></div>
	</header> <!-- .entry-header -->

	<?php cakifo_post_thumbnail(); ?>

	<?php if ( is_archive() || is_search() ) : ?>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<?php wp_link_pages(); ?>
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

	<?php endif; // is_archive() || is_search() ?>

	<footer class="entry-meta">
		<?php cakifo_entry_meta(); ?>
	</footer> <!-- .entry-meta -->

	<?php
		if ( is_singular() ) {
			do_atomic( 'in_singular' );
		}
	?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
