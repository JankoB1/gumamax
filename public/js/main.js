const searchBtn = document.getElementById("homepage-dims-search-btn")

const tyreWidthCbx = el.width
const tyreHeightCbx = el.ratio
const tyreDiameterCbx = el.diameter

let vehicleType = "Putničko"
let seasons = ""

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

const init_home = () => {
    searchBtn.onclick = goToShop
    mapCommonlyUsedDimens()

    categoryElements.car.on("click", () => {
        console.log("Putni%C4%8Dko")
        vehicleType = "Putni%C4%8Dko";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.car.addClass('active');
    })
    categoryElements.jeep.on("click", () => {
        console.log("4x4")
        vehicleType = "4x4";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.jeep.addClass('active');
    })
    categoryElements.truck.on("click", () => {
        console.log("Kamioni%20i%20autobusi")
        vehicleType = "Kamioni%20i%20autobusi";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.truck.addClass('active');
    })
    categoryElements.combi.on("click", () => {
        console.log("Dostavno%20vozilo")
        vehicleType = "Dostavno%20vozilo";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.combi.addClass('active');
    })
    categoryElements.motorbike.on("click", () => {
        console.log("Motocikli%20i%20skuteri")
        vehicleType = "Motocikli%20i%20skuteri";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.motorbike.addClass('active');
    })
    categoryElements.bike.on("click", () => {
        console.log("Bicikl")
        vehicleType = "Bicikl";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.bike.addClass('active');
    })
    categoryElements.tractor.on("click", () => {
        console.log("Poljoprivredno%20vozilo")
        vehicleType = "Poljoprivredno%20vozilo";
        let activeVehicle = document.querySelector('.vehicles-row .col.active');
        if(activeVehicle) {
            activeVehicle.classList.remove('active');
        }
        categoryElements.tractor.addClass('active');
    })
}

const goToShop = () => {
    window.location.href = urlTo("/gume")
        + "?diameter=" + tyreDiameterCbx.val()
        + "&width=" + tyreWidthCbx.val()
        + "&ratio=" + tyreHeightCbx.val()
        + "&vehicle_category=" + vehicleType
        + "&seasons=" + seasons
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

