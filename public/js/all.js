/*global $*/
/*jshint unused:false,forin:false*/

var iosOverlay = function(params) {

	"use strict";

	var overlayDOM;
	var noop = function() {};
	var defaults = {
		onbeforeshow: noop,
		onshow: noop,
		onbeforehide: noop,
		onhide: noop,
		text: "",
		icon: null,
		spinner: null,
		duration: null,
		id: null,
		parentEl: null
	};

	// helper - merge two objects together, without using $.extend
	var merge = function (obj1, obj2) {
		var obj3 = {};
		for (var attrOne in obj1) { obj3[attrOne] = obj1[attrOne]; }
		for (var attrTwo in obj2) { obj3[attrTwo] = obj2[attrTwo]; }
		return obj3;
	};

	// helper - does it support CSS3 transitions/animation
	var doesTransitions = (function() {
		var b = document.body || document.documentElement;
		var s = b.style;
		var p = 'transition';
		if (typeof s[p] === 'string') { return true; }

		// Tests for vendor specific prop
		var v = ['Moz', 'Webkit', 'Khtml', 'O', 'ms'];
		p = p.charAt(0).toUpperCase() + p.substr(1);
		for(var i=0; i<v.length; i++) {
			if (typeof s[v[i] + p] === 'string') { return true; }
		}
		return false;
	}());

	// setup overlay settings
	var settings = merge(defaults,params);

	// 
	var handleAnim = function(anim) {
		if (anim.animationName === "ios-overlay-show") {
			settings.onshow();
		}
		if (anim.animationName === "ios-overlay-hide") {
			destroy();
			settings.onhide();
		}
	};

	// IIFE
	var create = (function() {

		// initial DOM creation and event binding
		overlayDOM = document.createElement("div");
		overlayDOM.className = "ui-ios-overlay";
		overlayDOM.innerHTML += '<span class="title">' + settings.text + '</span';
		if (params.icon) {
			overlayDOM.innerHTML += '<img src="' + params.icon + '">';
		} else if (params.spinner) {
			overlayDOM.appendChild(params.spinner.el);
		}
		if (doesTransitions) {
			overlayDOM.addEventListener("webkitAnimationEnd", handleAnim, false);
			overlayDOM.addEventListener("msAnimationEnd", handleAnim, false);
			overlayDOM.addEventListener("oAnimationEnd", handleAnim, false);
			overlayDOM.addEventListener("animationend", handleAnim, false);
		}
		if (params.parentEl) {
			document.getElementById(params.parentEl).appendChild(overlayDOM);
		} else {
			document.body.appendChild(overlayDOM);
		}
		
		settings.onbeforeshow();
		// begin fade in
		if (doesTransitions) {
			overlayDOM.className += " ios-overlay-show";
		} else if (typeof $ === "function") {
			$(overlayDOM).fadeIn({
				duration: 200
			}, function() {
				settings.onshow();
			});
		}

		if (settings.duration) {
			window.setTimeout(function() {
				hide();
			},settings.duration);
		}

	}());

	var hide = function() {
		// pre-callback
		settings.onbeforehide();
		// fade out
		if (doesTransitions) {
			// CSS animation bound to classes
			overlayDOM.className = overlayDOM.className.replace("show","hide");
		} else if (typeof $ === "function") {
			// polyfill requires jQuery
			$(overlayDOM).fadeOut({
				duration: 200
			}, function() {
				destroy();
				settings.onhide();
			});
		}
	};

	var destroy = function() {
		if (params.parentEl) {
			document.getElementById(params.parentEl).removeChild(overlayDOM);
		} else {
			document.body.removeChild(overlayDOM);
		}
	};

	var update = function(params) {
		if (params.text) {
			overlayDOM.getElementsByTagName("span")[0].innerHTML = params.text;
		}
		if (params.icon) {
			if (settings.spinner) {
				settings.spinner.el.parentNode.removeChild(settings.spinner.el);
			}
			overlayDOM.innerHTML += '<img src="' + params.icon + '">';
		}
	};

	return {
		hide: hide,
		destroy: destroy,
		update: update
	};

};

var overlay;
var spinner;

var pleaseWaitLabel = "Molimo Vas, sačekajte...";

Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this;
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator === undefined ? "," : decSeparator,
        thouSeparator = thouSeparator === undefined ? "." : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces),10) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function number_format (number, decimals, dec_point, thousands_sep, show_null_as_zero) {
	if(show_null_as_zero==='undefined') show_null_as_zero = true;
	if(!show_null_as_zero)
		if(isNaN(number) || number==='undefined' || number==null)
			return '';
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function urlTo(uri){
   var i = uri.indexOf('/'),
       s='';
   if (i!=0) {
        s = window.location.hostname + '/'+uri
   }else
       s = window.location.hostname + uri;
   return  window.location.protocol+'//'+ s;
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

Array.prototype.clean = function(deleteValue) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == deleteValue) {
            this.splice(i, 1);
            i--;
        }
    }
    return this;
};

