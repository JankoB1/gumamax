@extends('admin.master')

@section('content')

    <div class="col-sm-12 infobox-container" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <!-- #section:pages/dashboard.infobox -->
        <a href="{!! url(route('admin.callback-request.index', ['status'=>'opened'])) !!}">
            <div id="callback-request-count" class="infobox infobox-green">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-phone"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number">@{{count}}</span>
                    <div class="infobox-content">Zahteva za poziv</div>
                </div>

                <!-- #section:pages/dashboard.infobox.stat -->
                <!--
                <div class="stat stat-success">8%</div>
                -->
                <!-- /section:pages/dashboard.infobox.stat -->
            </div>
        </a>

        <a href="{!! url(route('admin.contact-form-message.index-status', ['status'=>'opened'])) !!}">
            <div id="contact-form-message" class="infobox infobox-blue">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-comment"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number">@{{ count }}</span>
                    <div class="infobox-content">Neodgovorenih poruka</div>
                </div>
            <!--
                <div class="badge badge-success">
                    +32%
                    <i class="ace-icon fa fa-arrow-up"></i>
                </div>
              -->
            </div>
        </a>

        <a href="{!! url(route('admin.orders.index.status', ['status'=>'opened'])) !!}">
            <div id="orders-count" class="infobox infobox-pink">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-shopping-cart"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number">@{{ count }}</span>
                    <div class="infobox-content">Današnjih porudžbina</div>
                </div>
               <!-- <div class="stat stat-important">4%</div> -->
            </div>
        </a>

        <div id='product-count' class="infobox infobox-wood">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-cogs"></i>
            </div>

            <div class="infobox-data">
                <span class="infobox-data-number">@{{ count }}</span>
                <div class="infobox-content">Artikala u ponudi</div>
            </div>

        </div>

        <div id="merchant-health-1" v-bind:class="[health.error  ? 'infobox infobox-red' : 'infobox infobox-blue']">
            <div class="infobox-icon">
                <i class="ace-icon fa fa-server"></i>
            </div>

            <div  class="infobox-data">
                <span class="infobox-data-number">@{{health.time_elapsed}}</span>
                <div v-if="health.error" class="infobox-content">@{{ health.error }}</div>
                <div v-else class="infobox-content">ERP response</div>

            </div>

        </div>
    </div>

@endsection
@section('js')
    <script>
        var merchantHealthUrl           = "{!! url(route('admin.api.merchant-api.health', ['merchantId'=>'8080'])) !!}";
        var callbackRequestCountUrl     = "{!! url(route('admin.api.count.callback-request.status', ['status'=>'opened'])) !!}";
        var productCountUrl             = "{!! url(route('api.count.products')) !!}";
        var ordersCountUrl              = "{!! url(route('admin.api.count.orders.period',['period'=>'today'])) !!}";
        var contactFormMessagesCountUrl ="{!! url(route('admin.api.count.contact-form-message.status', ['status'=>'opened'])) !!}";
    </script>
    <script src="{{ mix('js/admin/merchant-health.js') }}"></script>

@endsection


