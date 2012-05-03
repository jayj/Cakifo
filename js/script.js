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

	$('.toogle-navbar').on( 'click', function(e){
		var nav = $('#topbar .wrap'),
			height = nav.height();

		if ( height === 0 ) {
			nav.stop().animateAuto( 'height', 700);
		} else {
			nav.stop().animate( { 'height': 0 }, 700);
		}
	});

	/**
	 * Equal Heights In Rows
	 * http://css-tricks.com/equal-height-blocks-in-rows/
	 */
	function equal_height_columns(selector) {
		var currentTallest = 0,
			currentRowStart = 0,
			rowDivs = [],
			$el,
			topPosition = 0,
			currentDiv = 0;

		selector.each(function() {
				$el = $(this);
				topPosition = $el.position().top;

			if (currentRowStart !== topPosition) {
				// We just came to a new row.  Set all the heights on the completed row
				for (currentDiv = 0; currentDiv < rowDivs.length ; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}

				// Set the variables for the new row
				rowDivs.length = 0; // empty the array
				currentRowStart = topPosition;
				currentTallest = $el.height();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
			}

			// Do the last row
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
		});
	}

	/* Call the equal_height_columns() function when window has been loaded to make sure the images are loaded */
	$(window).load(function() {
		equal_height_columns( $('.page-template-template-front-page .headline-list, #sidebar-subsidiary .widget, .not-found-widgets .widget') );
	});

	/* Call the equal_height_columns() function when window is being resized */
	$(window).resize(function() {
 		equal_height_columns( $('.page-template-template-front-page .headline-list, #sidebar-subsidiary .widget, .not-found-widgets .widget') );
	});

	/* A little surprise ;-) */ 	
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

})(jQuery, window, document);