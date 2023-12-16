@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>
            </div>
            <div class="widget-body">
                <div>
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="contact-form-message-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Naziv</th>
                            <th>Email</th>
                            <th>IP</th>
                            <th>Agent ID</th>
                            <th>Odgovoreno</th>
                            <th>Akcije</th>
                        </tr>
                        </thead>
                    </table>
                </div>
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

            var table = $('#contact-form-message-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/contact_form_message/{!! $status !!}'),
                "columns":[
                    {data: "id", name:"id", searchable:true},
                    {data: "name", name:"name", searchable:true},
                    {data: "email", name:"email", searchable:true},
                    {data: "from_ip", name:"from_ip", searchable:true},
                    {data: "agent_id", name:"agent_id", searchable:true},
                    {data: "answered_at", name:"answered_at", searchable:true},
                    {data: "actions", name:"actions", className:"table-actions-column", searchable:false}
                ]
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


            editButton.on('click', function(){
                var id =  $(table.row('.row_selected').node()).children().first().text();
                window.location = urlTo('admin/membership-request/'+id+'/edit');
            });

            deleteButton.on('click', function(e){
                var id =  $(table.row('.row_selected').node()).children().first().text();
                //swalAlert
                //brisanje
            });

        });

        function showContact($id){
            window.location=urlTo('admin/contact_form_message/'+$id+'/show');
            return false;
        }


    </script>
@endsection