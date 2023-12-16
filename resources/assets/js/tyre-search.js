var el = {
        width:      $("#tyre-width").prop("disabled", "disabled"),
        ratio:      $("#tyre-height").prop("disabled", "disabled"),
        diameter:   $("#tyre-diameter").prop("disabled", "disabled")
    },

    vehicleSearchControl = {
        brand:      $("#vehicle_brand").prop("disabled", "disabled"),
        model:      $("#vehicle_model").prop("disabled", "disabled"),
        engine:     $("#vehicle_engine").prop("disabled", "disabled"),
        years:      $("#vehicle_years").prop("disabled", "disabled"),
        dimensions: $("#tire_dimension").prop("disabled", "disabled")
    },

    vehicleCategory =   $("#vehicle_category"),
    searchMethod =      $("#search_method"),
    tyreInfoPanel =     $('.tyre-info'),

    tFilter = {
        /* pagination */
        total: 0,
        current_page: 1,
        last_page: 0,
        per_page: 10,
        from: 0,
        to: 0,
        next_page: 0,
        prev_page: 0,

        /* ordering */
        order: "price_with_tax|asc",

        /* filters */
        vehicle_category: "",
        search_method: "",
        searchQuery: "",
        width: "",
        ratio: "",
        diameter: "",
        vehicle_brand: "",
        vehicle_model: "",
        vehicle_engine: "",
        vehicle_years: "",
        vehicle_tire_dimension: "",

        /* aggregations */
        manufacturers: "",
        speed_indexes: "",
        seasons: "",

        /* source */
        source : ''
    },

    submitDimension = $('#btnSearchByDimension'),

    submitVehicles = $('#getTiresByVehicle');

function init_form() {
    alert("Test vite")
    initializeFormData();

    $('ul.vehicles.clearfix li > a[data-vehicle_category="' + tFilter.vehicle_category + '"]').addClass("active");

    $('.search ul.nav.nav-tabs li > a[data-search_method="'+tFilter.search_method+'"]').tab('show');

    updateSearchForm(tFilter.search_method)
}

submitDimension.on('click', function(e){
    e.preventDefault();
    if (isHomePage()){
        setProductFilterCookie();
        window.location=urlTo('products');
    } else
        showProducts(tFilter);
});

submitVehicles.on('click', function(e){
    e.preventDefault();
    if (isHomePage()){
        setProductFilterCookie();
        window.location=urlTo('products');
    } else
        showProducts(tFilter);
});

function initializeFormData() {

    getFilterFromUrl();

    if (tFilter.source!='url') {
        var c;

        c = getProductFilterCookie();

        if (c != undefined) {
            tFilter = c;
            tFilter.source = 'cookie';
        }
    }

    if ((typeof tFilter.vehicle_category === 'undefined') || tFilter.vehicle_category == '') {
        tFilter.vehicle_category = "Putničko"
    }

    setVehicleCategory(tFilter.vehicle_category);

    if ((typeof tFilter.search_method == 'undefined') || tFilter.search_method == '') {
        tFilter.search_method = "byDimension"
    }

    setSearchMethod(tFilter.search_method);


    if ((typeof tFilter.order == 'undefined') || tFilter.order == '') {
        tFilter.order = "price_with_tax|asc"
    }

    if ((typeof tFilter.per_page == 'undefined')|| tFilter.per_page == '' ) {
        tFilter.per_page = "10"
    }
}

function updateAllDimensions(){

    $.ajax({
        type: "GET",
        url: urlTo('api/products/dimensions/selected/bundle'),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data : tFilter
    }).done(function(result) {

        $.each(el, function(i, element){
            element.off('change');
        });


        fillAggregations(result.data.widths, el.width);
        fillAggregations(result.data.ratios, el.ratio);
        fillAggregations(result.data.diameters, el.diameter);

        el.width.val(tFilter.width);
        el.ratio.val(tFilter.ratio);
        el.diameter.val(tFilter.diameter);

        $.each(el, function(i, element){
            element.prop('disabled', false);
        });

        el.width.on('change', widthOnChange);
        el.ratio.on('change', ratioOnChange);
        el.diameter.on('change', diameterOnChange);

        if (!isHomePage()){

            showProducts(tFilter);

        }

    });
}

