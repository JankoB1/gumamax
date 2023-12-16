@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-9 col-lg-9">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title pull-left">Crousel </h5>
                <div class="widget-toolbar">
                    <span class="label label-info">Filter</span>
                    <select name="grid-filter" id="grid-filter">
                        <option value="0">Svi</option>
                        <option value="1" selected>Aktivni</option>
                    </select>
                </div>

                <div class="widget-toolbar pull-left no-border">

                    <a class="btn btn-xs bigger btn-info btnAddCarousel" href="{!!url('admin/carousel/create')!!}">Dodaj
                        <i class="ace-icon fa fa-plus icon-on-right"></i>
                    </a>
                </div>


            </div>

            <div class="widget-body">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="carousel-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Početak važenja</th>
                        <th>Kraj važenja</th>
                        <th>Aktivan (prvi)</th>
                        <th>Tip</th>
                        <th>Tip linka</th>
                        <th>HTML id</th>
                        <th>Slika</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('breadcrumbs')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="{!!url('admin')!!}">Admin</a>

			<span class="divider">
				<i class="icon-angle-right arrow-icon"></i>
			</span>
        </li>

        <li class="active">Carousel</li>
    </ul><!-- .breadcrumb -->
@stop

@section('js')
    <script>

        $(function(){
            var gridFilter = $('#grid-filter');
            var table = $('#carousel-table').DataTable( {
                "processing": true,
                "serverSide": true,
                "responsive" : true,
                "ajax": urlTo('admin/api/dt/carousel/1'),
                "columns":[
                    {data: "carousel_id", name:"carousel_id", searchable:true},
                    {data: "datetime_start", name:"datetime_start", searchable:true},
                    {data: "datetime_end", name:"datetime_end", searchable:true},
                    {data: "active", name:"active", searchable:true},
                    {data: "carousel_type", name:"carousel_type", searchable:true},
                    {data: "link_type", name:"link_type", searchable:true},
                    {data: "html_id", name:"html_id", searchable:true},
                    {data: "image", name:"image", searchable:true},

                ]
            });

            table.on('click', 'td', function () {
                    var id = $(this).parents('tr').children().first().text();
                    window.location = urlTo('admin/carousel/'+id);
            });

            gridFilter.on('change', function(e){
                var newUrl =urlTo('admin/api/dt/carousel/'+gridFilter.val());

                table.ajax.url( newUrl ).load();
            });
        });

    </script>
@endsection