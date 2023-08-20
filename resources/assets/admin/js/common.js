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

function number_format (number, decimals, dec_point, thousands_sep) {
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

String.prototype.trim = function(charlist) {
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
            item.html('<option value=""></option>').prop('disabled', 'disabled');
        }
        tmp++;
    });
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