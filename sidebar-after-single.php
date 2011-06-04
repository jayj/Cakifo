<?php
/**
 * After Single Sidebar Template
 *
 * Displays any widgets for the After Single dynamic sidebar if they are available.
 *
 * @package Cakifo
 * @subpackage Template
 */

if ( is_active_sidebar( 'after-single' ) ) : ?>

	<?php do_atomic( 'before_sidebar_single' ); // cakifo_before_sidebar_single ?>

	<aside id="sidebar-after-single" class="sidebar">

		<?php dynamic_sidebar( 'after-single' ); ?>

	</aside> <!-- #sidebar-after-single -->

    <?php do_atomic( 'after_sidebar_single' ); // cakifo_after_sidebar_single ?>

<?php endif; ?>