String.prototype.trimLeft = function(charlist) {
	if (charlist === undefined)
		charlist = "\s";
	return this.replace(new RegExp("^[" + charlist + "]+"), "");
};

String.prototype.trimRight = function(charlist) {
	if (charlist === undefined)
		charlist = "\s";
	return this.replace(new RegExp("[" + charlist + "]+$"), "");
};

String.prototype.trimDmx = function(charlist) {
 	return this.trimLeft(charlist).trimRight(charlist);
};

function numberWithCommas(x) {
    if(isNaN(x)){
        return '0';
    }else{
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
}

function enableInput(x, el){
    var tmp = 0;
    $.each(el, function(i, item){
        if(tmp <= x){
            item.prop('disabled', false);
        }
        tmp++;
    });
}

function disableInput(x, el){
    var tmp = 0;
    $.each(el, function(i, item){
        if(tmp >= x){
            item.prop('disabled', 'disabled');
        }
        tmp++;
    });
}

function disableSelect2(x, el){
    var tmp = 0;
    $.each(el, function(i, item){
        if(tmp >= x){
            item.empty();
            item.append($('<option>'));
            item.val(null).trigger('change');
            item.prop('disabled', true);
        }
        tmp++;
    });
}

function fillSelectOptions(route, element, selectedValue, itemsCallback) {
    element.empty();
    $.ajax({
        type: "GET",
        url: urlTo(route),
        contentType: "application/json; charset=utf-8",
        dataType: "json"
    }).done(function(result) {
        itemsCallback(result.data, element);
        element.prop("disabled", false);
        element.val(selectedValue);
    });
}

function fillSelect2Options(route, element, selectedValue, itemsCallback) {
    element.empty();
    element.val(null);
    $.ajax({
        type: "GET",
        url: urlTo(route),
        contentType: "application/json; charset=utf-8",
        dataType: "json"
    }).done(function(result) {
        itemsCallback(result.data, element);
        element.prop("disabled", false);
        var existValue = element.find('option[value="'+selectedValue+'"]').length>0;
        if (selectedValue&&existValue){
                element.val(selectedValue).trigger('change');
            }
        else {
            element.val(element.find('option:first').val()).trigger('change');
        }

    });
}

function fillAggregations(data, element){
    if (data){
        $.each(data, function(i, item) {
            if (item.key!==''){
                $('<option>').val(item.key).text(item.key).appendTo(element);
            }
        });
    }
}

function columnizeCheckboxes(labelName, className, requestedColumns, columnWidth) {
    var selectorString  = "div[class=\""+className+"\"]";
    selectorString += "[label=\"" + labelName + "\"]";
    var parentElement = $(selectorString);

    var divString = '<div style=\"float:left; width: ';
    divString += columnWidth + '%;\"></div>';
    var wrapperDiv = $(divString);

    var cb = parentElement.find('label.radio');
    var totalCheckboxes = cb.size();
    var checkboxesPerColumn = Math.ceil(totalCheckboxes / requestedColumns);

    var builtColumns = 1;
    for (var i = 0; builtColumns <= requestedColumns; i += checkboxesPerColumn) {
        var elem = null;
            elem = cb.slice(i, i + checkboxesPerColumn);
            elem.wrapAll(wrapperDiv);
            builtColumns++;
    }
}

function disableSelects(argument) {
    $.each(argument, function(i,item){
        item.attr("disabled", true);
    });
}

function getCurrentEndPoint(){
    var pathArray = window.location.pathname.split( '/' );
    if (pathArray.length>0){
        return pathArray[pathArray.length-1]
    } else
        return '';
}

function render(tmpl_name, tmpl_data) {
    if ( !render.tmpl_cache ) {
        render.tmpl_cache = {};
    }

    if ( ! render.tmpl_cache[tmpl_name] ) {
        var tmpl_dir = '/static/hb-templates';

        var tmpl_string='';

        $.ajax({
            url: urlTo(tmpl_dir + '/' + tmpl_name + '.html'),
            method: 'GET',
            async: false,
            dataType: 'html',
            success: function(data) {
                tmpl_string = data;
            }
        });

        render.tmpl_cache[tmpl_name] = Handlebars.compile(tmpl_string);
    }

    return render.tmpl_cache[tmpl_name](tmpl_data);
}

function renderWoCache(tmpl_name, tmpl_data) {

    render.tmpl_cache = {};

    var tmpl_dir = '/static/hb-templates';

    var tmpl_string='';

    $.ajax({
        url: urlTo(tmpl_dir + '/' + tmpl_name + '.html'),
        method: 'GET',
        async: false,
        dataType: 'html',
        success: function(data) {
            tmpl_string = data;
        }
    });

    render.tmpl_cache[tmpl_name] = Handlebars.compile(tmpl_string);

    return render.tmpl_cache[tmpl_name](tmpl_data);
}


function doHighlight(bodyText, searchTerm, highlightClass)
{
    var highlightStartTag = '<span class="'+highlightClass+'">',
        highlightEndTag = "</span>";

    // find all occurences of the search term in the given text,
    // and add some "highlight" tags to them (we're not using a
    // regular expression search, because we want to filter out
    // matches that occur within HTML tags and script blocks, so
    // we have to do a little extra validation)
    var newText = "";
    var i = -1;
    var lcSearchTerm = searchTerm.toLowerCase();
    var lcBodyText = bodyText.toLowerCase();

    while (bodyText.length > 0) {
        i = lcBodyText.indexOf(lcSearchTerm, i+1);
        if (i < 0) {
            newText += bodyText;
            bodyText = "";
        } else {
            // skip anything inside an HTML tag
            if (bodyText.lastIndexOf(">", i) >= bodyText.lastIndexOf("<", i)) {
                // skip anything inside a <script> block
                if (lcBodyText.lastIndexOf("/script>", i) >= lcBodyText.lastIndexOf("<script", i)) {
                    newText += bodyText.substring(0, i) + highlightStartTag + bodyText.substr(i, searchTerm.length) + highlightEndTag;
                    bodyText = bodyText.substr(i + searchTerm.length);
                    lcBodyText = bodyText.toLowerCase();
                    i = -1;
                }
            }
        }
    }
    return newText;

}

function fileNameTruncate(n, len) {
    var ext = n.substring(n.lastIndexOf(".") + 1, n.length).toLowerCase();
    var filename = n.replace('.' + ext,'');
    if(filename.length <= len) {
        return n;
    }
    filename = filename.substr(0, len) + (n.length > len ? '~' : '');

    return filename + '.' + ext;
};

function showCompanyFields(customerType)
{
    if(customerType=='2') {
        $('.company_fields').removeClass('hidden');
        $('#company_name').focus();
    } else {
        $('#company_name').val('');
        $('#tax_identification_number').val('');
        $('.company_fields').addClass('hidden');

    }
}

function _associateErrors(errors, $form, $btn)
{
    $.each(errors, function(elementName, error){
        var $group = $form.find('[name="'+elementName+'"]').parent();

        var err =  $('<span />').addClass('help-block').text(error);
        err.appendTo($group);
        $group.addClass('has-error');
    });

    if (typeof $btn==='undefined'){

        return true;
    }

    $btn.button('reset');
}

function associateErrors(jqXHR, $form, $btn)
{
    if (jqXHR.status!=422){
        swal(jqXHR.status.toString(), jqXHR.statusText,  "error");
    }
    try{
        var errors = jqXHR.responseJSON;
        _associateErrors(errors, $form);
    }catch(e){
        dmxErrorModalDialogLight(jqXHR.statusText, jqXHR.responseText).open();
    }

}

function clearError($form){
    $form.find('.form-group').removeClass('has-error').find('.help-block').remove();
}

function submitModalLaravelForm($form, $dialog, $btn, $successMessage, $dataTable){

    var formData = $form.serialize(), resourceUrl = $form.attr('action');

    clearError($form);

    $.post(resourceUrl, formData, function(response){
        $btn.button('reset');
        $dialog.close();

        if (typeof $dataTable != 'undefined'){
            $dataTable.ajax.reload();
        }

        if ((typeof $successMessage != 'undefined') && ( $successMessage != '')){
            swal($successMessage, '', 'success');
        }
    }).fail(function(jqXHR, textStatus, errorThrown){
        $btn.stopSpin();
        $dialog.enableButtons(true);
        associateErrors(jqXHR, $form, $btn);
    });
}

function dmxErrorModalDialogLight($title, $content){
    return new BootstrapDialog({
        title: $title,
        type: BootstrapDialog.TYPE_DANGER,
        size: BootstrapDialog.SIZE_WIDE,
        message: $('<div class="error-dialog-content"></div>').html($content),
        draggable: true,
        buttons : [
            {
                label: 'Zatvori',
                icon: 'fa fa-times',
                action: function(dialogRef){
                    dialogRef.close();
                }
            }
        ]
    });
}

function dmxModalDialog($title, $loadingMessage, $url, $formId, $successMessage, $dataTable){

    return new BootstrapDialog({  
        title: $title,
        data: {formId: $formId},
        message: $('<div>'+$loadingMessage+'</div>').load($url),       
        draggable: true,
        //autospin: true,
        buttons: [
            {
                label: 'Zatvori',
                action: function(dialogRef){
                    dialogRef.close();
                }
            },
            {
                label:'Pošalji',
                cssClass:'btn-primary',
                action: function (dialogRef) {
                    var formId = dialogRef.getData('formId');
                    var form = dialogRef.$modalBody.find('#'+formId);
                    var btn = dialogRef.$modalFooter.find('.btn.btn-primary');
                    var dlgBtn=this;
                    if (form.valid()){
                        dlgBtn.spin();
                        dialogRef.enableButtons(false);
                        dialogRef.setClosable(false);
                        submitModalLaravelForm(form, dialogRef, dlgBtn, $successMessage, $dataTable);
                    }
                }
            }
        ],
        onshow: function(dialogRef) {
            dialogRef.getModalDialog().addClass('modal-lg');
        },
        onhide: function(dialogRef){
            clearError(dialogRef.$modalBody.find('form'));
        }
    });
}

$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});

