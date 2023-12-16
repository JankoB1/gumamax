@extends('master')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop

@section('content')
    <div class="main account clearfix">
        <div class="page-title content clearfix">
            <h1 class="pull-left">Potvrda prijema</h1>
            <ol class="breadcrumb pull-right">
                <li><a href="{{ url('/') }}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Početna</a></li>
                <li class="active">Zahtev</li>
            </ol>
        </div>
        <div class="row" id="static-page">
            <div class="col-md-9">
                <h1>Vaš zahtev je primljen.</h1>
            </div>
        </div>
    </div>
@stop