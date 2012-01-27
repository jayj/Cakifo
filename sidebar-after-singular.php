<?php
/**
 * After Singular Sidebar Template
 *
 * Displays any widgets for the After Singular dynamic sidebar if they are available.
 *
 * @package		Cakifo
 * @subpackage	Template
 */

if ( is_active_sidebar( 'after-singular' ) ) : ?>

	<?php do_atomic( 'before_sidebar_singular' ); // cakifo_before_sidebar_singular ?>

	<aside id="sidebar-after-singular" class="sidebar">

		<?php dynamic_sidebar( 'after-singular' ); ?>

	</aside> <!-- #sidebar-after-singular -->

    <?php do_atomic( 'after_sidebar_singular' ); // cakifo_after_sidebar_singular ?>

<?php endif; ?>