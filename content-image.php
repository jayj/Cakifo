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
 * @since Cakifo 1.6.0
 */

do_atomic( 'before_entry' ); // cakifo_before_entry ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // cakifo_open_entry ?>

	<header class="entry-header">
		<?php
			if ( is_singular() )
				echo apply_atomic_shortcode( 'entry_title', '[entry-title permalink=""]' );
			else
				echo apply_atomic_shortcode( 'entry_title', '[entry-title]' );
		?>
	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cakifo' ) ); ?>
		<?php wp_link_pages(); ?>
	</div> <!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			echo apply_atomic_shortcode( 'byline_image', '<p>' . __( '[post-format-link] published on [entry-published] by [entry-author] [entry-comments-link before="| "] [entry-edit-link before="| "]', 'cakifo' ) . '</p>' );

			if ( is_singular() )
				echo apply_atomic_shortcode( 'entry_meta_image', '<p>' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="Tagged: "]', 'cakifo' ) . '</p>' );
		?>
	</footer> <!-- .entry-meta -->

	<?php
		if ( is_singular() )
			do_atomic( 'in_singular' ); // cakifo_in_singular (+ cakifo_after_singular)
	?>

	<?php do_atomic( 'close_entry' ); // cakifo_close_entry ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_entry' ); // cakifo_after_entry ?>
