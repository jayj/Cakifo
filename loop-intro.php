<?php
/**
 * The template for displaying page content in the template-front-page.php page template
 *
 * @package Cakifo
 * @subpackage Template
 * @since Cakifo 1.5.0
 */

do_atomic( 'before_intro' ); // cakifo_before_intro ?>

<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class( 'intro-post' ); ?>">

	<?php do_atomic( 'open_intro' ); // cakifo_open_intro ?>

	<header class="entry-header">
		<h2 class="entry-title"><?php the_title(); ?></h2>
	</header> <!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
		<?php echo do_shortcode( '[entry-edit-link]' ); ?>
	</div> <!-- .entry-content -->

	<?php do_atomic( 'close_intro' ); // cakifo_close_intro ?>

</article> <!-- #post-<?php the_ID(); ?> -->

<?php do_atomic( 'after_intro' ); // cakifo_after_intro ?>
