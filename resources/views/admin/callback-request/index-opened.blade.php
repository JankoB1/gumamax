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
                        <th>Akcije</th>
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
            var table = $('#callback-request-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/callback_request/{!! $status !!}'),
                "columns":[
                    {data: "id", name:"id", searchable:true},//0
                    {data: "created_at", name:"created_at", searchable:true},//1
                    {data: "app_id", name:"app_id", searchable:true, visible:false},//2
                    {data: "name", name:"name", searchable:true},//3
                    {data: "phone", name:"phone", searchable:false, visible:false},//3
                    {data: "asap", name:"asap", searchable:true },//4
                    {data: "scheduled_date", name:"scheduled_date", searchable:true, visible:true},//5
                    {data: "scheduled_time_span_id", name:"scheduled_time_span_id", searchable:true, visible:false},//5
                    {data: "called_at", name:"called_at", searchable:true},//6
                    {data: "agent_id", name:"agent_id", searchable:true, visible:false},//6
                    {data: "answer", name:"answer", searchable:true},//7
                    {data: "actions", name:"actions", searchable:false}//8
                ],
                "rowCallback": function( nRow, aData, iDisplayIndex ) {
                    $('td:eq(0)', nRow).html(
                            aData.id+'<br>'+'AppId:'+aData.app_id
                    );
                    $('td:eq(2)', nRow).html(
                            aData.name+'<br><strong>'+aData.phone+'</strong>'
                    );

                    $('td:eq(3)', nRow).html(
                            function(){ return '<label class="position-relative"><input type="checkbox" onclick="return false" class="ace" '+ ((aData.asap == 1)?'checked':'')+'/><span class="lbl"></span></label>';}
                    );

                    $('td:eq(4)', nRow).html(
                            function (){
                                if (aData.scheduled_date!=null){
                                    return aData.scheduled_date+'<br>'+aData.scheduled_time_span_id
                                }
                                return '';
                            }
                    );

                    $('td:eq(5)', nRow).html(
                            aData.called_at+'<br>'+'AgentId:'+ aData.agent_id
                    );

                    return nRow;
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

            function replyToCallbackReq($id) {
                var editUrl = urlTo('admin/callback_request/'+$id+'/edit');
                var $title = 'Odgovor na poziv';
                dmxModalDialog($title, '', editUrl, 'callback-request-reply-form-message', 'undefined', table).open();
                return false;
            }

    </script>
@endsection