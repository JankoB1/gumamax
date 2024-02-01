<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @yield('styles')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('scriptsTop')

</head>
<body>
    <div id="app">
        <header class="{{ Request::path() == '/'? 'homepage-header': '' }}">
            <nav class="navbar navbar-expand-md secondary-nav">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/visuals/logo.svg') }}" alt="gumamax logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <li class="location-li">
                                <a href="#">
                                    <img src="{{ asset('images/visuals/location-pin.svg') }}" alt="location pin">
                                    <span>Lokacije<br>Partnera</span>
                                </a>
                            </li>
                            <li class="phone-li">
                                <a href="#">
                                    <img src="{{ asset('images/visuals/phone.svg') }}" alt="phone">
                                    <span><strong>0800/111-808</strong><br>(Pon - Sub: 08-16h)</span>
                                </a>
                            </li>
                            <li class="cart-li">
                                <a id="gotoCart" href="/korpa">
                                    <img src="{{ asset('images/visuals/cart.svg') }}" alt="cart">
                                    <span class="cart-num">
                                        @if(session()->has("cart")) {{ session()->get("cart")["total_qty"] }} @else 0 @endif
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <img src="{{ asset('images/visuals/account.svg') }}" alt="account">
                                </a>
                            </li>
                            <li>
                                @if(Auth::guest())
                                    <a href="/login" class="sign-in">Prijavi se</a>
                                @else
                                    <a href="/logout" class="sign-in">Odjavi se</a>
                                @endif
                            </li>
                        </ul>

                    </div>
                </div>
            </nav>

            <nav class="navbar navbar-expand-md primary-nav">
                <div class="container-fluid">

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="navbar-nav me-auto">
                            <li><a href="/gume">Gume</a></li>
                            <li><a href="/ratkapne">Ratkapne</a></li>
                            <li><a href="/ulja">Ulja</a></li>
                            <li><a href="/akumulatori">Akumulatori</a></li>
                            <li><a href="#">Auto oprema</a></li>
                            <li><a href="#">O nama</a></li>
                            <li><a href="#" class="contact-us">Kontakt</a></li>
                        </ul>

                    </div>
                </div>
            </nav>
        </header>

        <header id="mobile-header">
            <div class="mobile-header-top">
                <div class="burger-mobile">
                    <div class="burger-line"></div>
                    <div class="burger-line"></div>
                    <div class="burger-line"></div>
                </div>
                <div class="logo-mobile">
                    <a href="{{ route('show-homepage') }}">
                        <img src="{{ asset('images/visuals/logo.svg') }}" alt="gumamax logo" width="170">
                    </a>
                </div>
                <ul class="navbar-nav">
                    <li class="location-li">
                        <a href="#">
                            <img src="{{ asset('images/visuals/location-pin.svg') }}" alt="location pin">
                        </a>
                    </li>
                    <li class="cart-li">
                        <a id="gotoCart" href="{{ route('show-make-order') }}">
                            <img src="{{ asset('images/visuals/cart.svg') }}" alt="cart">
                            <span class="cart-num">
                                        @if(session()->has("cart")) {{ session()->get("cart")["total_qty"] }} @else 0 @endif
                                    </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="{{ asset('images/visuals/account.svg') }}" alt="account">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mobile-header-overlay"></div>
            <div class="mobile-wide-menu">
                <ul>
                    <li><a href="">Gume</a></li>
                    <li><a href="">Ratkapne</a></li>
                    <li><a href="">Ulja</a></li>
                    <li><a href="">Akumulatori</a></li>
                    <li><a href="">Auto oprema</a></li>
                    <li><a href="">O nama</a></li>
                    <li><a href="">Kontakt</a></li>
                </ul>
                @if(Auth::guest())
                    <a href="/login" class="sign-in">Prijavi se</a>
                @else
                    <a href="/logout" class="sign-in">Odjavi se</a>
                @endif
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <footer>
        <div class="nl-container">
            <div class="row">
                <div class="col-md-6">
                    <h6>Pretplati se na naš newsletter</h6>
                    <p>Pretplatite se na našu mailing listu i primajte redovne obavesti</p>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
        <div class="footer-middle">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('images/visuals/logo.svg') }}" alt="logo">
                    <p>Servis za montažu guma ili prodavnica koja se nalazi u Vašoj opštini je odlučila da pristupi grupi svojih kolega iz drugih opština na teritoriji Srbije i da zajedno sa veleprodajom DELMAX oformi prodavnicu guma na internetu koja nosi naziv Gumamax.</p>
                    <div class="socials">
                        <img src="{{ asset('images/visuals/socials.svg') }}" alt="socials">
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <h6>Kompanija</h6>
                    <div class="divider-footer"></div>
                    <ul>
                        <li><a href="#">O nama</a></li>
                        <li><a href="#">Kontakt</a></li>
                        <li><a href="#">Prijava</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-6">
                    <h6>Info</h6>
                    <div class="divider-footer"></div>
                    <ul>
                        <li><a href="#">Korisne informacije</a></li>
                        <li><a href="#">EU nalepnica</a></li>
                        <li><a href="#">Uslovi korišćenja</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Podrška</h6>
                    <div class="divider-footer"></div>
                    <ul>
                        <li><a href="#">Korisnički servis</a></li>
                        <li><a href="#">Najčešća pitanja</a></li>
                        <li><a href="#">Reklamiranje</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-3">
                    <p>Copyright Gumamax 2023</p>
                </div>
                <div class="col-md-9">
                    <p>Metode plaćanja</p>
                    <div class="credit-cards">

                    </div>
                </div>
            </div>
        </div>
    </footer>

@yield('scriptsBottom')
<script src="{{ asset("js/layout.js") }}"></script>
<script>
    init()
</script>
</body>
</html>
