(function($, window, document) {

	/**
	 * Topbar navigation toggle functionality
	 */
	var topbar = $( document.getElementById( 'topbar' ) ),
		button = $( document.getElementById( 'menu-toggle' ) ),
		search = topbar.find( '.search' );

	if ( ! topbar.find( '.menu' ).children().length ) {
		button.hide();
	}

	button.off( 'click' ).click( function() {
		topbar.find( '.menu-list-container' ).stop().slideToggle(400);
		search.toggle();
		$( this ).toggleClass( 'toggled-on' );
	} );

	function equal_height_columns() {return;}

	/* A little surprise ;-) */
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

})(jQuery, window, document);
