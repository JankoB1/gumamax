let searchBtn = document.getElementById("homepage-dims-search-btn")

const tyreWidthCbx = el.width
const tyreHeightCbx = el.ratio
const tyreDiameterCbx = el.diameter

let seasons = "Leto"

let commonlyUsedSelectors = new Map()

let categoryElements = {
    car: $("#cat-car"),
    jeep: $("#cat-jeep"),
    truck: $("#cat-truck"),
    combi: $("#cat-combi"),
    motorbike: $("#cat-moto"),
    bike: $("#cat-bike"),
    tractor: $("#cat-tractor")
}

let seasonElements = {
    wint: $("#season-winter"),
    summ: $("#season-summer"),
    all: $("#season-all")
}

const init_home = () => {
    searchBtn.onclick = goToShop

    $.each(seasonElements, (i, elem) => {
        elem.off("click")
    })

    $.each(categoryElements, (i, elem) => {
        elem.off("click")
    })
    $("#tyre-dimensions").off("click")
    $("#tyre-vehicle-tab").off("click")

    $("#tyre-dimensions").on("click", () => {
        tFilter.search_method = "byDimension"
        console.log(tFilter.search_method)
        categoryElements = {
            car: $("#cat-car"),
            jeep: $("#cat-jeep"),
            truck: $("#cat-truck"),
            combi: $("#cat-combi"),
            motorbike: $("#cat-moto"),
            bike: $("#cat-bike"),
            tractor: $("#cat-tractor")
        }
        seasonElements = {
            wint: $("#season-winter"),
            summ: $("#season-summer"),
            all: $("#season-all")
        }
        searchBtn = document.getElementById("homepage-dims-search-btn")
        init_home()
    })

    $("#tyre-vehicle-tab").on("click", () => {
        tFilter.search_method = "byVehicle"
        console.log(tFilter.search_method)
        categoryElements = {
            car: $("#cat-car-vh"),
            jeep: $("#cat-jeep-vh"),
            truck: $("#cat-truck-vh"),
            combi: $("#cat-combi-vh"),
            motorbike: $("#cat-moto-vh"),
            bike: $("#cat-bike-vh"),
            tractor: $("#cat-tractor-vh")
        }
        seasonElements = {
            wint: $("#season-winter-vh"),
            summ: $("#season-summer-vh"),
            all: $("#season-all-vh")
        }
        searchBtn = document.getElementById("homepage-vehs-search-btn")
        init_home()
    })

    if(tFilter.search_method === "byDimension") {mapCommonlyUsedDimens()}

    seasons = "Letnja"
    let activeSeason = document.querySelector('.year-seasons .col.active');
    if(activeSeason) {
        activeSeason.classList.remove('active');
    }
    seasonElements.summ.addClass('active');

    seasonElements.wint.on("click", () => {
        seasons = "Zimska"
        console.log(seasons)
        let activeSeason = document.querySelector('.year-seasons .col.active');
        if(activeSeason) {
            activeSeason.classList.remove('active');
        }
        seasonElements.wint.addClass('active');
    });
    seasonElements.summ.on("click", () => {
        seasons = "Letnja"
        console.log(seasons)
        let activeSeason = document.querySelector('.year-seasons .col.active');
        if(activeSeason) {
            activeSeason.classList.remove('active');
        }
        seasonElements.summ.addClass('active');
    });
    seasonElements.all.on("click", () => {
        seasons = "Sve+sezone"
        console.log(seasons)
        let activeSeason = document.querySelector('.year-seasons .col.active');
        if(activeSeason) {
            activeSeason.classList.remove('active');
        }
        seasonElements.all.addClass('active');
    });

    tFilter.vehicle_category = "Putni%C4%8Dko";
    let activeVehicle = document.querySelector('.vehicles-row .col.active');
    if(activeVehicle) {
        activeVehicle.classList.remove('active');
    }
    categoryElements.car.addClass('active');

    categoryElements.car.on("click", () => {
        console.log("Putni%C4%8Dko")
        tFilter.vehicle_category = "Putni%C4%8Dko";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.car.addClass('active');
        updateSearchForm(tFilter.search_method)
    })
    categoryElements.jeep.on("click", () => {
        console.log("4x4")
        tFilter.vehicle_category = "4x4";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.jeep.addClass('active');
        updateSearchForm(tFilter.search_method)
    })
    categoryElements.truck.on("click", () => {
        console.log("Kamioni%20i%20autobusi")
        tFilter.vehicle_category = "Kamioni%20i%20autobusi";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.truck.addClass('active');
        updateSearchForm(tFilter.search_method)
    })
    categoryElements.combi.on("click", () => {
        console.log("Dostavno%20vozilo")
        tFilter.vehicle_category = "Dostavno%20vozilo";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.combi.addClass('active');
        updateSearchForm(tFilter.search_method)
    })
    categoryElements.motorbike.on("click", () => {
        console.log("Motocikli%20i%20skuteri")
        tFilter.vehicle_category = "Motocikli%20i%20skuteri";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.motorbike.addClass('active');
        updateSearchForm(tFilter.search_method)
    })
    categoryElements.bike.on("click", () => {
        console.log("Bicikl")
        tFilter.vehicle_category = "Bicikl";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.bike.addClass('active');
        updateSearchForm(tFilter.search_method)
    })
    categoryElements.tractor.on("click", () => {
        console.log("Poljoprivredno%20vozilo")
        tFilter.vehicle_category = "Poljoprivredno%20vozilo";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.tractor.addClass('active');
        updateSearchForm(tFilter.search_method)
    })

    updateSearchForm(tFilter.search_method)
}

