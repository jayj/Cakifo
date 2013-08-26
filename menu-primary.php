<?php
/**
 * Primary Menu Template
 *
 * Displays the Topbar and Primary Menu if it has active menu items.
 *
 * @package    Cakifo
 * @subpackage Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

	<?php do_atomic( 'before_menu_primary' ); // cakifo_before_menu_primary ?>

	<div id="topbar" class="topbar">

		<nav class="main-navigation site-navigation" role="navigation">

			<?php do_atomic( 'open_menu_primary' ); // cakifo_open_menu_primary ?>

				<h3 class="menu-toggle" title="<?php esc_attr_e( 'Toggle menu', 'cakifo' ); ?>">
					<?php _e( 'Menu', 'cakifo' ); ?>
				</h3>

				<a class="assistive-text" href="#main"><?php _e( 'Skip to content', 'cakifo' ); ?></a>

				<?php
					wp_nav_menu( array(
						'theme_location'  => 'primary',
						'container_class' => 'menu-list-container',
						'after'           => '<span class="sep">|</span>',
						'fallback_cb'     => ''
					) );
				?>

			<?php do_atomic( 'close_menu_primary' ); // cakifo_close_menu_primary ?>

		</nav> <!-- .main-navigation -->

	</div> <!-- .topbar -->

	<?php do_atomic( 'after_menu_primary' ); // cakifo_after_menu_primary ?>

<?php endif; ?>
