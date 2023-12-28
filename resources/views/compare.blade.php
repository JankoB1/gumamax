@extends('layouts.app')

@section('content')

    <div class="breadcrumb">
        <p>Početna / Pretraga / Rezultati / <strong>Usporedba</strong></p>
    </div>

    <section id="compare">
        <h2>Uporedi proizvode</h2>
        <div class="divider"></div>
        <div class="compare-inner">
            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Proizvod</p>
                </div>
                @foreach($items as $item)
                    <div class="col-md-2">
                        <div class="compare-featured-inner">
                            <img src="{{ asset('images/visuals/delete-icon.svg') }}" alt="delete icon" class="delete" onclick="rmFromCompare({{ $item["product_id"] }})">
                            <img src="{{ $item["image_url"]   }}" alt="featured image" class="featured-image">
                            <h5 class="compare-price"> {{ number_format($item["price_with_tax"], 2, ",", ".") }} RSD</h5>
                            <p>{{$item["manufacturer"]}}</p>
                            <p>{{$item["additional_description"]}}</p>
                            <button class="add-to-cart" onclick="addToCartCompare({{ $item["product_id"] }})">Dodaj u korpu</button>
                        </div>
                    </div>
                @endforeach
                <!--<div class="col-md-2">
                    <div class="compare-featured-inner">
                        <img src="{{ asset('images/visuals/delete-icon.svg') }}" alt="delete icon" class="delete">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="featured image" class="featured-image">
                        <h5 class="compare-price">4,659 RSD</h5>
                        <p>Tigar winter 195/65 R15
                            winter tigar drugi red</p>
                        <button class="add-to-cart">Dodaj u korpu</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="compare-featured-inner">
                        <img src="{{ asset('images/visuals/delete-icon.svg') }}" alt="delete icon" class="delete">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="featured image" class="featured-image">
                        <h5 class="compare-price">4,659 RSD</h5>
                        <p>Tigar winter 195/65 R15
                            winter tigar drugi red</p>
                        <button class="add-to-cart">Dodaj u korpu</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="compare-featured-inner">
                        <img src="{{ asset('images/visuals/delete-icon.svg') }}" alt="delete icon" class="delete">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="featured image" class="featured-image">
                        <h5 class="compare-price">4,659 RSD</h5>
                        <p>Tigar winter 195/65 R15
                            winter tigar drugi red</p>
                        <button class="add-to-cart">Dodaj u korpu</button>
                    </div>
                </div>-->
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">DOT broj</p>
                </div>
                @foreach($items as $item)
                    @if(sizeof(explode("/",$item["cat_no"])) > 1)
                        @switch(explode("/",$item["cat_no"])[1])
                            @case("2021")
                                <div class="col-md-2">
                                    <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot" class="dot-tag-compare">
                                </div>
                                @break
                            @default
                                <div class="col-md-2">
                                </div>
                                @break
                        @endswitch
                    @endif
                @endforeach
                <!--<div class="col-md-2">
                    <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot" class="dot-tag-compare">
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot" class="dot-tag-compare">
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot" class="dot-tag-compare">
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot" class="dot-tag-compare">
                </div>-->
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Karakteristike</p>
                </div>
                @foreach($items as $item)
                <div class="col-md-2">
                    <div>
                        <div class="single-tag">
                            <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                            <span class="letter">
                                @if(in_array($item["eu_badge"]["consumption"],$unknwVals))
                                    -
                                @else
                                    {{$item["eu_badge"]["consumption"]}}
                                @endif
                            </span>
                        </div>
                        <div class="single-tag">
                            <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                            <span class="letter">
                                @if(in_array($item["eu_badge"]["grip"],$unknwVals))
                                    -
                                @else
                                    {{$item["eu_badge"]["grip"]}}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="single-tag">
                            <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                            <span class="letter sound-letters">
                                @if($item["eu_badge"]["noise_db"] == "" || $item["eu_badge"]["noise_db"] == "-")
                                    -db
                                @else
                                    {{intval($item["eu_badge"]["noise_db"])}}db
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
                <!--<div class="col-md-2">
                    <div>
                        <img src="{{ asset('images/visuals/c-gas.png') }}" alt="gas">
                        <img src="{{ asset('images/visuals/b-weather.png') }}" alt="weather">
                    </div>
                    <div>
                        <img src="{{ asset('images/visuals/sound-c.png') }}" alt="sound">
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <img src="{{ asset('images/visuals/c-gas.png') }}" alt="gas">
                        <img src="{{ asset('images/visuals/b-weather.png') }}" alt="weather">
                    </div>
                    <div>
                        <img src="{{ asset('images/visuals/sound-c.png') }}" alt="sound">
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <img src="{{ asset('images/visuals/c-gas.png') }}" alt="gas">
                        <img src="{{ asset('images/visuals/b-weather.png') }}" alt="weather">
                    </div>
                    <div>
                        <img src="{{ asset('images/visuals/sound-c.png') }}" alt="sound">
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <img src="{{ asset('images/visuals/c-gas.png') }}" alt="gas">
                        <img src="{{ asset('images/visuals/b-weather.png') }}" alt="weather">
                    </div>
                    <div>
                        <img src="{{ asset('images/visuals/sound-c.png') }}" alt="sound">
                    </div>
                </div>-->
            </div>

            <div class="row season-row">
                <div class="col-md-4">
                    <p class="first-col">Sezona</p>
                </div>
                    @foreach($items as $item)
                    <div class="col-md-2">
                        @switch($item["vehicle_category"])
                            @case("Putničko")
                                <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                                <p>Putničko</p>
                                @break
                            @case("4x4")
                                <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                <p>4x4</p>
                                @break
                            @case("Dostavno vozilo")
                                <img src="{{ asset('images/visuals/combi.svg') }}" alt="car">
                                <p>Dostavno vozilo</p>
                                @break
                            @case("Poljoprivredno vozilo")
                                <img src="{{ asset('images/visuals/tractor.svg') }}" alt="car">
                                <p>Poljoprivredno vozilo</p>
                                @break
                            @case("Bicikl")
                                <img src="{{ asset('images/visuals/bicycle.svg') }}" alt="car">
                                <p>Bicikl</p>
                                @break
                            @case("Motocikli i skuteri")
                                <img src="{{ asset('images/visuals/motocycle.svg') }}" alt="car">
                                <p>Motocikli i skuteri</p>
                                @break
                            @case("Kamioni i autobusi")
                                <img src="{{ asset('images/visuals/truck.svg') }}" alt="car">
                                <p>Kamioni i autobusi</p>
                                @break
                            @default
                                <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                                <p>Drugo</p>
                                @break
                        @endswitch
                    </div>
                    @endforeach
                <!--<div class="col-md-2">
                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                    <p>Putničko</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                    <p>Putničko</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                    <p>Putničko</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                    <p>Putničko</p>
                </div>-->
            </div>

            <div class="row season-row">
                <div class="col-md-4">
                    <p class="first-col">Sezona</p>
                </div>
                @foreach($items as $item)
                    <div class="col-md-2">
                        @switch($item["season"])
                            @case("Zimske")
                                <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                <p>Zimska</p>
                                @break
                            @case("Zimska")
                                <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                <p>Zimska</p>
                                @break
                            @case("Letnje")
                                <img src="{{ asset('images/visuals/summer.svg') }}" alt="winter">
                                <p>Letnja</p>
                                @break
                            @case("Sve sezone")
                                <img src="{{ asset('images/visuals/all-seasons.svg') }}" alt="winter">
                                <p>Sve sezone</p>
                                @break
                            @default
                                <!--<img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">-->
                                <!--<p>Drugo</p>-->
                                @break
                        @endswitch
                    </div>
                @endforeach
                <!--<div class="col-md-2">
                    <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                    <p>Zimska</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                    <p>Zimska</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                    <p>Zimska</p>
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                    <p>Zimska</p>
                </div>-->
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Index brzine</p>
                </div>
                @foreach($items as $item)
                <div class="col-md-2">
                        @foreach($item["dimensions"] as $d)
                            @if($d["description"] == "Indeks brzine")
                                <h5>{{ $d["value_text"] }}</h5>
                            @endif
                        @endforeach
                </div>
                @endforeach
                <!--<div class="col-md-2">
                    <h5>A4</h5>
                </div>
                <div class="col-md-2">
                    <h5>A5</h5>
                </div>
                <div class="col-md-2">
                    <h5>A6</h5>
                </div>
                <div class="col-md-2">
                    <h5>D</h5>
                </div>-->
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Index novosti</p>
                </div>
                @foreach($items as $item)
                    <div class="col-md-2">
                        @foreach($item["dimensions"] as $d)
                            @if($d["description"] == "Indeks nosivosti")
                                <h5>{{ $d["value_text"] }}</h5>
                            @endif
                        @endforeach
                    </div>
                @endforeach
                <!--<div class="col-md-2">
                    <h5>45</h5>
                </div>
                <div class="col-md-2">
                    <h5>45</h5>
                </div>
                <div class="col-md-2">
                    <h5>45</h5>
                </div>
                <div class="col-md-2">
                    <h5>45</h5>
                </div>-->
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

@section("scriptsBottom")
    <script src="{{ asset("js/compare.js") }}"></script>
@endsection