const goToShop = () => {
    if (tFilter.search_method === "byDimension") {
        window.location.href = urlTo("/gume")
            + "?diameter=" + tyreDiameterCbx.val()
            + "&width=" + tyreWidthCbx.val()
            + "&ratio=" + tyreHeightCbx.val()
            + "&vehicle_category=" + tFilter.vehicle_category
            + "&seasons=" + seasons
            + "&search_method=" + tFilter.search_method
    } else if (tFilter.search_method === "byVehicle") {
        const splitDimensions1 = vehicleSearchControl.dimensions.val().split('/')
        const width = splitDimensions1[0]

        const splitDimensions2 =splitDimensions1[1].split('R')
        const ratio = splitDimensions2[0]
        const diameter = splitDimensions2[1]

        window.location.href = urlTo("/gume")
            + "?diameter=" + diameter
            + "&width=" + width
            + "&ratio=" + ratio
            + "&vehicle_category=" + tFilter.vehicle_category
            + "&seasons=" + seasons
            + "&search_method=" + tFilter.search_method
            + "&vehicle_brand=" + vehicleSearchControl.brand.val()
            + "&vehicle_model=" + vehicleSearchControl.model.val()
            + "&vehicle_engine=" + vehicleSearchControl.engine.val()
            + "&vehicle_years=" + vehicleSearchControl.years.val()
            + "&vehicle_tire_dimension=" + vehicleSearchControl.dimensions.val()
    }
}

const mapCommonlyUsedDimens = () => {
    let cd = document.querySelector(".common-used-dimensions")
        .querySelectorAll(".single-dimension")

    for (let d of cd){
        commonlyUsedSelectors.set(d, d.querySelector("p").innerText)
    }
}

const clickCommonDim = (elem) => {
    $.each(el, function(i, element){
        element.off('change');
    });

    for (let d of commonlyUsedSelectors.keys()) {
        d.querySelector("p").style.backgroundColor = "White"
        d.querySelector("p").style.color = "Black"
    }
    const p = elem.querySelector("p")
    p.style.backgroundColor = "DarkGrey"
    p.style.color = "White"

    const dimensions = p.innerText.split("/")

    const baseRoute = 'api/products/tyres/dimensions/'

    const diameter = dimensions[2]

    tyreWidthCbx.val(dimensions[0])

    fetch(urlTo(baseRoute + "ratios/" + tFilter.vehicle_category + '/' + dimensions[0])).then(
        (data) => {
            data.json().then(
                (json) => {
                    fillAggregations(json[ "data"], el.ratio)
                    el.ratio.val(dimensions[1])
                    fetch(urlTo(baseRoute + "diameters/" + tFilter.vehicle_category + '/' + dimensions[0] + '/' + dimensions[1])).then(
                        (data) => {
                            data.json().then(
                                (json) => {
                                    fillAggregations(json["data"], el.diameter)
                                    el.diameter.val(diameter.substring(1, diameter.length))
                                }
                            )
                        }
                    )
                }
            )
        }
    )

    $.each(el, function(i, element){
        element.prop('disabled', false);
    });

    el.width.on('change', widthOnChange);
    el.ratio.on('change', ratioOnChange);
    el.diameter.on('change', diameterOnChange);
}

