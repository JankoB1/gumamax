{!! Former::vertical_open()
    ->id('form-user-account')
    ->method('PUT')
    ->action(url(route('admin.profile.user.update',[$user->user_id])))
    ->secure()
!!}
    {!! Former::populate($user) !!}
    {!! Former::text('first_name')->label(_('First name'))->autofocus() !!}
    {!! Former::text('last_name')->label(_('Last name')) !!}


    {!! Former::text('phone_number')->label(_('Phone number')) !!}

    {!! Former::text('email')->label('E-mail')->disabled() !!}

<div class="form-actions">
    {!! Button::primary(_('Save'))->submit() !!}
    {!! Button::normal(_('Cancel'))->withAttributes(['class'=>'cancel_button']) !!}
</div>

{!! Former::close() !!}
@push('scripts')
<script id="script-admin-profile-general">
    $(function(){
        var formUser = $("#form-user-account");
        formUser.on('submit', function(e){
            e.preventDefault();
            var $form = $(this);
            if ($(this).valid()){
                showLoading();
                $.ajax({
                    url:$form.attr('action'),
                    method:$form.attr('method'),
                    type:'json',
                    data:$form.serialize()
                })
                        .done(function(response){
                            $('.widget-box').trigger('admin.user.updated', response);
                        })
                        .fail(function(jqXHR, textStatus, errorThrown){
                            associateErrors(jqXHR, $form);
                        })
                        .always(function(){hideLoading()});
            }
        });

        formUser.validate({
            rules : {
                first_name : {
                    required : true,
                },
                last_name : {
                    required : true,
                    maxlength : 64
                },
                phone_number : {
                    required : true,
                    maxlength : 20
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
</script>
@endpush