<ul class="nav nav-list">
    <li>
        <a href="{{URL::to('/admin')}}">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
        </a>
        <b class="arrow"></b>
    </li>
    <li>
        <a href="{{URL::to('admin/signup-partners')}}" class="dropdown-toggle">
            <i class="menu-icon fa fa-thumbs-up"></i>
            <span class="menu-text"> Prijave partnera </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>
        <ul class="submenu">
            <li><a href="{{URL::to('admin/signup-partners')}}"><i class="menu-icon fa fa-caret-right"></i>Na čekanju</a><b class="arrow"></b></li>
            <li><a href="{{URL::to('admin/signup-partners-rejected')}}"><i class="menu-icon fa fa-caret-right"></i>Odbijene</a><b class="arrow"></b></li>
        </ul>

    </li>


    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-star-o"></i>
            <span class="menu-text"> Ocene </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>

        <ul class="submenu">
            <li>
                <a href="{{URL::to('admin/reviews-partners')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Ocene partnera
                </a>
                <b class="arrow"></b>
            </li>

            <li>
                <a href="{{URL::to('admin/reviews-products')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Ocene prozivoda
                </a>
                <b class="arrow"></b>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{URL::to('admin/partners')}}">
            <i class="menu-icon fa fa-user"></i>
            <span class="menu-text"> Partneri </span>
        </a>
    </li>
    <li>
        <a href="{{URL::to('admin/users')}}">
            <i class="menu-icon fa fa-users"></i>
            <span class="menu-text"> Korisnici </span>
        </a>
    </li>
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-qrcode"></i>
            <span class="menu-text"> Proizvodi </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>

        <ul class="submenu">
            <li>
                <a href="{{URL::to('admin/products/erp-sync')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    ERP sinhronizacija
                </a>
            </li>

            <li>
                <a href="{{route('elastic-index')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Elastic
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/products')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Proizvodi
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/action_price')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Akcijske cene
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/discounts')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Popusti
                </a>
            </li>

        </ul>
    </li>

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="icon-star-empty"></i>
            <span class="menu-text"> Porudžbine </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>

        <ul class="submenu">
            <li>
                <a href="{{URL::to('admin/carts')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Otvorene korpe
                </a>
            </li>

            <li>
                <a href="{{URL::to('/admin/orders')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Narudžbenice
                </a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="icon-road"></i>
            <span class="menu-text"> Transport </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{URL::to('admin/transport')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    <span class="menu-text"> Transport </span>
                </a>
            </li>
            <li>
                <a href="{{URL::to('admin/shipment')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    <span class="menu-text"> Period isporuke </span>
                </a>
            </li>


        </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="icon-credit-card"></i>
            <span class="menu-text"> e-Commerce </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>

        <ul class="submenu">
            <li>
                <a href="{{URL::to('admin/e-commerce/form_sms')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    SMS
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/e-commerce/form_dms')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    DMS
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/e-commerce/transactions')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Transactions
                </a>
            </li>
            <li>
                <a href="{{URL::to('admin/e-commerce/batch')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Batch list
                </a>
            </li>
            <li>
                <a href="{{URL::to('admin/e-commerce/error')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Errors
                </a>
            </li>

        </ul>
    </li>

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="icon-question"></i>
            <span class="menu-text"> Razno </span>

            <b class="arrow fa fa-angle-down"></b>
        </a>

        <ul class="submenu">
            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-caret-right"></i>
                    <span class="menu-text"> FAQs </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{URL::to('/admin/faq-group')}}">
                            Grupe
                        </a>
                    </li>
                    <li>
                        <a href="{{URL::to('/admin/faq')}}">
                            Pitanja i odgovori
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{URL::to('admin/carousel')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Carousel
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/partners/turnover-summary')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Promet
                </a>
            </li>

            <li>
                <a href="{{URL::to('admin/cities')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Mesta
                </a>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{URL::to('/')}}">
            <i class="icon-arrow-left"></i>
            <span class="menu-text"> Povratak na sajt </span>
        </a>
    </li>

</ul><!-- /.nav-list -->
