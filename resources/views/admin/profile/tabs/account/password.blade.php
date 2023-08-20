
    {!! Former::vertical_open()
        ->id('form-password-change')
        ->method('PUT')
        ->action(route('api.profile.change_password'))
        ->secure()
     !!}
    {!! Former::password('password_old')->label(_('Old password'))->autofocus() !!}
    {!! Former::password('password')->label(_('New password')) !!}
    {!! Former::password('password_confirmation')->label(_('Confirm new password')) !!}
    <div class="form-actions">
        {!! Button::primary(_('Change password'))->submit() !!}
        {!! Button::normal(_('Cancel'))->withAttributes(['class'=>'cancel_button']) !!}

    </div>

    {!! Former::close() !!}

    <div class="reset-pass-link">
        <a href="{{ url('request-reset') }}" title="{!! _('Forget password?') !!}" style="padding-top:15px">{!! _('Forget password?') !!}</a>
    </div>
@push('scripts')
    <script>
        $(function() {
            var formPassword = $("#form-password-change");
            formPassword.on('submit', function (e) {
                e.preventDefault();
                var $form = $(this);
                if ($(this).valid()) {
                    showLoading();
                    $.ajax({
                        url: $form.attr('action'),
                        method: $form.attr('method'),
                        type: 'json',
                        data: $form.serialize()
                    })
                            .done(function (response) {
                                $('.widget-box').trigger('admin.password.updated', response);
                            })
                            .fail(function (jqXHR, textStatus, errorThrown) {
                                associateErrors(jqXHR, $form);
                            })
                            .always(function () {
                                hideLoading()
                            });
                }
            });

            formPassword.validate({
                rules: {
                    password_old: {
                        required: true,
                        minlength: 6,
                        maxlength: 30
                    },

                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 30
                    },

                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                        minlength: 6,
                        maxlength: 30
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
                submitHandler: function (form) {

                    changePassword();

                }
            });
        });
    </script>
@endpush