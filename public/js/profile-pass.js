var $form = $('#form-password-change');

function changePassword(){
    var form = $form;
    form.find('.msg').html('').removeClass("alert").removeClass("alert-danger");
    $.ajax({
        type: "PUT",
        url: form.attr('action'),
        data: form.serialize()
    }).done(function(response){
        swal({
            title: 'Info',
            text: 'Uspešno ste promenili lozinku!.',
            type: 'success'
        },
        function () {
            window.location=urlTo('/');
        });
        
    }).fail(function(jqXHR, textStatus, errorThrown){
        var e = JSON.parse(jqXHR.responseText),
            errorStr = '';

        $.each(e.errors, function(key, value) {
            errorStr += value;
        });
        sweetAlert("Greška", errorStr, "error");
    });
}

$form.validate({
    rules : {
        password_old : {
            required : true,
            minlength: 6,
            maxlength: 30
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
    },

    messages : {
        password_old : {
            required : 'Stara lozinka je obavezno polje',
            minlength : jQuery.validator.format('Stara lozinka mora imati najmanje {0} karaktera')
        },

        password : {
            required : 'Lozinka je obavezno polje',
            minlength : jQuery.validator.format('Lozinka mora imati najmanje {0} karaktera')
        },

        password_confirmation : {
            required : 'Portvrda lozinke je obavezno polje',
            equalTo : 'Portvrda lozinke mora biti identična prethodno unetoj lozinci'
        },

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

        changePassword();

    }
});