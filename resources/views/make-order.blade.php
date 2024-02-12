@extends('layouts.app')

@section('scriptsTop')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
@endsection

@section('content')

    <section id="order-details">
        <div class="row">
            <div class="col-md-7" id="checkout-content">
                <div class="order-details-inner">
                    <h2>Podaci za naručivanje</h2>
                    <div class="divider"></div>
                    <p>Odaberite iz ponuđenih opcija radi isporuke vaše porudžbine</p>

                    <form action="#" onsubmit="return false;">
                        @csrf
                        <div class="custom-fg" style="position: relative;">
                            <label for="city">Izbor mesta isporuke</label>
                            <input name="city" id="search_partners">
                            <div class="search-content">

                            </div>
                        </div>
                        <div class="checkboxes-delivery">
                            <div class="custom-fg-cb">
                                <input type="checkbox" name="delivery_place" id="delivery_place1" >
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
                            <select name="payment_method" id="payment_method" >
                                <option value="5">Karticom na sajtu</option>
                                <option value="4">Pouzećem</option>
                            </select>
                        </div>

                        <div class="delivery-details">

                            <div class="delivery-1 hidden">
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

                            <div class="delivery-3 hidden">
                                <h2>Kontakt i adresa</h2>
                                <div class="divider"></div>
                                <div class="delivery-3-fields">
                                    <div class="custom-fg">
                                        <label for="first_name">Ime primaoca</label>
                                        <input type="text" name="first_name" id="first_name" required autocomplete="first_name" @if($cart["shipping_recipient"] != null) value="{{  explode(' ', $cart["shipping_recipient"])[0] }}" @endif>
                                    </div>
                                    <div class="custom-fg">
                                        <label for="last_name">Prezime primaoca</label>
                                        <input type="text" name="last_name" id="last_name" required autocomplete="family_name" @if($cart["shipping_recipient"] != null) value="{{  explode(' ', $cart[ "shipping_recipient"])[1] }}" @endif>
                                    </div>
                                    <div class="custom-fg">
                                        <label for="city_d1">Mesto</label>
                                        <select type="text" name="city_d1" id="city_d1" required  @if($cart["shipping_recipient"] != null) value="{{  $cart[ "shipping_postal_code"] }}" @endif>
                                            @foreach($srbCities as $city)
                                                <option value="{{ $city->postal_code }}">{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="custom-fg">
                                        <label for="address">Adresa</label>
                                        <input type="text" name="address" id="address" required autocomplete="address" @if($cart["shipping_recipient"] != null) value="{{  $cart[ "shipping_address"] }}" @endif>
                                    </div>
                                    <div class="custom-fg">
                                        <label for="zip">Poštanski broj</label>
                                        <input type="text" name="zip" id="zip" required autocomplete="zip" @if($cart["shipping_recipient"] != null) value="{{  $cart[ "shipping_postal_code"] }}" @endif>
                                    </div>
                                    <div class="custom-fg">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" required autocomplete="email" @if($cart["shipping_recipient"] != null) value="{{  $cart[ "shipping_email"] }}" @endif>
                                    </div>
                                    <div class="custom-fg">
                                        <label for="tel">Broj telefona</label>
                                        <input type="tel" name="tel" id="tel" required autocomplete="tel" @if($cart["shipping_recipient"] != null) value="{{  $cart[ "shipping_phone"] }}" @endif>
                                    </div>
                                </div>
                                <button id="saveShippingBtn">Sačuvaj</button>
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
                            <p class="product-name"><span>{{$item["item"]["additional_description"]}}</span><span>{{ number_format($item["item"]["price_with_tax"], 2, ",", ".") }} RSD</span></p>
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

@section('scriptsBottom')
    <script src="{{ asset("js/vendor/jquery.js") }}"></script>
    <script src="{{ asset("js/checkout.js") }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbu6jooaUw8303AME9uzQsU95ol34P9OY&&v=weekly"
        defer
    ></script>
    <script src="{{ asset('js/all-partners-cart.js') }}" type="text/javascript"></script>
@endsection
