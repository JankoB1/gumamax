var loadingScreen = $('#loading-screen');
var erpMonsterOverlay;
var spinner;
function showLoading(text){
    var opts = {
        lines: 13, // The number of lines to draw
        length: 11, // The length of each line
        width: 5, // The line thickness
        radius: 17, // The radius of the inner circle
        corners: 1, // Corner roundness (0..1)
        rotate: 0, // The rotation offset
        color: '#FFF', // #rgb or #rrggbb
        speed: 1, // Rounds per second
        trail: 60, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'spinner', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: 'auto', // Top position relative to parent in px
        left: 'auto' // Left position relative to parent in px
    };
    if (spinner==undefined){

        var target = document.createElement("div");

        loadingScreen.append(target);


        spinner = new Spinner(opts).spin(target);
    }

    if(!loadingScreen.is(':visible')) {
        showOverlay(text);
        loadingScreen.show();
    }
    return false;
}

function hideLoading(){
    if(loadingScreen.is(':visible')){
        loadingScreen.hide();
    }
    return false;
}

function showOverlay(message){

    if (message==undefined){
        message= '';
    }
    if (erpMonsterOverlay == undefined){
        erpMonsterOverlay = iosOverlay({
            parentEl : 'loading-screen',
            text: message,
            spinner: spinner
        });
    }else{
        erpMonsterOverlay.update({text:message});
    }
}

function hideOverlay(){
    if (erpMonsterOverlay != undefined){
        erpMonsterOverlay.hide();
    }
}

function showOverlaySuccess(){
    iosOverlay({text: "Success!", duration:750, icon: urlTo("img/check.png")});
    return false;
}

function showOverlayError(){
    iosOverlay({text: "Error!", duration:750, icon: urlTo("img/clear.png")});
    return false;
}