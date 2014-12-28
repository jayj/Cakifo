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

	<?php if ( is_attachment() && $post->post_parent  ) : ?>

		<?php
			the_post_navigation( array(
				'prev_text' => _x( 'Return to <span class="post-title">%title</span>', 'Parent post link', 'cakifo' ),
			) );
		?>

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<?php the_post_navigation(); ?>

	<?php elseif ( ! is_singular() ) : ?>

		<?php
			the_posts_pagination( array(
				'mid_size'           => 2,
				'prev_text'          => __( 'Previous page', 'cakifo' ),
				'next_text'          => __( 'Next page', 'cakifo' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'cakifo' ) . ' </span>',
			) );
		?>

	<?php endif; ?>
