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

	<?php do_atomic( 'before_menu_secondary' ); // cakifo_before_menu_secondary ?>

	<nav class="secondary-navigation site-navigation" role="navigation">

		<?php do_atomic( 'open_menu_secondary' ); // cakifo_open_menu_secondary ?>

			<a class="assistive-text" href="#main" title="<?php esc_attr_e( 'Skip to content', 'cakifo' ); ?>"><?php _e( 'Skip to content', 'cakifo' ); ?></a>

			<?php
				wp_nav_menu(
					array(
						'theme_location' => 'secondary',
						'after'          => '<span class="sep">|</span>',
						'container'      => '',
						'fallback_cb'    => ''
					)
				);
			?>

		<?php do_atomic( 'close_menu_secondary' ); // cakifo_close_menu_secondary ?>

	</nav> <!-- .secondary-navigation -->

	<?php do_atomic( 'after_menu_secondary' ); // cakifo_after_menu_secondary ?>

<?php endif; ?>
