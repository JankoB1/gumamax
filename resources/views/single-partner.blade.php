@extends('layouts.app')

@section('content')

    <section id="single-partner-banner">
    </section>

    <section id="single-partner-meta">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ asset('images/visuals/logo-preview.png') }}" alt="">
            </div>
            <div class="col-md-3">
                <h4>Gumamax partner</h4>
                <h5>Servis sa mogućnošću montaže</h5>
                <h2>{{$partner->description}}</h2>
                <div class="single-partner-address">
                    <i class="fa-solid fa-location-dot"></i>
                    {{$partner->address.", ".$partner->city_name}}
                </div>
            </div>
            @if($isPartnerOwner)
            <div class="col-sm-1" >
                <a href="{{route("crm.partners.edit", $id)}}">
                    <img id="edit-icon" src="{{ asset('images/edit.svg') }}"/>
                </a>
            </div>
            @endif
        </div>
    </section>

    <section id="single-partner-map-as">
        <div class="row">
            <div class="col-md-6">
                <div class="as">
                    <h4>O nama</h4>
                    <p>{!! $partner->member->page->long_description !!}</p>
                    <div class="phone-time">
                        <i class="fa-solid fa-phone"></i>
                        {!! $partner->phone !!}
                        <i class="fa-solid fa-clock"></i>
                        @foreach($partner->daySpans as $daySpan)
                            @if(strcmp($daySpan["start_day"], $daySpan["end_day"]) == 0)
                                {{substr($daySpan["start_day"],0,3).": ".$daySpan["hours"]." | "}}
                            @else
                                {{substr($daySpan["start_day"],0,3)."-".substr($daySpan["end_day"],0,3).": ".$daySpan["hours"]." | "}}
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="cards">
                    <img src="{{ asset('images/visuals/card.png') }}" alt="cards">
                </div>
            </div>
            <div class="col-md-6">
                <div id="single-partner-map"></div>
            </div>
        </div>
    </section>

    <section id="services">
        <h2>Usluge</h2>
        <div class="row">
            @foreach($services as $service)
            <div class="col-md-2">
                <div class="single-service">
                    <img src="{{ asset('images/visuals/tyre.svg') }}" alt="tyres">
                    <p>{{ $service->description }}</p>
                </div>
            </div>
            @endforeach
            @foreach($servicesOther as $service)
                <div class="col-md-2">
                    <div class="single-service">
                        <img src="{{ asset('images/visuals/tyre.svg') }}" alt="tyres">
                        <p>{{ $service->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="partners">
        <div class="partners-logos" style="border-bottom: 0.5px solid #B0B0B0;">
            <div class="row">
                <div class="col">
                    <img src="{{ asset('images/visuals/michelin.svg') }}" alt="michelin">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/continental.svg') }}" alt="continental">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/pirelli.svg') }}" alt="pirelli">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/sava.svg') }}" alt="sava">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/kleber.svg') }}" alt="kleber">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <img src="{{ asset('images/visuals/bfgoodrich.svg') }}" alt="bfgoodrich">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/tigar.svg') }}" alt="tigar">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/orium.svg') }}" alt="orium">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/trayal.svg') }}" alt="trayal">
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/mitas.svg') }}" alt="mitas">
                </div>
            </div>
        </div>
    </section>

    <section id="benefits">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <img src="{{ asset('images/visuals/delivery.svg') }}" alt="delivery">
                    <p>Besplatna i brzaisporuka</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/services.svg') }}" alt="services">
                    <p>Više od 100<br>servisa</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/payments.svg') }}" alt="payments">
                    <p>Različite opcije<br>plaćanja</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/production.svg') }}" alt="production">
                    <p>Info. o god.<br>proizvodnje</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/refund.svg') }}" alt="refund">
                    <p>Vraćamo<br>novac</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/prices.svg') }}" alt="prices">
                    <p>Imate bolje<br>cene</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/legals.svg') }}" alt="legals">
                    <p>Pravna lica<br>i flote vozila</p>
                </div>
                <div class="col">
                    <img src="{{ asset('images/visuals/reservation.svg') }}" alt="reservation">
                    <p>Rezerviši gume<br>unapred</p>
                </div>
            </div>
        </div>
    </section>

@endsection
