const initPageSingleItem = () => {
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

const addToCart = () => {
    const pathSplitList = window.location.href.split("/")
    const mProdId = parseInt(pathSplitList.at(pathSplitList.length-2))
}

const addFeaturedToCart = (featuredId) => {
    console.log(featuredId)
}
