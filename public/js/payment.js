var wpwlOptions = {
    style: 'plain',
    forceCardHolderEqualsBillingName: false,
    showCVVHint: true,
    brandDetection: true,
    billingAddress: {},
    mandatoryBillingFields:{
        country: true,
        state: false,            
        city: true,
        postcode: true,
        street1: true,
        street2: false
    },
    labels: {
        cardNumber: "Broj kartice", 
        cardHolder: "Vlasnik kartice",
        expiryDate: "Važi do",
        givenName: "Ime",
        surname: "Prezime",
        submit: "Plati"
    },
    errorMessages: {
        cardNumberError: "Neispravan broj kartice",                
        expiryMonthError: "Neispravan datum važenja kartice",
        expiryYearError: "Neispravan datum važenja kartice",
        cvvError: "Neispravan CVV broj",
        cardHolderError: "Neispravni podaci o vlasniku kartice",
        givenNameError: "Nije uneto ime",
        surNameError: "Nije uneto prezime"
    },  
    onReady: function() {  
        $(".wpwl-group-cardNumber").after($(".wpwl-group-brand").detach());
        $(".wpwl-group-cvv").after( $(".wpwl-group-cardHolder").detach());

        var divTop = $("<div/>", {class: 'logo-row'}).appendTo(".wpwl-group-brand");
        var visa = $(".wpwl-brand:first").clone().removeAttr("class").attr("class", "wpwl-brand-card wpwl-brand-custom wpwl-brand-VISA")
        visa.detach().appendTo(divTop);                
        var master = $(visa).clone().removeClass("wpwl-brand-VISA").addClass("wpwl-brand-MASTER");
        master.appendTo(divTop);

        var divBottom = $("<div/>", {class: 'logo-row'}).appendTo(".wpwl-group-brand");
        var maestro = $(visa).clone().removeClass("wpwl-brand-VISA").addClass("wpwl-brand-MAESTRO").detach().appendTo(divBottom);

        $(".wpwl-label-billing").html("Adresa plaćanja");
        $("input.wpwl-control-stateText").attr("Placeholder", "Država/Provincija");
        $("input.wpwl-control-city").attr("Placeholder", "Mesto");
        $("input.wpwl-control-postcode").attr("Placeholder", "Poštanski broj");
        $("input.wpwl-control-street1").attr("Placeholder", "Adresa");
        $("input.wpwl-control-street2").attr("Placeholder", "Adresa (opciono)");
        $("select.wpwl-control-country").find('option:selected').text('Izberite zemlju');
    },
    onChangeBrand: function(e){
        $(".wpwl-brand-custom").css("opacity", "0.1");
        $(".wpwl-brand-" + e).css("opacity", "1"); 
    },
    onBeforeSubmitCard: function(e){
        if (!validateHolder(e)) {
            return false;
        }

        if (!$("#chkPaymentTerms").is(":checked")) {
            swal("", "Za nastavak transakcije potrebno je da potvrdite saglasnost sa uslovima plaćanja.", "warning");
            return false;
        }

        return true;
    },
    onAfterSubmit: function(){ 
        $('.wpwl-button-pay').html('Procesiram...');
    }
}

function validateHolder(e) {
    var holder = $('.wpwl-control-cardHolder').val();
    if (holder.trim().length < 2){
        $('.wpwl-control-cardHolder')
            .addClass('wpwl-has-error')
            .after('<div class="wpwl-hint wpwl-hint-cardHolderError">Neispravni podaci o vlasniku kartice</div>');
        return false;
    }
    return true;
}   

$("#chkPaymentTerms").on('change', function(){
    if ($(this).is(":checked")) {
        $('.wpwl-button-pay').prop('disabled', false);
    }
})