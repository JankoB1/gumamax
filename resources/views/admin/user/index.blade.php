@extends('admin.master')
@section('content')
    <div id='wdg-users' class="widget-box transparent collapsed">
        <div class="widget-header">
            <h5 class="widget-title">{!! _('Users') !!}</h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-search-plus" data-icon-show="fa-search-plus" data-icon-hide="fa-search-minus"></i>
                </a>
            </div>
            <div class="widget-toolbar no-border">
            </div>
        </div>
        <div class="widget-body" style="display: none;">
            <div class="widget-main">
                {{--  {!! Former::vertical_open()->id("crm-partner-search")!!}--}}
                {!! Former::setOption('automatic_label', false) !!}
                <fieldset>
                    <div class="row">
                        <div class="col-sm-1">
                            {!! Former::text('ID')
                            ->setAttributes(['class'=>'form-control column_filter', 'data-column'=>0])
                            ->placeholder(_('User Id')) !!}
                        </div>
                        <div class="col-sm-2">
                            {!! Former::text(_('First name'))->setAttributes(['class'=>'form-control column_filter', 'data-column'=>1])->placeholder(_('First name')) !!}
                        </div>
                        <div class="col-sm-2">
                            {!! Former::text(_('Last name'))->setAttributes(['class'=>'form-control column_filter', 'data-column'=>2])->placeholder(_('Last name')) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Former::text(_('Email'))->setAttributes(['class'=>'form-control column_filter', 'data-column'=>3])->placeholder(_('Email')) !!}
                        </div>
                        <div class="col-sm-2">
                            {!! Former::text(_('Phone number'))->setAttributes(['class'=>'form-control column_filter', 'data-column'=>4])->placeholder(_('Phone number')) !!}
                        </div>

                        <div class="col-sm-2">
                            <button class="btn btn-xs btn-default pull-right clear_filter">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </fieldset>
                {{--{!! Former::setOption('automatic_label', true) !!}
                {!! Former::close() !!} --}}
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" id="users-table">
            <thead>
            <tr>
                <th>{!! _('Id') !!}</th>
                <th>{!! _('First name') !!}</th>
                <th>{!! _('Last name') !!}</th>
                <th>{!! _('Email') !!}</th>
                <th>{!! _('Phone number') !!}</th>
                <th>{!! _('City') !!}</th>
                <th>{!! _('Actions') !!}</th>
            </tr>
            </thead>
        </table>
    </div>

@endsection

@section('page-plugin-js')

@endsection

@section('js')
    @parent
    <script>
        $(function(){
            $('#wdg-users').widget_box('show');
        });


        $('body').on('preInit.dt', function (e, settings) {
            showLoading();
        });

        var usersTable = $('#users-table');
        usersTable.DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive" : true,
            "dom": 'tilp',
            "ajax":"{!! route('admin.api.dt.users.role', ['role'=>$role]) !!}",
            "columns":[
                {data: "user_id", name:"user.user_id", searchable:false},
                {data: "first_name", name:"user.first_name", searchable:true},
                {data: "last_name", name:"user.last_name", searchable:true},
                {data: "email", name:"user.email", searchable:true},
                {data: "phone_number", name:"user.phone_number", searchable:true},
                {data: "city_name", name:"address.city_name", searchable:true},
                {data: "actions", name:"actions", searchable:false},
            ],
            "rowCallback": function( nRow, aData, iDisplayIndex ) {
                return nRow;
            }
        });

        usersTable.on('click', 'tbody tr', function () {
            if ( $(this).hasClass('row_selected') ) {
                $(this).removeClass('row_selected');
            }
            else {
                $('tr.row_selected', usersTable).removeClass('row_selected');
                $(this).addClass('row_selected');
            }
        });


        usersTable.on( 'processing.dt', function ( e, settings, processing ) {
            if (processing){showLoading()}else{hideLoading()}
        }).DataTable();

        function filterColumn(i) {
            var filterInput = $('input.column_filter[data-column="' + i + '"]');
            var val = filterInput.val();
            var colIsNumeric = filterInput.data('numeric_search');

            if (typeof colIsNumeric !== typeof undefined && !colIsNumeric) {
                usersTable.DataTable().columns(i).search(val, true, false).draw();
            }
            else {
                usersTable.DataTable().columns(i).search(val, false, true).draw();
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
            usersTable.DataTable()
                    .search('')
                    .columns().search('')
                    .draw();
        });

        function editRecord($id){
            var editUrl = urlTo('admin/profile/'+$id+'/edit');
            window.location = editUrl;
            return false;
        }
    </script>
@endsection