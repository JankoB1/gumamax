$(document).ready(function(){
	$('#modal-form').on("shown.bs.modal",function(){
		// $('#form-field-price').focus();
		$('#form-field-price').select();
	});

	$('#modal-form-delivery_radius').on("shown.bs.modal",function(){
		$('#form-field-delivery_radius').select();
	});

	$('.edit-pricelist').on("click",function(e){
		e.preventDefault();
		var tmp = $(this);
		$('#form-field-price').val(tmp.attr('data-price'));
		$('#form-field-id').val(tmp.attr('data-id'));
		$('#form-field-product_id').val(tmp.attr('data-product_id'));
	});

	$('.edit-delivery_radius').on("click",function(e){
		e.preventDefault();
		var tmp = $(this);
		$('#form-field-delivery_radius').val(tmp.attr('data-delivery_radius'));
	});


	$('#form-field-price').on('keydown',function(e){
		if(e.which==13){
			$('.btn-save-price').click();
		}
	});

	$('#form-field-delivery_radius').on('keydown',function(e){
		if(e.which==13){
			$('.btn-save-delivery_radius').click();
		}
	});

	$('.btn-save-price').on("click",function(){
		var tmp = $(this),
			partner_id = tmp.closest('#modal-form').find('#form-field-partner_id').val(),
			product_id = tmp.closest('#modal-form').find('#form-field-product_id').val(),
			price = tmp.closest('#modal-form').find('#form-field-price').val(),
			id = tmp.closest('#modal-form').find('#form-field-id').val();

		if (price<0) {
			$(".modal-body .err_msg").addClass("alert").addClass("alert-error").html("Cena ne može biti negativna!");
			return false;
		}

		if (price>1000000) {
			$(".modal-body .err_msg").addClass("alert").addClass("alert-error").html("Cena ne može biti veća od 1.000.000,00 RSD!");
			return false;
		}

		$(".modal-body .err_msg").removeClass("alert").removeClass("alert-error").html("");


		$.ajax({
			type: "POST",
			url: urlTo('admin/partner/'+partner_id+'/save/service-price'),
			data: 'partner_id='+partner_id+'&id='+id+'&price='+price+'&product_id='+product_id
		}).done(function(resp){
			var p,
				dataid;
			if(price=='-'){
				dataid = '';
				p = 0;
			} else {
				dataid = resp.value ? resp.value : id;
				p = price;
			}
			//remove hidden inputs
			$('#form-field-id').val('');
			$('#form-field-product_id').val('');
			//update price in grid
			$('.item_price_v > a[data-product_id='+product_id+']')
				.attr('data-price',p)
				.attr('data-id',dataid)
				.html(number_format(price,2,',','.'));

			$('#modal-form').modal('hide');
		});
		$(".modal-body .err_msg").removeClass("alert").removeClass("alert-error").html("");
	});

	$('.btn-save-delivery_radius').on('click', function(){
		var tmp = $(this),
			partner_id = tmp.closest('#modal-form-delivery_radius').find('#form-field-delivery_radius-partner_id').val(),
			crmpaid = tmp.closest('#modal-form-delivery_radius').find('#form-field-delivery_radius-crmpaid').val(),
			delivery_radius = tmp.closest('#modal-form-delivery_radius').find('#form-field-delivery_radius').val();
		if (delivery_radius<0) {
			$(".modal-body .err_msg").addClass("alert").addClass("alert-error").html("Udaljenost ne može biti negativna!");
			return false;
		}

		if (delivery_radius>1000000) {
			$(".modal-body .err_msg").addClass("alert").addClass("alert-error").html("Udaljenost ne može biti veća od 300 km!");
			return false;
		}		
		$(".modal-body .err_msg").removeClass("alert").removeClass("alert-error").html("");
		$.ajax({
			type: "POST",
			url: urlTo('admin/partner/'+partner_id+'/save/delivery-radius'),
			data: 'partner_id='+partner_id+'&dr='+delivery_radius+'&crmpaid='+crmpaid
		}).done(function(resp){
			var dr,
				dataid;
			if(delivery_radius=='-'){
				dataid = '';
				dr = 0;
			} else {
				dataid = resp.value ? resp.value : crmpaid;
				dr = delivery_radius;
			}
			//remove hidden inputs
			$('#form-field-delivery_radius-crmpaid').val('');
			//update price in grid
			$('a.edit-delivery_radius')
				.attr('data-delivery_radius',dr)
				.attr('data-crmpaid',crmpaid)
				.html(number_format(delivery_radius,2,',','.'));
			$('#modal-form').modal('hide');			
		});


		$(".modal-body .err_msg").removeClass("alert").removeClass("alert-error").html("");
	});

	$('.btn-save-mobile_distance').on('click', function(){
		var tmp = $(this),
			partner_id = tmp.closest('#modal-form-mobile_distance').find('#form-field-mobile_distance-partner_id').val(),
			crmpaid = tmp.closest('#modal-form-mobile_distance').find('#form-field-mobile_distance-crmpaid').val(),
			mobile_distance = tmp.closest('#modal-form-mobile_distance').find('#form-field-mobile_distance').val();
		if (mobile_distance<0) {
			$(".modal-body .err_msg").addClass("alert").addClass("alert-error").html("Udaljenost ne može biti negativna!");
			return false;
		}

		if (mobile_distance>1000000) {
			$(".modal-body .err_msg").addClass("alert").addClass("alert-error").html("Udaljenost ne može biti veća od 300 km!");
			return false;
		}		
		$(".modal-body .err_msg").removeClass("alert").removeClass("alert-error").html("");
		$.ajax({
			type: "POST",
			url: urlTo('admin/partner/'+partner_id+'/save/mobile-distance'),
			data: 'partner_id='+partner_id+'&dr='+mobile_distance+'&crmpaid='+crmpaid
		}).done(function(resp){
			var md,
				dataid;
			if(mobile_distance=='-'){
				dataid = '';
				md = 0;
			} else {
				dataid = resp.value ? resp.value : crmpaid;
				md = mobile_distance;
			}
			//remove hidden inputs
			$('#form-field-mobile_distance-crmpaid').val('');
			//update price in grid
			$('a.edit-mobile_distance')
				.attr('data-mobile_distance',md)
				.attr('data-crmpaid',crmpaid)
				.html(number_format(mobile_distance,2,',','.'));
			$('#modal-form').modal('hide');			
		});
		$(".modal-body .err_msg").removeClass("alert").removeClass("alert-error").html("");
	});


	$('.btn-save-modal-other').on("click",function(){
		var tmp = $(this),
			partner_id = tmp.closest('#modal-form-other').find('#form-field-other-partner_id').val(),
			data = {
				"partner_id": partner_id,
				"car_max_rim": tmp.closest('#modal-form-other').find('#maxrim_auto').val(),
				"suv_max_rim": tmp.closest('#modal-form-other').find('#maxrim_4x4').val(),
				"van_max_rim": tmp.closest('#modal-form-other').find('#maxrim_kombi').val(),
				"bike_max_rim": tmp.closest('#modal-form-other').find('#maxrim_motor').val(),
				"truck_max_rim": tmp.closest('#modal-form-other').find('#maxrim_kamion').val()
			};

		$.ajax({
			type: "POST",
			url: urlTo('admin/partner/'+partner_id+'/save/other-service'),
			data: data,
			dataType: "JSON"
		}).done(function(resp){
			if(!resp.value){
				if(data.car_max_rim!='-'){
					$('.car-mxr').html(data.car_max_rim+'"');
				} else {
					$('.car-mxr').html('');
				}
				if (data.suv_max_rim!='-') {
					$('.suv-mxr').html(data.suv_max_rim+'"');
				} else {
					$('.suv-mxr').html('');
				}
				if (data.bike_max_rim!='-') {
					$('.bike-mxr').html(data.bike_max_rim+'"');
				} else {
					$('.bike-mxr').html('');
				}
				if (data.truck_max_rim!='-') {
					$('.truck-mxr').html(data.truck_max_rim+'"');
				} else {
					$('.truck-mxr').html('');
				}

				if (data.van_max_rim!='-') {
					switch(data.van_max_rim) {
						case '15':
							$('.van-mxr').html('do 16"');
							break;
						case '16':
							$('.van-mxr').html('16" OBRUČ');
							break;
						case '17.5':
							$('.van-mxr').html('17,5"');
							break;
					}
				} else {
					$('.van-mxr').html('');
				}

				$('#modal-form-other').modal('hide');
			}
		});
	});

});