var GUMAMAX = {};

$(document).ready(function() {
	"use strict";

	//init tooltip
	$( 'a.tooltipLink' ).tooltip();

	$('#myCarousel').carousel({
		interval: false
	});

	$('[id^=carousel-selector-]').on("click", function(){
		var id_selector = $(this).attr("id");
		var id = parseInt(id_selector.substr(id_selector.length -1));

		$('#myCarousel').carousel(id);
	});

	//vehicles menu
	$( '.vehicles a' ).on("click", function(e) {
		e.preventDefault();
		$( '.vehicles a' ).removeClass( 'active' );
		$( this ).addClass( 'active' );
		$( '#vehicleSbD, #vehicleSbV' ).val( $( this ).attr( 'data-rel' ) );
	});

	//Cart page, shipping, check on click on whole container
	$( '.radio-container' ).on("click", function() {
        $( this ).find( 'input' ).prop( 'checked', true );
        $( '.radio-container' ).removeClass( 'checked' );
        $( this ).addClass( 'checked' );
    });

	//Datepicker
    $( '.datepicker' ).datepicker({
    	language: 'sr-latin'
    });

    $( '.input-group-addon' ).on("click", function() {
    	$( this ).prevAll( 'input' ).datepicker( 'show' );
    });

    //Youtube video in modal popup stop on close
    $( 'body' ).on( 'hidden.bs.modal', '#videoUputstvo', function () {
    	$( '#videoUputstvo iframe' )[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
	});

});