function updateAllVehicles(){
    $.ajax({
        type: "GET",
        url: urlTo('api/vehicles/michelin/selected/bundle'),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data : tFilter
    }).done(function(result) {

        $.each(vehicleSearchControl, function(i, element){
            element.off('change');
        });

        fillBrands(result.data.brands, vehicleSearchControl.brand);
        fillModels(result.data.models, vehicleSearchControl.model);
        fillAggregations(result.data.engines, vehicleSearchControl.engine);
        fillAggregations(result.data.years, vehicleSearchControl.years);
        fillVehicleTyreDimensions(result.data.dimensions, vehicleSearchControl.dimensions);


        vehicleSearchControl.brand.val(tFilter.vehicle_brand);
        vehicleSearchControl.model.val(tFilter.vehicle_model);
        vehicleSearchControl.engine.val(tFilter.vehicle_engine);
        vehicleSearchControl.years.val(tFilter.vehicle_years);
        vehicleSearchControl.dimensions.val(tFilter.vehicle_tire_dimension);

        $.each(vehicleSearchControl, function(i, element){
            element.prop("disabled", false);
        });

        vehicleSearchControl.brand.on('change', brandOnChange);
        vehicleSearchControl.model.on('change', modelOnChange);
        vehicleSearchControl.engine.on('change', engineOnChange);
        vehicleSearchControl.years.on('change', yearsOnChange);
        vehicleSearchControl.dimensions.on('change', vehicleDimensionsOnChange);

        /*
        if (!isHomePage()){

            showProducts(tFilter);
        }
        */
    });
}

function updateSearchForm(searchMethod) {
    switch (searchMethod) {
        case "byKeywords":
            updateSearchFormByKeyWords();
            break;
        case "byVehicle":
            updateSearchFormByVehicle();
            break;
        case "byDimension":
            updateSearchFormByDimension();
            break
    }
}

function updateSearchFormByDimension() {

    if ((tFilter.source=='cookie')||(tFilter.source=='url')) {
        updateAllDimensions();
        tFilter.source='';
    } else {
        disableSelect2(1, el);

        loadWidths(el.width, tFilter.width);
    }

}


function updateSearchFormByVehicle() {
    if ((tFilter.source=='cookie')||(tFilter.source=='url')) {
        updateAllVehicles();
        tFilter.source='';
    } else {
        disableSelect2(1, vehicleSearchControl);

        loadVehicleBrands(vehicleSearchControl.brand, tFilter.vehicle_brand);
    }
}

function updateSearchFormByKeyWords() {
    $("#searchQuery").val(tFilter.searchQuery);
    showProducts(tFilter)
}

vehicleCategory.on('change', function(){

    disableSelect2(0, el);

    el.width.prop('disabled', false);

    tFilter.vehicle_category = $(this).val();

});

searchMethod.on('change', function(){

    tFilter.search_method = $(this).val();

});


function loadWidths(element, selectedValue) {

    var route  = "api/products/tyres/dimensions/widths/"+tFilter.vehicle_category;

    fillSelect2Options(route, element, selectedValue, fillAggregations);
}

function loadRatios(element, selectedValue) {

    if (tFilter.width){

        var route  = 'api/products/tyres/dimensions/ratios/'+tFilter.vehicle_category+'/'+tFilter.width;

        fillSelect2Options(route, element, selectedValue, fillAggregations);

    } else

        element.empty();
}

function loadDiameters(element, selectedValue) {

    if (tFilter.width&&tFilter.ratio){

        var route  = "api/products/tyres/dimensions/diameters/"+tFilter.vehicle_category+"/"+tFilter.width+"/"+tFilter.ratio;

        fillSelect2Options(route, element, selectedValue, fillAggregations);

    } else

        element.empty();
}

var widthOnChange = function (){

    if ($(this).val()) {

        disableSelect2(1, el);

        tFilter.page = 1;

        tFilter.width = el.width.val();

        setProductFilterCookie();

        clearFacetQuery();

        loadRatios(el.ratio, tFilter.ratio);
    }
};

el.width.on("change", widthOnChange);

var ratioOnChange =function () {
    if ($(this).val()) {

        disableSelect2(2, el);

        tFilter.page = 1;

        tFilter.width = el.width.val();

        tFilter.ratio = el.ratio.val();

        setProductFilterCookie();
        clearFacetQuery();
        loadDiameters(el.diameter, tFilter.diameter);
    }
};

el.ratio.on("change", ratioOnChange);

var diameterOnChange = function(){

    if ($(this).val()) {

        disableSelect2(3, el);

        tFilter.page = 1;
        tFilter.width = el.width.val();
        tFilter.ratio = el.ratio.val();
        tFilter.diameter = el.diameter.val();

        setProductFilterCookie();

        clearFacetQuery();
/*
        if (!isHomePage()){

            showProducts(tFilter);
        }
*/
    }
};

