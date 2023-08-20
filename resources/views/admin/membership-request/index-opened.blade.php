@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>
            </div>

            <div class="widget-body">
                <div>
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="membership-request-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Naziv</th>
                        <th>Poslovnica</th>
                        <th>Vrsta</th>
                        <th>PIB</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Telefon</th>
                        <th>Email</th>
                        <th>Adresa</th>
                        <th>Mesto</th>
                        <th>Web adresa</th>
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

            var table = $('#membership-request-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/membership-request/{!! $status !!}'),
                "columns":[
                    {data: "id", name:"id", searchable:true},
                    {data: "name", name:"name", searchable:true},
                    {data: "department", name:"department", searchable:true},
                    {data: "is_installer", name:"is_installer", searchable:true},
                    {data: "tax_identification_number", name:"tax_identification_number", searchable:true},
                    {data: "first_name", name:"first_name", searchable:true},
                    {data: "last_name", name:"last_name", searchable:true},
                    {data: "phone", name:"phone", searchable:true},
                    {data: "email", name:"email", searchable:true},
                    {data: "address", name:"address", searchable:true},
                    {data: "city_id", name:"city_id", searchable:true},
                    {data: "web_address", name:"web_address", searchable:true},
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

        });

        function approveRequest($id){
            swal({
                        title: "Odobravanje prijave",
                        text: "Unesi šifru partnera iz eTrgovca:",
                        type: "input",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        inputPlaceholder: "Write something"
                    },
                    function(inputValue){
                        if (inputValue === false) return false;

                        if (inputValue === "") {
                            swal.showInputError("You need to write something!");
                            return false
                        }

                        $.ajax({
                            type: "POST",
                            url: urlTo('/admin/api/approve/membership-request/'+$id+'/'+inputValue)
                        }).done(function(resp){
                            console.log(resp);

                        });
                });
            }

        function rejectRequest($id){
            alert($id);
        }
        function showRequest($id, $status){
            window.location=urlTo('admin/membership-request/'+$id+'/'+$status+'/show');
            return false;
        }


    </script>
@endsection