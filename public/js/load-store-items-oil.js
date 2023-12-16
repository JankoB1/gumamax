let current_page = 1
let last_page

let product_ids = []

const forward = document.getElementById("navForward")
const backward = document.getElementById("navBackward")
const sort = document.getElementById("sort")

const PER_PAGE = 4
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

    loadStoreItems(PER_PAGE,"", sort.value).then(() => {
            loadItemData(PER_PAGE, "", sort.value).then(() =>  {})
            for (let elem of document.getElementsByClassName("plus")) {
                elem.onclick = () => {
                    let qtyElem = elem.parentNode.querySelector(".qty")
                    let currQty = parseInt(qtyElem.innerText)
                    qtyElem.innerText = currQty + 1
                }
            }
            for (let elem of document.getElementsByClassName("minus")) {
                elem.onclick = () => {
                    let qtyElem = elem.parentNode.querySelector(".qty")
                    let currQty = parseInt(qtyElem.innerText)
                    if (currQty > 1)
                        qtyElem.innerText = currQty - 1
                }
            }
        }
    )
}

const loadStoreItems = async (per_page, order) => {
    let htmlDiv = await fetch(urlTo("ulja/items")
        + "?page=" + current_page
        + "&order=" + order
        + "&per_page=" + per_page
    )
    document.getElementById("item-col").innerHTML = await htmlDiv.text()
}

const loadItemData = async (per_page, order) => {
    let data = await fetch(urlTo("api/products/oil/search")
        + "?page=" + current_page
        + "&order=" + order
        + "&per_page=" + per_page
    )
    let jsonData = await data.json()

    const perPage = jsonData["pagination"]["per_page"]
    const currPage = jsonData["pagination"]["current_page"]
    const total = jsonData["pagination"]["total"]
    const ofTotalResultText = ((currPage - 1) * perPage + 1) + "-" + ((currPage) * perPage) + " od " + total + " rezultata"

    last_page = total / perPage + (total % perPage > 0 ? 1 : 0)

    data = jsonData["data"]
    //mapiraj poziciju na stranici u koloni na product_id
    let cnt = -1
    data.forEach((e) => product_ids[++cnt] = e["product_id"])

    document.getElementById("result-numbering").innerText = ofTotalResultText

    current_page = currPage
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

    loadStoreItems(PER_PAGE, sort.value).then( () =>
        loadItemData(PER_PAGE, sort.value).then(() => checkNavForward())
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

    loadStoreItems(PER_PAGE, sort.value).then( () =>
        loadItemData(PER_PAGE, sort.value).then(() => checkNavBackward())
    )
}

const addToCart = (index,caller) => {
    //ovo je prod id konkretnog proizvoda
    let mProdId = product_ids[index]
    let quantity = parseInt(caller.parentNode.querySelector(".qty").innerText)
    console.log("ITEM " + mProdId + " qty " + quantity)
    //TODO: dodavanje proizvoda u korpu
}

const itemDetails = (index) => {
    let mProdId = product_ids[index]
    window.location.href = urlTo("proizvod/" + mProdId + "/ulje")
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
