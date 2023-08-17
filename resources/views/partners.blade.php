@extends('layouts.app')

@section('content')

    <section id="partners-search">
        <h2>Pronađite najbliži servis</h2>
        <div class="divider"></div>

        <div class="search-box">
            <input type="text">
            <button>Pretraga <i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <div class="checkboxes">
            <div class="form-group">
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
                <label for="delivery">Preuzimanje</label>
            </div>
            <div class="form-group">
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
                <label for="delivery_montage">Preuzimanje + Montaža</label>
            </div>
        </div>

        <div id="partners-map">

        </div>

        <div class="partners-list">
            <div class="single-partner-in-list">
                <div class="row">
                    <div class="col-md-1">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                    </div>
                    <div class="col-md-3">
                        <h4>Pneumatik</h4>
                        <div>
                            <span>
                                <i class="fa-solid fa-location-pin"></i>
                                Leskovac
                            </span>
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                Pon - Sub 09:16
                            </span>
                        </div>
                        <span class="tag">Preuzimanje</span>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <p><strong>Tel:</strong> 032/300-600</p>
                        <p><strong>Email:</strong> office@test.rs</p>
                        <p><strong>Adresa:</strong> Autoput Beograd - Novi Sad b. b., 11000 Beograd</p>
                    </div>
                    <div class="col-md-2">
                        <a href="#">Više informacija</a>
                        <button>Pokaži na mapi</button>
                    </div>
                </div>
            </div>

            <div class="single-partner-in-list">
                <div class="row">
                    <div class="col-md-1">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                    </div>
                    <div class="col-md-3">
                        <h4>Pneumatik</h4>
                        <div>
                            <span>
                                <i class="fa-solid fa-location-pin"></i>
                                Leskovac
                            </span>
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                Pon - Sub 09:16
                            </span>
                        </div>
                        <span class="tag">Preuzimanje</span>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <p><strong>Tel:</strong> 032/300-600</p>
                        <p><strong>Email:</strong> office@test.rs</p>
                        <p><strong>Adresa:</strong> Autoput Beograd - Novi Sad b. b., 11000 Beograd</p>
                    </div>
                    <div class="col-md-2">
                        <a href="#">Više informacija</a>
                        <button>Pokaži na mapi</button>
                    </div>
                </div>
            </div>

            <div class="single-partner-in-list">
                <div class="row">
                    <div class="col-md-1">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                    </div>
                    <div class="col-md-3">
                        <h4>Pneumatik</h4>
                        <div>
                            <span>
                                <i class="fa-solid fa-location-pin"></i>
                                Leskovac
                            </span>
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                Pon - Sub 09:16
                            </span>
                        </div>
                        <span class="tag">Preuzimanje</span>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <p><strong>Tel:</strong> 032/300-600</p>
                        <p><strong>Email:</strong> office@test.rs</p>
                        <p><strong>Adresa:</strong> Autoput Beograd - Novi Sad b. b., 11000 Beograd</p>
                    </div>
                    <div class="col-md-2">
                        <a href="#">Više informacija</a>
                        <button>Pokaži na mapi</button>
                    </div>
                </div>
            </div>

            <div class="single-partner-in-list">
                <div class="row">
                    <div class="col-md-1">
                        <img src="{{ asset('images/visuals/product-image.png') }}" alt="product image">
                    </div>
                    <div class="col-md-3">
                        <h4>Pneumatik</h4>
                        <div>
                            <span>
                                <i class="fa-solid fa-location-pin"></i>
                                Leskovac
                            </span>
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                Pon - Sub 09:16
                            </span>
                        </div>
                        <span class="tag">Preuzimanje</span>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <p><strong>Tel:</strong> 032/300-600</p>
                        <p><strong>Email:</strong> office@test.rs</p>
                        <p><strong>Adresa:</strong> Autoput Beograd - Novi Sad b. b., 11000 Beograd</p>
                    </div>
                    <div class="col-md-2">
                        <a href="#">Više informacija</a>
                        <button>Pokaži na mapi</button>
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
