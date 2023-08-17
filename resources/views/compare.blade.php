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
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">DOT broj</p>
                </div>
                <div class="col-md-2">
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
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Karakteristike</p>
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
            </div>

            <div class="row season-row">
                <div class="col-md-4">
                    <p class="first-col">Sezona</p>
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
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                    <p>Putničko</p>
                </div>
            </div>

            <div class="row season-row">
                <div class="col-md-4">
                    <p class="first-col">Sezona</p>
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
                </div>
                <div class="col-md-2">
                    <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                    <p>Zimska</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Index brzine</p>
                </div>
                <div class="col-md-2">
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
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p class="first-col">Index novosti</p>
                </div>
                <div class="col-md-2">
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