el.diameter.on("change", diameterOnChange);

function clearFilter() {

    tFilter.searchQuery = "";

    tFilter.width = "";
    tFilter.ratio = "";
    tFilter.diameter = "";

    tFilter.vehicle_brand = "";
    tFilter.model_model = "";
    tFilter.vehicle_engine = "";
    tFilter.vehicle_years = "";
    tFilter.vehicle_tire_dimension = "";

    tFilter.manufacturers = "";
    tFilter.speed_indexes = "";
    tFilter.seasons = "";

    $("input:checked", "#tire_dimension").prop("checked", false)
}

function clearFacetQuery() {
    tFilter.speed_indexes = "";
    tFilter.manufacturers = "";
    tFilter.seasons = "";
    setProductFilterCookie()
}

$('.filters').on('click','.facet-checkbox',function(){
    facetClick($(this).attr('data-facet'));
});

function setFacetStatus(e) {
    var t = getProductFilterCookie(),
        n = [],
        r = [];
    if (t != undefined) {
        tFilter = t;
        if (tFilter[e] != undefined) {
            r = tFilter[e];
            if (r.length > 0) {
                n = r.split("|");
                $.each(n, function(t, n) {
                    $("#" + e + '_facet [data-id="' + n + '"]').attr("checked", true)
                })
            }
        }
    }
}

function facetClick(e) {
    var t = [];
    var n = $("input:checked", "#" + e + "_facet");
    tFilter[e] = "";
    if (n.length > 0) {
        n.each(function() {
            t.push($(this).data("id"))
        });
        tFilter[e] = t.join("|")
    }
    setProductFilterCookie();
    showProducts(tFilter)
}

function buildFacet(e, t) {
    var n = e,
        r = $("#" + t + "_facet").html(""),
        i = false;
    for (var s = 0; s < n.length; s++) {
        i = tFilter[t] == n[s].key;
        r.append('<div class="checkbox"> <label><input type="checkbox" class="facet-checkbox" name="' + t + '[]" data-facet="'+t+'" data-id="'+ n[s].key+'" >'+n[s].key +' ('+n[s].doc_count+')</label> </div>');
        $("." + t + '[data-id="' + n[s].key + '"]').attr("checked", i)
    }
}

function loadFacet(e) {
    var t = "";
    if (e != null) {
        t = "seasons";
        buildFacet(e[t]["buckets"], t);
        setFacetStatus(t);
        t = "manufacturers";
        buildFacet(e[t]["buckets"], t);
        setFacetStatus(t);
        t = "speed_indexes";
        buildFacet(e[t]["buckets"], t);
        setFacetStatus(t)
    }
}

function setProductFilterCookie(data) {

    if (typeof data=='undefined'){
        data=tFilter;
    }

    sessionStorage.setItem("gumamax_tyres_filter", JSON.stringify(data));
}

function getProductFilterCookie() {
    var c = sessionStorage.getItem("gumamax_tyres_filter");
    return JSON.parse(c);
}

var brandOnChange = function(){
    if ($(this).val()) {
        disableSelect2(1, vehicleSearchControl);

        tyreInfoPanel.hide();

        tFilter.page = 1;

        tFilter.vehicle_brand = vehicleSearchControl.brand.val();

        setProductFilterCookie();

        clearFacetQuery();

        loadVehicleModels(vehicleSearchControl.model, tFilter.vehicle_model);
    }
};

vehicleSearchControl.brand.on("change", brandOnChange);

var modelOnChange=function(){
    if ($(this).val()) {

        disableSelect2(2, vehicleSearchControl);

        tyreInfoPanel.hide();

        tFilter.page = 1;

        tFilter.vehicle_brand = vehicleSearchControl.brand.val();

        tFilter.vehicle_model = vehicleSearchControl.model.val();

        setProductFilterCookie();

        clearFacetQuery();

        loadVehicleEngines(vehicleSearchControl.engine, tFilter.vehicle_engine);
    }
};
vehicleSearchControl.model.on("change", modelOnChange);

var engineOnChange = function() {
    if ($(this).val()) {

        disableSelect2(3, vehicleSearchControl);

        tyreInfoPanel.hide();

        tFilter.page = 1;

        tFilter.vehicle_brand = vehicleSearchControl.brand.val();

        tFilter.vehicle_model = vehicleSearchControl.model.val();

        tFilter.vehicle_engine = vehicleSearchControl.engine.val();

        setProductFilterCookie();

        clearFacetQuery();

        loadVehicleYears(vehicleSearchControl.years, tFilter.vehicle_years);
    }

};
vehicleSearchControl.engine.on("change", engineOnChange);

