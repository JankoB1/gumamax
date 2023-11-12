const searchBtn = document.getElementById("homepage-dims-search-btn")

const tyreWidthCbx = document.getElementById("tyre-width")
const tyreHeightCbx = document.getElementById("tyre-height")
const tyreDiameterCbx = document.getElementById("tyre-diameter")

let vehicleType = "Putničko"
let seasons = ""

let commonlyUsedSelectors = new Map()

const init_home = () => {
    searchBtn.onclick = goToShop
    mapCommonlyUsedDimens();
}

const goToShop = () => {
    window.location.href = urlTo("/gume")
        + "?diameter=" + tyreDiameterCbx.value
        + "&width=" + tyreWidthCbx.value
        + "&ratio=" + tyreHeightCbx.value
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
    for (let d of commonlyUsedSelectors.keys()) {
        d.querySelector("p").style.backgroundColor = "White"
        d.querySelector("p").style.color = "Black"
    }
    const p = elem.querySelector("p")
    p.style.backgroundColor = "DarkGrey"
    p.style.color = "White"

    const dimensions = p.innerText.split("/")

    const evt = document.createEvent("HTMLEvents");
    evt.initEvent("change", false, true);

    tyreWidthCbx.value = dimensions[0]

    tyreHeightCbx.value = dimensions[1]

    const diameter = dimensions[2]
    tyreDiameterCbx.value = diameter.substring(1, diameter.length)
}

