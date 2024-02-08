@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <h2>Lični podaci</h2>

                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">Ime<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="family-name" class="col-md-4 col-form-label text-md-end">Prezime<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="family_name" type="text" class="form-control @error('family_name') is-invalid @enderror" name="family_name" value="{{ old('family_name') }}" required autocomplete="family_name" autofocus>

                                @error('family_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tel" class="col-md-4 col-form-label text-md-end">Broj telefona<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="tel" type="text" class="form-control @error('tel') is-invalid @enderror" name="tel" value="{{ old('tel') }}" required autocomplete="tel" autofocus>

                                @error('tel')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Korisničko ime') }}<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('eMail adresa') }}<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Lozinka') }}<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Potvrdite lozinku') }}<sup>*</sup></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Vi ste:" class="control-label">Vi ste:</label>
                            <div class="radio">
                                <label for="customer_type_id" class="">
                                    <input class="personal_account" value="1" id="customer_type_id" type="radio" name="customer_type_id" checked="checked">Fizičko lice
                                </label>
                            </div>
                            <div class="radio"><label for="customer_type_id2" class=""><input class="company_account" value="2" id="customer_type_id2" type="radio" name="customer_type_id">Pravno lice</label>
                            </div>
                        </div>

                        <div class="company_fields" style="display: none">
                            <div class="form-group"><label for="company_name" class="control-label">Naziv pravnog lica</label><input class="form-control" id="company_name" type="text" name="company_name"></div>
                            <div class="form-group"><label for="tax_identification_number" class="control-label">PIB</label><input class="form-control" id="tax_identification_number" type="text" name="tax_identification_number"></div>
                        </div>

                        <h2>Podaci o vozilu</h2>

                        <div id="addVehicle" class="typ_id">
                            <div class="vehicleError"></div>
                            <div>
                                <div class="form-group has-error">
                                    <label for="year_of_production" class="control-label ">Godina proizvodnje</label>
                                    <select class="form-control @error('year_of_production') is-invalid @enderror" name="year_of_production" id="year_of_production"
                                            aria-required="true" aria-describedby="year_of_production-error" required autofocus>
                                        @foreach(range(date("Y"),1900) as $year)
                                            <option value="{{$year}}">{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mfa_id" class="control-label ">Naziv proizvodjača</label>
                                    <select class="form-control @error('mfa_id') is-invalid @enderror" name="mfa_id" id="mfa_id" disabled="disabled" required autofocus>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mod_id" class="control-label ">Model</label>
                                    <select class="form-control @error('mod_id') is-invalid @enderror" name="mod_id" id="mod_id" disabled="disabled" required autofocus>
                                    </select>
                                </div>
                                <div class="form-group"><label for="typ_id" class="control-label ">Vozilo</label>
                                    <select class="form-control @error('typ_id') is-invalid @enderror" name="typ_id" id="typ_id" disabled="disabled" required autofocus>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vin" class="control-label "></label>
                                    <input type="hidden" class="form-control" name="vin" id="vin">
                                </div>
                                <div class="form-group">
                                    <label for="engine_code" class="control-label "></label>
                                    <input type="hidden" class="form-control" name="engine_code" id="engine_code">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="commercial_description" id="commercial_description" disabled="disabled">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registracija') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scriptsBottom")
    <script src="{{ asset("js/registration.js") }}"></script>
@endsection
