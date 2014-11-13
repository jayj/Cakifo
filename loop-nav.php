<?php
/**
 * Loop Nav Template
 *
 * This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package  Cakifo
 * @subpackage Template
 */
?>

	<?php if ( is_attachment() ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '<span class="meta-nav">&larr;</span> Return to entry', 'cakifo' ) . '</span>' ); ?>
		</div> <!-- .loop-nav -->

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<nav class="pagination post-pagination clearfix">
			<h3 class="screen-reader-text"><?php _e( 'Post navigation', 'cakifo' ); ?></h3>
			<?php previous_post_link( '%link', '<span class="previous">' . sprintf( __( '<span class="meta-nav">&larr;</span> %s', 'cakifo' ), '%title' ) . '</span>' ); ?>
			<?php next_post_link( '%link', '<span class="next">' . sprintf( __( '%s <span class="meta-nav">&rarr;</span>', 'cakifo' ), '%title' ) . '</span>' ); ?>
		</nav> <!-- .pagination.post-pagination -->

	<?php elseif ( ! is_singular() ) : ?>

		<?php
			the_pagination( array(
				'mid_size'           => 2,
				'prev_text'          => __( '<span class="meta-nav">&larr;</span> Previous page', 'cakifo' ),
				'next_text'          => __( 'Next page <span class="meta-nav">&rarr;</span>', 'cakifo' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'cakifo' ) . ' </span>',
			) );
		?>

	<?php endif; ?>
