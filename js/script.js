(function($, window, document) {

	/**
	 * Topbar toggle functionality
	 */
	var topbar = $( '#topbar' ),
		search = topbar.find( '.search' ),
		timeout = false;

	$.fn.smallMenu = function() {
		topbar.find( '.site-navigation' ).removeClass( 'main-navigation' ).addClass( 'main-small-navigation' );
		topbar.find( '.site-navigation h3' ).removeClass( 'assistive-text' ).addClass( 'menu-toggle' );

		$( '.menu-toggle' ).off( 'click' ).click( function() {
			topbar.find( '.menu-list-container' ).stop().slideToggle(400);
			search.toggle();
			$(this).toggleClass( 'toggled-on' );
		} );
	};

	// Check viewport width on first load.
	if ( Modernizr.mq( 'screen and (max-width: 980px)' ) )
		$.fn.smallMenu();

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		if ( false !== timeout )
			clearTimeout( timeout );

		timeout = setTimeout( function() {
			if ( Modernizr.mq( 'screen and (max-width: 980px)' ) ) {
				$.fn.smallMenu();
				search.hide();
			} else {
				topbar.find( '.site-navigation' ).removeClass( 'main-small-navigation' ).addClass( 'main-navigation' );
				topbar.find( '.site-navigation h3' ).removeClass( 'menu-toggle' ).addClass( 'assistive-text' );
				topbar.find( '.menu-list-container' ).removeAttr( 'style' );
				search.show();
			}
		}, 200 );
	} );

	function equal_height_columns() {return;}

	/* A little surprise ;-) */
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

})(jQuery, window, document);
