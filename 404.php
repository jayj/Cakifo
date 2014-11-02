<?php
/**
 * 404 template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will
 * display a generic message.
 *
 * @package Cakifo
 * @subpackage Template
 */

/*
 * Include the header template part file
 *
 * Child Themes can replace this template part file globally, via `header.php`,
 * or in a specific context only, via `header-404.php`
 */
get_header( '404' ); ?>

	<?php do_atomic( 'before_main' ); ?>

	<main id="main" class="site-main" role="main">

		<?php do_atomic( 'open_main' ); ?>

		<article id="post-0" class="<?php hybrid_entry_class(); ?>">

			<header class="entry-header">
				<h1 class="error-404-title entry-title"><?php _e( "Whoah! 404 error! We can't find the page!", 'cakifo' ); ?></h1>
			</header> <!-- .entry-header -->

			<div class="entry-content">
				<p>
					<?php printf( __( "You tried going to %s but the page no longer exists. All is not lost!  Perhaps searching, or one of the links below, can help.", 'cakifo' ), '<code>' . esc_html( $_SERVER['REQUEST_URI'] ) . '</code>' ); ?>
				</p>

				<?php get_search_form(); ?>

				<?php do_atomic( '404_content' ); ?>
			</div> <!-- .entry-content -->

		</article> <!-- .hentry -->

		<?php
			/*
			 * Include the Error Page widgets "sidebar" template part file
			 *
			 * Child Themes can replace this template part file globally,
			 * via `sidebar.php`, or in the Error 404 Page context only, via
			 * `sidebar-404.php`
			 */
			get_sidebar( '404' );
		?>

		<?php do_atomic( 'close_main' ); ?>

	</main> <!-- .site-main -->

	<?php do_atomic( 'after_main' ); ?>

<?php
	/*
	 * Include the footer template part file
	 *
	 * Child Themes can replace this template part file globally, via `footer.php`,
	 * or in a specific context only, via `footer-404.php`
	 */
	get_footer( '404' );
?>
