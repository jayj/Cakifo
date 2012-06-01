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

if ( is_active_sidebar( 'subsidiary' ) ) : ?>

	<div class="clear"></div>

	<?php do_atomic( 'before_sidebar_subsidiary' ); // cakifo_before_sidebar_subsidiary ?>

	<div id="sidebar-subsidiary">

		<?php do_atomic( 'open_sidebar_subsidiary' ); // cakifo_open_sidebar_subsidiary ?>

		<?php dynamic_sidebar( 'subsidiary' ); ?>

		<?php do_atomic( 'close_sidebar_subsidiary' ); // cakifo_close_sidebar_subsidiary ?>

	</div> <!-- #sidebar-subsidiary -->

	<?php do_atomic( 'after_sidebar_subsidiary' ); // cakifo_after_sidebar_subsidiary ?>

<?php endif; ?>
