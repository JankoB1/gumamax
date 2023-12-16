$(document).ready(function(){

	var frmRatingProduct = $('#form-rating-product');

    $('[data-toggle="tooltip"]').tooltip();

	$('input[type="range"]').on("change",function(){
		var t = $(this);
		var u = 0,
			i = 0;
		t.parent().find(".pr_ocena").text(t.val());

		$.each($('.ratings'),function(){
			u += parseInt($(this).val());
			i++;
		});

		$('#overall').attr('value',Math.round(u/i));
		$('#overall_h').attr('value',Math.round(u/i));
		$('#rateit-overall').rateit('value',Math.round(u/i));
	});


    frmRatingProduct.validate({
        rules: {
            review_title: {
                required : true,
                rangelength: [2,128]
            },
            review_product: {
                required : true
            },
            nickname: {
                required : true,
                maxlength : 64
            },
            email : {
                required : true,
                email: true,
                maxlength : 250
            },
            site_review: {
                required : true
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            var rating = {
                "user_id": $('user_id').val(),
                "product_id" : $("#product_id").val(),
                "overall_rating" : $('#overall_h').val(),
                "dry_traction" : $('#dry_traction').val(),
                "wet_traction" : $('#wet_traction').val(),
                "steering_feel" : $('#steering_feel').val(),
                "quietness" : $('#quietness').val(),
                "purchase_again" : $('#purchase_again').val(),
                "review_title" : escapeHTML($('#review_title').val()),
                "review_product" : escapeHTML($('#review_product').val()),
                "nickname" : $('#nickname').val(),
                "email" : $('#email').val(),
                "site_rating": $('.btn-group > .active >').val(),
                "site_review": escapeHTML($('#site_review').val())
            };

            $.ajax({
                type: "POST",
                url: urlTo('/review/products/'+rating.product_id),
                data: rating
            }).done(function(response){
                if (!response.error) {
                    swal({
                            title: 'Podaci su uspešno sačuvani.',
                            text: 'Hvala Vam što ste ocenili proizvod.',
                            type: 'success'
                        },
                        function () {
                            window.location.href = urlTo('review/products/'+rating.product_id);
                        });
                } else {
                    sweetAlert('Greška', 'Došlo je do greške! Podaci nisu sačuvani.', 'error');
                }
            }).fail(function(){
                sweetAlert('Greška', 'Došlo je do greške! Podaci nisu sačuvani.', 'error');
            });
        }
    })
});
