@inject('cities', 'Delmax\Webapp\Models\City')
{!!Former::vertical_open()
->id('form-partner-basic')
->method($formMethod)
->action($formUrl)
->secure()!!}
<fieldset>
    {!!Former::populate($partner) !!}

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="row">
                <div class="col-xs-4">
                    {!! Former::text('erp_company_id')->label('ErpCompanyId') !!}
                </div>
                <div class="col-xs-4">
                    {!! Former::text('erp_partner_id')->label('ErpPartnerId') !!}
                </div>
                <div class="col-xs-4">
                    {!! Former::text('tax_identification_number')->label('PIB') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    {!! Former::text('description')->label('Naziv') !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    {!! Former::text('description2')->label('Odeljenje') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    {!! Former::select('city.city_id')->name('city_id')
                    ->fromQuery($cities::serbianCities(['city_id', 'city_name']), 'city_name', 'city_id')
                    ->placeholder('Odaberi mesto')
                    ->label('Mesto')
                    !!}
                </div>
                <div class="col-xs-12 col-sm-6">
                    {!! Former::text('address')->label('Adresa') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    {!! Former::text('phone')->label('Telefon') !!}
                </div>
                <div class="col-xs-6 col-sm-6">
                    {!! Former::text('fax') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    {!! Former::email('email') !!}
                </div>
                <div class="col-xs-12 col-sm-6">
                    {!! Former::text('web_address')->label('Sajt') !!}
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group clearfix">
                <div id="map" class="map-canvas" style="height: 375px"></div>
            </div>
            <div class="row">

                <div class="col-xs-6 col-sm-6">
                    {!! Former::hidden('latitude')->id('latitude')->label('Geo.širina') !!}
                </div>
                <div class="col-xs-6 col-sm-6">
                    {!! Former::hidden('longitude')->id('longitude')->label('Geo.dužina') !!}
                </div>
            </div>
        </div>
    </div>
</fieldset>
@if(!$modal)
    {!! Former::submit("Sačuvaj") !!}

@endif

{!!Former::close()!!}

@if(!$modal)    @section('page-plugin-js') @parent @endif
<script src="{{asset("js/maps.js")}}"></script>
@if(!$modal) @endsection @endif

@if(!$modal)
@section('js')
@parent
@endif
<script id="crm-form-partner-basic-map">
    initialZoom = 12;
    initialLocation = {
        lat: parseFloat("{!! number_format($partner->latitude,12,'.','') !!}"),
        lng: parseFloat("{!! number_format($partner->longitude,12,'.','') !!}")
    };

    function mapModalInit() {

        if (typeof google === 'object' && typeof google.maps === 'object') {
            mapInitializeWithGeocoding();
        } else {
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBYE-QLKrq0kE3ieeZd2QIRkvija1LNS3Y&callback=mapInitializeWithGeocoding";
            document.body.appendChild(script);
        }
    }
    mapModalInit();

</script>

<script id="validate-form-partner-basic">
    $(function(){
        $('#form-partner-basic').validate({
            rules : {
                erp_company_id : {
                    required : true,
                    minlength: 2,
                    maxlength : 20
                },

                erp_partner_id : {
                    required : true,
                    maxlength : 20
                },

                description : {
                    required : true,
                    minlength: 2,
                    maxlength : 32
                },

                description2 : {
                    maxlength : 32
                },

                tax_identification_number:{
                    required: true
                },

                city_id : {
                    required : true
                },

                address : {
                    required : true,
                    minlength : 2,
                    maxlength : 48
                },
                phone : {
                    required : true,
                    minlength : 1,
                    maxlength : 20
                },

                email : {
                    required : true,
                    email: true,
                    minlength: 1,
                    maxlength: 64
                },

                web_address : {
                    maxlength: 64
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
@if(!$modal)
@endsection
@endif
