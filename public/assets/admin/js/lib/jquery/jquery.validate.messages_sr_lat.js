/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: SR (Serbian - Latin alphabet; srpski jezik - latinica)
 */
(function($) {
	$.extend($.validator.messages, {
		required: "Polje je obavezno.",
		remote: "Sredite ovo polje.",
		email: "Unesite ispravnu E-mail adresu",
		url: "Unesite ispravan URL.",
		date: "Unesite ispravan datum.",
		dateISO: "Unesite ispravan datum (ISO).",
		number: "Unesite ispravan broj.",
		digits: "Unesite samo cifre.",
		creditcard: "Unesite ispravan broj kreditne kartice.",
		equalTo: "Unesite istu vrednost ponovo.",
		extension: "Ekstenzija nije ispravna.",
		maxlength: $.validator.format("Unesite manje od {0} karaktera."),
		minlength: $.validator.format("Unesite barem {0} karaktera."),
		rangelength: $.validator.format("Unesite vrednost dugačku između {0} i {1} karaktera."),
		range: $.validator.format("Unesite vrednost između {0} i {1}."),
		max: $.validator.format("Unesite vrednost manju ili jednaku {0}."),
		min: $.validator.format("Unesite vrednost veću ili jednaku {0}.")
	});
}(jQuery));
