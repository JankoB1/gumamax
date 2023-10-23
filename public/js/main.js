const searchBtn = document.getElementById("homepage-dims-search-btn")

const tyreWidthCbx = document.getElementById("tyre-width")
const tyreHeightCbx = document.getElementById("tyre-height")
const tyreDiameterCbx = document.getElementById("tyre-diameter")

let vehicleType = "Putničko"
let seasons = ""

const init_home = () => {
    searchBtn.onclick = goToShop
}

const goToShop = () => {
    window.location.href = urlTo("/prodavnica")
        + "?diameter=" + tyreDiameterCbx.value
        + "&width=" + tyreWidthCbx.value
        + "&ratio=" + tyreHeightCbx.value
        + "&vehicle_category=" + vehicleType
        + "&seasons=" + seasons
}

