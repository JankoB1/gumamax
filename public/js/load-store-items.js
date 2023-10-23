let current_page = 1
let last_page

let product_ids = []
let compare_btn_map = new Map();

const PER_PAGE = 4

const COMPARE_LIST_COOKIE_NAME = "to_compare"

const refreshBtn = document.getElementById("refresh-btn")
const forward = document.getElementById("navForward")
const backward = document.getElementById("navBackward")
const sort = document.getElementById("sort")
const comparePopup = document.getElementById("compare-popup")

let mManufacturers = ""
let mSeasons = ""


function interceptUndefined(s){
    if (s === undefined)
        return ""
    else
        return s
}

const initPage = (seasons,diameter) => {

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

    loadStoreItems(PER_PAGE,"", seasons, sort.value).then(() => {
            loadItemData(PER_PAGE, "", seasons, sort.value).then(() =>  {})
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

    refreshBtn.onclick = refresh
    sort.onchange = () => {
        document.getElementById("radio-sort")
            .querySelectorAll("input[type=radio]")
            .forEach(
                (e,k,p) => e.checked = false
            )
        refresh()
    }

    for(let elem of document.getElementsByClassName("single-best-seller")){
        elem.onclick = () => {
            window.location.href = urlTo("proizvod/" + elem.getAttribute("product_id"))
        }
    }
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

    loadStoreItems(PER_PAGE,mManufacturers, mSeasons, sort.value).then( () =>
        loadItemData(PER_PAGE,mManufacturers, mSeasons, sort.value).then(() => checkNavForward())
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

    loadStoreItems(PER_PAGE,mManufacturers, mSeasons, sort.value).then( () =>
        loadItemData(PER_PAGE,mManufacturers, mSeasons, sort.value).then(() => checkNavBackward())
    )
}

const loadStoreItems = async (per_page, manufacturers, seasons, order) => {
    let htmlDiv = await fetch(urlTo("prodavnica/items")
        + "?page=" + current_page
        + "&order=" + order
        + "&manufacturers=" + manufacturers
        + "&seasons="+ seasons
        + "&per_page=" + per_page
        )
    document.getElementById("item-col").innerHTML = await htmlDiv.text()
}

const loadItemData = async (per_page, manufacturers, seasons, order) => {
    let data = await fetch(urlTo("api/products/tyres/search")
        + "?page=" + current_page
        + "&order=" + order
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

    compare_btn_map.clear()
    let compareBtns = [].slice.call(document.getElementById("item-col").getElementsByClassName("compare"))
    for (let b of compareBtns){
        let pr_id = product_ids[compareBtns.indexOf(b)]
        compare_btn_map.set(pr_id,b);
        if (getCookie(COMPARE_LIST_COOKIE_NAME).includes(pr_id + "#")) {
            b.style.backgroundColor = "lightgrey"
            addToComparePopup(pr_id)
        }
    }
}

const itemDetails = (index) => {
    let mProdId = product_ids[index]
    window.location.href = urlTo("proizvod/" + mProdId)
}

const addToComparePopup = async (prodId) => {
    let prod = await (await fetch(urlTo("/api/products/tyres/") + prodId)).json()
    if (getCookie(COMPARE_LIST_COOKIE_NAME).split("#").length !== 1) comparePopup.style.display = "block"
    comparePopup.innerHTML = " <div class=\"single-best-seller row\" product_id=\'" + prodId + "\'>\n" +
        "                            <div class=\"col-md-3\">\n" +
        "                                <img src=\"" + prod["image_url"] + "\" alt=\"product image\"\n class=\"compare-popup-product-image\">" +
        "                            </div>\n" +
        "                            <div class=\"col-md-9\">\n" +
        "                                <p>" + prod["additional_description"] + "</p>\n" +
        "                                <h6>" + parseFloat(prod["price_with_tax"]).toString().replace(".",",") + " RSD</h6>\n" +
        "                            </div>" +
        "                           <div class=\"col-md-2\">" +
        "                               <img src=\"http://localhost/images/visuals/delete-icon.svg\" id=\"compare-popup-delete\" onclick=\"iconRemoveFromComparePopup(" + prodId + ")\">" +
        "                           </div>"+
        "                        </div>"+
         comparePopup.innerHTML
}
const removeFromComparePopup = (prodId) => {
    for(let el of comparePopup.getElementsByClassName("single-best-seller")){
        if (parseInt(el.getAttribute("product_id")) === prodId)
            comparePopup.removeChild(el)
    }
}

const iconRemoveFromComparePopup = (prodId) => {
    setCookie(COMPARE_LIST_COOKIE_NAME,getCookie(COMPARE_LIST_COOKIE_NAME).replace(prodId + "#",""),1)
    compare_btn_map.get(prodId).style.backgroundColor = "white"
    removeFromComparePopup(prodId);
    if(getCookie(COMPARE_LIST_COOKIE_NAME).split("#").length === 1) comparePopup.style.display = "none"
}

const selectForCompare = (caller, index) => {
    let mProdId = product_ids[index]
    if (getCookie(COMPARE_LIST_COOKIE_NAME).includes(mProdId+"#")){
        setCookie(COMPARE_LIST_COOKIE_NAME,getCookie(COMPARE_LIST_COOKIE_NAME).replace(mProdId + "#",""),1)
        caller.style.backgroundColor = "white"
        removeFromComparePopup(mProdId);
        if(getCookie(COMPARE_LIST_COOKIE_NAME).split("#").length === 1) comparePopup.style.display = "none"
    }else if(getCookie(COMPARE_LIST_COOKIE_NAME).split("#").length < 5){
        setCookie(COMPARE_LIST_COOKIE_NAME,getCookie(COMPARE_LIST_COOKIE_NAME).concat(mProdId + "#"),1)
        caller.style.backgroundColor = "lightgrey"
        addToComparePopup(mProdId).then(r => {})
    }else{
        //TODO: Upozori o maks 4
        console.log("Max 4 " + getCookie(COMPARE_LIST_COOKIE_NAME).split("#").length)
    }
}

const getCookie = (cname) => {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const setCookie = (cname, cvalue, exdays) => {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const addToCart = (index,caller) => {
    //ovo je prod id konkretnog proizvoda
    let mProdId = product_ids[index]
    let quantity = parseInt(caller.parentNode.querySelector(".qty").innerText)
    console.log("ITEM " + mProdId + " qty " + quantity)
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

    current_page = 1

    document.getElementById("radio-sort")
        .querySelectorAll("input[type=radio]:checked").forEach((e,k,p) => sort.value = e.value)

    loadStoreItems(4,mManufacturers,mSeasons, sort.value).then(
        () => loadItemData(4,mManufacturers,mSeasons, sort.value).then( () => {
            checkNavForward()
            checkNavBackward()
            })
    )
}