var yearsOnChange = function() {
    if ($(this).val()) {

        tyreInfoPanel.hide();

        disableSelect2(4, vehicleSearchControl);

        tFilter.page = 1;

        tFilter.vehicle_brand = vehicleSearchControl.brand.val();


        tFilter.vehicle_model = vehicleSearchControl.model.val();

        tFilter.vehicle_engine = vehicleSearchControl.engine.val();

        tFilter.vehicle_years = vehicleSearchControl.years.val();

        setProductFilterCookie();

        clearFacetQuery();

        loadVehicleTireDimensions(vehicleSearchControl.dimensions, tFilter.vehicle_tire_dimension);
    }
};
vehicleSearchControl.years.on("change", yearsOnChange);

var vehicleDimensionsOnChange = function() {
    if ($(this).val()) {
        disableInput(5, vehicleSearchControl);

        var selectedTyre = $(this).find('option:selected');

        tyreInfoPanel.hide();

        tFilter.page = 1;

        tFilter.vehicle_brand = vehicleSearchControl.brand.val();

        tFilter.vehicle_model = vehicleSearchControl.model.val();

        tFilter.vehicle_engine = vehicleSearchControl.engine.val();

        tFilter.vehicle_years = vehicleSearchControl.years.val();

        tFilter.vehicle_tire_dimension = vehicleSearchControl.dimensions.val();

        tFilter.width   =   selectedTyre.data('width');

        tFilter.diameter = selectedTyre.data('radial');

        tFilter.ratio   =selectedTyre.data('ratio');

        setProductFilterCookie();

        clearFacetQuery();
/*
        showProducts(tFilter);
*/
        displayInfo(selectedTyre);

    }
};

vehicleSearchControl.dimensions.on("change", vehicleDimensionsOnChange);


$('ul.vehicles.clearfix').on('click', 'li a', function() {

    setVehicleCategory($(this).data("vehicle_category"));

    setProductFilterCookie();

    clearFilter();

    updateSearchForm(tFilter.search_method);

    showProducts(tFilter)

});

$('.search ul.nav.nav-tabs').on("click", "li a", function() {

    setSearchMethod($(this).data("search_method"));

    setProductFilterCookie();

    updateSearchForm(tFilter.search_method);

});

$(".container").on("click", ".facet", function() {
    facetClick($(this).attr("data-facet"))
});

function checkSearchCriteria(){

    switch (tFilter.search_method) {
        case "byKeywords":
            return false;
            break;
        case "byVehicle":
            return (tFilter.vehicle_tire_dimension);
            break;
        case "byDimension":
            return (tFilter.width&&tFilter.ratio&&tFilter.diameter);
            break;
        default:
            return false;
    }
}

$('.filters-box .reset').on("click", function(e){
    e.preventDefault();
    clearFacetQuery();
    showProducts(tFilter);
});


function setVehicleCategory(value) {
    vehicleCategory.val(value).change();
}

function setSearchMethod(value){
    searchMethod.val(value).change();
}


function loadVehicleBrands(element, selectedValue) {

    var route  = "api/vehicles/michelin/brands";

    fillSelect2Options(route, element, selectedValue, fillBrands);
}

function loadVehicleModels(element, selectedValue) {

    if (tFilter.vehicle_brand) {

        var route  = "api/vehicles/michelin/models/"+tFilter.vehicle_brand;

        fillSelect2Options(route, element, selectedValue, fillModels);

    } else
        element.empty();
}

function loadVehicleEngines(element, selectedValue) {

    if (tFilter.vehicle_brand&&tFilter.vehicle_model) {

        var route  = "api/vehicles/michelin/engines/"+tFilter.vehicle_brand+'/'+tFilter.vehicle_model;

        fillSelect2Options(route, element, selectedValue, fillAggregations);

    } else
        element.empty();
}

function loadVehicleYears(element, selectedValue) {

    if (tFilter.vehicle_brand&&tFilter.vehicle_model&&tFilter.vehicle_engine) {

        var route  = "api/vehicles/michelin/years/"+tFilter.vehicle_brand+'/'+tFilter.vehicle_model+'/'+tFilter.vehicle_engine;

        fillSelect2Options(route, element, selectedValue, fillAggregations);

    } else
        element.empty();
}

