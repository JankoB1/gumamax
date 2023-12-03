@extends('layouts.app')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop
@section('styles')
    <link rel="stylesheet" href="{{ asset("css/payment.css") }}">
@endsection

@section('content')

@stop

@section('scriptsBottom')

    <script src="{{ asset("js/payment.js") }}"></script>
    <script src="{{ asset("js/vendor/jquery.js") }}"></script>

    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkoutId}}"></script>


@stop
