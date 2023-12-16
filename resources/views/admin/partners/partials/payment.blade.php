@inject('paymentMethods', 'Delmax\Models\PaymentMethod')
<!-- start:payment -->
<div id="payment-page">
	<?php $partnerPaymentMethods = $partner->paymentMethods->pluck('payment_method_id')->toArray() ?>
	<legend>Načini plaćanja</legend>
	<pre>{!! json_encode($partnerPaymentMethods) !!}</pre>
	{!! Former::horizontal_open()->action(url('/partner/'.$partner->partner_id.'/payment-methods/save'))->id('form-pm')->secure() !!}

		@foreach($paymentMethods->all() as $paymentMethod)
		<div class="checkbox">
			<label for="payment_methods[{!!$paymentMethod->payment_method_id!!}]">
				<input type="checkbox" id="payment_methods[{!!$paymentMethod->payment_method_id!!}]"
					   name="payment_methods[{!! $paymentMethod->payment_method_id!!}]"
					   {!! in_array($paymentMethod->payment_method_id, $partnerPaymentMethods) ? 'checked' : ''!!}>
					   {!!$paymentMethod->description !!}
			</label>
		</div>
		@endforeach
<br>
<small>Za promenu podataka na ovoj stranici, obratite se Gumamax-u</small>

{!! Former::close() !!}

</div>
<!-- end:payment -->
