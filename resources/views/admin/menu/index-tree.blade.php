@extends('admin.master')

@section('content')
    <div class="col col-xs-12 col-sm-9 col-lg-9">
        <div class="widget-box widget-container-col">
            <div class="widget-header">
                <h5 class="widget-title">Menu</h5>
            </div>

            <div class="widget-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="dd" id="nestableMenu">
                            <ol class="dd-list">
                                <div id="dd-empty-placeholder"></div>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

   {!! Html::script('assets/admin/ace/js/jquery.nestable.min.js') !!}

   <script type="text/javascript">

        function addElement(id, parent_id, element) {

            if (parent_id == null) {
                $('#dd-empty-placeholder').append(element);
            } else
            if ($("li[data-id='"+parent_id+"']").length == 0) {
                $('#dd-tmp-placeholder').append(element);
            } else {
                $("li[data-id='"+parent_id+"'] > ol").append(element);
            }
        }

        function buildMenu(items) {
            var html = '';

            $("<div id='dd-tmp-placeholder'></div>").appendTo($("#nestableMenu"));  //div u koji se smeštaju child stavke čiji parent još nije kreiran

            $.each(items, function (index, item) {

                html = "<li class='dd-item dd2-item' data-id='" + item.id + "' data-parent-id='" + item.parent_id +"'>";
                html += "<div class='dd-handle dd2-handle'>" +
                        "<i class='normal-icon ace-icon fa " + item.icon + " grey bigger-130'></i>" +
                        "<i class='drag-icon ace-icon fa fa-arrows bigger-125'></i></div>";
                html += "<div class='dd2-content'>" + item.title + "</div>";

                if (item.child_count > 0) {
                    html += "<ol class='dd-list'></ol>"
                }

                html += "</li>";

                addElement(item.id, item.parent_id, html);

                $("#dd-tmp-placeholder li[data-parent-id='" + item.id + "']").appendTo($("li[data-id='"+item.id+"'] > ol"));
            });

            $('.dd').nestable({
                callback: function(l,e) {
                    showLoading();
                    var itemsData = [],
                        items = e.parent().children("li"),
                        parent_id = e.parents(".dd-item").data('id');

                    items.each(function() {
                        var li = $(this),
                            item = {};

                        item.id = li.data('id');
                        item.parent_id = parent_id;
                        item.order_index = li.index() + 1;

                        itemsData.push(item);
                    });

                    $.ajax({
                        type: "PUT",
                        url: urlTo('admin/menu/'+ e.data('id')),
                        dataType: "json",
                        data: {data:itemsData}
                    }).done(function(response) {
                    }).fail(function(xhr, textStatus, errorThrown){
                        console.warn(textStatus);
                    }).always(function(){
                        hideLoading();
                    });
                }
            });

            //$("#dd-tmp-placeholder").remove();
            $('.dd').nestable('expand');
        }

        showLoading();
        $.ajax({
            'type': 'GET',
            'url': urlTo('admin/api/menu/items'),
            'dataType': 'json'
        }).done(function(response){
            buildMenu(response.items);
        }).always(function(){
            hideLoading();
        });

        $('.dd-handle a').on('mousedown', function(e){
            e.stopPropagation();
        });

        $('[data-rel="tooltip"]').tooltip();

    </script>

@endsection