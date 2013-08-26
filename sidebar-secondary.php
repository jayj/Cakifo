<?php
/**
 * Secondary Sidebar Template
 *
 * Displays widgets for the Secondary dynamic sidebar if any have been added to the sidebar through the
 * widgets screen in the admin by the user.  Otherwise, nothing is displayed.
 *
 * @package    Cakifo
 * @subpackage Template
 */

if ( is_active_sidebar( 'secondary' ) ) : ?>

	<?php do_atomic( 'before_sidebar_secondary' ); // cakifo_before_sidebar_secondary ?>

	<aside id="sidebar-secondary" class="sidebar-secondary widget-area">

		<?php do_atomic( 'open_sidebar_secondary' ); // cakifo_open_sidebar_secondary ?>

		<?php dynamic_sidebar( 'secondary' ); ?>

		<?php do_atomic( 'close_sidebar_secondary' ); // cakifo_close_sidebar_secondary ?>

	</aside> <!-- .sidebar-secondary -->

	<?php do_atomic( 'after_sidebar_secondary' ); // cakifo_after_sidebar_secondary ?>

<?php endif; ?>
