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
            <h1>Result codes for successfully processed transactions that should be manually reviewed</h1>

            <p><strong>Detalji transakcije:</strong></p>
                Result: {!! $status->result->description !!}<br>
                Result code:{!! $status->result->code !!}<br>
                3D Secure:{!! $status->threeDSecure->eci !!}<br>
                Descriptor : <strong> {!! $status->descriptor !!}</strong><br>
        </div>
@stop

@section('js')

@stop
