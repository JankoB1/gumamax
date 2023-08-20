var productsLayout = $('#results'),
    productsList = $('#result-items'),
    productsNoResults = $('.no-results'),
    resultsOrder = $('.result-header .form-control.sortby'),
    containerNumberOfResults=$('.navbar-text.result-num span'),
    productQuery ={},
    replacementsQuery={};


function init_results_form(){
    T.prefetch('gmx-product');

}

function clearProductList(){
    productsList.html('');
}

function showProducts(query) {
    var loadMoreDiv = $('.load-more');
    productQuery = query;

    if (loadMoreDiv.length>0) {
        loadMoreDiv.remove();
    }
    productsNoResults.hide();
    productsLayout.hide();
    clearProductList();
    productsLayout.show();
    getData();
    scrollToResult();
}

function getData(){

    if (checkSearchCriteria()) {

        $.ajax({
            type: "GET",
            url: urlTo('api/products/tyres/search'),
            contentType: "application/json; charset=utf-8",
            data: productQuery,
            dataType: "json"
        }).done(function(result){

            setPagination(result.pagination);

            loadFacet(result.aggregations);

            var productsHtml='';

            T.render('gmx-product', function(t) {
                productsHtml = t(result) ;
                  });

            productsList.append(productsHtml);

            $('[data-toggle="tooltip"]').tooltip();

            initCompare();

            $('.loader-lg').hide();

            if(result.pagination.total>0){
                if (result.pagination.next_page>0) {
                    var $loadMoreDiv = '<div class="load-more row text-center"><a class="load-more-button btn btn-primary" href="#">Učitaj još</a></div>';
                    productsLayout.append($loadMoreDiv);
                    $('.load-more-button').on('click', function(e){

                        e.preventDefault();
                        $('.load-more').remove();
                        nextPage();
                    });
                }
            } else {
                productsNoResults.show();
            }

            $('.rateit').rateit();

            shop.bindProducts();

        }).fail(function(){
            $('.loader-lg').hide();
            productsNoResults.show();
        });
    }
}

function setPagination(pagination) {

    productQuery.current_page =   pagination.current_page;
    productQuery.next_page    =   pagination.next_page;
    productQuery.last_page    =   pagination.last_page;
    containerNumberOfResults.html(pagination.total);

}

function setOrder(order){
    productQuery.order = order;
    setProductFilterCookie(productQuery);
}

resultsOrder.on('change', function(){
    productQuery.page = 1;
    setOrder($(this).val());
    showProducts(productQuery);
});


function nextPage(){
    if (productQuery.current_page<productQuery.last_page){
        $('.loader-lg').show();
        productQuery.page = productQuery.page+1;
        getData();
    }
}

function scrollToResult(){
    if( productsLayout.length ) {
        $('html, body').stop().animate({
            scrollTop: productsLayout.offset().top
        }, 1000);
    }
}

function getReplacements(replacementsHolder){

    replacementsQuery={
        product_id : replacementsHolder.data('product_id'),
        requested_qty : replacementsHolder.data('requested_qty'),
        per_page : 15,
        page : 1,
        order : "price_with_tax|asc"
    };

    showLoading();

    $.ajax({
        type: "GET",
        url: urlTo('api/products/tyres/replacements'),
        contentType: "application/json; charset=utf-8",
        data: replacementsQuery,
        dataType: "json"
    }).done(function(result){

        replacementsHolder.empty();

        hideLoading();

        if(result.pagination.total>0){
            T.render('gmx-product-replacements', function(t) {
                replacementsHolder.append(t(result));
            });
            replacementsHolder.show();
        } else {
            productsLayout.html('<div class="alert alert-warning"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
        }

        shop.bindProducts();

    }).fail(function(){
        hideLoading();
        productsList.html('<div class="alert alert-danger"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
    });

}