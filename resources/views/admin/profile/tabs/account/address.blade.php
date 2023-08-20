@inject('cities', 'Delmax\Webapp\Models\City')
@inject('countries', 'Delmax\Webapp\Models\Country')
{!! Former::vertical_open()
                  ->id('form-user-address')
                  ->method('PUT')
                  ->action(route('admin.profile.address.update',[$user->user_id]))
                  ->secure()
!!}

{!! ($address) ? Former::populate($address):'' !!}

    {!! Former::hidden('id') !!}
    {!! Former::hidden('address_type_id') !!}
    {!! Former::hidden('addressable_type') !!}
    {!! Former::hidden('addressable_id') !!}
    {!! Former::hidden('default_address') !!}
    {!! Former::hidden('recipient') !!}
    {!! Former::hidden('latitude')->id('latitude') !!}
    {!! Former::hidden('longitude')->id('longitude') !!}
{{--
    {!! Former::select('country_id')
        ->fromQuery($countries::all(['country_id', 'description']), 'description', 'country_id')
        ->placeholder(_('Select country'))
        ->label(_('Country'))
    !!}
--}}
    {!! Former::select('city_id')
        ->fromQuery($cities::serbianCities(['city_id', 'city_name']), 'city_name', 'city_id')
        ->placeholder(_('Select city'))
        ->label(_('City'))->autofocus()
    !!}

    {!! Former::text('address')->label(_('Street name and house number (floor, gate)')) !!}

    {!! Former::text('address2')->label('') !!}

    <div class="form-group clearfix">
        <div id="map" class="map-canvas" style="height: 375px"></div>
    </div>


<div class="form-actions">
    {!! Button::primary(_('Save'))->submit() !!}
    {!! Button::normal(_('Cancel'))->withAttributes(['class'=>'cancel_button']) !!}
</div>
{!! Former::close() !!}

@push('scripts')

<script id="script-admin-profile-address">
    $(function() {
        var formAddress = $("#form-user-address");
        formAddress.on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            if ($(this).valid()) {
                showLoading();
                $.ajax({
                    url:$form.attr('action'),
                    method:$form.attr('method'),
                    type:'json',
                    data:$form.serialize()
                })
                        .done(function(response){
                            $('.widget-box').trigger('admin.address.updated', response);
                        })
                        .fail(function(jqXHR, textStatus, errorThrown){
                            associateErrors(jqXHR, $form);
                        })
                        .always(function(){hideLoading()});
            }
        });

        formAddress.validate({
            rules: {
                address: {
                    required: true,
                    maxlength: 250
                },

                address2: {
                    maxlength: 250
                },

                city_id: {
                    required: true
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

