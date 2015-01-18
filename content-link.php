<?php
/**
 * Link Content Template
 *
 * Template used to show posts with the 'link' post format.
 *
 * This can be overridden in child themes with content-link.php
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<?php the_title( sprintf( '<h1 class="entry-title post-title"><a href="%s">', esc_url( hybrid_get_the_post_format_url() ) ), '</a></h1>' ); ?>

			<?php cakifo_post_format_link(); ?>
		</header> <!-- .entry-header -->

		<?php cakifo_post_thumbnail(); ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div> <!-- .entry-content -->

		<footer class="entry-meta">
			<div class="byline"><?php cakifo_posted_on(); ?></div>
			<?php cakifo_entry_meta(); ?>
		</footer> <!-- .entry-meta -->

		<?php do_atomic( 'in_singular' ); ?>

	<?php else : ?>

		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title post-title"><a href="%s">', esc_url( hybrid_get_the_post_format_url() ) ), '</a></h2>' ); ?>

			<?php cakifo_post_format_link(); ?>
		</header> <!-- .entry-header -->

		<?php cakifo_post_thumbnail(); ?>

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
			<?php cakifo_posted_on(); ?>
		</footer> <!-- .entry-meta -->

	<?php endif; ?>

	<?php do_atomic( 'close_entry' ); ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); ?>
