
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
