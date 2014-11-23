/* global wp, _wpCustomizeSettings */

/**
 * Handles the customizer live preview settings.
 */
( function( $ ) {

	/*
	 * Shows a live preview of changing the site title.
	 */
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {

			$( '.site-title span' ).text( to );

		} );
	} );


	/*
	 * Shows a live preview of changing the site description.
	 */
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {

			$( '.site-description' ).text( to );

		} );
	} );


	/*
	 * Handles the header textcolor.  This code also accounts for the possibility
	 * that the header text color may be set to 'blank', in which case, the title is hidden.
	 * If there's no header image, the description is hidden as well.
	 */
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {

			if ( 'blank' === to ) {

				// Hide the title
				$( '.site-title span' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );

				// Hide the description if the title is hidden and there's no header image
				if ( 'remove-header' === _wpCustomizeSettings.values.header_image ) {
					$( '.site-description' ).css( {
						'clip': 'rect(1px, 1px, 1px, 1px)',
						'position': 'absolute'
					} );
				}

			} else {

				// Show the title and description
				$( '.site-title span, .site-description' ).css( {
					'color': to,
					'clip': 'auto',
					'position': 'relative'
				} );

			}

		} );
	} );
} )( jQuery );