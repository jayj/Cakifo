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

		<div class="pagination">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '&larr; Return to entry', hybrid_get_textdomain() ) . '</span>' ); ?>
		</div> <!-- .pagination -->

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<div class="pagination post-pagination clearfix">
			<?php previous_post_link( '%link', '<span class="previous">&larr; %title</span>' ); ?>
			<?php next_post_link( '%link', '<span class="next">%title &rarr;</span>' ); ?>
		</div> <!-- .pagination.post-pagination -->

	<?php
		elseif ( !is_singular() && current_theme_supports( 'loop-pagination' ) ) :

			loop_pagination( array( 'prev_text' => __( '&larr; Previous', hybrid_get_textdomain() ), 'next_text' => __( 'Next &rarr;', hybrid_get_textdomain() ) ) ); 
	?>

	<?php elseif ( !is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '&larr; Previous', hybrid_get_textdomain() ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Next &rarr;', hybrid_get_textdomain() ) . '</span>' ) ) ) : ?>

		<div class="pagination">
			<?php echo $nav; ?>
		</div> <!-- .pagination.loop-pagination -->

	<?php endif; ?>