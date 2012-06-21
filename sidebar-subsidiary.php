<?php
/**
 * Subsidiary Sidebar Template
 *
 * Displays widgets for the Subsidiary (footer) dynamic sidebar if any have been added to the sidebar through the
 * widgets screen in the admin by the user.  Otherwise, nothing is displayed.
 *
 * @package Cakifo
 * @subpackage Template
 */

/* The footer widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
$subsidiary_active       = is_active_sidebar( 'subsidiary' );
$subsidiary_two_active   = is_active_sidebar( 'subsidiary-two' );
$subsidiary_three_active = is_active_sidebar( 'subsidiary-three' );

if ( ! $subsidiary_active && ! $subsidiary_two_active && ! $subsidiary_three_active )
	return;

	// If we get this far, we have widgets. Let's count the number of footer sidebars to enable dynamic classes for the footer
	$count = 0;

	if ( $subsidiary_active )
		$count++;

	if ( $subsidiary_two_active )
		$count++;

	if ( $subsidiary_three_active )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one-col';
			break;
		case '2':
			$class = 'two-col';
			break;
		case '3':
			$class = 'three-col';
			break;
	}

?>

<?php do_atomic( 'before_sidebar_subsidiary' ); // cakifo_before_sidebar_subsidiary ?>

	<div id="sidebar-subsidiary" class="<?php echo esc_attr( $class ); ?> clearfix">

		<?php do_atomic( 'open_sidebar_subsidiary' ); // cakifo_open_sidebar_subsidiary ?>

		<?php if ( $subsidiary_active ) : ?>
			<div id="first-footer-col" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'subsidiary' ); ?>
			</div><!-- #first-footer-col.widget-area -->
		<?php endif; ?>

		<?php if ( $subsidiary_two_active ) : ?>
			<div id="second-footer-col" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'subsidiary-two' ); ?>
			</div><!-- #second-footer-col.widget-area -->
		<?php endif; ?>

		<?php if ( $subsidiary_three_active ) : ?>
			<div id="third-footer-col" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'subsidiary-three' ); ?>
			</div><!-- #third-footer-col.widget-area -->
		<?php endif; ?>

		<?php do_atomic( 'close_sidebar_subsidiary' ); // cakifo_close_sidebar_subsidiary ?>

	</div> <!-- #sidebar-subsidiary -->

<?php do_atomic( 'after_sidebar_subsidiary' ); // cakifo_after_sidebar_subsidiary ?>
