@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.4.1/swiper-bundle.css" />
@endsection

@section('scriptsTop')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.4.1/swiper-bundle.min.js"></script>
@endsection

@section('content')

    <section id="hero-homepage">
        <div class="container-fluid">
            <div class="swiper hero-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-6">
                                <div class="left-banner">
                                    <h5>Zimska akcija</h5>
                                    <h2>5,300 din</h2>
                                    <p>It has survived not only five centuries, but also the leap into
                                        electronic typesetting, remaining essentially unchanged.</p>
                                    <a href="#">Saznaj više</a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="right-banner">
                                    <img src="{{ asset('images/visuals/tyre-slider.png') }}" alt="tyre">
                                    <p><strong>50%</strong><br>popusta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-6">
                                <div class="left-banner">
                                    <h5>Zimska akcija</h5>
                                    <h2>5,300 din</h2>
                                    <p>It has survived not only five centuries, but also the leap into
                                        electronic typesetting, remaining essentially unchanged.</p>
                                    <a href="#">Saznaj više</a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="right-banner">
                                    <img src="{{ asset('images/visuals/tyre-slider.png') }}" alt="tyre">
                                    <p><strong>50%</strong><br>popusta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section id="tyres-search">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tyre-dimensions" data-bs-toggle="tab" data-bs-target="#tyre-dimensions-tab-pane" type="button" role="tab" aria-controls="tyre-dimensions-tab-pane" aria-selected="true">Izbor gume po dimenziji</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tyre-vehicle-tab" data-bs-toggle="tab" data-bs-target="#tyre-vehicle-tab-pane" type="button" role="tab" aria-controls="tyre-vehicle-tab-pane" aria-selected="false">Izbor gume po vozilu</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tyre-dimensions-tab-pane" role="tabpanel" aria-labelledby="tyre-dimensions-tab" tabindex="0">
                <div class="container-fluid">

                    <div class="row vehicles-row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col">
                                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/jeep.svg') }}" alt="jeep">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/truck.svg') }}" alt="truck">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/combi.svg') }}" alt="combi">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/motocycle.svg') }}" alt="motocycle">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/bicycle.svg') }}" alt="bicycle">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/tractor.svg') }}" alt="tractor">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 offset-md-2">
                            <img src="{{ asset('images/visuals/info.svg') }}" alt="info">
                            <span>Poručivanje 3 meseca unapred sa garantovano najboljim cenama</span>
                        </div>
                    </div>
                </div>

                <div class="row filter-row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="year-seasons">
                                    <p>God. Doba</p>
                                    <div class="single-season active">
                                        <img src="{{ asset('images/visuals/summer.svg') }}" alt="summer">
                                        <span>Leto</span>
                                    </div>
                                    <div class="single-season">
                                        <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                        <span>Zima</span>
                                    </div>
                                    <div class="single-season">
                                        <img src="{{ asset('images/visuals/all-seasons.svg') }}" alt="all seasons">
                                        <span>Sve sezone</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row tyres-selects">
                                    <div class="col-4">
                                        <p>Širina <span>pneumatika</span></p>
                                        <select name="tyre-width" id="tyre-width">
                                            <option value="201">201</option>
                                            <option value="202">202</option>
                                            <option value="203">203</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <p>Visina <span>pneumatika</span></p>
                                        <select name="tyre-height" id="tyre-height">
                                            <option value="55">55</option>
                                            <option value="56">56</option>
                                            <option value="57">57</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <p>Prečnik <span>pneumatika</span></p>
                                        <select name="tyre-diameter" id="tyre-diameter">
                                            <option value="R16">R16</option>
                                            <option value="R17">R17</option>
                                            <option value="R18">R18</option>
                                        </select>
                                    </div>
                                    <img src="{{ asset('images/visuals/tyre-filter.png') }}" alt="tyre filter">
                                    <button class="search-mob mob">Pretraga</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 common-used-dimensions">
                        <div class="row">
                            <p>Najčešće dimenzije</p>
                            <div class="col-md-4">
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                                <div class="single-dimension">
                                    <p>165/70 R14</p>
                                </div>
                            </div>
                            <button type="button">Pretraga</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="tyre-vehicle-tab-pane" role="tabpanel" aria-labelledby="tyre-vehicle-tab" tabindex="0">
                <div class="container-fluid">

                    <div class="row vehicles-row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col">
                                    <img src="{{ asset('images/visuals/car.svg') }}" alt="car">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/jeep.svg') }}" alt="jeep">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/truck.svg') }}" alt="truck">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/combi.svg') }}" alt="combi">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/motocycle.svg') }}" alt="motocycle">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/bicycle.svg') }}" alt="bicycle">
                                </div>
                                <div class="col">
                                    <img src="{{ asset('images/visuals/tractor.svg') }}" alt="tractor">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 offset-md-2">
                            <img src="{{ asset('images/visuals/info.svg') }}" alt="info">
                            <span>Poručivanje 3 meseca unapred sa garantovano najboljim cenama</span>
                        </div>
                    </div>
                </div>

                <div class="row filter-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="year-seasons">
                                    <p>God. Doba</p>
                                    <div class="single-season active">
                                        <img src="{{ asset('images/visuals/summer.svg') }}" alt="summer">
                                        <span>Leto</span>
                                    </div>
                                    <div class="single-season">
                                        <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                        <span>Zima</span>
                                    </div>
                                    <div class="single-season">
                                        <img src="{{ asset('images/visuals/all-seasons.svg') }}" alt="all seasons">
                                        <span>Sve sezone</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-11">
                                <div class="row">
                                    @foreach($logos as $logo)
                                        <img src="{{ $logo }}" alt="">
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Model</p>
                                        <select name="model" id="model"></select>
                                    </div>
                                    <div class="col-md-3">
                                        <p>Tip</p>
                                        <select name="type" id="type"></select>
                                    </div>
                                    <div class="col-md-3">
                                        <p>Godište</p>
                                        <select name="year" id="year"></select>
                                    </div>
                                    <div class="col-md-3">
                                        <p>Veličina</p>
                                        <select name="size" id="size"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="search-button">Pretraga</button>
                                </div>
                            </div>
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

    <section id="featured-products">
        <div class="featured-products-header">
            <h2>Izdvojeni proizvodi</h2>
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

    <section id="homepage-banners">
        <div class="row">
            <div class="col-md-6">
                <div class="summer-tyre-wrapper half-width-banner">
                    <div class="content">
                        <img src="{{ asset('images/visuals/pirelli.svg') }}" alt="pirelli">
                        <h3>Letnja guma</h3>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiu</p>
                        <a href="#">Pogledaj ponudu</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="oil-wrapper half-width-banner">
                    <div class="content">
                        <img src="{{ asset('images/visuals/shell.svg') }}" alt="pirelli">
                        <h3>Motorno ulje</h3>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiu</p>
                        <a href="#">Pogledaj ponudu</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="delivery full-width-banner">
                    <div class="row">
                        <div class="col-md-4">
                            <h2>Brza i <strong>besplatna</strong> isporuka</h2>
                            <p>Na preko 100 mesta za montažu (ili  uz preuzimanje u Delmax i OMV poslovnicama) širom Srbije</p>
                        </div>
                        <div class="col-md-4">
                            <div class="row fwb-badges">
                                <div class="col-3 offset-1">
                                    <img src="{{ asset('images/visuals/security.svg') }}" alt="security">
                                    <p>Sigurno</p>
                                </div>
                                <div class="col-3">
                                    <img src="{{ asset('images/visuals/reliable.svg') }}" alt="reliable">
                                    <p>Pouzdano</p>
                                </div>
                                <div class="col-3">
                                    <img src="{{ asset('images/visuals/quality.svg') }}" alt="quality">
                                    <p>Kvalitetno</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src="{{ asset('images/visuals/delivery-man.png') }}" class="delivery-man" alt="delivery man">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="special-offer">
        <div class="special-offer-wrapper">
            <h5>Gumamax</h5>
            <h2>Posebna ponuda <strong>bbs felni</strong></h2>
            <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
            <div class="row">
                <div class="col-md-3 col-6"><img src="{{ asset('images/visuals/special-wheel1.png') }}" alt="special wheel"></div>
                <div class="col-md-3 col-6"><img src="{{ asset('images/visuals/special-wheel2.png') }}" alt="special wheel"></div>
                <div class="col-md-3 col-6"><img src="{{ asset('images/visuals/special-wheel3.png') }}" alt="special wheel"></div>
                <div class="col-md-3 col-6"><img src="{{ asset('images/visuals/special-wheel4.png') }}" alt="special wheel"></div>
            </div>
            <a href="#">Saznaj više</a>
        </div>
    </section>

    <section id="partners">
        <h5>Gumamax</h5>
        <h2>Partner kojim <br><strong>možete verovati</strong></h2>
        <p>Upoznajte se sa procesom poručivanja guma (i ostalih proizvoda), preuzimanja, montiranja odabira vulkanizera i ostalih željenih preferenci.</p>

        <div class="partners-logos">
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

@endsection

@section('scriptsBottom')
    <script>
        let heroSlider = new Swiper(".hero-swiper", {
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        jQuery.ajax({
            url: window.location.origin + '/api/products/dimensions/selected/bundle?total=0&current_page=1&last_page=0&per_page=10&from=0&to=0&next_page=0&prev_page=0&order=price_with_tax%7Casc&vehicle_category=Putni%C4%8Dko&search_method=byDimension&searchQuery=&width=130&ratio=90&diameter=10&vehicle_brand=&vehicle_model=&vehicle_engine=&vehicle_years=&vehicle_tire_dimension=&manufacturers=&speed_indexes=&seasons=&source=cookie&page=1', method: 'get', headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }, success: function (result) {
                console.log(result);
            }
        });
    </script>
@endsection
