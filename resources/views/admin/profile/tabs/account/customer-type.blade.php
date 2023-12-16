{!! Former::vertical_open()
->id('form-customer-type')
->method('PUT')
->action(route('admin.profile.customer.update', [$user->user_id]))
->secure()
!!}
{!! Former::populate($customer) !!}
{!! Former::hidden('id') !!}
{!! Former::hidden('user_id') !!}
{!! Former::radios(_('You are:'))->radios([
        'Fizičko lice' => ['name' => 'customer_type_id', 'value' => '1', 'class'=>'personal_account'],
        'Pravno lice'  => ['name' => 'customer_type_id', 'value' => '2', 'class'=>'company_account']
    ])->check($customer->customer_type_id) !!}

<div class="company_fields hidden">
    {!! Former::text('company_name')->name('company_name')->label(_('Company name'))->autofocus() !!}
    {!! Former::text('tax_identification_number')->name('tax_identification_number')->label(_('Tax identification number')) !!}
</div>

<div class="form-actions">
    {!! Button::primary(_('Save'))->submit() !!}
    {!! Button::normal(_('Cancel'))->withAttributes(['class'=>'cancel_button']) !!}
</div>
{!! Former::close() !!}
@push('scripts')
<script>

    $(function() {
       var customerForm = $("#form-customer-type");
        showCompanyFields($('input[name="customer_type_id"]:checked').val());

        $('input[name="customer_type_id"]').on('click change', function () {
            showCompanyFields($(this).val());
        });

        customerForm.on('submit', function(e){
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
                            $('.widget-box').trigger('admin.customer.updated', response);
                        })
                        .fail(function(jqXHR, textStatus, errorThrown){
                            associateErrors(jqXHR, $form);
                        })
                        .always(function(){hideLoading()});
            }
        });

        customerForm.validate({
            rules: {
                company_name: {
                    required: '.company_account:checked'
                },

                tax_identification_number: {
                    required: '.company_account:checked'
                }
            },

            messages: {
                company_name: {
                    required: 'Naziv preduzeća je obavezno polje'
                },

                tax_identification_number: {
                    required: 'PIB je obavezno polje'
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
            }
        });
    });
</script>
@endpush