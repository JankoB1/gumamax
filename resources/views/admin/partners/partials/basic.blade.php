@inject('cities', 'Delmax\Webapp\Models\City')
	{!!Former::horizontal_open()
		->id('form-partner-basic')
		->method($formMethod)
		->action($formUrl)
		->secure()!!}
	<fieldset>
	{!!Former::populate($partner) !!}

				<div class="row">
				<div class="col-xs-12 col-sm-6">
					{!! Former::text('erp_company_id')->label('ErpCompanyId') !!}
					{!! Former::text('erp_partner_id')->label('ErpPartnerId') !!}
					{!! Former::text('description')->label('Naziv') !!}
					{!! Former::text('description2')->label('Odeljenje') !!}
					{!! Former::text('tax_identification_number')->label('PIB') !!}
					{!! Former::select('city.city_id')->name('city_id')
    					->fromQuery($cities::serbianCities(['city_id', 'city_name']), 'city_name', 'city_id')
    					->placeholder('Odaberi mesto')
    					->label('Mesto')
					!!}
					{!! Former::text('address')->label('Adresa') !!}
					{!! Former::text('phone') !!}
					{!! Former::text('fax') !!}
					{!! Former::email('email') !!}
					{!! Former::text('web_address')->label('Sajt') !!}
				</div>
				<div class="col-xs-12 col-sm-6">
					{!! Former::text('latitude')->label('Geo.širina') !!}
					{!! Former::text('longitude')->label('Geo.dužina') !!}
					<div class="form-group clearfix">
						<div id="map" class="map-canvas" style="height: 375px"></div>
					</div>
				</div>
				</div>
		</fieldset>
		<div class="form-actions center">
		{!! Button::primary(_('Save'))->withAttributes(['class'=>'btnPartnerSubmit'])->submit() !!}
		</div>

		{!!Former::close()!!}

@push('scripts')
<script id="validate-form-partner-basic">
	$(function(){
		$('#form-partner-basic').validate({
			rules : {
				name : {
					required : true,
					minlength: 2,
					maxlength : 32
				},

				department : {
					maxlength : 32
				},

				is_installer:{
					required: true
				},

				tax_identification_number:{
					required: true
				},

				first_name : {
					required : true,
					minlength: 2
				},

				last_name : {
					required : true,
					minlength : 2,
					maxlength : 32
				},

				city_id : {
					required : true
				},

				address : {
					required : true,
					minlength : 2,
					maxlength : 48
				},
				phone : {
					required : true,
					minlength : 1,
					maxlength : 20
				},

				email : {
					required : true,
					email: true,
					minlength: 1,
					maxlength: 64
				},

				web_address : {
					maxlength: 64
				}
			},
			highlight: function(element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error');
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if(element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			}
		});
	});

</script>
@endpush




