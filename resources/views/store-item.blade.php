@for ($cnt = 0; $cnt < $nItems; $cnt++)
<div class="single-shop-product">
    <div class="row">
        <div class="col-md-4">
            <div class="single-shop-product-left">
                <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-img" alt="dot">
                <img src="{{ $products[$cnt]["image_url"] }}" alt="product image" class="featured-image">
            </div>
        </div>
        <div class="col-md-8">
            <div class="top-shop-product">
                <h3 class="product-title">{{ $products[$cnt]["manufacturer"] }} {{ $products[$cnt]["additional_description"] }}</h3>
                <img src="{{ asset('images/visuals/compare.svg') }}" alt="compare" class="compare">
                <div class="stock">
                    @if($products[$cnt]["stock_status"] != 0)
                        <div class="stock-status-in"></div>
                        <p class="stock-status-text">Na stanju <strong>({{ (int) $products[$cnt]["stock_status_qty"] }} kom.)</strong></p>
                    @else
                        <div class="stock-status-out"></div>
                        <p class="stock-status-text">Nije na stanju</p>
                    @endif
                </div>
            </div>
            <div class="bottom-shop-product">
                <div class="row">
                    <div class="col-md-2">
                        @switch($products[$cnt]["vehicle_category"])
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
                        @switch($products[$cnt]["season"])
                            @case("Zimska")
                                <img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">
                                <p>Zimska</p>
                                @break
                            @case("Letnja")
                                <img src="{{ asset('images/visuals/summer.svg') }}" alt="winter">
                                <p>Letnja</p>
                                @break
                            @default
                                <!--<img src="{{ asset('images/visuals/winter.svg') }}" alt="winter">-->
                                <!--<p>Drugo</p>-->
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="price-details">
                    <h5 class="single-shop-price"> {{ number_format($products[$cnt]["price_with_tax"], 2, ",", ".") }} RSD</h5>
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
@endfor