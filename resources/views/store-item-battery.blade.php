@for ($cnt = 0; $cnt < $nItems; $cnt++)
<div class="single-shop-product">
    <div class="row">
        <div class="col-md-4">
            <div class="single-shop-product-left">
                @if(sizeof(explode("/",$products[$cnt]["cat_no"])) > 1 && explode("/",$products[$cnt]["cat_no"])[1] == "2021") <img src="{{ asset('images/visuals/dot-tag.png') }}" class="dot-img" alt="dot"> @endif
                <img style="cursor: pointer" src="{{ $products[$cnt]["image_url"] }}" alt="product image" class="featured-image" onclick="itemDetails( {{ $cnt }} )">
            </div>
        </div>
        <div class="col-md-8">
            <div class="top-shop-product">
                <h3 style="cursor: pointer" class="product-title" onclick="itemDetails( {{ $cnt }} )">{{ $products[$cnt]["manufacturer"] }} {{ $products[$cnt]["additional_description"] }}</h3>
                <img src="{{ asset('images/visuals/compare.svg') }}" alt="compare" class="compare-icon ripple" onclick="selectForCompare(this,{{ $cnt }})">
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
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
                <div class="price-details">
                    <h5 class="single-shop-price"> {{ number_format($products[$cnt]["price_with_tax"], 2, ",", ".") }} RSD</h5>
                    <a href="/proizvod/{{ $products[$cnt]["product_id"] }}">Detalji o proizvodu</a>
                </div>
                <p>Isporuka od tri do sedam radnih dana</p>
            </div>
        </div>
    </div>
    <div class="row bottom-single-product">
        <div class="col-md-9">
            <div class="quantity-area">
                <span class="minus ripple">-</span>
                <span class="qty">1</span>
                <span class="plus ripple">+</span>
            </div>
            <button class="add-to-cart" onclick="addToCart( {{ $cnt }}, this)">Dodaj u korpu</button>
        </div>
    </div>
</div>
@endfor
