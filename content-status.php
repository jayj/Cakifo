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

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<header class="entry-header clearfix">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_atomic( 'status_avatar', 48 ) ); ?>

		<?php
			printf( '<h1 class="entry-author author vcard"><a class="url fn n" href="%1$s">%2$s</a></h1>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		?>

		<a href="<?php the_permalink(); ?>" class="entry-date">
			<?php cakifo_post_date(); ?>
		</a>

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

	<?php if ( ! get_option( 'show_avatars' ) ) : ?>
		<footer class="entry-meta">
			<?php cakifo_posted_on(); ?>
		</footer> <!-- .entry-meta -->
	<?php endif; ?>

	<?php
		if ( is_singular() ) {
			do_atomic( 'in_singular' );
		}
	?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
