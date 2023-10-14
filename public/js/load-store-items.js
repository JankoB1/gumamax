let current_page = 1
let last_page

let product_ids = []

const PER_PAGE = 4

const forward = document.getElementById("navForward")
const backward = document.getElementById("navBackward")

let mManufacturers = ""
let mSeasons = ""
const initPage = () => {

    forward.onclick = navForward
    backward.onclick = navBackward

    if (current_page === last_page) {
        forward.style.color = "LightGray"
        forward.onclick = null
    }
    if (current_page === 1) {
        backward.style.color = "LightGray"
        backward.onclick = null
    }

    loadStoreItems(4,"","").then(
        () => loadItemData(4,"","")
    )
}

const checkNavForward = () => {
    if (current_page === last_page) {
        forward.style.color = "LightGray"
        forward.classList.remove("ripple")
        forward.onclick = null
    } else {
        forward.onclick = navForward
    }

    if (current_page !== 1) {
        backward.style.color = ""
        backward.classList.add("ripple")
        backward.onclick = navBackward
    }
}

const navForward = () => {
    current_page++

    forward.onclick = null
    backward.onclick = null

    loadStoreItems(PER_PAGE,mManufacturers, mSeasons).then( () =>
        loadItemData(PER_PAGE,mManufacturers, mSeasons).then(() => checkNavForward())
    )
}

const checkNavBackward = () => {
    if (current_page !== last_page) {
        forward.style.color = ""
        forward.classList.add("ripple")
        forward.onclick = navForward
    }

    if (current_page === 1) {
        backward.style.color = "LightGray"
        backward.classList.remove("ripple")
        backward.onclick = null
    }else{
        backward.onclick = navBackward
    }
}

const navBackward = () => {
    current_page--

    forward.onclick = null
    backward.onclick = null

    loadStoreItems(PER_PAGE,mManufacturers, mSeasons).then( () =>
        loadItemData(PER_PAGE,mManufacturers, mSeasons).then(() => checkNavBackward())
    )
}

const loadStoreItems = async (per_page, manufacturers, seasons) => {
    let htmlDiv = await fetch(urlTo("prodavnica/items")
        + "?page=" + current_page
        + "&manufacturers=" + manufacturers
        + "&seasons="+ seasons
        + "&per_page=" + per_page
        )
    document.getElementById("item-col").innerHTML = await htmlDiv.text()
}

const loadItemData = async (per_page, manufacturers, seasons) => {
    let data = await fetch(urlTo("api/products/tyres/search")
        + "?page=" + current_page
        + "&manufacturers=" + manufacturers
        + "&seasons="+ seasons
        + "&per_page=" + per_page
    )
    let jsonData = await data.json()

    const perPage = jsonData["pagination"]["per_page"]
    const currPage = jsonData["pagination"]["current_page"]
    const total = jsonData["pagination"]["total"]
    const ofTotalResultText = ((currPage-1)*perPage+1)  + "-" + ((currPage)*perPage) + " od " + total + " rezultata"

    last_page = total/perPage + (total%perPage>0?1:0)

    data = jsonData["data"]
    //mapiraj poziciju na stranici u koloni na product_id
    let cnt = -1
    data.forEach((e) => product_ids[++cnt] = e["product_id"])

    document.getElementById("result-numbering").innerText = ofTotalResultText

    current_page = currPage
}

const itemDetails = (index) => {
    let mProdId = product_ids[index]
    window.location.href = urlTo("proizvod/" + mProdId)
}

const addToCart = (index) => {
    //ovo je prod id konkretnog proizvoda
    let mProdId = product_ids[index]
    //TODO: dodavanje proizvoda u korpu
}

const urlTo = (uri) => {
    var i = uri.indexOf('/'),
        s='';
    if (i!==0) {
        s = window.location.hostname + '/'+uri
    }else
        s = window.location.hostname + uri;
    return  window.location.protocol+'//'+ s;
}

const refresh = () => {
    mManufacturers = ""
    mSeasons = ""

    document.getElementById("brands-filter")
        .querySelectorAll("input[type=checkbox]:checked")
        .forEach((e,k,p) => {
        mManufacturers = mManufacturers.concat("," + e.value)
    })
    mManufacturers = mManufacturers.slice(1,)

    document.getElementById("seasons-filter")
        .querySelectorAll("input[type=checkbox]:checked")
        .forEach((e,k,p) => {
            mSeasons = mSeasons.concat("," + e.value)
    })
    mSeasons = mSeasons.slice(1,)
    console.log(mSeasons)

    current_page = 1
    loadStoreItems(4,mManufacturers,mSeasons).then(
        () => loadItemData(4,mManufacturers,mSeasons).then( () => {
            checkNavForward()
            checkNavBackward()
            })
    )
}

