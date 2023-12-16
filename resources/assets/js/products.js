var productsLayout = $('#results'),
    productsList = $('#result-items'),
    resultsPerPage = $('nav ul.pagination.products.per-page li a'),
    resultsOrder = $('.result-header .form-control.sortby'),
    paginatorPluginOptions,
    paginatorContainer = $('nav .pagination.pages.clearfix'),
    containerNumberOfResults=$('.navbar-text.result-num span');

function init_results_form(){

    T.prefetch('gmx-product');

    clearProductList();
}

function clearProductList(){
    productsList.html('');
}

function showProducts() {
    getData();
}

function getData(){

    productsLayout.hide();

    clearProductList();

    if (checkSearchCriteria()) {

        productsLayout.show();

        setOrder(tFilter.order);

        setPerPage(tFilter.per_page);

        $.ajax({
            type: "GET",
            url: urlTo('api/products/tyres/search'),
            contentType: "application/json; charset=utf-8",
            data: tFilter,
            dataType: "json"
        }).done(function(result){

            loadFacet(result.aggregations);

            var productsHtml='';

            T.renderSync('gmx-product', function(t) {
                productsHtml = t(result) ;
            });

            productsList.append(productsHtml);

            setPagination(result.pagination);

            $('[data-toggle="tooltip"]').tooltip();

            initCompare();

            $('.animation_image').hide();

            if(result.pagination.total>0){

            } else {
                productsList.html('<div class="alert alert-warning"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
            }

            $('.rateit').rateit();

            shop.bindProducts();

        }).fail(function(){
            $('.animation_image').hide();
            productsList.html('<div class="alert alert-danger"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
        });
    }
}

function setPagination(result) {

    tFilter.current_page =   result.current_page;
    tFilter.next_page    =   result.next_page;
    tFilter.last_page    =   (result.last_page>0)?result.last_page:1;

    paginatorPluginOptions = {
        size: "normal",
        numberOfPages: 4,
        currentPage : tFilter.current_page,
        totalPages  : tFilter.last_page,
        bootstrapMajorVersion: 3,
        useBootstrapTooltip: true,
        itemContainerClass: function (type, page, current) {
            return (page === current) ? "active" : "cursor-pointer";
        },
        shouldShowPage: function (type, page, current) {
            switch (type) {
                case "prev":
                    return (current !== 1);
                    break;
                case "next":
                    return (current !== this.totalPages);
                    break;
                case "first":
                case "last":
                    return false;
                default:
                    return true;
            }
        },
        itemTexts: function (type, page, current) {
            switch (type) {
                case "first":
                    return "";
                case "prev":
                    return "&laquo;";
                case "next":
                    return "&raquo;";
                case "last":
                    return "";
                case "page":
                    return page;
            }
        },
        onPageClicked: function(e,originalEvent,type,page){
            tFilter.page = page;
            setProductFilterCookie();
            showProducts();
        }
    };
    containerNumberOfResults.html(result.total);
    paginatorContainer.bootstrapPaginator(paginatorPluginOptions);

}

function setPerPage(per_page){

    tFilter.per_page = per_page;

    $('nav ul.pagination.per-page li').removeClass('active');

    $('nav ul.pagination.per-page li a[data-per_page="'+per_page+'"]').parent().addClass('active');

}

function setOrder(order){
    tFilter.order = order;
    resultsOrder.val(order);
    setProductFilterCookie();
}

resultsOrder.on('change', function(){
    tFilter.page = 1;
    setOrder($(this).val());
    showProducts();
});

resultsPerPage.on('click', function(){
    tFilter.page = 1;
    setPerPage($(this).data('per_page'));
    setProductFilterCookie();
    showProducts();
});
