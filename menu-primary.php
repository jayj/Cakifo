<?php
/**
 * Primary Menu Template
 *
 * Displays the Topbar and Primary Menu if it has active menu items.
 *
 * @package		Cakifo
 * @subpackage	Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

	<?php do_atomic( 'before_menu_primary' ); // cakifo_before_menu_primary ?>

	<div id="topbar">

		<div class="wrap">

			<?php do_atomic( 'open_menu_primary' ); // cakifo_open_menu_primary ?>

			<nav class="menu">
				<h3 class="assistive-text"><?php _e( 'Main menu', 'cakifo' ); ?></h3>
				<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => '',
						'menu_class'     => '',
						'after'          => '<span class="sep">|</span>',
						'fallback_cb'    => ''
					) );
				?>
			</nav> <!-- .menu -->

			<?php do_atomic( 'close_menu_primary' ); // cakifo_close_menu_primary ?>

		</div> <!-- .wrap -->

	</div> <!-- #topbar -->

	<?php do_atomic( 'after_menu_primary' ); // cakifo_after_menu_primary ?>

<?php endif; ?>