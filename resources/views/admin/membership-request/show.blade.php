@extends('admin.master')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
    @stop

    @inject('cities', 'Delmax\Webapp\Models\City')

    @section('content')
            <!-- start:main content -->
    <div class="main clearfix">

        <div class="page-title content clearfix">

            <h1 class="pull-left">Prijavite Vašu firmu i postanite Gumamax partner</h1>

            <ol class="breadcrumb pull-right">
                <li><a href="{!! url('/') !!}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Početna</a></li>
                <li class="active">Prijava</li>
            </ol>

        </div>

        <div class="row" id="customer-forms">

            @if ($errors->any())
                <div class="alert alert-error">Greška!
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif

            {!! Former::vertical_open()
                ->id('form-partner-register')
                ->method($formMethod)
                ->action(url($formUrl))
                ->secure()
            !!}
                {!! Former::populate($model) !!}
            <div class="row">
                <input type="hidden" name="country_id" id="country_id" value="SRB">
                <div class="col-sm-6">

                    {!! Former::text('name')->label('Naziv')->required() !!}
                    {!! Former::text('department')->label('Poslovnica/Odeljenje') !!}

                    <div class="form-group">
                        <label for="is_installer" class="control-label required">Servis / Prodavnica</label>
                        <select class="form-control" name="is_installer" id="is_installer">
                            <option value="1" {!! (old('is_installer')==1) ? 'selected': '' !!} >Servis</option>
                            <option value="0" {!! (old('is_installer')==0) ? 'selected': '' !!} >Prodavnica</option>
                        </select>
                    </div>
                    {!! Former::text('tax_identification_number')->label('PIB')->required() !!}
                    {!! Former::text('first_name')->label('Kontakt osoba (ime)')->required() !!}
                    {!! Former::text('last_name')->label('Kontakt osoba (prezime)')->required() !!}

                    {!! Former::select('city.city_id')->name('city_id')->id('city_id')
                                ->fromQuery($cities::serbianCities(['city_id', 'city_name']), 'city_name', 'city_id')
                                ->placeholder('Odaberi mesto')
                                ->label('Mesto')
                    !!}
                    {!! Former::text('address')->label('Adresa (Ulica i broj)')->required() !!}
                    {!! Former::text('phone')->label('Telefon')->required() !!}
                    {!! Former::text('email')->label('Email')->required() !!}
                    {!! Former::text('web_address')->label('Web adresa') !!}
                </div>

                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <div><h3>Prevlačenjem oznake (markera) možete podesiti tačnu lokaciju Vašeg servisa ili prodavnice</h3></div>
                            <div id="map" class="map-canvas"></div>
                            <div class="">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" readonly name="latitude" id="latitude" value="{!!  old('latitude')=='' ? 43.9667 : old('latitude') !!}">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" readonly name="longitude" id="longitude" value="{!!  old('longitude')=='' ? 21.2500 : old('longitude')  !!}">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <input type="submit" name="signup_submit" id="signup_submit" value="Pošalji prijavu" class="btn btn-info btn-large">
                </div>
            </div>
            {!! Former::close() !!}

            <br>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3 bold-text"><em>NAPOMENA:</em></div>
                    <div class="col-sm-9 napomena bold-text">Nakon dobijanja prijave kontaktiraćemo Vas u roku od 7 dana i dati Vam preciznije informacije.</div>
                </div>
            </div>



        </div>
    </div>

@endsection


@section('js')
    {!! Html::script(mix('js/maps.js')) !!}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYE-QLKrq0kE3ieeZd2QIRkvija1LNS3Y&callback=mapInitializeWithGeocoding" async defer></script>
    <script>
        $(function(){
            $('#form-partner-register').validate({
                rules : {
                    name : {
                        required : true,
                        minlength: 2,
                        maxlength : 32
                    },

                    department : {
                        maxlength : 32
                    },

                    is_installer:{
                        required: true
                    },

                    tax_identification_number:{
                        required: true
                    },

                    first_name : {
                        required : true,
                        minlength: 2
                    },

                    last_name : {
                        required : true,
                        minlength : 2,
                        maxlength : 32
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
@stop
