@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-12 col-lg-12">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>
                @if(auth()->user()->hasRole(['superadmin']))
                    <div class="widget-toolbar">
                        <button class="btn btn-xs bigger btn-danger deleteButton">Briši
                            <i class="ace-icon fa fa-remove icon-on-right"></i>
                        </button>
                    </div>
                @endif
                <div class="widget-toolbar">
                    <button class="btn btn-xs bigger btn-warning editButton">Izmeni
                        <i class="ace-icon fa fa-pencil icon-on-right"></i>
                    </button>
                </div>

            </div>

            <div class="widget-body">
                <div>
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="product-review-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Artikal ID</th>
                            <th>Naslov</th>
                            <th>Nickname</th>
                            <th>Ocena sajta</th>
                            <th>Odobreno</th>
                            <th>Odobreno od</th>
                            <th>Odbijeno</th>
                            <th>Odbijeno od</th>
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

            var table = $('#product-review-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/product_review/{!! $status !!}'),
                "columns":[
                    {data: "product_review_id", name:"product_review_id", searchable:true},
                    {data: "product_id", name:"product_id", searchable:true},
                    {data: "review_title", name:"review_title", searchable:true},
                    {data: "nickname", name:"nickname", searchable:true},
                    {data: "site_rating", name:"site_rating", searchable:true},
                    {data: "approved_at", name:"approved_at", searchable:true},
                    {data: "approved_by_user_id", name:"approved_by_user_id", searchable:true},
                    {data: "rejected_at", name:"rejected_at", searchable:true},
                    {data: "rejected_by_user_id", name:"rejected_by_user_id", searchable:true}
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
                window.location = urlTo('admin/product_review/'+id+'/edit');
            });

            deleteButton.on('click', function(e){
                var id =  $(table.row('.row_selected').node()).children().first().text();
                //swalAlert
                //brisanje
            });

        });


    </script>
@endsection