@extends('layouts.master')

@section('content')
@include('includes.headertemplate')
<div role="main" data-role="main-content" class="content">
	<div class="inner-page-container">
		<form id="payment-form" method="POST" action="{{ url('/payment') }}" data-ajax="false">
			<input id="cardholder-name" type="text">
			<!-- placeholder for Elements -->
			<div id="card-element"></div>
			<button id="card-button">{{__('messages.Pay with card')}}</button>
		</form>
	</div>
</div>
@include('includes.fixedfooter')
@endsection
@section('footer-script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
	// 
	var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}');

	var elements = stripe.elements();
	var cardElement = elements.create('card', {
		hidePostalCode: true
	});
	cardElement.mount('#card-element');

	//
	var cardholderName = document.getElementById('cardholder-name');
	var cardButton = document.getElementById('card-button');

	cardButton.addEventListener('click', function(ev) {
		stripe.createPaymentMethod('card', cardElement, {
			billing_details: {name: cardholderName.value}
		}).then(function(result) {
			if (result.error) {
				console.log(result.error);
				// Show error in payment form
			} else {
				let data = {
					'_token': "{{ csrf_token() }}",
					'payment_method_id': result.paymentMethod.id
				}
				// Otherwise send paymentMethod.id to your server (see Step 2)
				fetch('{{ url('confirm-payment') }}', {
					method: 'POST',
					body: JSON.stringify(data),
					headers: {
						'Content-Type': 'application/json'
					}
				}).then(function(result) {
					// Handle server response (see Step 3)
					result.json().then(function(json) {
						handleServerResponse(json);
					})
				});
			}
		});

		ev.preventDefault();
	});

	function handleServerResponse(response) {
		if (response.error) {
			// Show error from server on payment form
		} else if (response.requires_action) {
			// Use Stripe.js to handle required card action
			stripe.handleCardAction(
				response.payment_intent_client_secret
			).then(function(result) {
				if (result.error) {
					// Show error in payment form
				} else {
					let data = {
						'_token': "{{ csrf_token() }}",
						'payment_intent_id': result.paymentIntent.id
					}
					// The card action has been handled
					// The PaymentIntent can be confirmed again on the server
					fetch('{{ url('confirm-payment') }}', {
						method: 'POST',
						body: JSON.stringify(data),
						headers: {
							'Content-Type': 'application/json'
						}
					}).then(function(confirmResult) {
						return confirmResult.json();
					}).then(handleServerResponse);
				}
			});
		} else {
			// Show success message
		}
	}
</script>
@endsection