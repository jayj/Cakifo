<?php
/**
 * Quote Content Template
 *
 * Template used to show posts with the 'quote' post format.
 *
 * This can be overridden in child themes with `content-quote.php`
 *
 * @package Cakifo
 * @subpackage Template
 */

do_atomic( 'before_entry' ); ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); ?>

	<?php if ( is_singular() ) : ?>

		<header class="entry-header">
			<h1 class="entry-title post-title"><?php single_post_title(); ?></h1>
		</header> <!-- .entry-header -->

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
