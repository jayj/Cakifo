<?php
/**
 * Image Content Template
 *
 * Template used to show posts with the 'image' post format.
 *
 * This can be overridden in child themes with `content-image.php`
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

		<?php cakifo_post_format_link(); ?>
	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'cakifo' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages();
		?>
	</div> <!-- .entry-content -->

	<footer class="entry-meta">
		<div class="byline"><?php cakifo_posted_on(); ?></div>

		<?php
			if ( is_singular() ) {
				cakifo_entry_meta();
			}
		?>
	</footer> <!-- .entry-meta -->

	<?php
		if ( is_singular() ) {
			do_atomic( 'in_singular' );
		}
	?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
