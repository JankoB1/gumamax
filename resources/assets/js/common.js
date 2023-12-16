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