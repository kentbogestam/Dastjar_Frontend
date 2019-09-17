@extends('layouts.master')

@section('content')
@include('includes.headertemplate')
<div role="main" data-role="main-content" class="content">
	<div class="inner-page-container row-confirm-payment">
		<form id="payment-form" method="POST" action="{{ url('confirm-payment-test') }}" data-ajax="false">
			<input id="cardholder-name" type="text" placeholder="Cardholder name">
			<!-- placeholder for Elements -->
			<div id="card-element"></div>
			<div class="card-errors"></div>
			<button type="button" id="card-button" class="ui-btn ui-mini">{{__('messages.Pay with card')}}</button>
		</form>
	</div>
</div>
@include('includes.fixedfooter')
@endsection
@section('footer-script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
	// Initialize Stripe and card element
	var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}');
	var stripe2;

	var elements = stripe.elements();
	var cardElement = elements.create('card', {
		hidePostalCode: true
	});
	cardElement.mount('#card-element');

	//
	// var cardholderName = document.getElementById('cardholder-name');
	var cardButton = document.getElementById('card-button');

	cardButton.addEventListener('click', function(ev) {
		$('#card-button').prop('disabled', true);
		$('.row-confirm-payment').find('div.card-errors').html('');

		stripe.createPaymentMethod('card', cardElement).then(function(result) {
			if (result.error) {
				// Show error in payment form
				$('#card-button').prop('disabled', false);
			} else {
				let data = {
					'_token': "{{ csrf_token() }}",
					'payment_method_id': result.paymentMethod.id
				}
				// Otherwise send paymentMethod.id to your server (see Step 2)
				fetch('{{ url('confirm-payment-test') }}', {
					method: 'POST',
					body: JSON.stringify(data),
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					}
				}).then(function(result) {
					// Handle server response (see Step 3)
					result.json().then(function(json) {
						handleServerResponse(json);
						$('#card-button').prop('disabled', false);
					})
				});
			}
		});

		ev.preventDefault();
	});

	function handleServerResponse(response) {
		if (response.error) {
			// Show error from server on payment form
			let message = response.error;
			if( typeof(response.error) == 'object' ) {
				message = response.error.message;
			}
			$('.row-confirm-payment').find('div.card-errors').html(message);
		} else if (response.requires_action) {
			// Use Stripe.js to handle required card action
			stripe2 = Stripe('{{ env('STRIPE_PUB_KEY') }}', {
				stripeAccount: response.stripeAccount
			});

			stripe2.handleCardAction(
				response.payment_intent_client_secret
			).then(function(result) {
				if (result.error) {
					// Show error in payment form
					let message = result.error;
					if( typeof(result.error) == 'object' ) {
						message = result.error.message;
					}
					$('.row-confirm-payment').find('div.card-errors').html(message);
				} else {
					let data = {
						'_token': "{{ csrf_token() }}",
						'payment_intent_id': result.paymentIntent.id
					}
					// The card action has been handled
					// The PaymentIntent can be confirmed again on the server
					fetch('{{ url('confirm-payment-test') }}', {
						method: 'POST',
						body: JSON.stringify(data),
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						}
					}).then(function(confirmResult) {
						return confirmResult.json();
					}).then(handleServerResponse);
				}
			});
		} else {
			// Show success message
			$('.row-confirm-payment').find('div.card-errors').html('');
			alert('Payment success');
		}
	}
</script>
@endsection