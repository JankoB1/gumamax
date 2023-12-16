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
                {data: "actions", name:"actions", searchable:false}
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

        function replyToContact(id){
            var editUrl = urlTo('admin/contact_form_message/'+id+'/edit'),
                title = 'Odgovor na kontakt';

            dmxModalDialog(title, '', editUrl, 'contact-reply-form-message', 'undefined', table).open();
            return false;
        }


    </script>
@endsection