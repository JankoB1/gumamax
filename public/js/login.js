$(function() {

	$("#form-login").validate({
		rules : {			
			email : {
				required : true,
				email: true,
				maxlength: 250
			},

            password : {
                required : true               
            },            
		},

        messages : {
            email : {
                required : 'E-mail je obavezno polje',
            },
            password : {
                required : 'Lozinka je obavezno polje'
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