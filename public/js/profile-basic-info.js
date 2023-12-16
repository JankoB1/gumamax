$(function(){

    showCompanyFields($('input[name=customer_type_id]:checked').val());

    $('input[name=customer_type_id]').on('click', function(){
        showCompanyFields($(this).val());
    });

    $("#form_basic_info").validate({
        rules : {
            first_name : {
                required : true,
                minlength: 2
            },
            last_name : {
                required : true,
                minlength : 2,
                maxlength : 64
            },
            phone_number : {
                required : true,
                minlength : 1,
                maxlength : 20
            },
            email : {
                required : true,
                email: true,
                minlength: 1,
                maxlength: 250
            },

            company_name : {
                required : '.company_account:checked'
            },

            tax_identification_number : {
                required : '.company_account:checked'
            }
        },

        messages : {
            first_name : {
                required : 'Ime je obavezno polje'
            },
            last_name : {
                required : 'Prezime je obavezno polje'
            },
            phone_number : {
                required : 'Broj telefona je obavezno polje'
            },
            email : {
                required : 'E-mail je obavezno polje',
            },


            company_name : {
                required : 'Naziv preduzeća je obavezno polje'
            },

            tax_identification_number : {
                required : 'PIB je obavezno polje'
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
        }
    });
});