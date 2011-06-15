<?php
/**
 * Loop Nav Template
 *
 * This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

	<?php if ( is_attachment() ) : ?>

		<nav class="pagination">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '&larr; Return to entry', hybrid_get_textdomain() ) . '</span>' ); ?>
		</nav> <!-- .pagination -->

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<nav class="pagination post-pagination clearfix">
			<?php previous_post_link( '%link', '<span class="previous">&larr; %title</span>' ); ?>
			<?php next_post_link( '%link', '<span class="next">%title &rarr;</span>' ); ?>
		</nav> <!-- .pagination.post-pagination -->

	<?php
		elseif ( !is_singular() && current_theme_supports( 'loop-pagination' ) ) :

			loop_pagination( array(
				'before' => '<nav class="pagination loop-pagination">',
				'after' => '</nav>',
				'mid_size' => 2,
				'prev_text' => __( '&larr; Previous', hybrid_get_textdomain() ),
				'next_text' => __( 'Next &rarr;', hybrid_get_textdomain() )
			) ); 
		
		elseif ( !is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '&larr; Previous', hybrid_get_textdomain() ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Next &rarr;', hybrid_get_textdomain() ) . '</span>' ) ) ) : ?>

		<nav class="pagination">
			<?php echo $nav; ?>
		</nav> <!-- .pagination.loop-pagination -->

	<?php endif; ?>