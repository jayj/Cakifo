<?php
/**
 * Handles the Cakifo upgrades
 *
 * The functions will check a server for information about
 * a new version
 *
 * If there's a update, it will show a notice in the dashboard and
 * allow you to upgrade directly from it
 *
 * @credits to the Genesis Framework for some of the code
 */

add_action( 'admin_notices', 'cakifo_update_notice' );
add_filter( 'site_transient_update_themes', 'cakifo_update_push' );
add_filter(' transient_update_themes', 'cakifo_update_push' );
add_action( 'load-update.php', 'cakifo_clear_update_transient' );
add_action( 'load-themes.php', 'cakifo_clear_update_transient' );

/**
 * Pings the server for information about the new version
 *
 * @since 1.3
 * @return array List of information about the new update
 */
function cakifo_update_check() {

	// Send request to see if there's an update available
	$response = wp_remote_get( 'http://wpthemes.jayj.dk/themerss/cakifo.json' );

	if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) )
		return false;

	$cakifo_update = wp_remote_retrieve_body( $response );

	if ( empty( $cakifo_update ) )
		return false;

	// Decode the JSON object
	$update = json_decode( $cakifo_update, true );

	return array(
		'title' => $update['items'][0]['title'],
		'new_version' => $update['items'][0]['version'],
		'url' => $update['items'][0]['link'],
		'message' => $update['items'][0]['message'],
		'childmessage' => $update['items'][0]['childmessage'],
		'requires' => $update['items'][0]['requires'],
		'package' => $update['items'][0]['zip']
	);
}

/**
 * Checks if there's a new version available 
 *
 * @since 1.3
 * @return boolean true if there's an update, false if no
 */
function cakifo_update_available() {

	$theme_data = hybrid_get_theme_data();
	$update = get_transient( 'cakifo-update-check' );
	$update_available = (boolean) get_transient( 'cakifo-update-available' );

	// No transient, get a fresh result from the server
	if ( false === $update ) {
		$update = cakifo_update_check();
		set_transient( 'cakifo-update-check', $update, 60*60*48 ); // 48 hours
	}

	// Is there a new version?
	if ( false === $update_available ) {
		if ( version_compare( $update['new_version'], $theme_data['Version'], '>' ) )
			$update_available = true;
		else
			$update_available = false;

		set_transient( 'cakifo-update-available', $update_available, 60*60*48 ); // 48 hours
	}

	return (boolean) $update_available;
}
	
/**
 * Display the update notice
 * 
 * @since 1.2
 */
function cakifo_update_notice() {

	if ( current_user_can( 'update_themes' ) ) :

		 $update_available = cakifo_update_available();

		// There's an update available
		if ( $update_available ) :
			$update = get_transient( 'cakifo-update-check' );
			$update_url = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=cakifo', 'upgrade-theme_cakifo' );
			$update_onclick = __( 'Upgrading Cakifo will overwrite the current installed version of Cakifo. Are you sure you want to upgrade?. "Cancel" to stop, "OK" to upgrade.', 'cakifo' );

			// The notice
			echo '<div class="update-nag">';
				printf( __( 'Cakifo %s is available. <a href="%s">Check out what\'s new</a> or <a href="%s" onclick="return cakifo_confirm_upgrade(\'%s\');">update now</a>. ', 'cakifo' ), 
					esc_html( $update['new_version'] ),
					esc_url( $update['url'] ),
					$update_url,
					esc_js( $update_onclick ) 
				);
				echo $update['message'];
					if ( is_child_theme() )
					echo '&nbsp;' . $update['childmessage'];
			echo '</div>';

			// Add some inline script for upgrade alert
			echo '<script type="text/javascript">function cakifo_confirm_upgrade( text ) {
					var answer = confirm( text );
					if( answer ) { return true; }
					else { return false; }
				}</script>';
		endif;

	endif; // current_user_can( 'update_themes' )

	return;
}

/**
 * This function clears out the Cakifo update transient data
 * so that the server will do a fresh check, when the user
 * loads certain admin pages.
 *
 * @since 1.3
 */
function cakifo_clear_update_transient() {
	delete_transient( 'cakifo-update-check' );
	delete_transient( 'cakifo-update-available' );
	remove_action( 'admin_notices', 'cakifo_update_notice' );
}

/**
 * This function filters the value that is returned when
 * WordPress tries to pull theme update transient data. It uses
 * cakifo_update_available() to check to see if we need to do an
 * update, and if so, adds the proper array to the $value->response
 * object. WordPress handles the rest.
 *
 * @since 1.3
 */
function cakifo_update_push( $value ) {

	$update_available = cakifo_update_available();
	
	if ( $update_available ) {
		$update = get_transient( 'cakifo-update-check' );
		$value->response['cakifo'] = $update;
	}

	return $value;
}

?>