(function($, window, document) {

	/**
	 * jQuery plugin: Animate Height/Width to "Auto"
	 * http://darcyclarke.me/development/fix-jquerys-animate-to-allow-auto-values/
	 */
	jQuery.fn.animateAuto = function(prop, speed, callback){
		var elem, height, width;

		return this.each(function(i, el) {
			el = jQuery(el);
			elem = el.clone().css({ 'height':'auto', 'width': 'auto' }).appendTo(el.parent());
			height = elem.css('height');
			width = elem.css('width');
			elem.remove();

			if(prop === 'height')
			el.animate({ 'height': height }, speed, callback);
			else if(prop === 'width')
			el.animate({ 'width': width }, speed, callback);
			else if(prop === 'both')
			el.animate({ 'width': width, 'height': height }, speed, callback);
		});
	};

	/**
	 * Topbar toggle functionality
	 */
	$('#topbar > .toggle-navbar').on( 'click', function(e) {
		var nav = $('#topbar .wrap'),
		height = nav.height();

		if ( height === 0 ) {
			nav.stop().animateAuto( 'height', 700);
		} else {
			nav.stop().animate( { 'height': 0 }, 700);
		}
	});

	function equal_height_columns() {return;}

	/* A little surprise ;-) */
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

})(jQuery, window, document);
