let paymentMethod
let orderBtn
let saveShippingInfoBtn
let citySelect

var wpwlOptions = {style:"card"}


let content = document.getElementById("checkout-content")
const order = () => {
    /*switch (paymentMethod.value){
        case 'card':
            orderNPayByCard()
            break;
        case 'on-spot':
            orderNPayOnSpot()
            break;
    }*/
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fetch(
        urlTo("ordernpay"),
        {
            method: "POST",
            body: JSON.stringify({
                cart: JSON.parse(sessionStorage.getItem("gmx-cart"))
            }),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
                "X-CSRF-Token": csrf
            }
        }
    ).then(response => {
        if (response.redirected){
           window.location.href = response.url
        } else {
            response.json()
                .then(data => {
                    console.log(data.erp_result.newOrder.checkout_id)
                    window.location.href = urlTo('testpay') + "?coid=" + data.erp_result.newOrder.checkout_id
                });
        }
    })
}

const saveShippingInfo = () => {
    let mCart = JSON.parse(sessionStorage.getItem("gmx-cart"))
    mCart.payment_method_id = document.getElementById("payment_method").value
    mCart.shipping_city = citySelect.options[citySelect.selectedIndex].text
    mCart.shipping_postal_code = document.getElementById("zip").value
    mCart.shipping_address = document.getElementById("address").value
    mCart.shipping_recipient = document.getElementById("first_name").value + " " + document.getElementById("last_name").value
    mCart.shipping_email = document.getElementById("email").value
    mCart.shipping_phone = document.getElementById("tel").value

    if (document.getElementById("delivery_place2").checked) {
        mCart.shipping_method_id = 1
        mCart.shipping_option_id = 1
    } else if (document.getElementById("delivery_place3").checked) {
        mCart.shipping_method_id = 2
        mCart.shipping_option_id = 2
    } else {

    }

    sessionStorage.setItem("gmx-cart", JSON.stringify(mCart))

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fetch(
        urlTo("/api/save-shipping-info"),
        {
            method: "POST",
            body: JSON.stringify({cart: mCart}),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
                "X-CSRF-Token": csrf
            }
        }
    ).then(response => {
        response.json().then(data => {
            sessionStorage.setItem("gmx-cart", JSON.stringify(data))
        })
    })
}

$(document).ready(() => {
    paymentMethod = document.getElementById("payment_method")
    orderBtn = document.getElementById("orderBtn")
    orderBtn.onclick = order

    citySelect = document.getElementById("city_d1")
    citySelect.onchange = () => {
        document.getElementById("zip").value = citySelect.value
    }

    saveShippingInfoBtn = document.getElementById("saveShippingBtn")
    saveShippingInfoBtn.onclick = saveShippingInfo

    if (JSON.parse(sessionStorage.getItem("gmx-cart")).shipping_method_id === 1) {
        document.getElementById("delivery_place2").setAttribute("checked", "true")
        const event = document.createEvent("HTMLEvents");
        event.initEvent('click', false, true);
        document.getElementById("delivery_place2").dispatchEvent(event);
    } else if (JSON.parse(sessionStorage.getItem("gmx-cart")).shipping_method_id === 2) {
        document.getElementById("delivery_place3").setAttribute("checked", "true")
        const event = document.createEvent("HTMLEvents");
        event.initEvent('click', false, true);
        document.getElementById("delivery_place3").dispatchEvent(event);
    }
})

const prepareCheckout = async () => {
    fetch(urlTo("ordernpay")).then(response => {
        response.json()
            .then( data => {
                console.log(data.erp_result.newOrder.checkout_id)

                const newDiv = document.createElement("div")
                newDiv.style.height = "200px"
                newDiv.id = "card-placeholder"
                newDiv.innerHTML =
                    "<form action=\"/gume\" className=\"paymentWidgets\" data-brands=\"AMEX, DINERS,DIRECTDEBIT_SEPA, DIRECTDEBIT_SEPA_MIX_AT, DIRECTDEBIT_SEPA_MIX_DE, DISCOVER, GIROPAY, JCB, MAESTRO, MASTER, MASTERPASS, PAYDIREKT, PAYPAL, RATENKAUF, VISA\"></form>"

                document.body.appendChild(newDiv)

                let script = document.createElement('script')
                script.src = 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=' + data.erp_result.newOrder.checkout_id;

                script.onload = () => {
                    console.log(script.src)
                }

                t = document.getElementById('card-placeholder')
                t.parentNode.appendChild(script)

            });
    })
}

const orderNPayByCard = () => {
    /*window.location.href = '/ordernpay'
        + "?method=card"
        + "&amount=" + totalAmount*/
    const progDiv = document.createElement("div")
    //progDiv.innerHTML = "<progress value=\"75\" min=\"0\" max=\"100\" style=\"visibility:hidden;height:0;width:0;\">75%</progress>"
    /*progDiv.setAttribute("style", "  width: 100px;\n" +
        "  height: 100px;\n" +
        "  border-radius: 50%;\n" +
        "  background: \n" +
        "    radial-gradient(closest-side, white 79%, transparent 80% 100%),\n" +
        "    conic-gradient(hotpink 75%, pink 0);")*/
    content = document.getElementById("checkout-content")
    content.innerHTML = ''
    content.appendChild(progDiv);
    prepareCheckout()
}
const orderNPayOnSpot = () => {

}

const rmCartItem = (caller,item) => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fetch(
        urlTo('/api/rm-cart-item'),
        {
            method: "POST",
            body: JSON.stringify({product: item.item, qty: item.qty}),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
                "X-CSRF-Token": csrf
            }
        }).then((response) => {
        response.json().then(
            (data) => {
                sessionStorage.setItem("gmx-cart", JSON.stringify(data))
                console.log(data)
                window.location.reload()
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
