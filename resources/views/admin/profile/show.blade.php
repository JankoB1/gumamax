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
                        <li class="active"><a data-toggle="tab" href="#account"><i class="blue ace-icon fa fa-user bigger-120"></i>{!!_('Account')!!}</a></li>
                        <li><a data-toggle="tab" href="#companies"><i class="blue ace-icon fa fa-industry bigger-120"></i>{!!_('Your Companies')!!}</a></li>
                        <li><a data-toggle="tab" href="#activity"><i class="orange ace-icon fa fa-rss bigger-120"></i>{!! _('Activity') !!}</a></li>
                    </ul>
                    <div class="tab-content no-border padding-24">
                        <div id="account" class="tab-pane in active">
                            @include('admin.profile.tabs.account.index')
                        </div><!-- /#account -->
                        <div id="companies" class="tab-pane">
                            @include('admin.profile.tabs.members.index')
                        </div><!-- /#companies -->
                        <div id="activity" class="tab-pane">
                            @include('admin.profile.tabs.activity.index')
                        </div><!-- /#activity -->

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-plugin-js')
    @parent
    {!! Html::script(mix('js/maps.js')) !!}   

@endsection

@section('js')
    @parent
    <script id="admin-profile-show">
        //$.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.loading = "<div><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
        var autoOpenNextEdit = true;

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href"); // activated tab

            if (target=='#account'){

            }else if (target=='#activity'){
                $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
                $('#users-activity-table').DataTable();

            } else if(target=='#companies') {

            }
        });

        $('.cancel_button').on('click', function(){
           var btn = $(this);
           var form =  btn.closest('form');
            form.trigger('reset');
            form.validate().resetForm();
            if (form.attr('id')=='form-customer-type'){
                showCompanyFields($('#customer_type_id').val());
            }
            $(this).closest('.widget-box').widget_box('hide');
        });

    </script>

    <script id="profile-user-address-map">

        var initialZoom = 15;

        @if($address)           
            var initialLocation = {
                lat: parseFloat("{!! number_format($address->latitude,12,'.','') !!}"),
                lng: parseFloat("{!! number_format($address->longitude,12,'.','') !!}")
            };
        @endif

        function mapModalInit() {
            if (typeof google === 'object' && typeof google.maps === 'object') {
                mapInitializeWithGeocoding();
            } else {
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBYE-QLKrq0kE3ieeZd2QIRkvija1LNS3Y&callback=mapInitializeWithGeocoding";
                document.body.appendChild(script);
            }
        }
        mapModalInit();

    </script>

@endsection
