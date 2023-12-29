@extends('layouts.app')

@section('content')

    <section id="order-details">
        <div class="row">
            <div class="col-md-7" id="checkout-content">
                <div class="order-details-inner">
                    <h2>Podaci za naručivanje</h2>
                    <div class="divider"></div>
                    <p>Odaberite iz ponuđenih opcija radi isporuke vaše porudžbine</p>

                    <form action="#" method="POST">
                        @csrf
                        <div class="custom-fg">
                            <label for="city">Izbor mesta isporuke</label>
                            <select name="city" id="city">

                            </select>
                        </div>
                        <div class="checkboxes-delivery">
                            <div class="custom-fg-cb">
                                <input type="checkbox" name="delivery_place" id="delivery_place1">
                                <label for="delivery_place1">Isporuka sa mogućnošću montaže</label>
                            </div>
                            <div class="custom-fg-cb">
                                <input type="checkbox" name="delivery_place" id="delivery_place2">
                                <label for="delivery_place2">Isporuka u prodajnom mestu</label>
                            </div>
                            <div class="custom-fg-cb">
                                <input type="checkbox" name="delivery_place" id="delivery_place3">
                                <label for="delivery_place3">Isporuka na željenu adresu</label>
                            </div>
                        </div>

                        <div class="custom-fg">
                            <label for="city">Način plaćanja</label>
                            <select name="payment_method" id="payment_method">
                                <option value="card">Karticom na sajtu</option>
                                <option value="on-spot">Pouzećem</option>
                            </select>
                        </div>

                        <div class="delivery-details">

                            <div class="delivery-1">
                                <h2>Željeni termin</h2>
                                <div class="divider"></div>
                                <p>Odaberite preferirani termin za montažu</p>
                                <div class="custom-fg">
                                    <label for="day">Dan</label>
                                    <select name="day" id="day"></select>
                                </div>
                                <div class="custom-fg">
                                    <label for="time">Vreme</label>
                                    <select name="time" id="time"></select>
                                </div>
                            </div>

                            <div class="delivery-3">
                                <h2>Kontakt i adresa</h2>
                                <div class="divider"></div>
                                <div class="delivery-3-fields">
                                    <div class="custom-fg">
                                        <label for="first_name">Ime</label>
                                        <input type="text" name="first_name" id="first_name">
                                    </div>
                                    <div class="custom-fg">
                                        <label for="last_name">Prezime</label>
                                        <input type="text" name="last_name" id="last_name">
                                    </div>
                                    <div class="custom-fg">
                                        <label for="city_d1">Grad</label>
                                        <input type="text" name="city_d1" id="city_d1">
                                    </div>
                                    <div class="custom-fg">
                                        <label for="address">Adresa</label>
                                        <input type="text" name="address" id="address">
                                    </div>
                                    <div class="custom-fg">
                                        <label for="zip">Poštanski broj</label>
                                        <input type="text" name="zip" id="zip">
                                    </div>
                                    <div class="custom-fg">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email">
                                    </div>
                                    <div class="custom-fg">
                                        <label for="tel">Broj telefona</label>
                                        <input type="tel" name="tel" id="tel">
                                    </div>
                                </div>
                                <button>Sačuvaj</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <div class="order-summary">
                    <h3>Vaša porudžbina</h3>
                    <div class="divider"></div>
                    <div class="order-products">
                        @if($cart == null || $cart["total_qty"] == 0)
                            <p>Korpa je prazna</p>
                        @else
                        @foreach($cart["items"] as $item)
                        <div class="single-order-product">
                            <img src="{{ $item["item"]["image_url"] }}" alt="">
                            <p class="product-name">{{$item["item"]["additional_description"]}}</p>
                            <p class="product-price">{{ number_format($item["item"]["price_with_tax"], 2, ",", ".") }} RSD</p>
                            <i style="cursor: pointer;" class="fa-solid fa-xmark" onclick="rmCartItem(this,{{json_encode($item)}})"></i>
                        </div>
                        @endforeach
                        @endif
                        <!--<div class="single-order-product">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="">
                            <p class="product-name">Tigar winter 195/65 R15</p>
                            <p class="product-price">4.660 RSD</p>
                            <i class="fa-solid fa-xmark"></i>
                        </div>-->
                    </div>
                    <div class="order-prices">
                        <div class="order-subtotal">
                            <h4>Cena</h4>
                            <p>{{ number_format($cart["total_amount_with_tax"], 2, ",", ".") }} RSD</p>
                        </div>
                        <div class="order-shipping">
                            <h4>Poštarina</h4>
                            <p>@if($cart["shipping_amount_with_tax"] == 0) Besplatno @else {{ number_format($cart["shipping_amount_with_tax"], 2, ",", ".") }} RSD @endif</p>
                        </div>
                        <div class="order-total">
                            <h4>Ukupno</h4>
                            <p>{{ number_format($cart["total_amount_with_tax"] + $cart["shipping_amount_with_tax"], 2, ",", ".") }} RSD</p>
                        </div>
                    </div>
                    <button id="orderBtn">Poruči</button>
                </div>
            </div>
        </div>
    </section>

    <section id="choose-partner">
        <h2>Odaberite partnera</h2>
        <div class="divider"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="single-choose-partner">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="">
                        </div>
                        <div class="col-md-8">
                            <h5>Pneumatik</h5>
                            <p><i class="fa-solid fa-gear"></i> servis sa mogućnošću montaže</p>
                            <p><i class="fa-solid fa-location-dot"></i> Niš, Dragoslava jovanovića 8b</p>
                        </div>
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/delmax-logo.png') }}" alt="">
                            <h6>DELMAX PRODAVNICA</h6>
                        </div>
                    </div>
                </div>
                <div class="single-choose-partner">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="">
                        </div>
                        <div class="col-md-8">
                            <h5>Pneumatik</h5>
                            <p><i class="fa-solid fa-gear"></i> servis sa mogućnošću montaže</p>
                            <p><i class="fa-solid fa-location-dot"></i> Niš, Dragoslava jovanovića 8b</p>
                        </div>
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/delmax-logo.png') }}" alt="">
                            <h6>DELMAX PRODAVNICA</h6>
                        </div>
                    </div>
                </div>
                <div class="single-choose-partner">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="">
                        </div>
                        <div class="col-md-8">
                            <h5>Pneumatik</h5>
                            <p><i class="fa-solid fa-gear"></i> servis sa mogućnošću montaže</p>
                            <p><i class="fa-solid fa-location-dot"></i> Niš, Dragoslava jovanovića 8b</p>
                        </div>
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/delmax-logo.png') }}" alt="">
                            <h6>DELMAX PRODAVNICA</h6>
                        </div>
                    </div>
                </div>
                <div class="single-choose-partner">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/product-image.png') }}" alt="">
                        </div>
                        <div class="col-md-8">
                            <h5>Pneumatik</h5>
                            <p><i class="fa-solid fa-gear"></i> servis sa mogućnošću montaže</p>
                            <p><i class="fa-solid fa-location-dot"></i> Niš, Dragoslava jovanovića 8b</p>
                        </div>
                        <div class="col-md-2">
                            <img src="{{ asset('images/visuals/delmax-logo.png') }}" alt="">
                            <h6>DELMAX PRODAVNICA</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="partners-map"></div>
            </div>
        </div>
    </section>

@endsection

@yield('scriptsBottom')
<script src="{{ asset("js/vendor/jquery.js") }}"></script>
<script src="{{ asset("js/checkout.js") }}"></script>
