let current_page = 1
let last_page

const PER_PAGE = 4

const forward = document.getElementById("navForward")
const backward = document.getElementById("navBackward")

const initPage = () => {

    forward.onclick = navForward
    backward.onclick = navBackward

    if (current_page === last_page) {
        forward.style.color = "LightGray"
        forward.onclick = null
    }
    if (current_page === 0) {
        backward.style.color = "LightGray"
        backward.onclick = null
    }
}

const navForward = () => {
    current_page++

    loadStoreItems(PER_PAGE)
    loadItemData(PER_PAGE).then(() => {
        if (current_page === last_page) {
            forward.style.color = "LightGray"
            forward.onclick = null
        }
        if (current_page !== 0) {
            backward.style.color = ""
            backward.onclick = navBackward
        }
    })
}

const navBackward = () => {
    current_page--

    loadStoreItems(PER_PAGE)
    loadItemData(PER_PAGE).then(() => {
        console.log(current_page + " - " + last_page)
        if (current_page !== last_page) {
            forward.style.color = ""
            forward.onclick = navForward
        }
        if (current_page === 0) {
            backward.style.color = "LightGray"
            backward.onclick = null
        }
    })
}

const loadStoreItems = async (per_page) => {
    let htmlDiv = await fetch(urlTo("prodavnica/items")
        + "?page=" + current_page
        + "&per_page=" + per_page
        )
    document.getElementById("item-col").innerHTML = await htmlDiv.text()
}

const loadItemData = async (per_page) => {
    let data = await fetch(urlTo("api/products/tyres/search")
        + "?page=" + current_page
        + "&per_page=" + per_page
    )
    let jsonData = await data.json()

    const perPage = jsonData["pagination"]["per_page"]
    const currPage = jsonData["pagination"]["current_page"]
    const total = jsonData["pagination"]["total"]
    const ofTotalResultText = (currPage*perPage+1)  + "-" + ((currPage+1)*perPage) + " od " + total + " rezultata"

    last_page = total/perPage + (total%perPage>0?1:0)
    console.log(total + ", " + perPage + ", " + total/perPage + ", " + last_page)

    document.getElementById("result-numbering").innerText = ofTotalResultText

    current_page = currPage
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

