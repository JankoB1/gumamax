let paymentMethod
let orderBtn

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
                cart: cart
            }),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
                "X-CSRF-Token": csrf
            }
        }
    ).then(response => {
        response.json()
            .then( data => {
                console.log(data.erp_result.newOrder.checkout_id)
                window.location.href = urlTo('testpay') + "?coid=" + data.erp_result.newOrder.checkout_id
            });
    })
}

$(document).ready(() => {
    paymentMethod = document.getElementById("payment_method")
    orderBtn = document.getElementById("orderBtn")
    orderBtn.onclick = order

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

const urlTo = (uri) => {
    var i = uri.indexOf('/'),
        s='';
    if (i!==0) {
        s = window.location.hostname + '/'+uri
    }else
        s = window.location.hostname + uri;
    return  window.location.protocol+'//'+ s;
}
