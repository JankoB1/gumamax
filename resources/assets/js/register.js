$("#addVehicle").addVehicle({
    vehicleUrl: urlTo('/vehicle/ajax'),
    loaderElement: ($('#load-screen-modal').length>0)?$('#load-screen-modal'):$('#load-screen'),
    showNameField: false,
    showEngineCode: false,
    showVIN: false,
    sendButton: false,
    formGroupClass : 'form-group',
    year  : {required:''},
    mfa_id: {required:''},
    mod_id: {required:''},
    typ_id: {required:''}
});


$(function(){

	showCompanyFields($('input[name=customer_type_id]:checked').val());

	$('input[name=customer_type_id]').on('click', function(){
		showCompanyFields($(this).val());
	});

	$("#form-register").validate({
		rules : {
			first_name : {
				required : true,
				minlength: 2,
                maxlength : 250
			},
			last_name : {
				required : true,
				minlength : 2,
				maxlength : 250
			},
			phone_number : {
				required : true,
				maxlength : 20,
                phone_regex : true

			},
			email : {
				required : true,
				email: true,
				maxlength: 250
			},
            password : {
                required : true,
                minlength: 6,
                maxlength: 30
            },
            password_confirmation : {
                required : true,
                equalTo: "#password",
                minlength: 6,
                maxlength: 30
            },
            company_name : {
                required : '.company_account:checked'
            },
            tax_identification_number : {
                required : '.company_account:checked',
                minlength: 9
            },
            year_of_production : {
              required : true
            },
            mfa_id: {
                required : function(element) {
                    return $("#year_of_production").val() > 0;
                }
            },
            mod_id: {
                required : function(element) {
                    return $("#mfa_id").val() > 0;
                }
            },
            typ_id: {
                required : function(element) {
                    return $("#mod_id").val() > 0;
                }
            }
		},

        messages : {
            first_name : {
                required : 'Ime je obavezno polje'
            },
            last_name : {
                required : 'Prezime je obavezno polje'
            },
            email : {
                required : 'E-mail je obavezno polje',
            },
            password_confirmation : {
                equalTo : 'Portvrda lozinke mora biti identična prethodno unetoj lozinci'
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