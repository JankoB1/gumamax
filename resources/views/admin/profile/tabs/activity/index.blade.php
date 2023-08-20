<div id="wdg-users-activity" class="widget-box transparent">
    <div class="widget-header widget-header-small">
        <h4 class="widget-title blue smaller">
            <i class="ace-icon fa fa-rss orange"></i>
            {!! _('Recent Activities') !!}
        </h4>

        <div class="widget-toolbar action-buttons">
            <a href="#" data-action="reload">
                <i class="ace-icon fa fa-refresh blue"></i>
            </a>
            &nbsp;
            <a href="#" class="pink">
                <i class="ace-icon fa fa-trash-o"></i>
            </a>
        </div>

    </div>
    <div class="widget-body" style="display: none;">
        <div class="widget-main">
            {{--  {!! Former::vertical_open()->id("crm-partner-search")!!}--}}
            {!! Former::setOption('automatic_label', false) !!}
            <fieldset>
                <div class="row">
                    <div class="col-sm-5">
                        {!! Former::text(_('Time'))
                        ->setAttributes(['class'=>'form-control column_filter', 'data-column'=>0])
                        ->placeholder(_('Time')) !!}
                    </div>
                    <div class="col-sm-5">
                        {!! Former::text(_('Description'))->setAttributes(['class'=>'form-control column_filter', 'data-column'=>1])->placeholder(_('Description')) !!}
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
        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" id="users-activity-table">
            <thead>
            <tr>
                <th>{!! _('Time') !!}</th>
                <th>{!! _('Description') !!}</th>
            </tr>
            </thead>
        </table>
    </div>

@section('js')
    @parent
    <script id="script-user-activity">
        $(function(){
            $('#wdg-users-activity').widget_box('show');
        });


        $('body').on('preInit.dt', function (e, settings) {
            showLoading();
        });

        var usersActivityTable = $('#users-activity-table');
        usersActivityTable.DataTable( {
            "processing": true,
            "serverSide": true,
            "responsive" : true,
            "dom": 'tilp',
            "ajax":"{!! route('admin.api.dt.profile.activity', ['user_id'=>$user->user_id]) !!}",
            "columns":[
                {data: "created_at", name:"user_activity.created_at", searchable:true},
                {data: "description", name:"user_activity.description", searchable:true},
            ],
            "rowCallback": function( nRow, aData, iDisplayIndex ) {
                return nRow;
            }
        });

        usersActivityTable.on('click', 'tbody tr', function () {
            if ( $(this).hasClass('row_selected') ) {
                $(this).removeClass('row_selected');
            }
            else {
                $('tr.row_selected', usersActivityTable).removeClass('row_selected');
                $(this).addClass('row_selected');
            }
        });


        usersActivityTable.on( 'processing.dt', function ( e, settings, processing ) {
            if (processing){showLoading()}else{hideLoading()}
        }).DataTable();

        function filterColumn(i) {
            var filterInput = $('input.column_filter[data-column="' + i + '"]');
            var val = filterInput.val();
            var colIsNumeric = filterInput.data('numeric_search');

            if (typeof colIsNumeric !== typeof undefined && !colIsNumeric) {
                usersActivityTable.DataTable().columns(i).search(val, true, false).draw();
            }
            else {
                usersActivityTable.DataTable().columns(i).search(val, false, true).draw();
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
            usersActivityTable.DataTable()
                    .search('')
                    .columns().search('')
                    .draw();
        });

    </script>
@endsection