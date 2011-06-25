/**
 * The development version of the compressed script.js
 * Use this file for development purposes by adding this to your 'wp-config.php' file:
 * define( 'SCRIPT_DEBUG', true );
 */
jQuery(document).ready(function($) {

	/**
	 * Equal Heights In Rows
	 * http://css-tricks.com/equal-height-blocks-in-rows/
	 */
	var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = [],
		$el,
		topPosition = 0,
		currentDiv = 0;

	$('.page-template-template-front-page .headline-list, #sidebar-subsidiary .widget, .not-found-widgets .widget').each(function() {

		$el = $(this);
		topPosition = $el.position().top;

		if (currentRowStart != topPosition) {
			// we just came to a new row.  Set all the heights on the completed row
			for (currentDiv = 0; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}

			// set the variables for the new row
			rowDivs.length = 0; // empty the array
			currentRowStart = topPosition;
			currentTallest = $el.height();
			rowDivs.push($el);
		} else {
			// another div on the current row.  Add it to the list and check if it's taller
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		}

		// do the last row
		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	});
	
	/* A little surprise ;-) */ 	
	var kkeys=[],kkkeys="38,38,40,40,37,39,37,39,66,65";
	$(document).keydown(function(e){kkeys.push(e.keyCode);if( kkeys.toString().indexOf(kkkeys)>= 0){$(document).unbind('keydown',arguments.callee);$('body').addClass('shake-it-baby');}});

});