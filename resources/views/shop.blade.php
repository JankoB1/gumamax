@extends('layouts.app')

@section('content')

    <div class="breadcrumb">
        <p>Početna / Pretraga / <strong>Rezultati</strong></p>
    </div>

    <section id="shop-inner">
        <h2>Rezultati pretrage</h2>
        <div class="divider"></div>
        <div class="sort-bar">
            <div class="row">
                <div class="col-md-4">
                    <p>1-20 od 100 rezultata</p>
                </div>
                <div class="col-md-5 offset-md-3">
                    <span>Poredaj po</span>
                    <select name="sort" id="sort">
                        <option value="1">Popularnosti</option>
                        <option value="2">Popularnosti</option>
                        <option value="3">Popularnosti</option>
                    </select>
                    <div class="navigation">
                        <span class="left"><i class="fa-solid fa-chevron-left"></i></span>
                        <span class="right"><i class="fa-solid fa-chevron-right"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-products">
            <div class="row">
                <div class="col-md-4 filters-md-4">
                    <div class="filters">
                        <div class="categories">
                            <h4>Kategorije</h4>
                            <div class="form-group">
                                <input type="checkbox" name="cat1">
                                <label for="cat1">Zimske gumee</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="cat2">
                                <label for="cat1">Zimske gumee</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="cat3">
                                <label for="cat1">Zimske gumee</label>
                            </div>
                        </div>

                        <div class="brands">
                            <h4>Brendovi</h4>
                            <div class="form-group">
                                <input type="checkbox" name="brand1">
                                <label for="cat1">Pirelli</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand2">
                                <label for="cat1">Tigar</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Sawa</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Orium</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Michelin</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">BFGoodrichi</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Continental</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Kleber</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Mitas</label>
                            </div>
                        </div>

                        <div class="price-filter">
                            <h4>Cena</h4>
                            <p>Sortiraj prema</p>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Najnižoj ka najvišoj</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="brand3">
                                <label for="cat1">Najvišoj ka najnižoj</label>
                            </div>
                        </div>

                        <button>Osveži</button>
                    </div>

                    <div class="shop-filter-banner">
                        <h3>Floating program</h3>
                        <div class="divider"></div>
                        <p>Za vlasnike 2 i više vozila</p>
                        <a href="#">Saznaj više</a>
                    </div>

                    <div class="best-sellers">
                        <h4>Najprodavanije</h4>
                        <div class="divider"></div>
                        <div class="single-best-seller row">
                            <div class="col-md-3">
                                <img src="{{ asset('images/visuals/best-seller.png') }}" alt="best seller image">
                            </div>
                            <div class="col-md-9">
                                <p>Gume za motocikl</p>
                                <h6>4,650 RSD</h6>
                            </div>
                        </div>
                        <div class="single-best-seller row">
                            <div class="col-md-3">
                                <img src="{{ asset('images/visuals/best-seller.png') }}" alt="best seller image">
                            </div>
                            <div class="col-md-9">
                                <p>Gume za motocikl</p>
                                <h6>4,650 RSD</h6>
                            </div>
                        </div>
                        <div class="single-best-seller row">
                            <div class="col-md-3">
                                <img src="{{ asset('images/visuals/best-seller.png') }}" alt="best seller image">
                            </div>
                            <div class="col-md-9">
                                <p>Gume za motocikl</p>
                                <h6>4,650 RSD</h6>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-8">
                    <div class="single-shop-product">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="single-shop-product-left">
                                    <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-img" alt="dot">
                                    <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="top-shop-product">
                                    <h3 class="product-title">Tigar winted 195/65 R15</h3>
                                    <img src="{{ asset('images/visuals/compare.svg') }}" alt="compare" class="compare">
                                    <div class="stock">
                                        <div class="stock-status in-stock"></div>
                                        <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                                    </div>
                                </div>
                                <div class="bottom-shop-product">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                            <p>Putničko</p>
                                        </div>
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                            <p>Zimska</p>
                                        </div>
                                    </div>
                                    <div class="price-details">
                                        <h5 class="single-shop-price">4,650 RSD</h5>
                                        <a href="#">Detalji o proizvodu</a>
                                    </div>
                                    <p>Isporuka od tri do sedam radnih dana</p>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-single-product">
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                                    <span class="letter">C</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                                    <span class="letter">B</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                                    <span class="letter sound-letters">72db</span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="quantity-area">
                                    <span class="minus">-</span>
                                    <span class="qty">1</span>
                                    <span class="plus">+</span>
                                </div>
                                <button class="add-to-cart">Dodaj u korpu</button>
                            </div>
                        </div>
                    </div>
                    <div class="single-shop-product">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="single-shop-product-left">
                                    <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-img" alt="dot">
                                    <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="top-shop-product">
                                    <h3 class="product-title">Tigar winted 195/65 R15</h3>
                                    <img src="{{ asset('images/visuals/compare.svg') }}" alt="compare" class="compare">
                                    <div class="stock">
                                        <div class="stock-status in-stock"></div>
                                        <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                                    </div>
                                </div>
                                <div class="bottom-shop-product">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                            <p>Putničko</p>
                                        </div>
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                            <p>Zimska</p>
                                        </div>
                                    </div>
                                    <div class="price-details">
                                        <h5 class="single-shop-price">4,650 RSD</h5>
                                        <a href="#">Detalji o proizvodu</a>
                                    </div>
                                    <p>Isporuka od tri do sedam radnih dana</p>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-single-product">
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                                    <span class="letter">C</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                                    <span class="letter">B</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                                    <span class="letter sound-letters">72db</span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="quantity-area">
                                    <span class="minus">-</span>
                                    <span class="qty">1</span>
                                    <span class="plus">+</span>
                                </div>
                                <button class="add-to-cart">Dodaj u korpu</button>
                            </div>
                        </div>
                    </div>
                    <div class="single-shop-product">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="single-shop-product-left">
                                    <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-img" alt="dot">
                                    <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="top-shop-product">
                                    <h3 class="product-title">Tigar winted 195/65 R15</h3>
                                    <img src="{{ asset('images/visuals/compare.svg') }}" alt="compare" class="compare">
                                    <div class="stock">
                                        <div class="stock-status in-stock"></div>
                                        <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                                    </div>
                                </div>
                                <div class="bottom-shop-product">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                            <p>Putničko</p>
                                        </div>
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                            <p>Zimska</p>
                                        </div>
                                    </div>
                                    <div class="price-details">
                                        <h5 class="single-shop-price">4,650 RSD</h5>
                                        <a href="#">Detalji o proizvodu</a>
                                    </div>
                                    <p>Isporuka od tri do sedam radnih dana</p>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-single-product">
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                                    <span class="letter">C</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                                    <span class="letter">B</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                                    <span class="letter sound-letters">72db</span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="quantity-area">
                                    <span class="minus">-</span>
                                    <span class="qty">1</span>
                                    <span class="plus">+</span>
                                </div>
                                <button class="add-to-cart">Dodaj u korpu</button>
                            </div>
                        </div>
                    </div>
                    <div class="single-shop-product">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="single-shop-product-left">
                                    <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-img" alt="dot">
                                    <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="top-shop-product">
                                    <h3 class="product-title">Tigar winted 195/65 R15</h3>
                                    <img src="{{ asset('images/visuals/compare.svg') }}" alt="compare" class="compare">
                                    <div class="stock">
                                        <div class="stock-status in-stock"></div>
                                        <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                                    </div>
                                </div>
                                <div class="bottom-shop-product">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                            <p>Putničko</p>
                                        </div>
                                        <div class="col-md-2">
                                            <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                            <p>Zimska</p>
                                        </div>
                                    </div>
                                    <div class="price-details">
                                        <h5 class="single-shop-price">4,650 RSD</h5>
                                        <a href="#">Detalji o proizvodu</a>
                                    </div>
                                    <p>Isporuka od tri do sedam radnih dana</p>
                                </div>
                            </div>
                        </div>
                        <div class="row bottom-single-product">
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                                    <span class="letter">C</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                                    <span class="letter">B</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                                    <span class="letter sound-letters">72db</span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="quantity-area">
                                    <span class="minus">-</span>
                                    <span class="qty">1</span>
                                    <span class="plus">+</span>
                                </div>
                                <button class="add-to-cart">Dodaj u korpu</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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
