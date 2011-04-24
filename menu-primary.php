<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package Cakifo
 * @subpackage Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

	<?php do_atomic( 'before_menu_primary' ); // cakifo_before_menu_primary ?>

    <div id="topbar">

        <div class="wrap">

			<?php do_atomic( 'open_menu_primary' ); // cakifo_open_menu_primary ?>

            <?php
            	wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => 'nav',
					'container_class' => 'menu',
					'menu_class' => '',
					'after' => '<span class="sep">|</span>',
					'fallback_cb' => ''
				 ) );
			?>

            <?php do_atomic( 'close_menu_primary' ); // cakifo_close_menu_primary ?>

        </div> <!-- .wrap -->

    </div> <!-- #topbar -->

    <?php do_atomic( 'after_menu_primary' ); // cakifo_after_menu_primary ?>

<?php endif; ?>