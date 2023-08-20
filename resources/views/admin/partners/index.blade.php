@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>

            </div>

            <div class="widget-body">
                <div>
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="partners-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Naziv</th>
                            <th>Odeljenje</th>
                            <th>Servis</th>
                            <th>PIB</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Web</th>
                            <th>Mesto</th>
                            <th>Long.</th>
                            <th>Lat.</th>
                            <th>Odobren</th>
                            <th>Odbijen</th>
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

            var addButton = $('.addButton');
            var editButton = $('.editButton');
            var deleteButton = $('.deleteButton');

            var table = $('#partners-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/partners'),
                "columns":[
                    {data: "partner_id", name:"partner_id", searchable:true},
                    {data: "name", name:"name", searchable:true},
                    {data: "department", name:"department", searchable:true},
                    {data: "is_installer", name:"is_installer", searchable:true, className:"center"},
                    {data: "tax_identification_number", name:"tax_identification_number", searchable:true},
                    {data: "email", name:"email", searchable:true},
                    {data: "phone", name:"phone", searchable:true},
                    {data: "web_address", name:"web_address", searchable:true},
                    {data: "city_id", name:"city_id", searchable:false},
                    {data: "longitude", name:"longitude", searchable:false},
                    {data: "latitude", name:"latitude", searchable:false},
                    {data: "approved_at", name:"approved_at", searchable:false},
                    {data: "rejected_at", name:"rejected_at", searchable:false},
                    {data: "actions", name:"actions", searchable:false},
                ],
                "rowCallback": function( nRow, aData, iDisplayIndex ) {
                    $('td:eq(3)', nRow).html(
                            function(){ return '<label class="position-relative"><input type="checkbox" onclick="return false" class="ace" '+ ((aData.is_installer == 1)?'checked':'')+'/><span class="lbl"></span></label>';}
                    );
                    return nRow;
                }
            });

            function editRecord($id){

                var editurl = urlTo('admin/partners/'+$id+'/edit');

                window.location = editurl;

                return false;
            }


    </script>
@endsection