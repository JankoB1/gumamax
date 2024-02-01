@extends('layouts.app')

@section('content')

    <section id="single-product-container" class="container-fluid">
        <div class="breadcrumb">
            <p>Početna / Autogume / <strong>{{$product["manufacturer"]}} {{$product["additional_description"]}}</strong></p>
        </div>
        <div class="single-product-inner">
            <div class="row">
                <div class="col-md-6">
                    <div class="left-product-area">
                        <div class="row">
                            <div class="col-2">
                                @if(sizeof(explode("/",$product["cat_no"])) > 1 && explode("/",$product["cat_no"])[1] == "2021")
                                    <img src="{{ asset('images/visuals/dot-tag.png') }}" alt="dot tag" class="dot-tag">
                                @endif
                                <div class="single-product-gallery">
                                    <div class="single-image">
                                        <img src="{{ $product["thumbnail_url_110"] }}" alt="product image">
                                    </div>
                                   <!-- <div class="single-image">
                                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                                    </div>
                                    <div class="single-image">
                                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-10">
                                @if(in_array(head(array_filter($product["dimensions"],function ($d) {
                                                return $d["dimension_id"] == 11;
                                            }))["value_text"]
                                    . "/" . head(array_filter($product["dimensions"],function ($d) {
                                                return $d["dimension_id"] == 12;
                                            }))["value_text"]
                                    . "/R" . head(array_filter($product["dimensions"],function ($d) {
                                                return $d["dimension_id"] == 13;
                                            }))["value_text"]
                                            , $bestsellingDimens))
                                    <span class="tag">Najtraženije</span>
                                @endif
                                <img src="{{ $product["image_url"] }}" alt="featured product image" class="single-featured-image">
                            </div>
                        </div>
                        <div class="row bottom-tags">
                            <div class="col-md-2 offset-md-2">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/bi_fuel-pump.svg') }}" alt="gas">
                                    <span class="letter">
                                        @if(in_array($product["eu_badge"]["consumption"],$unknwVals))
                                            -
                                        @else
                                            {{$product["eu_badge"]["consumption"]}}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/carbon_rain-heavy.svg') }}" alt="weather">
                                    <span class="letter">
                                        @if(in_array($product["eu_badge"]["grip"],$unknwVals))
                                            -
                                        @else
                                            {{$product["eu_badge"]["grip"]}}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="single-tag">
                                    <img src="{{ asset('images/visuals/sound.svg') }}" alt="sound">
                                    <span class="letter sound-letters">
                                        @if($product["eu_badge"]["noise_db"] == "" || $product["eu_badge"]["noise_db"] == "-")
                                            -db
                                        @else
                                            {{intval($product["eu_badge"]["noise_db"])}}db
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="single-product-meta">
                        <p class="single-product-cat">{{$product["description"]}}</p>
                        <h2 class="single-product-title">{{$product["manufacturer"]}} {{$product["additional_description"]}}</h2>
                        <div class="vws-cont">
                            <div class="row">
                                <div class="col-md-2">
                                    @switch($product["vehicle_category"])
                                        @case("Putničko")
                                            <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                            <p>Putničko</p>
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
                                            <img src="{{ asset('images/visuals/jeep.svg') }}" alt="car">
                                            <p>Drugo</p>
                                            @break
                                    @endswitch
                                </div>
                                <div class="col-md-2">
                                    @switch($product["season"])
                                        @case("Zimske")
                                            <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                            <p>Zimska</p>
                                            @break
                                        @case("Letnje")
                                            <img src="{{ asset('images/visuals/summer.svg') }}" alt="winter">
                                            <p>Letnja</p>
                                            @break
                                        @default
                                            <!--<img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">-->
                                            <!--<p>Drugo</p>-->
                                            @break
                                    @endswitch
                                </div>
                                <div class="col-md-6 offset-md-1">
                                    <div class="stock">
                                        @if($product["stock_status"] != 0)
                                            <div class="stock-status-in"></div>
                                            <p class="stock-status-text">Na stanju <strong>({{ (int) $product["stock_status_qty"] }} kom.)</strong></p>
                                        @else
                                            <div class="stock-status-out"></div>
                                            <p class="stock-status-text">Nije na stanju</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="price-quantity-cont">
                            <h5 class="price">{{ number_format($product["price_with_tax"], 2, ",", ".") }} RSD</h5>
                            <div>
                                <div class="quantity-area">
                                    <span class="minus ripple">-</span>
                                    <span class="qty">1</span>
                                    <span class="plus ripple">+</span>
                                </div>
                                <button class="add-to-cart" onclick="addToCart(this)">Dodaj u korpu</button>
                            </div>
                        </div>
                        <div class="product-table-info">
                            <table>
                                @for($cnt = 0; $cnt < sizeof($product["dimensions"]); $cnt+=2)
                                    <tr>
                                        <td>{{ $product["dimensions"][$cnt]["description"] }}</td>
                                        <td>{{ $product["dimensions"][$cnt]["value_text"] }}</td>
                                        @if(sizeof($product["dimensions"])>$cnt+1)
                                            <td>{{ $product["dimensions"][$cnt+1]["description"] }}</td>
                                            <td>{{ $product["dimensions"][$cnt+1]["value_text"] }}</td>
                                        @else
                                            <td>Zemlja porekla</td>
                                            <td>{{ $product["country_of_origin"] }}</td>
                                        @endif
                                    </tr>
                                @endfor
                                @if(sizeof($product["dimensions"])%2==0)
                                    <td>Zemlja porekla</td>
                                    <td>{{ $product["country_of_origin"] }}</td>
                                @endif
                               <!-- <tr>
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
                                </tr>-->
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
                @foreach($featured as $f)
                    <div class="col-md-3 col-6">
                        <div class="product-wrapper">
                            <div class="top-area">
                                <img src="{{ $f["image_url"] }}" alt="product image" class="featured-image">
                                @if(sizeof(explode("/",$f["cat_no"])) > 1 && explode("/",$f["cat_no"])[1] == "2021") <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-tag" alt="dot"> @endif                                <div class="product-main-meta">
                                    <h5 class="product-price">{{ number_format($f["price_with_tax"], 2, ",", ".") }} RSD</h5>
                                    <p>{{ $f["manufacturer"] }} {{ $f["additional_description"] }}</p>
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
                                <button type="button" onclick="addFeaturedToCart({{$f["product_id"]}}, this)">Dodaj u korpu</button>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!--<div class="col-md-3 col-6">
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
    <script src="{{ asset("js/single-product.js") }}"></script>
    <script>
        initPageSingleItem()
    </script>
@endsection
