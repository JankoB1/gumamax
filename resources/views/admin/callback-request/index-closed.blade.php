@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>

            </div>

            <div class="widget-body">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="callback-request-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Kreirano</th>
                        <th>App</th>
                        <th>Ime</th>
                        <th>Telefon</th>
                        <th>Odmah</th>
                        <th>Zakazano za datum</th>
                        <th>Zakazano za vreme</th>
                        <th>Pozvano u</th>
                        <th>Agent</th>
                        <th>Odgovor</th>
                        <th>Završeno</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
   
    <script>
        var selected =[];
        $(function(){
            var addButton = $('.addButton');
            var editButton = $('.editButton');
            var deleteButton = $('.deleteButton');

            var table = $('#callback-request-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/callback_request/{!! $status !!}'),
                "columns":[
                    {data: "id", name:"id", searchable:true},
                    {data: "created_at", name:"created_at", searchable:true},
                    {data: "app_id", name:"app_id", searchable:true},
                    {data: "name", name:"name", searchable:true},
                    {data: "phone", name:"phone", searchable:true},
                    {data: "asap", name:"asap", searchable:true, class:"center"},
                    {data: "scheduled_date", name:"scheduled_date", searchable:true},
                    {data: "scheduled_time_span_id", name:"scheduled_time_span_id", searchable:true},
                    {data: "called_at", name:"called_at", searchable:true},
                    {data: "agent_id", name:"agent_id", searchable:true},
                    {data: "answer", name:"answer", searchable:true},
                    {data: "closed", name:"closed", searchable:true, class:"center"}
                ],
                "rowCallback": function( nRow, aData, iDisplayIndex ) {
                    $('td:eq(5)', nRow).html(
                        function(){ return '<label class="position-relative"><input type="checkbox" class="ace" '+ ((aData.asap == 1)?'checked':'')+'/><span class="lbl"></span></label>';}
                    );
                    $('td:eq(11)', nRow).html(
                            function(){ return '<label class="position-relative"><input type="checkbox" class="ace" '+ ((aData.closed == 1)?'checked':'')+'/><span class="lbl"></span></label>';}
                    );
                }
            });


            table.on('click', 'tbody tr', function () {
                if ( $(this).hasClass('row_selected') ) {
                    $(this).removeClass('row_selected');
                }
                else {
                    table.$('tr.row_selected').removeClass('row_selected');
                    $(this).addClass('row_selected');
                }
            });

        });

    </script>
@endsection