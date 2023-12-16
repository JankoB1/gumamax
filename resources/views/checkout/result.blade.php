@extends('master')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop

@section('content')
    <div class="main">
        <div class="page-title content clearfix">
            <h1 class="pull-left">Rezultat transakcije</h1>
        </div>
        <div class="row" id="static-page">
            @if($order->payment_method_id==4)
                @include('checkout.pay-order.message')
            @else
                @include('checkout.pay-on-spot.message')
            @endif
        </div>
    </div>

@stop

@section('js')



@stop
