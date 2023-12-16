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