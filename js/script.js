/* global screenReaderText */
/**
 * Theme functions file.
 */

( function( $ ) {

	/*
	 * Add dropdown toggle that display child menu items.
	 *
	 * @author	Twenty Fifteen
	 */
	$( '.site-navigation .menu-item-has-children > a' )
		.after( '<button class="dropdown-toggle" aria-expanded="false">' + screenReaderText.expand + '</button>' );

	$( '.dropdown-toggle' ).on( 'click', function( e ) {
		var $this = $( this );

		$this.toggleClass( 'submenu-open' );
		$this.siblings( '.sub-menu' ).toggleClass( 'submenu-toggled-on' );

		$this.attr( 'aria-expanded', $this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );

		$this.html( $this.html() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand );

		e.preventDefault();
	} );

	/*
	 * Enables menu toggle for small screens.
	 */
	( function() {
		var nav = $( '.main-navigation, .secondary-navigation' ), button, menu;

		if ( ! nav ) {
			return;
		}

		button = nav.find( '.menu-toggle' );

		if ( ! button ) {
			return;
		}

		menu = nav.find( '.menu-list-container' );

		// Hide button if menu is missing or empty.
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click.cakifo', function() {
			$(this).parents(nav).toggleClass( 'toggled-on' );
		} );
	} )();

	/*
	 * Makes "skip to content" link work correctly in IE9, Chrome, and Opera
	 * for better accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	var ua = navigator.userAgent.toLowerCase();

	if ( ( ua.indexOf( 'webkit' ) > -1 || ua.indexOf( 'opera' ) > -1 || ua.indexOf( 'msie' ) > -1 ) &&
		document.getElementById && window.addEventListener ) {

		window.addEventListener( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.nodeName ) ) {
					element.tabIndex = -1;
				}

				element.focus();

				// Repositions the window on jump-to-anchor to account for toolbar height.
				window.scrollBy( 0, -80 );
			}
		}, false );
	}

	/* jshint ignore:start */
	function equal_height_columns() {return;}

	/* A little surprise ;-) */
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

	/* jshint ignore:end */

})(jQuery);
