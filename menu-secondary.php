<?php
/**
 * Secondary Menu Template
 *
 * Displays Secondary Menua above the content section if
 * it has active menu items.
 *
 * @package Cakifo
 * @subpackage Template
 */

if ( has_nav_menu( 'secondary' ) ) : ?>

	<?php do_atomic( 'before_menu_secondary' ); ?>

	<nav class="secondary-navigation site-navigation" role="navigation">

		<?php do_atomic( 'open_menu_secondary' ); ?>

			<h3 class="menu-toggle" title="<?php esc_attr_e( 'Toggle secondary menu', 'cakifo' ); ?>">
				<?php _e( 'Secondary menu', 'cakifo' ); ?>
			</h3>
				<?php
					/* Translators: %s is the nav menu name. */
					printf( _x( '%s Menu', 'nav menu title', 'cakifo' ), cakifo_get_menu_name( 'secondary' ) );
				?>

			<?php
				wp_nav_menu( array(
					'theme_location' => 'secondary',
					'container_class' => 'menu-list-container',
					'after'          => '<span class="sep">|</span>',
					'fallback_cb'    => ''
				) );
			?>

		<?php do_atomic( 'close_menu_secondary' ); ?>

	</nav> <!-- .secondary-navigation -->

	<?php do_atomic( 'after_menu_secondary' ); ?>

<?php endif; ?>
