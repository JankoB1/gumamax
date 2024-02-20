@extends('layouts.app')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop

@section('content')
    <div class="ty-page">
        <div class="order-success">
            <div class="ty-icon">
                <img src="{{ asset('images/visuals/order-check.svg') }}" alt="check">
            </div>
            <span>Vaša porudžbina je uspešno obavljena</span>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="ty-icon">
                    <img src="{{ asset('images/visuals/ty1.svg') }}" alt="ty">
                </div>
                <p>Kontaktiraćemo vas sa povratnim informacijama</p>
            </div>
            <div class="col-md-4">
                <div class="ty-icon">
                    <img src="{{ asset('images/visuals/ty1.svg') }}" alt="ty">
                </div>
                <p>Termin isporuke<br><span>(Maksimalno do 48 sati na željenu adresu)</span></p>
            </div>
            <div class="col-md-4">
                <div class="ty-icon">
                    <img src="{{ asset('images/visuals/ty1.svg') }}" alt="ty">
                </div>
                <p>Mogućnost povrata</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5"></div>
        </div>
    </div>
@stop