function loadVehicleTireDimensions(element, selectedValue) {

    if (tFilter.vehicle_brand&&tFilter.vehicle_model&&tFilter.vehicle_engine&&tFilter.vehicle_years){
        var route  = "api/vehicles/michelin/dimensions/"+tFilter.vehicle_brand+'/'+tFilter.vehicle_model+'/'+tFilter.vehicle_engine+'/'+tFilter.vehicle_years;
        fillSelect2Options(route, element, selectedValue, fillVehicleTyreDimensions);
    } else {

        element.empty();
    }

}

function fillVehicleTyreDimensions(data, element) {
    if (data) {
            $.each(data, function (i, tyrePackages) {
             var optgroup = $('<optgroup>').val('pack-' + tyrePackages.package_id).prop('label', 'Opcija: ' + tyrePackages.package_id).appendTo(element);

             $.each(tyrePackages.package, function (j, item) {

                 var itemText = item.width + '/' + item.ratio + ' R' + item.radial + ' ' + item.load + ' ' + item.speed + ' ' + translateTirePosition(item.position);
                 var itemValue = item.width + '/' + item.ratio + 'R' + item.radial;
                 optgroup.data('radial', item.radial);
                 optgroup.prop('label', item.radial + ' "');
                 $('<option>')
                     .val(itemValue)
                     .data('position', item.position)
                     .data('width', item.width)
                     .data('ratio', item.ratio)
                     .data('radial', item.radial)
                     .data('load', item.load)
                     .data('speed', item.speed)
                     .data('normalpressure', item.normalpressure)
                     .data('highwaypressure', item.highwaypressure)
                     .data('season', item.season)
                     .text(itemText)
                     .appendTo(optgroup);
             })
        });
    }
}

function fillBrands(data, element){

    var optgroup = $('<optgroup>').val('top-brands').prop('label', 'Najtraženiji').appendTo(element);

    $.each(data.top, function(i, item) {
        $('<option>').val(item).text(item).appendTo(optgroup);
    });

    optgroup = $('<optgroup>').val('all-brands').prop('label', 'Svi').appendTo(element);

    fillAggregations(data.all, optgroup);

}

function fillModels(data, element){
    if (data){
        $.each(data, function(i, groups) {
            var optgroup = $('<optgroup>').val(groups.name).prop('label', groups.name).appendTo(element);
            fillAggregations(groups.models, optgroup);
        });
    }
}

function displayInfo(element){

    tyreInfoPanel.hide();

    var normalPressure = element.data('normalpressure');
    var highwayPressure = element.data('highwaypressure');

    var position = element.data('position');

    var normalPressureDiv = $('div.pressure.'+position+'.normalpressure span');
    var highwayPressureDiv = $('div.pressure.'+position+'.highwaypressure span');

    normalPressureDiv.text('');
    highwayPressureDiv.text('');

    if (element.data('position').length>0){

        if (normalPressure>0) {
            //pressureDiv = $('<div>').addClass('pressure').addClass(element.data('position')).addClass('normalpressure').html('<span>Normal: '+normalPressure+' bar </span>');
            normalPressureDiv.text(normalPressure);
        }
        if (highwayPressure>0) {
            highwayPressureDiv.text(highwayPressure);
        }
    }

    $('[data-toggle="tooltip"]').tooltip();

/*
    if (element.data('speed')!==''){
        li = $('<li>').addClass('list-group-item').text('Brzinski index: '+element.data('speed'));
        li.appendTo(ul);
    }

    if (element.data('load')>0){
        li = $('<li>').addClass('list-group-item').text('Indeks nosivosti: '+element.data('load'));
        li.appendTo(ul);
    }


    if (element.data('season')!==''){
        li = $('<li>').addClass('list-group-item').text('Samo za: '+element.data('season'));
        li.appendTo(ul);
    }

    ul.appendTo(tyreInfoPanel);
    */

    tyreInfoPanel.show();

}

function translateTirePosition(s){
    switch (s) {
        case "front":
            return "prednji";
        case "rear":
            return "zadnji";
        default :
            return "prednji i zadnji";
    }
}

function isHomePage(){

    return (getCurrentEndPoint()=='');

}

function getFilterFromUrl(){
    for (var property in tFilter) {
        if (tFilter.hasOwnProperty(property)) {
            if (getUrlParameter(property) !== undefined) {
                tFilter[property] = getUrlParameter(property);
                tFilter.source='url';
            }
        }
    }
}

