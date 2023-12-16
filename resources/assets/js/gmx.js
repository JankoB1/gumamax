$(document).ready(function() {
	"use strict";

	//steps 123
	// $(".steps li a").on("click", function(e){
	// 	var $t = $(this);
	// 	console.log($t.parents('li'));
	// });

	// Support for AJAX loaded modal window.
	// Focuses on first input textbox after it loads the window.
    // $('[data-toggle="modal"]').click(function(e) {
    //     e.preventDefault();
    //     var $t = $(this);
    //     var url = $t.attr('href') || $t.data('target') || '';
    //     var modal_id = $t.data('id');
    //     if (url.indexOf('#') === 0) {
    //         $(url).modal('open');
    //     } else {
    //         $.get(url, function(data) {
    //         	$('<div />', { "class":"modal hide fade", "id":modal_id, "html":data}).modal();
    //         }).success(function() {
    //         	$('input:text:visible:first').focus();
    //         });
    //     }
    // });


    $('a.popup-modal').on("click", function(e){
    	var $t = $(this);
    	e.preventDefault();
    	$.ajax({
    		url: $t.attr('href'),
    		dataType: 'html',
    		type: 'get'
    	}).done(function(resp){
    		$($t.data('target') ).find('.modal-content').html(resp);
    		$($t.data('target') ).modal();
    	});
    });


    $('.required').prepend(' <span class="delmax-red-text"> * </span>');

    $('.carousel').carousel();
});
