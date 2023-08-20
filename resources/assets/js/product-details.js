$(document).ready(function(){

	$('#qty,.addToCartBtn').attr('disabled', $('.stock_status span').text()=='Nema');

	$('.product_thumb').eq(0).addClass('active');
	$('.product_thumb').on('click',function(e){
		var clickedThumb = $(this),
			oldImgSrc = $('img.zoomable').parent('a').attr('href'),
			newImgHref = clickedThumb.data('imgzoom'),
			newImgSrc = clickedThumb.attr("href");

		e.preventDefault();

		$('.product_thumb').removeClass('active');
		clickedThumb.addClass('active');

		$('.product_image').find('a[href="'+newImgHref+'"]').attr('href',oldImgSrc);
		$('img.zoomable').attr('src',newImgSrc);
		$('img.zoomable').parent('a').attr('href',newImgHref);
	});
});