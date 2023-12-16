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