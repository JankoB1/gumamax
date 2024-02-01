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

const addToCart = (caller) => {
    const pathSplitList = window.location.href.split("/")
    const mProdId = parseInt(pathSplitList.at(pathSplitList.length-2))
    let quantity = parseInt(caller.parentNode.querySelector(".qty").innerText)

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fetch(urlTo('/api/products/tyres/' + mProdId)).then((response) => {
        response.json().then(
            (data) => {
                fetch(
                    urlTo('/api/add-cart-item'),
                    {
                        method: "POST",
                        body: JSON.stringify({product: data, qty: quantity, uuid: generateUUID()}),
                        headers: {
                            "Content-type": "application/json; charset=UTF-8",
                            "X-CSRF-Token": csrf
                        }
                    }).then((response) => {
                    response.text().then(
                        (data) => {
                            caller.classList.add('added');
                            caller.innerText = 'Dodato u korpu';
                            setTimeout(function() {
                                caller.classList.remove('added');
                                caller.innerText = 'Dodaj u korpu';
                            }, 2000);
                            let numSpan = document.getElementsByClassName("cart-num").item(0)
                            const currentCartQuantity = Number(numSpan.innerText)
                            numSpan.innerText = (currentCartQuantity + quantity).toString()
                        }
                    )
                })
            }
        )
    })
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

const generateUUID = () => {
    var d = new Date().getTime();
    if(window.performance && typeof window.performance.now === "function"){
        d += performance.now(); //use high-precision timer if available
    }
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
}

const addFeaturedToCart = (mProdId, caller) => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fetch(urlTo('/api/products/tyres/' + mProdId)).then((response) => {
        response.json().then(
            (data) => {
                fetch(
                    urlTo('/api/add-cart-item'),
                    {
                        method: "POST",
                        body: JSON.stringify({product: data, qty: 1, uuid: generateUUID()}),
                        headers: {
                            "Content-type": "application/json; charset=UTF-8",
                            "X-CSRF-Token": csrf
                        }
                    }).then((response) => {
                    response.text().then(
                        (data) => {
                            caller.classList.add('added');
                            caller.innerText = 'Dodato u korpu';
                            setTimeout(function() {
                                caller.classList.remove('added');
                                caller.innerText = 'Dodaj u korpu';
                            }, 2000);
                            let numSpan = document.getElementsByClassName("cart-num").item(0)
                            const currentCartQuantity = Number(numSpan.innerText)
                            numSpan.innerText = (currentCartQuantity + 1).toString()
                        }
                    )
                })
            }
        )
    })
}
