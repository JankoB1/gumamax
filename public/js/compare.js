const addToCartCompare = (prodId) => {

}

const rmFromCompare = (prodId) => {
    removeFromCookie(prodId).then( () => {
        location.reload()
    } )
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

const removeFromCookie = (prodId) => {
    //setCookie(COMPARE_LIST_COOKIE_NAME,getCookie(COMPARE_LIST_COOKIE_NAME).replace(prodId + "#",""),1)
    return fetch(urlTo('gume/rm-to-compare?id=' + prodId));
}
