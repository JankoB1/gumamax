@extends('admin.master')
@section('custom-css')

@endsection
@section('content')
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="clearfix">
                        <div id="user-profile-2" class="user-profile">
                            <div class="tabbable">
                                <ul class="nav nav-tabs padding-18">
                                    <li class="active"><a data-toggle="tab" href="#company"><i class="blue ace-icon fa fa-industry bigger-120"></i>{!!_('Company')!!}</a></li>
                                    <li><a data-toggle="tab" href="#contacts"><i class="black ace-icon fa fa-user-secret bigger-120"></i>{!! _('Contacts') !!}</a></li>
                                    <li><a data-toggle="tab" href="#resources"><i class="blue ace-icon fa fa-users bigger-120"></i>{!! _('Resources') !!}</a></li>
                                    <li><a data-toggle="tab" href="#pictures"><i class="pink ace-icon fa fa-picture-o bigger-120"></i>{!! _('Pictures') !!}</a></li>
                                    <li><a data-toggle="tab" href="#payment"><i class="green ace-icon fa fa-money bigger-120"></i>{!! _('Payment methods') !!}</a></li>
                                    <li><a data-toggle="tab" href="#price-list"><i class="orange ace-icon fa fa-list-ul bigger-120"></i>{!! _('Price list') !!}</a></li>
                                    <li><a data-toggle="tab" href="#e-commerce"><i class="orange ace-icon fa fa-rss bigger-120"></i>{!! _('e-commerce') !!}</a></li>
                                    <li><a data-toggle="tab" href="#other"><i class="orange ace-icon fa fa-rss bigger-120"></i>{!! _('Other') !!}</a></li>
                                </ul>
                                <div class="tab-content no-border padding-24">
                                    <div id="company" class="tab-pane in active">
                                        @include('admin.partners.partials.basic')
                                    </div><!-- /#company -->
                                    <div id="contacts" class="tab-pane">
                                        @include('admin.partners.partials.contacts')
                                    </div><!-- /#contacts -->
                                    <div id="resources" class="tab-pane">
                                        <div id="resources-content"></div>
                                     {{-- @include('admin.partners.partials.resources')--}}
                                    </div><!-- /#resources -->
                                    <div id="pictures" class="tab-pane">
                                        @include('admin.partners.partials.pics')
                                    </div><!-- /#pictures -->
                                    <div id="payment" class="tab-pane">
                                        @include('admin.partners.partials.payment')
                                    </div><!-- /#info -->
                                    <div id="price-list" class="tab-pane">
                                        @include('admin.partners.partials.price-list')
                                    </div><!-- /#price-list -->
                                    <div id="e-commerce" class="tab-pane">
                                    </div><!-- /#e-commerce -->
                                    <div id="other" class="tab-pane">
                                        @include('admin.partners.partials.other')
                                    </div><!-- /#other -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection

@section('page-plugin-js')
    <script>
        var initialZoom = 15;
        var initialLocation = {
            lat: parseFloat("{!! number_format($partner->latitude,12,'.','') !!}"),
            lng: parseFloat("{!! number_format($partner->longitude,12,'.','') !!}")
        };
    </script>
    {!! Html::script(mix('js/maps.js')) !!}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYE-QLKrq0kE3ieeZd2QIRkvija1LNS3Y&callback=mapInitializeWithGeocoding" async defer></script>
@endsection


