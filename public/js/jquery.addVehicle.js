;(function($){

	$.fn.addVehicle = function(options){

		var defaults = {
			year: {
				"label": "Godina proizvodnje",
				"name": "year_of_production",
				"value": "",
				"required":"required"
			},
			mfa_id: {
				"label": "Naziv proizvodjača",
				"name": "mfa_id",
				"value": "",
				"required":"required"
			},
			mod_id: {
				"label": "Model",
				"name": "mod_id",
				"value": "",
				"required":"required"
			},
			typ_id: {
				"label": "Vozilo",
				"name": "typ_id",
				"value": "",
				"required":"required"
			},
			vin: {
				"label": "Broj šasije",
				"name": "vin",
				"value": "",
				"required":""
			},
			engineCode: {
				"label": "Broj motora",
				"name": "engine_code",
				"value": "",
				"required":""
			},
			vehicleName: {
				"label": "Naziv",
				"name": "commercial_description",
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
			containerClass: "typ_id",
			userId: '',
			guestId :'',
			vehicleUrl: "",
			hiddenType: "",
			vehicleList: "",
			formGroupClass : 'control-group',
			formElementClass : 'form-control',
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
				"mfa_id": null,
				"mod_id": null,
				"typ_id": null,
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

					widget.config.year.value = widget.el.year.val();
					widget.config.mfa_id.value = widget.el.mfa_id.val();
					widget.config.mod_id.value = widget.el.mod_id.val();
					widget.config.typ_id.value = widget.el.typ_id.val();
					widget.config.vin.value = widget.el.vin.val();
					widget.config.engineCode.value = widget.el.engineCode.val();
					widget.config.vehicleName.value = widget.el.vehicleName.val();


					widget.getData(
						widget.el.error,
						{
							"a"           	: "insertVehicle",
							"year"      	: widget.config.year.value,
							"mfa_id"      	: widget.config.mfa_id.value,
							"mod_id"      	: widget.config.mod_id.value,
							"typ_id"      	: widget.config.typ_id.value,
							"vin"         	: widget.config.vin.value,
							"engine_code"  	: widget.config.engineCode.value,
							"commercial_description" : widget.config.vehicleName.value,
							"userId"      	: widget.config.userId
						},
						widget.insertVehicleSuccess,
						widget.insertVehicleError
					);

				});
			}

			widget.el.year.on("change", function(e){
				widget.config.year.value = $(this).val();

				widget.getData(
					widget.el.mfa_id,
					{
						a: "manufacturersByYear",
						year: widget.config.year.value
					},
					widget.manufacturersByYear
				);

				widget.el.mod_id.val("").prop('disabled', 'disabled');
				widget.el.typ_id.val("").prop('disabled', 'disabled');
			});

			widget.el.mfa_id.on("change", function(e){
				widget.config.mfa_id.value = $(this).val();

				widget.getData(
					widget.el.mod_id,
					{
						a: "modelsByYear",
						year: widget.config.year.value,
						mfa_id: widget.config.mfa_id.value
					},
					widget.modelsByYear
				);

				widget.el.typ_id.val("").prop('disabled', 'disabled');
				widget.el.vehicleName.val("").prop('disabled', "disabled");
			});

			widget.el.mod_id.on("change", function(e){
				widget.config.mod_id.value = $(this).val();

				widget.getData(
					widget.el.typ_id,
					{
						a: "vehiclesByYear",
						year: widget.config.year.value,
						mfa_id: widget.config.mfa_id.value,
						mod_id: widget.config.mod_id.value
					},
					widget.vehiclesByYear
				);

				widget.el.vehicleName.val("").prop('disabled', 'disabled');
				widget.config.vehicleName.value = widget.el.mfa_id.find(":selected").text()+' '+$(this).find(":selected").text();
			});

			widget.el.typ_id.on("change", function(e){
				widget.config.typ_id.value = $(this).val();
				widget.config.vehicleName.value = widget.el.mfa_id.find(":selected").text()+' '+$(this).find(":selected").text();
				widget.el.vehicleName.val(widget.config.vehicleName.value).prop('disabled', false);
				if(widget.config.hiddenType != "")
					widget.config.hiddenType.value=widget.config.typ_id.value;
			});
		}

		AddVehicle.prototype.init = function(){
			this.element.addClass(this.config.containerClass);

			this.el.error = $("<div/>", {
				"class": this.config.error.errorClass
			}).appendTo(this.element);

			var form = $("<div/>").appendTo(this.element);

			var cgYRS = $('<div class="'+this.config.formGroupClass+'"/>'),
				cgMBY = $('<div class="'+this.config.formGroupClass+'"/>'),
				cgVBY = $('<div class="'+this.config.formGroupClass+'"/>'),
				cgVTY = $('<div class="'+this.config.formGroupClass+'"/>'),
				cgVIN = $('<div class="'+this.config.formGroupClass+'"/>'),
				cgENC = $('<div class="'+this.config.formGroupClass+'"/>'),
				cgVNM = $('<div class="'+this.config.formGroupClass+'"/>');


			$("<label/>", {
				"text": this.config.year.label,
				"for": this.config.year.name,
				"class":"control-label" +' '+ this.config.year.required
			}).appendTo(cgYRS);
			this.el.year = $("<select/>", {
				"class": this.config.formElementClass,
				"name": this.config.year.name,
				"id": this.config.year.name
			}).appendTo(cgYRS);
			this.inityear(this.el.year);

			cgYRS.appendTo(form);

			$("<label/>", {
				"text": this.config.mfa_id.label,
				"for": this.config.mfa_id.name,
				"class":"control-label" +' '+ this.config.mfa_id.required
			}).appendTo(cgMBY);
			this.el.mfa_id = $("<select/>", {
				"class": this.config.formElementClass,
				"name": this.config.mfa_id.name,
				"id": this.config.mfa_id.name,
				"disabled": "disabled"
			}).appendTo(cgMBY);

			cgMBY.appendTo(form);

			$("<label/>", {
				"text": this.config.mod_id.label,
				"for": this.config.mod_id.name,
				"class":"control-label" +' '+ this.config.mod_id.required
			}).appendTo(cgVBY);
			this.el.mod_id = $("<select/>", {
				"class": this.config.formElementClass,
				"name": this.config.mod_id.name,
				"id": this.config.mod_id.name,
				"disabled": "disabled"
			}).appendTo(cgVBY);

			cgVBY.appendTo(form);

			$("<label/>", {
				"text": this.config.typ_id.label,
				"for": this.config.typ_id.name,
				"class":"control-label" +' '+ this.config.typ_id.required
			}).appendTo(cgVTY);
			this.el.typ_id = $("<select/>", {
				"class": this.config.formElementClass,
				"name": this.config.typ_id.name,
				"id": this.config.typ_id.name,
				"disabled": "disabled"
			}).appendTo(cgVTY);

			cgVTY.appendTo(form);

			$("<label/>", {
				"text": this.showVIN ? this.config.vin.label : '',
				"for": this.config.vin.name,
				"class":"control-label" +' '+ this.config.vin.required
			}).appendTo(cgVIN);
			this.el.vin = $("<input/>", {
				"type": this.showVIN ? "text":"hidden",
				"class": this.config.formElementClass,
				"name": this.config.vin.name,
				"id": this.config.vin.name
			}).appendTo(cgVIN);

			cgVIN.appendTo(form);

			$("<label/>", {
				"text": this.showEngineCode ? this.config.engineCode.label : '',
				"for": this.config.engineCode.name,
				"class":"control-label" +' '+ this.config.engineCode.required
			}).appendTo(cgENC);

			this.el.engineCode = $("<input/>", {
				"type": this.showEngineCode ? "text":"hidden",
				"class": this.config.formElementClass,
				"name": this.config.engineCode.name,
				"id": this.config.engineCode.name
			}).appendTo(cgENC);

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
				"class": this.config.formElementClass,
				"name": this.config.vehicleName.name,
				"id": this.config.vehicleName.name,
				"disabled": "disabled"
			}).appendTo(cgVNM);

			cgVNM.appendTo(form);

			if(this.config.sendButton){
				this.el.send = $("<button/>", {
					"text": this.config.send.label,
					"class": this.config.send.elClass + " btn btn-primary"
				}).appendTo(form);
			}

			//this.initDataFromSession(this.el);
		}

		AddVehicle.prototype.inityear = function(el){
			var d = new Date();
			var html = '<option value=""></option>';
			for (var i = d.getFullYear(); i >= 1900; i--) {
				html += '<option value="'+i+'">'+i+'</option>';
			}
			el.html(html);
		}

		AddVehicle.prototype.showLoader = function(el){
			var loader = $('<span class="add-vehicle-spinner"/>').appendTo(el.parents().children('label'));

			loader.show();
			return loader;
		}

		AddVehicle.prototype.getData = function(el, data, success, error){
			var widget = this,
			loader = this.showLoader(el);
			
			$.ajax({
				type: "GET",
				url: widget.config.vehicleUrl,
				contentType: "application/json; charset=utf-8",
				data: data,
				dataType: "json"
			}).done(function(data) {
				loader.hide();
				var res;
				if(!data.error){
					res = success(el, data.data, widget);
					el.prop('disabled', false);
				}else{
					if(typeof error != 'undefined')
						res = error(el, data.data, widget);
				}

			}).fail(function(){
				loader.hide();
			});
		}

		AddVehicle.prototype.manufacturersByYear = function(el, data, widget){
			var html = '<option value=""></option>';

			$.each(data, function(i, item){
				html += '<option value="'+item['id']+'">'+item['description']+'</option>';
			});
			el.html(html);
			return true;
		}

		AddVehicle.prototype.modelsByYear = function(el, data, widget){
			var html = '<option value=""></option>';
			$.each(data, function(i, item){
				html += '<option value="'+item['id']+'">'+item['description']+' | '+item['age']+'</option>';
			});
			el.html(html);
			return true;
		}

		AddVehicle.prototype.vehiclesByYear = function(el, data, widget){
			var html = '<option value=""></option>';
		
			$.each(data, function(i, item){
				html += '<option value="'+item['id']+'">'+item['description']+" | "+ item['kw']+' kW | '
				+item['hp']+' ks | '+item['ccm_tech']+' ccm| '+item['body']+' | '+item['fuel']+'</option>';
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
			widget.config.mfa_id.value = '';
			widget.config.mod_id.value = '';
			widget.config.typ_id.value = '';
			widget.config.vin.value = '';
			widget.config.engineCode.value = '';
			widget.config.vehicleName.value = '';
			widget.config.user_vehicle_id = '';

			widget.el.year.val("");
			widget.el.mfa_id.val("").prop('disabled', 'disabled');
			widget.el.mod_id.val("").prop('disabled', 'disabled');
			widget.el.typ_id.val("").prop('disabled', 'disabled');
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
