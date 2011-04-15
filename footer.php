<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

	<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template ?>

	<?php get_sidebar( 'secondary' ); // Loads the sidebar-secondary.php template ?>

	<?php do_atomic( 'close_content' ); // cakifo_close_content ?>

</div> <!-- #content -->

	<?php do_atomic( 'after_main' ); // cakifoafter_main ?>

	<?php get_template_part( 'menu', 'subsidiary' ); // Loads the menu-subsidiary.php template ?>

	<?php do_atomic( 'before_footer' ); // cakifo_before_footer ?>

	<div id="footer">

		<?php do_atomic( 'open_footer' ); // cakifo_open_footer ?>

		<div class="wrap">

			<?php echo apply_atomic_shortcode( 'footer_content', hybrid_get_setting( 'footer_insert' ) ); ?>

			<?php do_atomic( 'footer' ); // cakifo_footer ?>

			<?php get_sidebar( 'subsidiary' ); // Loads the sidebar-subsidiary.php template ?>

		</div> <!-- .wrap -->

		<?php do_atomic( 'close_footer' ); // cakifo_close_footer ?>

	</div> <!-- #footer -->

	<?php do_atomic( 'after_footer' ); // cakifo_after_footer ?>

</div> <!-- #wrapper -->

	<?php do_atomic( 'close_body' ); // cakifo_close_body ?>

	<?php wp_footer(); // wp_footer ?>

</body>
</html>