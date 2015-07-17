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

	<?php get_sidebar( 'primary' );?>

	<?php get_sidebar( 'secondary' );?>

	<?php do_atomic( 'close_content' ); ?>

</div> <!-- .site-content -->

	<?php do_atomic( 'after_content' ); ?>

	<?php do_atomic( 'before_footer' ); ?>

	<footer id="footer" class="site-footer" role="contentinfo">

		<?php do_atomic( 'open_footer' ); ?>

			<div class="footer-content clearfix">
				<?php cakifo_footer_content(); ?>
			</div> <!-- .footer-content -->

			<?php do_atomic( 'footer' ); ?>

			<?php
				/*
				 * A sidebar in the footer? Yep. You can can customize your footer
				 * with three columns of widgets.
				 */
				if ( ! is_404() ) {
					get_sidebar( 'subsidiary' );
				}
			?>

		<?php do_atomic( 'close_footer' ); ?>

	</footer> <!-- .site-footer -->

	<?php do_atomic( 'after_footer' ); ?>

</div> <!-- #wrapper -->

	<?php do_atomic( 'close_body' );?>

	<?php wp_footer(); ?>

</body>
</html>
