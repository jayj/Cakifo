<?php
/**
 * Error Page (404) Sidebar Template
 *
 * Displays any widgets for the Error Page dynamic sidebar if they are available.
 *
 * @package Cakifo
 * @subpackage Template
 */

if ( is_active_sidebar( 'error-page' ) ) : ?>

	<div class="not-found-widgets clearfix">

		<?php dynamic_sidebar( 'error-page' ); ?>

	</div> <!-- .not-found-widgets -->

<?php endif; ?>