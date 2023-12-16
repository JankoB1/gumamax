;(function($){

	$.fn.addVehicle = function(options){

		var defaults = {
			year: {
				"label": "Godina proizvodnje",
				"elClass": "year",
				"name": "year",
				"value": "",
				"required":"required"
			},
			MFA_ID: {
				"label": "Naziv proizvodjača",
				"elClass": "manufacturersByYear",
				"name": "manufacturersByYear",
				"value": "",
				"required":"required"
			},
			MOD_ID: {
				"label": "Model",
				"elClass": "vehiclesByYear",
				"name": "vehiclesByYear",
				"value": "",
				"required":"required"
			},
			TYP_ID: {
				"label": "Vozilo",
				"elClass": "vehiclesType",
				"name": "vehiclesType",
				"value": "",
				"required":"required"
			},
			vin: {
				"label": "Broj šasije",
				"elClass": "vin",
				"name": "vin",
				"value": "",
				"required":""
			},
			engineCode: {
				"label": "Broj motora",
				"elClass": "engineCode",
				"name": "engineCode",
				"value": "",
				"required":""
			},
			vehicleName: {
				"label": "Naziv",
				"elClass": "vehicleName",
				"name": "vehicleName",
				"value": "",
				"required":""
			},
			"sendButton": false,
			"showNameField": false,
			"showVIN": false,
			"showEngineCode": false,

			send: {
				"label": "Dodaj vozilo",
				"elClass": "send"
			},
			error: {
				"errorClass": "vehicleError",
				"success": "Uspešno ste dodali vozilo",
				"err": "Dodavanje vozila nije uspelo"
			},
			containerClass: "TYP_ID",
			userId: '',
			guestId :'',
			vehicleUrl: "",
			hiddenType: "type_id",
			vehicleList: "",
			loaderElement: '',
			close: function(){
			},
			htmlType: function(){
			}
		}

		function AddVehicle(element, options){
			var widget = this;

			widget.config = $.extend(true, {}, defaults, options);
			widget.element = element;
			widget.el = {
				"year": null,
				"MFA_ID": null,
				"MOD_ID": null,
				"TYP_ID": null,
				"vin": null,
				"engineCode": null,
				"vehicleName": null,
				"send": null,
				"error": null
			}

			this.init();

			widget.element.on("submit", function(e){
				e.preventDefault();
				return false;
			});

			if(widget.config.sendButton){
				widget.el.send.on("click", function(e){
					e.preventDefault();
					widget.config.vin.value = widget.el.vin.val();
					widget.config.engineCode.value = widget.el.engineCode.val();
					widget.config.vehicleName.value = widget.el.vehicleName.val();


					widget.getData(
						widget.el.error,
						{
							"a"           : "insertVehicle",
							"TYP_ID"      : widget.config.TYP_ID.value,
							"vin"         : widget.config.vin.value,
							"engineCode"  : widget.config.engineCode.value,
							"vehicleName" : widget.config.vehicleName.value,
							"userId"      : widget.config.userId
						},
						widget.insertVehicleSuccess,
						widget.insertVehicleError
					);

					// widget.config.htmlType(
					// 	widget.config.vehicleList,
					// 	widget.config.TYP_ID.value,
					// 	widget.config.user_vehicle_id,
					// 	widget.config.vehicleName.value
					// );

					// widget.resetForm(widget.el, widget);
				});
			}

			widget.el.year.on("change", function(e){
				widget.config.year.value = $(this).val();

				widget.getData(
					widget.el.MFA_ID,
					{
						a: "manufacturersByYear",
						year: widget.config.year.value
					},
					widget.manufacturersByYear
				);

				widget.el.MOD_ID.val("").prop('disabled', 'disabled');
				widget.el.TYP_ID.val("").prop('disabled', 'disabled');
			});

			widget.el.MFA_ID.on("change", function(e){
				widget.config.MFA_ID.value = $(this).val();

				widget.getData(
					widget.el.MOD_ID,
					{
						a: "modelsByYear",
						year: widget.config.year.value,
						MFA_ID: widget.config.MFA_ID.value
					},
					widget.modelsByYear
				);

				widget.el.TYP_ID.val("").prop('disabled', 'disabled');
				widget.el.vehicleName.val("").prop('disabled', "disabled");
			});

			widget.el.MOD_ID.on("change", function(e){
				widget.config.MOD_ID.value = $(this).val();

				widget.getData(
					widget.el.TYP_ID,
					{
						a: "vehiclesByYear",
						year: widget.config.year.value,
						MFA_ID: widget.config.MFA_ID.value,
						MOD_ID: widget.config.MOD_ID.value
					},
					widget.vehiclesByYear
				);

				widget.el.vehicleName.val("").prop('disabled', 'disabled');
				widget.config.vehicleName.value = widget.el.MFA_ID.find(":selected").text()+' '+$(this).find(":selected").text();
			});

			widget.el.TYP_ID.on("change", function(e){
				widget.config.TYP_ID.value = $(this).val();
				widget.config.vehicleName.value = widget.el.MFA_ID.find(":selected").text()+' '+$(this).find(":selected").text();
				widget.el.vehicleName.val(widget.config.vehicleName.value).prop('disabled', false);
				if(widget.config.hiddenType != "")
					widget.config.hiddenType.value=widget.config.TYP_ID.value;
			});
		}

		AddVehicle.prototype.init = function(){
			this.element.addClass(this.config.containerClass);

			this.el.error = $("<div/>", {
				"class": this.config.error.errorClass
			}).appendTo(this.element);

			var form = $("<div/>").appendTo(this.element);
			var cgYRS = $('<div class="control-group"/>'),
				cgMBY = $('<div class="control-group"/>'),
				cgVBY = $('<div class="control-group"/>'),
				cgVTY = $('<div class="control-group"/>'),
				cgVIN = $('<div class="control-group"/>'),
				cgENC = $('<div class="control-group"/>'),
				cgVNM = $('<div class="control-group"/>');


			$("<label/>", {
				"text": this.config.year.label,
				"for": this.config.year.name,
				"class":"control-label" +' '+ this.config.year.required
			}).appendTo(cgYRS);
			this.el.year = $("<select/>", {
				"class": this.config.year.elClass,
				"name": this.config.year.name,
				"id": this.config.year.name
			}).appendTo(cgYRS).wrap('<div class="controls"/>');
			this.inityear(this.el.year);

			cgYRS.appendTo(form);

			$("<label/>", {
				"text": this.config.MFA_ID.label,
				"for": this.config.MFA_ID.name,
				"class":"control-label" +' '+ this.config.MFA_ID.required
			}).appendTo(cgMBY);
			this.el.MFA_ID = $("<select/>", {
				"class": this.config.MFA_ID.elClass,
				"name": this.config.MFA_ID.name,
				"id": this.config.MFA_ID.name,
				"disabled": "disabled"
			}).appendTo(cgMBY).wrap('<div class="controls"/>');

			cgMBY.appendTo(form);

			$("<label/>", {
				"text": this.config.MOD_ID.label,
				"for": this.config.MOD_ID.name,
				"class":"control-label" +' '+ this.config.MOD_ID.required
			}).appendTo(cgVBY);
			this.el.MOD_ID = $("<select/>", {
				"class": this.config.MOD_ID.elClass,
				"name": this.config.MOD_ID.name,
				"id": this.config.MOD_ID.name,
				"disabled": "disabled"
			}).appendTo(cgVBY).wrap('<div class="controls"/>');

			cgVBY.appendTo(form);

			$("<label/>", {
				"text": this.config.TYP_ID.label,
				"for": this.config.TYP_ID.name,
				"class":"control-label" +' '+ this.config.TYP_ID.required
			}).appendTo(cgVTY);
			this.el.TYP_ID = $("<select/>", {
				"class": this.config.TYP_ID.elClass,
				"name": this.config.TYP_ID.name,
				"id": this.config.TYP_ID.name,
				"disabled": "disabled"
			}).appendTo(cgVTY).wrap('<div class="controls"/>');

			cgVTY.appendTo(form);

			$("<label/>", {
				"text": this.showVIN ? this.config.vin.label : '',
				"for": this.config.vin.name,
				"class":"control-label" +' '+ this.config.vin.required
			}).appendTo(cgVIN);
			this.el.vin = $("<input/>", {
				"type": this.showVIN ? "text":"hidden",
				"class": this.config.vin.elClass,
				"name": this.config.vin.name,
				"id": this.config.vin.name
			}).appendTo(cgVIN).wrap('<div class="controls"/>');

			cgVIN.appendTo(form);

			$("<label/>", {
				"text": this.showEngineCode ? this.config.engineCode.label : '',
				"for": this.config.engineCode.name,
				"class":"control-label" +' '+ this.config.engineCode.required
			}).appendTo(cgENC);

			this.el.engineCode = $("<input/>", {
				"type": this.showEngineCode ? "text":"hidden",
				"class": this.config.engineCode.elClass,
				"name": this.config.engineCode.name,
				"id": this.config.engineCode.name
			}).appendTo(cgENC).wrap('<div class="controls"/>');

			cgENC.appendTo(form);

			if(this.config.showNameField){
				$("<label/>", {
					"text": this.config.vehicleName.label,
					"for": this.config.vehicleName.name,
					"class":"control-label" +' '+ this.config.vehicleName.required
				}).appendTo(cgVNM);
			}
			this.el.vehicleName = $("<input/>", {
				"type": this.showNameField ? "text":"hidden",
				"class": this.config.vehicleName.elClass,
				"name": this.config.vehicleName.name,
				"id": this.config.vehicleName.name,
				"disabled": "disabled"
			}).appendTo(cgVNM).wrap('<div class="controls"/>');

			cgVNM.appendTo(form);

			if(this.config.sendButton){
				this.el.send = $("<button/>", {
					"text": this.config.send.label,
					"class": this.config.send.elClass + " btn"
				}).appendTo(form);
			}

			this.initDataFromSession(this.el);
		}

		AddVehicle.prototype.inityear = function(el){
			var d = new Date();
			var html = '<option value=""></option>';
			for (var i = d.getFullYear(); i >= 1900; i--) {
				html += '<option value="'+i+'">'+i+'</option>';
			}
			el.html(html);
		}

		AddVehicle.prototype.initDataFromSession = function(){
			var widget = this;
			var tmp = '';
			$.ajax({
				"type": "GET",
				"url": "/vehicle/ajax/session",
				"contentType": "application/json; charset=utf-8"
			}).done(function(response){
				if(response.length==0 || response==undefined || response=='null' || response==null){
					$.removeCookie('oldRegVehicle');
					return false;
				}
				$.cookie.json = true;
				var qSel = $.cookie('oldRegVehicle');
				if(qSel!==undefined && qSel.year.value!==undefined && qSel.year.value!==''){
					widget.el.year.val(qSel.year.value);
					var R = $.parseJSON(response);
					if(R.manufacturersByYear.value.length>0){
						$.each(R.manufacturersByYear.value, function(i,mby){
							tmp += '<option value="'+mby.MFA_ID+'">'+mby.MFA_BRAND+'</option>';
						});
						widget.el.MFA_ID.attr("disabled", !(qSel.manufacturersByYear.enabled));
						widget.el.MFA_ID.append(tmp);
						widget.el.MFA_ID.val(qSel.manufacturersByYear.value);

						if(R.vehiclesByYear.value.length>0){
							tmp = '';
							$.each(R.vehiclesByYear.value, function(j,vby){
								tmp += '<option value="'+vby.MOD_ID+'">'+vby.DESCRIPTION+' | '+vby.AGE+'</option>';
							});
							widget.el.MOD_ID.attr("disabled", !(qSel.vehiclesByYear.enabled));
							widget.el.MOD_ID.append(tmp);
							widget.el.MOD_ID.val(qSel.vehiclesByYear.value);

							if(R.vehiclesType!=undefined && R.vehiclesType.value.length>0){
								tmp = '';
								$.each(R.vehiclesType.value, function(k,vt){
									tmp += '<option value="'+vt.TYP_ID+'">'+vt.DESCRIPTION+'</option>';
								});
								widget.el.TYP_ID.attr("disabled", !(qSel.vehiclesType.enabled));
								widget.el.TYP_ID.append(tmp);
								widget.el.TYP_ID.val(qSel.vehiclesType.value);
							}
						}
					}
					widget.el.vin.attr("disabled", !(qSel.vin.enabled));
					widget.el.vin.val(qSel.vin.value);

					widget.el.engineCode.attr("disabled", !(qSel.engineCode.enabled));
					widget.el.engineCode.val(qSel.engineCode.value);

					widget.el.vehicleName.attr("disabled", !(qSel.vehicleName.enabled));
					widget.el.vehicleName.val(qSel.vehicleName.value);

					widget.config.year.value = widget.el.year.val();
					widget.config.MFA_ID.value = widget.el.MFA_ID.val();
					widget.config.MOD_ID.value = widget.el.MOD_ID.val();
					widget.config.TYP_ID.value = widget.el.TYP_ID.val();
					widget.config.vin.value = widget.el.vin.val();
					widget.config.engineCode.value = widget.el.engineCode.val();
					widget.config.vehicleName.value = widget.el.vehicleName.val();

					// nakon sto je sve procitano i ucitano, obrisan je COOKIE
					$.removeCookie('oldRegVehicle');
				}
			});
		}


		AddVehicle.prototype.getData = function(el, data, success, error){
			var widget = this;
			widget.config.loaderElement.show();
			$.ajax({
				type: "GET",
				url: widget.config.vehicleUrl,
				contentType: "application/json; charset=utf-8",
				data: data,
				dataType: "json"
			}).done(function(data) {
				widget.config.loaderElement.hide();
				var res,
					count=0;
				if(!data.error){
					res = success(el, data.value, widget);
					// Ovo postoji samo zbog kompatibilnosti sa IE8
					for(var i in data) {
						if(data.hasOwnProperty(i)) {
							count++;
						}
					}
					//Ovo ne radi u IE8 :-(
					//if(Object.keys(data).length==1){
					if(count==1){
						// console.log('user_vehicle_id='+data.value);
						// console.log(widget.el);
						// widget.config.user_vehicle_id = data.value;
						widget.config.htmlType(
							widget.config.vehicleList,
							widget.config.TYP_ID.value,
							data.value,
							// $('#vehicleName').val()
							widget.config.vehicleName.value
						);
						widget.resetForm(widget.el, widget);
					}
					el.prop('disabled', false);
				}else{
					if(typeof error != 'undefined')
						res = error(el, data.value, widget);
				}

			}).fail(function(){
				widget.config.loaderElement.hide();
			});
		}

		AddVehicle.prototype.manufacturersByYear = function(el, data, widget){
			var html = '<option value=""></option>';

			$.each(data, function(i, item){
				html += '<option value="'+item['MFA_ID']+'">'+item['MFA_BRAND']+'</option>';
			});
			el.html(html);
			return true;
		}

		AddVehicle.prototype.modelsByYear = function(el, data, widget){
			var html = '<option value=""></option>';
			$.each(data, function(i, item){
				html += '<option value="'+item['MOD_ID']+'">'+item['DESCRIPTION']+' | '+item['AGE']+'</option>';
			});
			el.html(html);
			return true;
		}

		AddVehicle.prototype.vehiclesByYear = function(el, data, widget){
			var html = '<option value=""></option>';

			$.each(data, function(i, item){
				html += '<option value="'+item['TYP_ID']+'">'+item['DESCRIPTION']+' | '+item['AGE']+' | '+item['KW_FROM']+' kW | '
				+item['HP_FROM']+' ks | '+item['CCM']+' ccm| '+item['BODY']+' | '+item['FUEL']+'</option>';
			});
			el.html(html);
			return true;
		}

		AddVehicle.prototype.insertVehicleSuccess = function(el, data, widget){
			el.html('<div class="alert alert-success">'+widget.config.error.success+'</div>');
			widget.config.user_vehicle_id = data;
			widget.config.close();
			return true;
		}

		AddVehicle.prototype.insertVehicleError = function(el, data, widget){
			el.html('<div class="alert alert-error">'+widget.config.error.err+'</div>');
			return false;
		}

		AddVehicle.prototype.resetForm = function(el, widget){
			widget.config.year.value = '';
			widget.config.MFA_ID.value = '';
			widget.config.MOD_ID.value = '';
			widget.config.TYP_ID.value = '';
			widget.config.vin.value = '';
			widget.config.engineCode.value = '';
			widget.config.vehicleName.value = '';
			widget.config.user_vehicle_id = '';

			widget.el.year.val("");
			widget.el.MFA_ID.val("").prop('disabled', 'disabled');
			widget.el.MOD_ID.val("").prop('disabled', 'disabled');
			widget.el.TYP_ID.val("").prop('disabled', 'disabled');
			widget.el.vin.val("");
			widget.el.engineCode.val("");
			widget.el.vehicleName.val("").prop('disabled', 'disabled');
			widget.el.error.html("");
			return true;
		}

		AddVehicle.prototype.ulHTML = function(widget){
			if(widget.config.vehiclesType!=null && widget.config.vehicleList != ""){
				var html = '<div class="profile_box">'
				+'<div class="pull-right edit"><a href="#" title="" data-action="Izmeni">Izmeni</a></div>'
				+'<p class="data-profil"><strong>'+widget.config.vehicleName.value+'</strong></p>'
				+'<div class="clearfix"></div>'
				+'</div>';
				$(html).appendTo(widget.config.vehicleList).fadeIn("slow");
			}
			return true;
		}


		new AddVehicle(this.first(), options);
		return this.first();
	};
}(jQuery));