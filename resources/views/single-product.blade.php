@extends('layouts.app')

@section('content')

    <section id="single-product-container" class="container-fluid">
        <div class="breadcrumb">
            <p>Početna / Autogume / <strong>Tigar Wintera</strong></p>
        </div>
        <div class="single-product-inner">
            <div class="row">
                <div class="col-md-6">
                    <div class="left-product-area">
                        <div class="row">
                            <div class="col-2">
                                <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot tag" class="dot-tag">
                                <div class="single-product-gallery">
                                    <div class="single-image">
                                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                                    </div>
                                    <div class="single-image">
                                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                                    </div>
                                    <div class="single-image">
                                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                                    </div>
                                </div>
                            </div>
                            <div class="col-10">
                                <span class="tag">Najtraženije</span>
                                <img src="{{ asset('images/visuals/product-image.png') }}" alt="featured product image" class="single-featured-image">
                            </div>
                        </div>
                        <div class="row bottom-tags">
                            <div class="col-md-2 offset-md-2">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                                    <span class="letter">C</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                                    <span class="letter">B</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                                    <span class="letter sound-letters">72db</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="single-product-meta">
                        <p class="single-product-cat">Automobilske gume</p>
                        <h2 class="single-product-title">Tigar Winter 195/65 R15</h2>
                        <div class="vws-cont">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                    <p>Putničko</p>
                                </div>
                                <div class="col-md-2">
                                    <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                    <p>Zimska</p>
                                </div>
                                <div class="col-md-6 offset-md-1">
                                    <div class="stock">
                                        <div class="stock-status in-stock"></div>
                                        <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="price-quantity-cont">
                            <h5 class="price">4,650 RSD</h5>
                            <div>
                                <div class="quantity-area">
                                    <span class="minus">-</span>
                                    <span class="qty">1</span>
                                    <span class="plus">+</span>
                                </div>
                                <button class="add-to-cart">Dodaj u korpu</button>
                            </div>
                        </div>
                        <div class="product-table-info">
                            <table>
                                <tr>
                                    <td>Vrsta vozila</td>
                                    <td>Automobil</td>
                                    <td>Ušteda goriva</td>
                                    <td>60%</td>
                                </tr>
                                <tr>
                                    <td>Sezona</td>
                                    <td>Leto</td>
                                    <td>Prijanjanje</td>
                                    <td>100%</td>
                                </tr>
                                <tr>
                                    <td>Visina</td>
                                    <td>20</td>
                                    <td>Nivo buke</td>
                                    <td>78 db</td>
                                </tr>
                                <tr>
                                    <td>Širina</td>
                                    <td>15</td>
                                    <td>Godina proizvodnje</td>
                                    <td>2001</td>
                                </tr>
                                <tr>
                                    <td>Profil</td>
                                    <td>14</td>
                                    <td>Težina</td>
                                    <td>10 kg</td>
                                </tr>
                                <tr>
                                    <td>Indeks brzine</td>
                                    <td>20,5</td>
                                    <td>Zemlja porekla</td>
                                    <td>Nemačka</td>
                                </tr>
                                <tr>
                                    <td>Indeks nosivosti</td>
                                    <td>2T</td>
                                    <td>O proizvođaču</td>
                                    <td>Autobahn</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-products">
        <div class="featured-products-header">
            <h2>Slični proizvodi</h2>
            <div class="divider"></div>
        </div>
        <div class="featured-products-list products-list">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="product-wrapper">
                        <div class="top-area">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                            <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot tag" class="dot-tag">
                            <div class="product-main-meta">
                                <h5 class="product-price">4,650 rsd</h5>
                                <p>Tigar winter 195/65 R15 winter tigar 2 red</p>
                            </div>
                        </div>
                        <div class="bottom-area">
                            <div class="product-tags">
                                <img src="{{ asset('images/visuals/product-tags.png') }}" alt="product tags">
                            </div>
                            <div class="stock">
                                <div class="stock-status in-stock"></div>
                                <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                            </div>
                            <p class="note">Isporuka od tri do sedan radnih dana</p>
                            <button type="button">Dodaj u korpu</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="product-wrapper">
                        <div class="top-area">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                            <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot tag" class="dot-tag">
                            <div class="product-main-meta">
                                <h5 class="product-price">4,650 rsd</h5>
                                <p>Tigar winter 195/65 R15 winter tigar 2 red</p>
                            </div>
                        </div>
                        <div class="bottom-area">
                            <div class="product-tags">
                                <img src="{{ asset('images/visuals/product-tags.png') }}" alt="product tags">
                            </div>
                            <div class="stock">
                                <div class="stock-status in-stock"></div>
                                <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                            </div>
                            <p class="note">Isporuka od tri do sedan radnih dana</p>
                            <button type="button">Dodaj u korpu</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="product-wrapper">
                        <div class="top-area">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                            <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot tag" class="dot-tag">
                            <div class="product-main-meta">
                                <h5 class="product-price">4,650 rsd</h5>
                                <p>Tigar winter 195/65 R15 winter tigar 2 red</p>
                            </div>
                        </div>
                        <div class="bottom-area">
                            <div class="product-tags">
                                <img src="{{ asset('images/visuals/product-tags.png') }}" alt="product tags">
                            </div>
                            <div class="stock">
                                <div class="stock-status in-stock"></div>
                                <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                            </div>
                            <p class="note">Isporuka od tri do sedan radnih dana</p>
                            <button type="button">Dodaj u korpu</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="product-wrapper">
                        <div class="top-area">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image" class="featured-image">
                            <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot tag" class="dot-tag">
                            <div class="product-main-meta">
                                <h5 class="product-price">4,650 rsd</h5>
                                <p>Tigar winter 195/65 R15 winter tigar 2 red</p>
                            </div>
                        </div>
                        <div class="bottom-area">
                            <div class="product-tags">
                                <img src="{{ asset('images/visuals/product-tags.png') }}" alt="product tags">
                            </div>
                            <div class="stock">
                                <div class="stock-status in-stock"></div>
                                <p class="stock-status-text">Na stanju <strong>(10 kom)</strong></p>
                            </div>
                            <p class="note">Isporuka od tri do sedan radnih dana</p>
                            <button type="button">Dodaj u korpu</button>
                        </div>
                    </div>
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
