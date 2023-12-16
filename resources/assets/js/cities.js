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