@section('js')
<script id="admin-partner-profile-edit">
    //$.fn.editable.defaults.mode = 'inline';
    $.fn.editableform.loading = "<div><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
    var autoOpenNextEdit = true;
    var map;

    var pplTable = $('#partner-price-list-table');

    function partnerMapInitialize() {
        var testNum = "{!! $partner->latitude !!}";
        var phptest = "{!! phpversion() !!}";
        var partnerLocation = {
            lat: parseFloat("{!! number_format($partner->latitude,12,'.','') !!}"),
            lng: parseFloat("{!! number_format($partner->longitude,12,'.','') !!}")
        };

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: new google.maps.LatLng(partnerLocation),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow({maxWidth: 350});

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(partnerLocation),
            map: map,
            icon: '{!! url('img/pin.png') !!}'
        });

        google.maps.event.addListener(marker, 'click', (function(marker) {
            return function() {
                infowindow.setContent('<strong>{!! $partner->name.' '.$partner->department !!}</strong><br />{!! $partner->address !!}, {!! $partner->city->postal_code !!}, {!! $partner->city->city_name !!}<br />Telefon: {!! $partner->phone !!}');
                infowindow.open(map, marker);
            }
        })(marker));
    }
        var search_timeout;
        var apiUrlpplTable  = "{!! url(route('admin.api.dt.partners-price-list-gmx', ['partnerId'=>$partner->partner_id])) !!}";
        var apiEditablePost = "{!! url(route('admin.api.editablePost.partners-price-list')) !!}";
        var partnerId = parseInt("{!! $partner->partner_id !!}");
        pplTable.DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive" : true,
            "dom": 'trilp',
            "ajax": apiUrlpplTable,
            "columns":[
                {data: "product_id",        name:"product.product_id", searchable:true, visible:false},
                {data: "id",                name:"partner_price_list.id", searchable:true, visible:false},
                {data: "product_group",     name:"product_group.description", searchable:true, responsivePriority: 3},
                {data: "vehicle_category",  name:"vehicle_category.value_text", searchable:true, responsivePriority: 2},
                {data: "description",       name:"description.description", searchable:true, responsivePriority: 2},
                {data: "additional_description", name:"product.additional_description", searchable:true, responsivePriority: 1},
                {data: "diameter",          name:"diameter.value_text", searchable:true, responsivePriority: 2},
                {data: "wheel_material",    name:"wheel_material.value_text", searchable:true, responsivePriority: 3},
                {data: "price_with_tax",    name:"partner_price_list.price_with_tax", searchable:true, responsivePriority: 1}
            ],
            "rowCallback": function( nRow, aData, iDisplayIndex ) {
                $('td:eq(6)', nRow).html(
                    '<span class="editable editable-click price_with_tax">'+aData.price_with_tax+'</span>'
                );
                $('td:eq(6) span', nRow).editable({
                    type: 'text',
                    name: 'price_with_tax',
                    emptytext : 'Prazno',
                    pk : {id:aData.id, partner_id: partnerId, product_id:aData.product_id},
                    url : apiEditablePost,
                    title: 'Unesi cenu',
                    success: function(data, config) {
                        if(data && data.id) {  //record created, response like {"id": 2}
                            //set pk
                            $(this).editable('option', 'pk', data);
                        }
                    }
                });
                return nRow;
            },
            "drawCallback":function(settings){
                pplTable.find('.editable').on('hidden', function(e, reason){
                    if(reason === 'save' || reason === 'nochange') {
                        var $next = $(this).closest('tr').next().find('.editable');
                        if(autoOpenNextEdit) {
                            $('tr.row_selected', pplTable).removeClass('row_selected');
                            $(this).closest('tr').next().toggleClass('row_selected');
                            setTimeout(function() {

                                $next.editable('show');
                            }, 300);
                        } else {
                            $next.focus();
                        }
                    }
                })
            }
        });

        pplTable.on('click', 'tbody tr', function () {
            if ( $(this).hasClass('row_selected') ) {
                $(this).removeClass('row_selected');
            }
            else {
                $('tr.row_selected', pplTable).removeClass('row_selected');
                $(this).addClass('row_selected');
            }
        });

        function filterColumn(i) {
            var filterInput = $('input.column_filter[data-column="' + i + '"]');
            var val = filterInput.val();
            var colIsNumeric = filterInput.data('numeric_search');

            if (typeof colIsNumeric !== typeof undefined && !colIsNumeric) {
                pplTable.DataTable().columns(i).search(val, true, false).draw();
            }
            else {
                pplTable.DataTable().columns(i).search(val, false, true).draw();
            }
        }

    $('input.column_filter').on('input', function () {
        var $t = $(this);
        if (typeof search_timeout !== 'undefined') {
            clearTimeout(search_timeout);
        }
        search_timeout = setTimeout(function () {
            search_timeout = undefined;
            filterColumn($t.attr('data-column'));
        }, 350);
    });

    $('.clear_filter').on('click', function (e) {
        e.preventDefault();
        $('.column_filter').val('');
        pplTable.DataTable()
                .search('')
                .columns().search('')
                .draw();
    });

        function editRecord_pplTable($id){

            var editurl = urlTo('admin/partners/'+$id+'/edit');

            window.location = editurl;

            return false;
        }



    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href"); // activated tab
        console.log(target);
        if (target=='#basic'){
            google.maps.event.trigger(map, 'resize');
        }else if (target=='#price-list'){
                $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
                pplTable.DataTable();
        } else if(target=='#resources'){
            if ($('#resources-content').is(':empty')){
                showLoading();
                $.ajax("{!! url(route('admin.partners.partner-about.edit', ['id'=>$partner->partner_id])) !!}")
                        .done(function(result){
                            $('#resources-content').html(result);
                        }).always(function(){
                            hideLoading();
                        });
            }
        }
    });

    $('.widget-header').on('click', function(){
        $(this).closest('.')
    });

</script>

@endsection
