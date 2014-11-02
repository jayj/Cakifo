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
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'cakifo' ); ?></h3>
			<?php previous_post_link( '%link', '<span class="previous">' . sprintf( __( '<span class="meta-nav">&larr;</span> %s', 'cakifo' ), '%title' ) . '</span>' ); ?>
			<?php next_post_link( '%link', '<span class="next">' . sprintf( __( '%s <span class="meta-nav">&rarr;</span>', 'cakifo' ), '%title' ) . '</span>' ); ?>
		</nav> <!-- .pagination.post-pagination -->

	<?php
		// Pagination if the theme supports "loop-pagination".
		elseif ( ! is_singular() && current_theme_supports( 'loop-pagination' ) ) :

			loop_pagination( array(
				'before'    => '<nav class="pagination loop-pagination clearfix"> <h3 class="assistive-text">' . __( 'Post navigation', 'cakifo' ) . '</h3>',
				'after'     => '</nav>',
				'mid_size'  => 2,
				'prev_text' => __( '<span class="meta-nav">&larr;</span> Previous', 'cakifo' ),
				'next_text' => __( 'Next <span class="meta-nav">&rarr;</span>', 'cakifo' )
			) );

		// Normal 'Previous' and 'Next' links
		elseif ( ! is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '<span class="meta-nav">&larr;</span> Previous', 'cakifo' ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Next <span class="meta-nav">&rarr;</span>', 'cakifo' ) . '</span>' ) ) ) : ?>

		<nav class="pagination clearfix">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'cakifo' ); ?></h3>
			<?php echo $nav; ?>
		</nav> <!-- .pagination -->

	<?php endif; ?>