function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameter,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameter = sURLVariables[i].split('=');

        if (sParameter[0] === sParam) {
            return sParameter[1] === undefined ? true : sParameter[1];
        }
    }
}

function undefinedOrNull(value) {
    return value === null || value === undefined;
}

function escapeHTML( text ) {
    return text.replace( /&/g, "&amp;" )
        .replace( /</g, "&lt;" )
        .replace( />/g, "&gt;" )
        .replace( /"/g, "&quot;" )
        .replace( /'/g, "&#39;" );
}

function generateUUID() {
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
/*
$(window).scroll(function(event) {
    function footer()
    {
        var scroll = $(window).scrollTop();
        if(scroll > 50)
        {
            $(".footer-nav").fadeIn("slow").addClass("show");
        }
        else
        {
            $(".footer-nav").fadeOut("slow").removeClass("show");
        }

        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function() {
            if ($('.footer-nav').is(':hover')) {
                footer();
            }
            else
            {
                $(".footer-nav").fadeOut("slow");
            }
        }, 2000));
    }
    footer();
});
*/
function authCheck(){

    return ($('meta[name="auth-token"]').attr('content')==1);

}


function _erpMonsterCheckTimeFormat (value){
    if (!value.match('^([01]?[0-9]|2[0-3]):[0-5][0-9]$')){
        return 'Expected time format: HH:mm';
    }
}

function htmlDecode(input) {
    var doc = new DOMParser().parseFromString(input, "text/html");
    return doc.documentElement.textContent;
}
jQuery.extend(jQuery.validator.messages, {
    required: "Obavezan unos za ovo polje.",
    remote: "Ispravite ovo polje.",
    email: "Unesite ispravnu e-mail adresu.",
    url: "Unesite ispravan URL.",
    date: "Unesite ispravan datum.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Unesite ispravan broj.",
    digits: "Unesite samo cifre.",
    creditcard: "Unesite ispravan broj kartice.",
    equalTo: "Unesite istu vrednost.",

    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Maksimalno dozvoljeno {0} karaktera."),
    minlength: jQuery.validator.format("Morate uneti najmanje {0} karaktera."),
    rangelength: jQuery.validator.format("Uneta vrednost mora biti duga od {0} do {1} karaktera."),
    range: jQuery.validator.format("Uneta vrednost mora biti između {0} i {1}."),
    max: jQuery.validator.format("Uneta vrednost mora biti manja ili jednaka {0}."),
    min: jQuery.validator.format("Uneta vrednost mora biti veća ili jednaka {0}.")
});

jQuery.validator.addMethod("phoneNumber", function (phone_number, element) {

    phone_number = phone_number.replace(/\s+/g, "");

    var phoneNumberLength = 0;
    for (var i=0; i<phone_number.length; i++) {
        if ($.isNumeric(phone_number[i]))
            phoneNumberLength++;
    }
    return this.optional(element) || phoneNumberLength >= 9 && phone_number.match(/^(\()*(\+)*\d+(\))*(\-|\/|\s|)\d+(\-|\s)*\d+(\-|\s)*\d+(\-|\s)*\d+$/);
}, "Unesite ispravan broj telefona.");

jQuery.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        return this.optional(element) || regexp.test(value);
    },
    "Please check your input."
);

jQuery.validator.addMethod("phone_regex", function(value, element) {

    return this.optional( element ) || /(^\+\d{1,19}$)|(^\d{1,20}$)/.test( value );
}, 'Unseite samo cifre za broj telefona');

jQuery.cachedScript = function( url, options ) {
    options = $.extend( options || {}, {
      dataType: "script",
      cache: true,
      url: url
    });
      
    return jQuery.ajax( options );
};
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
/*
 * This decorates Handlebars.js with the ability to load
 * templates from an external source, with light caching.
 *
 * To render a template, pass a closure that will receive the
 * template as a function parameter, eg,
 *   T.render('templateName', function(t) {
 *       $('#somediv').html( t() );
 *   });
 * Source: https://github.com/wycats/handlebars.js/issues/82
 */
var Template = function() {
    this.cached = {};
};

var T = new Template();

$.extend(Template.prototype, {
    render: function(name, callback) {
        if (T.isCached(name)) {
            callback(T.cached[name]);
        } else {
            $.get(T.urlFor(name), function(raw) {
                T.store(name, raw);
                T.render(name, callback);
            });
        }
    },

    renderSync: function(name, callback) {
        if (!T.isCached(name)) {
            T.fetch(name);
        }
        T.render(name, callback);
    },
    prefetch: function(name) {
        $.get(T.urlFor(name), function(raw) {
            T.store(name, raw);
        });
    },
    fetch: function(name) {
        // synchronous, for those times when you need it.
        if (! T.isCached(name)) {
            var raw = $.ajax({'url': T.urlFor(name), 'async': false}).responseText;
            T.store(name, raw);
        }
    },
    isCached: function(name) {
        return !!T.cached[name];
    },
    store: function(name, raw) {
        T.cached[name] = Handlebars.compile(raw);
    },
    urlFor: function(name) {
        return urlTo("/static/hb-templates/"+ name + ".html");
    }
});

Handlebars.registerHelper('hb_format_currency', function(value, currencyStr){
    var sufix = "";

    if (!(currencyStr===null)) {
        sufix = " " + currencyStr;
    }

    if(!(value===null))
        return parseFloat(value).formatMoney(2,'.',',').toString() + sufix;
    else
        return '-';
});

Handlebars.registerHelper('hb_cart_item_select_qty', function(value){
    var opts='';
    if (value == 0) {
       opts = '<option value="0">0</option>';
    } else {
        var lim=(this.qty>4)?this.qty:4;
        for (var i = 1; i <= lim; i++) {
            opts += (this.qty==i)?'<option selected value="'+this.qty+'">'+this.qty+'</option>':'<option value="'+i+'">'+i+'</option>';
        }
    }

    return new Handlebars.SafeString(opts);
});

Handlebars.registerHelper('hb_cart_item_select_state', function(value){
    var state = '';
    if (value == 0) 
        state = 'disabled';
    
    return new Handlebars.SafeString(state);
});

Handlebars.registerHelper('hb_cart_item_error_class', function(){
    var errorClass = '';
    if (this.qty<this.requested_qty){
        errorClass = 'alert-form';
    } else {
        errorClass = 'cart-content-item-form';
    }
    return new Handlebars.SafeString(errorClass);
});

Handlebars.registerHelper('hb_cart_item_error_message', function(){
    if (this.qty<this.requested_qty){
        var msg = '<div class="alert-msg"><div><p><i class="fa fa-exclamation-circle"></i> U međuvremenu je došlo do promene stanja zaliha. Trenutno je raspoloživo: <strong>'+ this.qty + '</strong> a Vi ste tražili: <strong>'+this.requested_qty+'</strong></p>'+
            '<input type="hidden" class="show-replacements"/>'+
            '</div></div>';
        return new Handlebars.SafeString(msg);
    } else
        return '';
});

Handlebars.registerHelper('hb_manufacturer_logo', function() {
    return  urlTo('img/mnf_logos/'+ this.manufacturer.toLowerCase() + ".png");
});

Handlebars.registerHelper('hb_country_of_origin', function() {

    if (this.country_of_origin) {
        return  "Zemlja porekla: "+ this.country_of_origin;
    }
});

Handlebars.registerHelper('hb_stock_status', function() {
    var result = '';
    if  (this.stock_status>0){
        var q = parseInt(this.stock_status_qty),
            z = (q<4) ? 'Ostalo još: '+q+' kom' : 'Na stanju';
        result = '<p class="stock-status text-success"><strong>'+z+'</strong></p>';
    }else{
        result = '<p class="stock-status text-danger"><strong>Nema</strong></p>';
    }
    return new Handlebars.SafeString(result);
});

Handlebars.registerHelper('hb_product_url', function() {
    var uri = 'products/tyres/' + this.product_id+'/'
        +(this.manufacturer+' '
        +this.additional_description).trim(' ').replace(/([^a-zA-Z0-9])/gi,'-').toLowerCase();
    return urlTo(uri);
});

Handlebars.registerHelper('hb_tyres_cat_no', function() {

    var cai = this.cat_no.toString().split('/');
    var catNo = '<span>'+ cai[0] +'</span>';
    var result =  (cai.length == 1) ? catNo : catNo + ' <span> DOT: ' + cai[1] + '</span>';
    return new Handlebars.SafeString(result);
});

Handlebars.registerHelper('hb_list_price', function(){
    if(this.list_price!==null)
        return parseFloat(this.list_price).formatMoney(2,'.',',');
    else return '-' ;
});

Handlebars.registerHelper('hb_price_with_tax', function(){
    if(this.price_with_tax!==null)
        return parseFloat(this.price_with_tax).formatMoney(2,'.',',');
    else return '-' ;
});


Handlebars.registerHelper('hb_super_price', function(){
    if(this.action_price!==null)
        return parseFloat(this.action_price).formatMoney(2,'.',',');
    else if(this.super_price!==null)
        return parseFloat(this.super_price).formatMoney(2,'.',',');
    else
        return '-';
});

Handlebars.registerHelper('hb_super_price_url',function(){

    if ($('#user_id').val()=='') {return urlTo('login')} else return '#';
});

Handlebars.registerHelper('hb_super_price_tooltip_title', function(){
    if ($('#user_id').val()==''){return 'SUPER CENA! Pravo na super cene možete ostvariti registracijom Vašeg vozila'}else return '';
});

Handlebars.registerHelper('hb_product_select_qty', function(){
    var opts='',
        lim = parseInt(this.stock_status_qty);
    for (var i = 1; i < lim; i++) {
        opts += '<option value="'+i+'">'+i+'</option>';
    }
    opts += '<option selected value="'+lim+'">'+lim+'</option>';
    return new Handlebars.SafeString(opts);
});

Handlebars.registerHelper('hb_compare_link', function(){

    return urlTo('products/tyres/compare');

});

Handlebars.registerHelper('hb_partner_url', function() {
    var uri = 'member/' + this.member_id+'/'
        +(this.name+' '+this.department).trim(' ').replace(/([^a-zA-Z0-9])/gi,'-').toLowerCase();
    return urlTo(uri);
});

Handlebars.registerHelper('hb_format_distance', function(){
    return number_format(this.distance, 2, ',','.');
});

Handlebars.registerHelper('hb_format_cl', function(){
    return number_format(this.install_price.cel, 2, ',','.');
});

Handlebars.registerHelper('hb_format_al', function(){
    return number_format(this.install_price.alu, 2, ',','.');
});

Handlebars.registerHelper('equal', function(lvalue, rvalue, options) {
    if (arguments.length < 3)
        throw new Error("Handlebars Helper equal needs 2 parameters");
    if( lvalue!=rvalue ) {
        return options.inverse(this);
    } else {
        return options.fn(this);
    }
});

Handlebars.registerHelper('hb_url', function(value){

    if((value!==null))
        return urlTo(value);
    else
        return '';
});
var GUMAMAX = {};

$(document).ready(function() {
	"use strict";

	//init tooltip
	$( 'a.tooltipLink' ).tooltip();

	$('#myCarousel').carousel({
		interval: false
	});

	$('[id^=carousel-selector-]').on("click", function(){
		var id_selector = $(this).attr("id");
		var id = parseInt(id_selector.substr(id_selector.length -1));

		$('#myCarousel').carousel(id);
	});

	//vehicles menu
	$( '.vehicles a' ).on("click", function(e) {
		e.preventDefault();
		$( '.vehicles a' ).removeClass( 'active' );
		$( this ).addClass( 'active' );
		$( '#vehicleSbD, #vehicleSbV' ).val( $( this ).attr( 'data-rel' ) );
	});

	//Cart page, shipping, check on click on whole container
	$( '.radio-container' ).on("click", function() {
        $( this ).find( 'input' ).prop( 'checked', true );
        $( '.radio-container' ).removeClass( 'checked' );
        $( this ).addClass( 'checked' );
    });

	//Datepicker
    $( '.datepicker' ).datepicker({
    	language: 'sr-latin'
    });

    $( '.input-group-addon' ).on("click", function() {
    	$( this ).prevAll( 'input' ).datepicker( 'show' );
    });

    //Youtube video in modal popup stop on close
    $( 'body' ).on( 'hidden.bs.modal', '#videoUputstvo', function () {
    	$( '#videoUputstvo iframe' )[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
	});

});

$('.city-delivery-autocomplete').autocomplete({
	minChars: 2,
	deferRequestBy: 150,
	noCache: true,
	serviceUrl: urlTo('/cities/json'),
	paramName: 'term',
	formatResult: function(suggestions, response) {
		return  '<span class="col">'+suggestions.value +'</span>'+
				'<span class="col text-center">'+suggestions.data.free_shipment +'h</span>'+
				'<span class="col text-center">'+suggestions.data.courier_shipment+'h</span>';
	},
	transformResult: function(response){
		response = JSON.parse(response);
		return {
			suggestions: $.map(response, function(dataItem){
				return {
					value: dataItem.city_name,
					data: {
						city_id: dataItem.city_id,
						free_shipment: dataItem.free_shipment,
						courier_shipment: dataItem.courier_shipment,
						latitude: dataItem.latitude,
						longitude: dataItem.longitude
					}

				}
			})
		}
	},
	onSelect: function (suggestion) {
		this.value = suggestion.value +', '+ suggestion.data.city_id;
		$('#aLat').val(suggestion.data.latitude);
		$('#aLon').val(suggestion.data.longitude);
		$('#aCity_id').val(suggestion.data.city_id).trigger('change');
	},

	beforeRender: function(container){
		container[0].innerHTML = '<span class="col col-title">Mesto</span>'+
								 '<span class="col col-title">Gumamax</span>'+
								 '<span class="col col-title">Brza pošta</span>' + container[0].innerHTML;
	},
	strict: true
});



$('.container').on('click', '.checkbox.product-compare input[type=checkbox]', function(){
	$.cookie.json = false;
    var selItem = $(this),
        c = $.cookie('gmx_cmp_p'),
        id = selItem.data('id'),
        n = selItem.data('short') || '',
        nn = selItem.data('description') || '';

    var arrC = (c!=undefined && c!='') ? c.split('|') : [];

    if(c==undefined) { c=''; }

    if(selItem.prop('checked')){
        if(arrC.length>2) {
            sweetAlert('Obaveštenje','Moguće je izabrati najviše 3 proizvoda za poređenje.','info');       
            return false;
        } else if(c.indexOf(id+'='+n+'='+nn) < 0) {
            arrC.push(id+'='+n+'='+nn);
            var tr = '<tr class="compare-item" data-id="'+id+'">'+
            		 '<td class="compare-product-name" title="'+nn+'">'+ n.substr(0, n.indexOf('</span>')+7)+'</td>'+
            		 '<td class="text-right"><a href="#" class="item_cmp_remove remove"></a></td>'+
            		 '</tr>';
            $('#compare-list').prepend(tr);
            $('.compare-error').html('');
            $("#compare-box").show().removeClass("hide");
        } else {
            $('.compare-error').html('<div class="alert alert-info">Proizvod se već nalazi u listi za poređenje.</div>');
        }
        c = arrC.join('|');

        $.cookie('gmx_cmp_p',c,{path:'/'})
    } else {
        removeProductFromCompareList(id);
    }

    buildCompareLink();
});

$('.container').on("click",".item_cmp_remove.remove",function(){
    var t = $(this).closest('tr');
    removeProductFromCompareList(t.data('id'));
});


function removeProductFromCompareList(id){
	$.cookie.json = false;
    var c = $.cookie('gmx_cmp_p'),
        arrC;
    if (c!=undefined && c!=''){
        var re = new RegExp( '('+id+'=.*?)(\\||$)' ,"g");
        c = c.replace(re,'');
        // c = c.trim(' |').replace('||','|');
        c = c.trim().trimRight('|');
        $.cookie('gmx_cmp_p',c,{path:'/'});
        $('#compare-list').find('tr[data-id="'+id+'"]').remove();
        $('.container').find('.checkbox.product-compare [data-id="'+id+'"]').attr('checked',false);
        arrC = (c!=undefined && c!='') ? c.split('|') : [];
        buildCompareLink();
        if (arrC.length==0) {
            $("#compare-box").hide();
            $.removeCookie('gmx_cmp_p');
        }

    }
}

function initCompare(){
	$.cookie.json = false;
    var c = $.cookie('gmx_cmp_p');
    if (c!=undefined && c!=''){
        c = c.split('|');
        c = c.clean('');
        $('.compare-item').remove();
        for (var i = 0; i < c.length; i++) {
            var c1 = c[i].split('=');

            var tr = '<tr class="compare-item compare-item-'+ (i+1) +'" data-id="'+c1[0]+'">'+
            		 '<td class="compare-product-name" title="'+c1[2]+'">'+ c1[1].substr(0, c1[1].indexOf('</span>')+7)+'</td>'+
            		 '<td class="text-right"><a href="#" class="item_cmp_remove remove"></a></td>'+
            		 '</tr>';

            $('#compare-list').prepend(tr);
            $('input[data-id='+c1[0]+']').attr('checked',true);
        }
        if (c.length>0)
        {
        	$("#compare-box").show().removeClass("hide");
        	buildCompareLink();
        }
    }
}

function buildCompareLink()
{
	var url = urlTo('products/tyres/compare/');
	var ids = '';
	$('#compare-list tr.compare-item').each(function(i,item){
		ids += $(this).data('id')+'/';
	});
	ids = ids.trimRight('/');
	url += ids;
	// console.log(url);

	$('.compare-link').attr("href", url);
}

var productsLayout = $('#results'),
    productsList = $('#result-items'),
    productsNoResults = $('.no-results'),
    resultsOrder = $('.result-header .form-control.sortby'),
    containerNumberOfResults=$('.navbar-text.result-num span'),
    productQuery ={},
    replacementsQuery={};


function init_results_form(){
    T.prefetch('gmx-product');

}

function clearProductList(){
    productsList.html('');
}

function showProducts(query) {
    var loadMoreDiv = $('.load-more');
    productQuery = query;

    if (loadMoreDiv.length>0) {
        loadMoreDiv.remove();
    }
    productsNoResults.hide();
    productsLayout.hide();
    clearProductList();
    productsLayout.show();
    getData();
    scrollToResult();
}

function getData(){

    if (checkSearchCriteria()) {

        $.ajax({
            type: "GET",
            url: urlTo('api/products/tyres/search'),
            contentType: "application/json; charset=utf-8",
            data: productQuery,
            dataType: "json"
        }).done(function(result){

            setPagination(result.pagination);

            loadFacet(result.aggregations);

            var productsHtml='';

            T.render('gmx-product', function(t) {
                productsHtml = t(result) ;
                  });

            productsList.append(productsHtml);

            $('[data-toggle="tooltip"]').tooltip();

            initCompare();

            $('.loader-lg').hide();

            if(result.pagination.total>0){
                if (result.pagination.next_page>0) {
                    var $loadMoreDiv = '<div class="load-more row text-center"><a class="load-more-button btn btn-primary" href="#">Učitaj još</a></div>';
                    productsLayout.append($loadMoreDiv);
                    $('.load-more-button').on('click', function(e){

                        e.preventDefault();
                        $('.load-more').remove();
                        nextPage();
                    });
                }
            } else {
                productsNoResults.show();
            }

            $('.rateit').rateit();

            shop.bindProducts();

        }).fail(function(){
            $('.loader-lg').hide();
            productsNoResults.show();
        });
    }
}

function setPagination(pagination) {

    productQuery.current_page =   pagination.current_page;
    productQuery.next_page    =   pagination.next_page;
    productQuery.last_page    =   pagination.last_page;
    containerNumberOfResults.html(pagination.total);

}

function setOrder(order){
    productQuery.order = order;
    setProductFilterCookie(productQuery);
}

resultsOrder.on('change', function(){
    productQuery.page = 1;
    setOrder($(this).val());
    showProducts(productQuery);
});


function nextPage(){
    if (productQuery.current_page<productQuery.last_page){
        $('.loader-lg').show();
        productQuery.page = productQuery.page+1;
        getData();
    }
}

function scrollToResult(){
    if( productsLayout.length ) {
        $('html, body').stop().animate({
            scrollTop: productsLayout.offset().top
        }, 1000);
    }
}

function getReplacements(replacementsHolder){

    replacementsQuery={
        product_id : replacementsHolder.data('product_id'),
        requested_qty : replacementsHolder.data('requested_qty'),
        per_page : 15,
        page : 1,
        order : "price_with_tax|asc"
    };

    showLoading();

    $.ajax({
        type: "GET",
        url: urlTo('api/products/tyres/replacements'),
        contentType: "application/json; charset=utf-8",
        data: replacementsQuery,
        dataType: "json"
    }).done(function(result){

        replacementsHolder.empty();

        hideLoading();

        if(result.pagination.total>0){
            T.render('gmx-product-replacements', function(t) {
                replacementsHolder.append(t(result));
            });
            replacementsHolder.show();
        } else {
            productsLayout.html('<div class="alert alert-warning"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
        }

        shop.bindProducts();

    }).fail(function(){
        hideLoading();
        productsList.html('<div class="alert alert-danger"> <h4 class="text-center">'+noResultsLabel+'</h4></div>');
    });

}
$(document).ready(function(){

	$('#qty,.addToCartBtn').attr('disabled', $('.stock_status span').text()=='Nema');

	$('.product_thumb').eq(0).addClass('active');
	$('.product_thumb').on('click',function(e){
		var clickedThumb = $(this),
			oldImgSrc = $('img.zoomable').parent('a').attr('href'),
			newImgHref = clickedThumb.data('imgzoom'),
			newImgSrc = clickedThumb.attr("href");

		e.preventDefault();

		$('.product_thumb').removeClass('active');
		clickedThumb.addClass('active');

		$('.product_image').find('a[href="'+newImgHref+'"]').attr('href',oldImgSrc);
		$('img.zoomable').attr('src',newImgSrc);
		$('img.zoomable').parent('a').attr('href',newImgHref);
	});
});