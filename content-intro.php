<?php
/**
 * The template for displaying page content in the template-front-page.php page template
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.6.0
 */

do_atomic( 'before_intro' ); // cakifo_before_intro ?>

<div id="post-<?php the_ID(); ?>" class="hentry intro-post">

	<?php do_atomic( 'open_intro' ); // cakifo_open_intro ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
		<?php echo hybrid_entry_edit_link_shortcode( array() ); ?>
	</div> <!-- .entry-content -->

	<?php do_atomic( 'close_intro' ); // cakifo_close_intro ?>

</div> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_intro' ); // cakifo_after_intro ?>
