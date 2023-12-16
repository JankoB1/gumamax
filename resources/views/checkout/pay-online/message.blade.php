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

            @if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $transactionStatus->result->code))

            <h1>ČESTITAMO! Uspešno ste izvršili plaćanje.</h1>
            <p>
                Zadovoljstvo nam je da Vas obavestimo da je Vaša porudžbenica <br>
                Broj online porudžbenice: <strong> {!! $order->number !!}</strong>  <br>
                uspešno naplaćena sa Vaše kartice i da je verifikacija porudžbine završena.
            </p>
            <p><strong>Detalji transakcije:</strong></p>
            Result: {!! $transactionStatus->result->description !!}<br>
            Result code:{!! $transactionStatus->result->code !!}<br>
            3D Secure:{!! $transactionStatus->threeDSecure->eci !!}<br>
            Descriptor : <strong> {!! $transactionStatus->descriptor !!}</strong><br>
            <p>Na Vašu email adresu ćemo Vam poslati detaljnije podatke o Vašoj kupovini.</p>
            <p>Ukoliko imate bilo kakvih pitanja možete nas kontaktirati na {!!config('gumamax.web_support_email_address')!!} ili putem telefona {{Config::get('gumamax.web_support_phone')}}.</p>
            <p>Hvala na ukazanom poverenju!</p>
            @elseif(preg_match("/^(000\.400\.0|000\.400\.100)/", $transactionStatus->result->code))

                <h1>Result codes for successfully processed transactions that should be manually reviewed</h1>

                <p><strong>Detalji transakcije:</strong></p>
                Result: {!! $transactionStatus->result->description !!}<br>
                Result code:{!! $transactionStatus->result->code !!}<br>
                3D Secure:{!! $transactionStatus->threeDSecure->eci !!}<br>
                Descriptor : <strong> {!! $transactionStatus->descriptor !!}</strong><br>
            @endif

            {{--
                        @elseif($transaction_response['result_code'] == '116')

                                <!-- declined: no funds -->
                        <p>Nažalost moramo da Vas obavestimo da transakcija nije uspešno izvršena, jer nemate dovoljno sredstava na računu za ovo plaćanje.</p>
                        <p>Molimo Vas da pokušate da platite preko drugog računa ili da odaberete neki drugi vid plaćanja.</p>
                        Načini plaćanja:
                        <ul>
                            <li>Opšta uplatnica (uplatu možete izvršiti u bilo kojoj banci/pošti ili putem e-bankinga)</li>
                            <li>Na licu mesta</li>
                        </ul>
                        <p>Hvala na razumevanju!</p>

                        @elseif($transaction_response['result_code'] == '400' || $transaction_response['result'] == 'AUTOREVERSED' || $transaction_response['result'] == 'REVERSED')

                                <!-- declined: money returned -->
                        <p>Nažalost moramo da Vas obavestimo da transakcija koju ste započeli nije uspešno završena.</p>
                        <p>Izvršen je povraćaj sredstava na Vaš račun.</p>
                        <p>Molimo Vas da pokušate da platite preko drugog računa ili da odaberete neki drugi vid plaćanja.</p>
                        Načini plaćanja:
                        <ul>
                            <li>Opšta uplatnica (uplatu možete izvršiti u bilo kojoj banci/pošti ili putem e-bankinga)</li>
                            <li>Na licu mesta</li>
                        </ul>
                        <p>Hvala na razumevanju!</p>

                        @elseif($transaction_response['result_code'] == '???' || $transaction_response['result'] == 'CREATED' || $transaction_response['result'] == 'TIMEOUT' || $transaction_response['result'] == 'PENDING')

                            <div class="alert alert-error"><h1>Transakcija neuspešna</h1></div>
                            <p>Nažalost moramo da Vas obavestimo da transakcija nije uspešno izvršena.</p>
                            <p>Molimo Vas da pokušate da platite preko drugog računa ili da odaberete neki drugi vid plaćanja.</p>
                            Načini plaćanja:
                            <ul>
                                <li>Opšta uplatnica (uplatu možete izvršiti u bilo kojoj banci/pošti ili putem e-bankinga)</li>
                                <li>Na licu mesta</li>
                            </ul>
                            <p>Hvala na razumevanju!</p>

                            @else

                                    <!-- declined -->
                            <div class="alert alert-error"><h1>Transakcija neuspešna</h1></div>
                            <p>Nažalost moramo da Vas obavestimo da transakcija nije uspešno izvršena.</p>
                            <p>Molimo Vas da pokušate da platite preko drugog računa ili da odaberete neki drugi vid plaćanja.</p>
                            Načini plaćanja:
                            <ul>
                                <li>Opšta uplatnica (uplatu možete izvršiti u bilo kojoj banci/pošti ili putem e-bankinga)</li>
                                <li>Na licu mesta</li>
                            </ul>
                            <p>Hvala na razumevanju!</p>

                        @endif
            --}}


            </div>
        </div>

@stop

@section('js')

@stop
