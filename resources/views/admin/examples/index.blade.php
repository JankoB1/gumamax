@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-9 col-lg-9">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>
                <div class="widget-toolbar">
                    <button class="btn btn-xs bigger btn-danger deleteButton">Briši
                        <i class="ace-icon fa fa-remove icon-on-right"></i>
                    </button>
                </div>
                <div class="widget-toolbar">
                    <button class="btn btn-xs bigger btn-warning editButton" href="{!!route('admin.menu.edit')!!}">Izmeni
                        <i class="ace-icon fa fa-pencil icon-on-right"></i>
                    </button>
                </div>
                <div class="widget-toolbar">
                    <button class="btn btn-xs bigger btn-info addButton" href="{!!route('admin.menu.create')!!}">Dodaj
                        <i class="ace-icon fa fa-plus icon-on-right"></i>
                    </button>
                </div>

            </div>

            <div class="widget-body">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="menu-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>ParentId</th>
                        <th>Title</th>
                        <th>Order index</th>
                        <th>Route name</th>
                        <th>Url</th>
                        <th>Is active</th>
                        <th>icon</th>
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

            var table = $('#menu-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/menu'),
                "columns":[
                    {data: "id", name:"id", searchable:true},
                    {data: "parent_id", name:"parent_id", searchable:true},
                    {data: "title", name:"title", searchable:true},
                    {data: "order_index", name:"order_index", searchable:true},
                    {data: "route_name", name:"route_name", searchable:true},
                    {data: "url", name:"url", searchable:true},
                    {data: "is_active", name:"is_active", searchable:true},
                    {data: "icon", name:"icon", searchable:true},
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
            } );

            addButton.on('click', function(){
                window.location = "{!!route('admin.menu.create')!!}";
            });

            editButton.on('click', function(){
                var id =  $(table.row('.row_selected').node()).children().first().text();
                window.location = urlTo('admin/menu/'+id+'/edit');
            });

            deleteButton.on('click', function(e){
                var id =  $(table.row('.row_selected').node()).children().first().text();
                //swalAlert
                //brisanje
            });

        });



    </script>
@endsection