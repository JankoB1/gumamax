var dailyFirstShow = true;

var pFilter = {
    order: 'distance asc',
    page: 1,
    next_page: 0,
    last_page: 0,
    results_per_page: 5,
    return_count: true,
    count: '',
    radius: 5,
    delivery: '',
    postal_code: '',
    city_id: '',
    latitude: '',
    longitude: '',
    history: false,
    ac_city: '',
    delatnost: 2
};

var partnersList = $('#result-items'),
    locatorResultsPerPage = $('nav ul.pagination.shipping.per-page li a'),
    resultsOrder = $('.result-header .form-control.sortby'),
    paginatorPluginOptions,
    paginatorContainer = $('nav .pagination.pages.clearfix'),
    containerNumberOfResults=$('.navbar-text.result-num span'),

    radius = $("#radius"),
    aCity = $('#aCity_id');

function init_partners() {
    var c = sessionStorage.getItem("gumamax_partner_filter");
    var pF =JSON.parse(c);

    if(pF != null){
        if(pF.history == true){
            pFilter = pF;
            radius.val(pFilter.radius);
            $('#cities').val(pFilter.ac_city);
            $("#aCity_id").val(pFilter.city_id);
        }
    }else{
    	setPartnerFilterCookie();
    }
    showPartners();
}

function setFormFilter() {
	pFilter.radius = radius.find('option:selected').val() || 5;
    pFilter.city_id = $('#aCity_id').val();
    pFilter.latitude = $('#aLat').val() || 44;
    pFilter.longitude = $('#aLon').val() || 21;
    pFilter.ac_city = $('#cities').val();
    pFilter.delatnost = $('#delatnost').find('option:selected').val() || 2;
    pFilter.order = resultsOrder.find('option:selected').data('order');
}

function getPartners() {
    showLoading();
    if (pFilter.city_id!='') {
        $(".partners_list").html('<div class="alert alert-info col-md-12">'+pleaseWaitLabel+'</div>');
    } else {
        $(".partners_list").html('<div class="alert alert-warning"><h4 class="text-center">'+inputCityLabel+'</h4></div>');
        hideLoading();
        return false;
    }

    $.ajax({
        type: "GET",
        url: urlTo("api/partner/locator"),
        contentType: "application/json; charset=utf-8",
        data: pFilter,
        dataType: "json"
    }).done(function(data){
        hideLoading();

        if(data.count !== null)
            pFilter.count = data.count;

        clearPartnerList();
        T.render('gmx-shipping-partner', function(t) {
            partnersList.append(t(data));
            shop.bindShipping();
            getLocations();
        });

        partnersList.removeClass("hide");
        $('.partner_count').html(data.count);

        setPagination(data);
        resultsOrder.val(resultsOrder.find('option[data-order="'+pFilter.order+'"]').val());


    }).fail(function(){
        hideLoading();
        partnersList.html('<div class="alert alert-danger"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
    });
}

function clearPartnerList() {
    partnersList.html('');
}

function showPartners() {
    if (!dailyFirstShow)
    {
    	$('#results').show();
    	mapInitialize();
        getPartners();
    } else {
    	$('#results').hide();
    }
}

function init_results_form() {

    clearPartnerList();
}

$('#form-partner-search').on('submit', function(e){
	dailyFirstShow = false;
	e.preventDefault();
	setFormFilter();
	showPartners();
});

aCity.on("change",function(){
    dailyFirstShow = false;
    pFilter.return_count = true;
    pFilter.page = 1;
    pFilter.city_id = $(this).val();
    pFilter.latitude = $('#aLat').val();
    pFilter.longitude = $('#aLon').val();
    setPartnerFilterCookie();
    showPartners();
});

radius.on("change",function(){
	dailyFirstShow = false;
    pFilter.return_count = true;
    pFilter.page = 1;

    pFilter.radius = $(this).find('option:selected').val();
    setPartnerFilterCookie();
    showPartners();
});

resultsOrder.on('change', function(){
    pFilter.page = 1;
    pFilter.order = $(this).find('option:selected').data('order');
    setPartnerFilterCookie();
    showPartners();
});

locatorResultsPerPage.on('click', function(){
    pFilter.page = 1;
    setPerPage($(this).data('per_page'));
    setPartnerFilterCookie();
    showPartners();
});

function setPerPage(per_page){

    pFilter.results_per_page = per_page;

    $('nav ul.pagination.per-page li').removeClass('active');

    $('nav ul.pagination.per-page li a[data-per_page="'+per_page+'"]').parent().addClass('active');

}

function setPagination(result) {

    pFilter.page		=   result.page;
    pFilter.next_page	=   ++result.page;
    pFilter.last_page	=   Math.ceil(result.count/result.results_per_page);

    paginatorPluginOptions = {
        size: "normal",
        numberOfPages: 4,
        currentPage: pFilter.page,
        totalPages: pFilter.last_page,
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
            pFilter.page = page;
            pFilter.next_page = (page==pFilter.last_page) ? pFilter.last_page : ++page;
            setPartnerFilterCookie();
            showPartners();
        }
    };
    containerNumberOfResults.html(result.count);

    if (result.count>0){
        paginatorContainer.bootstrapPaginator(paginatorPluginOptions);
    }

    setPerPage(result.results_per_page);

}

function setPartnerFilterCookie() {
    sessionStorage.setItem("gumamax_partner_filter", JSON.stringify(pFilter));
}

$(window).on("scroll", function(){
	if ($('.result-header').length >= 2) {
		if ($('.mapaffix').height() >= $('#result-items').height())
			return false;

		var mw = $('.mapaffix').width();
		var tte = parseInt($('.result-header:first').offset().top, 10); // top top edge
		var teh = parseInt($('.result-header:first').height(), 10);     // top edge height
		var te = tte+teh;												// top edge
		var be = parseInt($('.result-header:last').offset().top, 10);   // bottom edge
		var f  = $(window).scrollTop();

		if ( f > te+15 ) {
			if ( f + 555 + teh > be ) {
				$('#result-content').css("position", "relative");
				// $('#result-items').css("position", "relative");
				$(".mapaffix").css("position", "absolute").css("top", "auto").css("bottom",15).css("left", $('#result-items').width());
			} else {
				$('#result-content').css("position111", "static");
				// $('#result-items').css("position", "static");
				var l = $("#result-items").offset().left + $("#result-items").width();
				$(".mapaffix").css("position", "fixed").css("top", 15).css("left",l).css("width",mw);
			}
		} else {
			$(".mapaffix").css("position", "relative").css("left",0).css("top", 0).css("bottom",0);
		}
	}
});

$(function() {
    $('#custom-shipping-address').validate({
        rules: {
            shipping_postal_code: {
                required: true
            },
            shipping_recipient: {
                required: true,
                minlength: 3,
                maxlength: 250
            },
            shipping_address: {
                required: true,
                minlength: 3,
                maxlength: 250
            },
            shipping_phone: {
                required: true,
                minlength: 5,
                phone_regex : true
            },

            shipping_email:{
                required : true,
                email : true,
                maxlength:64
            }
        },

        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
});