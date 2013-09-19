(function($) {

	/**
	 * Enables menu toggle for small screens.
	 */
	( function() {
		var nav = $( '.main-navigation, .secondary-navigation' ), button, menu;

		if ( ! nav ) {
			return;
		}

		button = nav.find( '.menu-toggle' );
		menu   = nav.find( '.menu-list-container' );

		if ( ! button ) {
			return;
		}

		// Hide button if menu is missing or empty.
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click', function() {
			$(this).parent().toggleClass( 'toggled-on' );
		} );
	} )();

	function equal_height_columns() {return;}

	/* A little surprise ;-) */
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

})(jQuery);
