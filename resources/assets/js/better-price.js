$(function() {
    var frmBetterPrice = $('#frmBetterPrice');

    $('#betterPriceForm').on('show.bs.modal', function (event) {
        var sender = $(event.relatedTarget);
        var product_id = sender.data('product_id');

        $('#betterPriceForm :input').val('');

        $('#product_id').val(product_id);
    });

    frmBetterPrice.validate({
        rules: {
            customer_name: {
                required: true,
                rangelength: [2,250]
            },
            customer_email: {
                required: true,
                email: true,
                maxlength: 250
            },
            shop_name: {
                required: true,
                rangelength: [2,250]
            },
            shop_phone_number: {
                required: true,
                maxlength: 250,
                phoneNumber: true
            },
            price: {
                required: true,
                number: true
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            var formData = $(form).serialize();

            $.ajax({
                type: "POST",
                url: urlTo('api/products/betterprice'),
                data: formData
            }).done(function (response) {
                if (!response.error) {
                    swal({
                            title: 'Primili smo Vaše obaveštenje.',
                            text: 'Odgovorićemo Vam u roku od 24 časa.',
                            type: 'success'
                        },
                        function () {
                            $('#betterPriceForm').modal('hide');
                        });
                } else {
                    sweetAlert('Greška', 'Došlo je do greške! Podaci nisu sačuvani.', 'error');
                }
            }).fail(function () {
                sweetAlert('Greška', 'Došlo je do greške! Podaci nisu sačuvani.', 'error');
            });
        }
    });
});

