@extends('layouts.app')

@section('title')
    Prodaja guma na internetu. Dobra ponuda!
@stop

@section('content')
    <div class="main">
        <div class="page-title content clearfix">
            <h1 class="pull-left">Rezultat transakcije</h1>
        </div>
        <div class="row" id="static-page">
            <div class="payment-result-caption">Uspešno ste izvršili plaćanje.</div>
            <p>Vaša porudžbenica je uspešno plaćena karticom
                <strong>{{ $status->paymentBrand. ' **** '. $status->card->last4Digits }}</strong> u iznosu od
                <strong>{{ $status->amount. ' '. $status->currency }}</strong></p>

            <div class="col-md-6 col-xs-12">
                <div class="well">
                    <strong>Detalji transakcije:</strong><br>
                    Broj online porudžbenice: <strong> {{ $order->number }}</strong><br>
                    Autorizacioni kod banke: <strong> </strong><br>
                    Vreme transakcije: <strong>{{ $status->timestamp_local }}
                    </strong>
                </div>

                <p>Na Vašu email adresu biće poslati detaljniji podaci o izvršenoj kupovini. Ukoliko imate bilo kakvih pitanja možete
                   nas kontaktirati na <strong>{{config('gumamax.web_support_email_address')}}</strong> ili putem telefona
                    <strong>{{Config::get('gumamax.web_support_phone')}}</strong>.</p>
                <p>Hvala na ukazanom poverenju!</p>
            </div>
        </div>
    </div>
@stop

@section('scriptsBottom')
<script>
    console.log("deleting cart from session")
    sessionStorage.removeItem("gmx-cart")
</script>
@stop
