@extends('v1.user.layouts.master')

@section('content')
	@include('v1.user.elements.store-delivery-service')

	<div id="cart-wrapper">
		<div class="cart-list">
			<h4>{{ __('messages.Order Details') }}</h4>
			<a href="javascript:void(0)" id="delete-cart" data-content="{{ __("messages.Delete Cart Order") }}"><i class="fa fa-trash"></i></a>
		</div>
		<div class="container-fluid">
			<div class="cart-table">
				<div class="table-responsive">
					<table class="table" id="table-custom-2">
						<input type="hidden" name="redirectUrl" id="redirectUrl" value="{{ url('restro-menu-list/').'/'.$order->store_id }}"/>
						<input type="hidden" name="orderid" id="orderid" value="{{ $order->order_id }}" />
						<input type="hidden" name="baseUrl" id="baseUrl" value="{{ url('/')}}"/>
						{{ csrf_field() }}
						@php
						$j = 0;
						$cntCartItems = 0;
						@endphp
						@foreach($orderDetails as $value)
							@php
							$cntCartItems += $value->product_quality;
							$j++;
							@endphp
							<tr class="custom_row1" id="row_{{$j}}">
								<td colspan="2">
									{{ $value->product_name }} 
									<p>{{ $value->price }} {{ $order->currencies }}</p>
									<input type="hidden" name="prod[{{$j}}]" id="prod{{$j}}" value="{{ $value->product_id }}">
									<input type="hidden" name="itemprice[{{$j}}]" id="itemprice{{$j}}" value="{{$value->price}}"/>
								</td>
								<td class="text-center">
									<div class="quantity">
										<span class="minus min" onclick="decrementCartValue('{{$j}}','{{ __("messages.Delete Product") }}')"><i class="fa fa-minus"></i></span>
										<span class="inputBox">
											<input type="text" name="product[{{$j}}][prod_quant]" value="{{ $value->product_quality }}" maxlength="2" readonly size="1" id="qty{{$j}}" />
										</span>
										<span class="plus max" onclick="incrementCartValue('{{$j}}')"><i class="fa fa-plus"></i></span>
									</div>
								</td>
								<td class="text-right">
									<span id="itemtotalDisplay{{$j}}">{{ number_format($value->price*$value->product_quality, 2, '.', '') }}</span> {{ $order->currencies }}
									 <input type="hidden" name="itemtotal[{{$j}}]" id="itemtotal{{$j}}" value="{{ $value->price*$value->product_quality }}" class="itemtotal"/>
								</td>
							</tr>
						@endforeach
					</table>
					<div class="block-total">
						<table class="table">
							<tr class="row-sub-total">
								<td class="text-right" width="70%">SUB TOTAL</td>
								<td class="text-right" width="30%">
									<span id="sub-total">{{ number_format($order->order_total, 2, '.', '') }}</span> {{$order->currencies}}
								</td>
							</tr>
							@if($customerDiscount)
								<tr class="row-discount">
									<td class="text-right" width="70%">DISCOUNT</td>
									<td class="text-right" width="30%">
										<span id="discount-amount">{{ number_format($orderInvoice['discount'], 2, '.', '') }}</span> {{ $order->currencies }}
									</td>
								</tr>
							@endif
							@if( in_array(3, $store_delivery_type) && Helper::isPackageSubscribed(12) )
								<tr class="row-delivery-charge {{ !isset($orderInvoice['homeDelivery']['delivery_charge']) ? 'hidden' : '' }}">
									<td class="text-right" width="70%">DELIVERY CHARGE</td>
									<td class="text-right" width="30%">
										<span id="delivery-charge">{{ isset($orderInvoice['homeDelivery']['delivery_charge']) ? number_format($orderInvoice['homeDelivery']['delivery_charge'], 2, '.', '') : '0.00' }}</span> {{ $order->currencies }}
									</td>
								</tr>
							@endif
							<tr class="row-total">
								<td class="text-right" width="70%"><strong>TOTAL</strong></td>
								<td class="text-right" width="30%">
									<span id="grandTotalDisplay" style="font-weight: bold;">{{ number_format(($order->final_order_total), 2, '.', '') }}</span>
									<span><strong>{{$order->currencies}}</strong></span>
									<input type="hidden" name="grandtotal" id="grandtotal" value="{{$order->final_order_total}}"/>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<!-- Loyalty -->
				<div class="row text-center row-loyalty-discount">
					<div class="col-md-12 loyalty-discount-text">
						{!! isset($orderInvoice['loyalty_quantity_free']) ? $orderInvoice['loyaltyOfferApplied'] : '' !!}
					</div>
				</div>
			</div>

			<!-- Delivery Type -->
			@if($storedetails->deliveryTypes->count() >= 1)
				<div class="contact-form-section row-order-delivery-type">
					<ul>
						@foreach($storedetails->deliveryTypes as $row)
							@if($row->delivery_type == 1 || $row->delivery_type == 2 || ($row->delivery_type == 3 && Helper::isPackageSubscribed(12)))
								<li>
									<div class="radio">
										<label>
											<input type="radio" name="delivery_type" value="{{ $row->delivery_type }}">
											@if($row->delivery_type == 1)
												{{ __('messages.deliveryOptionDineIn') }}
											@elseif($row->delivery_type == 2)
												{{ __('messages.deliveryOptionTakeAway') }}
											@elseif($row->delivery_type == 3 && Helper::isPackageSubscribed(12))
												{{ __('messages.deliveryOptionHomeDelivery') }}
											@endif
										</label>
									</div>
								</li>
							@endif
						@endforeach
					</ul>
				</div>
			@else
				@foreach($storedetails->deliveryTypes as $row)
					@if($storedetails->deliveryTypes[0]['delivery_type'] != 3 || Helper::isPackageSubscribed(12))
						<input type="radio" name="delivery_type" value="{{ $row->delivery_type }}" checked="" class="hidden">
					@endif
				@endforeach
			@endif
			
			{{-- If store support home delivery --}}
			@if( in_array(3, $store_delivery_type) && Helper::isPackageSubscribed(12) )
				<div class="row block-address hidden"></div>
			@endif

			<div class="row" style="padding: 10px 0">
				@if(Session::get('paymentmode') !=0 && $order->final_order_total > 0)
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-pay" disabled="">{{ __('messages.proceedToPay') }}</button>
					</div>
					<div class="col-md-12 panel panel-default row-confirm-payment hidden">
						@if(isset($paymentMethod->data))
							<div class="row-saved-cards">
								<form id="list-saved-cards">
									@foreach($paymentMethod->data as $row)
										<div class="radio">
											<label>
												<input type="radio" name="payment_method_id" value="{{ $row->id }}">
												<i class="fa fa-cc-visa" aria-hidden="true"></i>
												<i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i>
												{{ $row->card->last4 }}
											</label>
										</div>
									@endforeach
									<div class="card-errors text-danger"></div>
									<button type="button" id="charging-saved-cards" class="btn" style="display: none;">{{ __('messages.paySecurely') }}</button>
								</form>
							</div>
						@endif
						<div class="row-new-card">
							<div class="radio">
								<label>
									<input type="radio" name="pay-options" id="pay-options">
									{{ __('messages.payOptions') }}
								</label>
							</div>
							<div class="section-pay-with-card hidden">
								<form id="payment-form">
									<!-- placeholder for Elements -->
									<div id="card-element"></div>
									<div class="card-errors text-danger"></div>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="isSaveCard" id="isSaveCard" checked="">
											{{ __('messages.saveCardInfo') }}
										</label>
									</div>
									<button type="button" id="card-button" class="btn">{{__('messages.Pay with card')}}</button>
								</form>
							</div>
						</div>
					</div>
				@else
					<div class="col-md-12 text-center">
						<button type="button" class="btn send-order" disabled="">{{ __('messages.send order and pay in restaurant') }}</button>
					</div>
				@endif
			</div>

			<!-- Modal delete cart -->
			<div id="delete-cart-alert" class="modal fade" role="dialog">
				<div class='modal-dialog'>
					<div class="modal-content">
						<div class="modal-body text-center">
							<p>{{ __("messages.Delete Cart Order") }}</p><br>
			           		<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
							<button type="button" class="btn btn-primary submit-btn" onclick="deleteFullCart('{{ url("emptyCart/") }}','1','{{ __("messages.Delete Cart Order") }}')">{{ __('messages.Delete') }}</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal delete cart item -->
			<div id="delete-cart-item-alert" class="modal fade" role="dialog">
				<div class='modal-dialog'>
					<div class="modal-content">
						<div class="modal-body text-center">
							<p>{{ __("messages.Delete Product") }}</p><br>
			           		<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
							<button type="button" class="btn btn-primary delete">{{ __('messages.Delete') }}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('footer-script')
<script type="text/javascript" src="{{ asset('plugins/validation/jquery.validate.min.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
	// 
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': "{{ csrf_token() }}"
		}
	});

	@if(Session::get('paymentmode') !=0 && $order->final_order_total > 0)
		// Initialize Stripe and card element
		var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}');

		var elements = stripe.elements();
		var cardElement = elements.create('card', {
			hidePostalCode: true
		});
		cardElement.mount('#card-element');

		// Pay with Card
		// var cardholderName = document.getElementById('cardholder-name');
		var cardButton = document.getElementById('card-button');

		cardButton.addEventListener('click', function(ev) {
			$('#card-button').prop('disabled', true);
			$('.row-new-card').find('div.card-errors').html('');

			stripe.createPaymentMethod('card', cardElement).then(function(result) {
				if (result.error) {
					// Show error in payment form
					let message = result.error;
					if( typeof(result.error) == 'object' ) {
						message = result.error.message;
					}
					$('.row-new-card').find('div.card-errors').html(message);
					$('#card-button').prop('disabled', false);
				} else {
					let isSaveCard = $('#isSaveCard').is(':checked') ? 1 : 0;
					let data = {
						'_token': "{{ csrf_token() }}",
						'isSaveCard': isSaveCard,
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
							$('#card-button').prop('disabled', false);
						})
					});
				}
			});

			ev.preventDefault();
		});

		// Handle response when 'Pay with card'
		function handleServerResponse(response) {
			if (response.error) {
				// Show error from server on payment form
				let message = response.error;
				if( typeof(response.error) == 'object' ) {
					message = response.error.message;
				}
				$('.row-new-card').find('div.card-errors').html(message);
			} else if (response.requires_action) {
				// Use Stripe.js to handle required card action
				stripe.handleCardAction(
					response.payment_intent_client_secret
				).then(function(result) {
					if (result.error) {
						// Show error in payment form
						let message = result.error;
						if( typeof(result.error) == 'object' ) {
							message = result.error.message;
						}
						$('.row-new-card').find('div.card-errors').html(message);
					} else {
						let isSaveCard = $('#isSaveCard').is(':checked') ? 1 : 0;
						
						let data = {
							'_token': "{{ csrf_token() }}",
							'isSaveCard': isSaveCard,
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
				$('.row-new-card').find('div.card-errors').html('');
				window.location.href = "{{ url('order-view/'.$order->order_id) }}";
			}
		}

		// Pay with PaymentMethod
		$('#charging-saved-cards').on('click', function(ev) {
			if( $('input[name=payment_method_id]:checked').length )
			{
				$('#charging-saved-cards').prop('disabled', true);
				let payment_method_id = $('input[name=payment_method_id]:checked').val();
				let data = {
					'_token': "{{ csrf_token() }}",
					'chargingSavedCard': true,
					'payment_method_id': payment_method_id
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
						handleServerResponseSavedCard(json);
						$('#charging-saved-cards').prop('disabled', false);
					})
				});
			}
			else
			{
				alert('Please select card first!');
			}

			ev.preventDefault();
		});
		
		// Handle response when pay with 'saved card' 
		function handleServerResponseSavedCard(response) {
			if (response.error) {
				// Show error from server on payment form
				let message = response.error;
				if( typeof(response.error) == 'object' ) {
					message = response.error.message;
				}
				$('.row-saved-cards').find('div.card-errors').html(message);
			} else if (response.requires_action) {
				// Use Stripe.js to handle required card action
				stripe.handleCardAction(
					response.payment_intent_client_secret
				).then(function(result) {
					if (result.error) {
						// Show error in payment form
						let message = result.error;
						if( typeof(result.error) == 'object' ) {
							message = result.error.message;
						}
						$('.row-saved-cards').find('div.card-errors').html(message);
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
				$('.row-saved-cards').find('div.card-errors').html('');
				window.location.href = "{{ url('order-view/'.$order->order_id) }}";
			}
		}

		// 
		$('input[name=payment_method_id]').on('click', function() {
			$('#charging-saved-cards').show();
			// Hide 'pay with card'
			$('#pay-options').prop('checked', false);
			$('.section-pay-with-card').addClass('hidden');
		});

		// 
		$('#pay-options').on('click', function() {
			$('input[name=payment_method_id]').prop('checked', false);
			$('#charging-saved-cards').hide();
			$('.section-pay-with-card').removeClass('hidden');
		});
	@endif

	// Scroll automatically 'add new address'
	/*$(document).on("collapsibleexpand", "#add-new-address[data-role=collapsible]", function () {
		var position = $(this).offset().top;
		$.mobile.silentScroll(position-200);
	});*/

	// Proceed to pay (show payment method)
	$('.btn-pay').on('click', function(e) {
		// 
		if( $('#frm-user-address').length && !$('#frm-user-address').valid())
		{
			return false;
		}

		//
		$('.row-confirm-payment').removeClass('hidden'); 
	});

	// Delivery address form validation
	$('#frm-user-address').validate({
		rules: {
			user_address_id: {
				required: true
			}
		},
		messages: {
			user_address_id: {
				required: '{{ __('messages.fieldRequired') }}'
			}
		},
		errorPlacement: function (error, element) {
			if(element.is(':radio'))
			{
				error.insertAfter(element.closest('.ui-grid-a'));
			}
		}
	});

	// Update value in basket
	var cntCartItems = "{{ $cntCartItems }}";
	$('.cart-badge').html(cntCartItems);
	$('.cart-badge').removeClass('hidden');

	// Show delete cart popup
	$('#delete-cart, #leave-cart').on('click', function() {
		var content = $(this).data('content');
		$('#delete-cart-alert').find('p').html(content);
		$('#delete-cart-alert').modal('show');
	});

	// Update 'delivery_type'
	$(document).on('change', 'input[name=delivery_type]', function() {
		orderUpdateDeliveryType();
	});

	// Update user address
	$(document).on('change', 'input[name=user_address_id]', function() {
		updateOrderUserAddress();
	});

	// Check default delivery type on load
	function checkDefaultDeliveryType()
	{
		if($('input[name=delivery_type]').length)
		{
			var deliveryTypes = new Array();

			// 
			$('input[name=delivery_type]').each(function() {
				deliveryTypes.push($(this).val());
			});

			if(deliveryTypes.length == 1)
			{
				$('input[name=delivery_type][value="'+deliveryTypes[0]+'"]').prop('checked', true);
			}
			else
			{
				// Dine In/Take Away, Take Away/Home Delivery, Dine In/Take Away/Home Delivery
				if( (deliveryTypes.indexOf("1") != -1 && deliveryTypes.indexOf("2") != -1) || (deliveryTypes.indexOf("2") != -1 && deliveryTypes.indexOf("3") != -1) || (deliveryTypes.indexOf("1") != -1 && deliveryTypes.indexOf("2") != -1 && deliveryTypes.indexOf("3") != -1) )
				{
					$('input[name=delivery_type][value="2"]').prop('checked', true);
				}
				// Dine In/Home Delivery
				else if(deliveryTypes.indexOf("1") != -1 && deliveryTypes.indexOf("3") != -1)
				{
					$('input[name=delivery_type][value="3"]').prop('checked', true);
				}
			}

			orderUpdateDeliveryType();
		}
	}

	// Update order delivery type
	function orderUpdateDeliveryType()
	{
		// 
		if($('input[name=delivery_type]:checked').val() == '3')
		{
			getHomeDeliveryPartContent($('#orderid').val());
		}
		else
		{
			$('.block-address').addClass('hidden');
			$('.btn-pay').prop('disabled', false);
			$('.send-order').prop('disabled', false);
		}

		// Start: Just to update cart
		id = 1;
		var qty = parseInt($('#qty'+id).val(), 10);
		var prod = $('#prod'+id).val();
		// End: Just to update cart
		
		// Update 'delivery_type' in DB
		$.ajax({
			type: 'POST',
			url: "{{ url('order-update-delivery-type') }}",
			data: {
				'_token': "{{ csrf_token() }}",
				'order_id': "{{ $order->order_id }}", 
				'delivery_type': $('input[name=delivery_type]:checked').val()
			},
			dataType: 'json',
			success: function(response) {
				updateCart(qty, prod, 0, 0);
			}
		});
	}

	// Get 'home delivery' content to proceed order
	function getHomeDeliveryPartContent(order_id)
	{
		$.ajax({
			url: "{{ url('get-home-delivery-part-content') }}/"+order_id,
			dataType: 'json',
			success: function(response) {
				$('.block-address').html(response.html);
				$('.block-address').removeClass('hidden');

				updateOrderUserAddress();
			}
		});
	}

	// Update order 'user_address_id'
	function updateOrderUserAddress()
	{
		// 
		$('.block-address').find('p.error').remove();
		$('.btn-pay').prop('disabled', true);
		$('.row-confirm-payment').addClass('hidden');
		$('.send-order').prop('disabled', true);

		// 
		if($('input[name=user_address_id]:checked').length)
		{
			$.ajax({
				type: 'POST',
				url: "{{ url('update-order-user-address') }}",
				data: {
					'_token': "{{ csrf_token() }}",
					'order_id': "{{ $order->order_id }}", 
					'user_address_id': $('input[name=user_address_id]:checked').val()
				},
				dataType: 'json',
				success: function(response) {
					if(!response.status)
					{
						// $('input[name=user_address_id]').prop('checked', false);
						$('.block-address form#frm-user-address').after('<p class="text-center text-danger error">'+response.msg+'</p>');
					}
					else
					{
						$('.btn-pay').prop('disabled', false);
						$('.send-order').prop('disabled', false);
					}
				}
			});
		}
	}

	// Save address
	$(document).on('submit', '#save-address', function(e) {
		e.preventDefault();

		// Form validate
		if($('#save-address').valid())
		{
			var formData = $(this).serialize();

			// Send data to server through the Ajax call
			$.ajax({
				type: 'POST',
				url: "{{ url('save-user-address') }}",
				data: formData,
				async: 'true',
				dataType: 'json',
				beforeSend: function() {
					showLoading();
				},
				complete: function() {
					hideLoading('Processing...');
				},
				success: function(result) {
					if(result.status)
					{
						getHomeDeliveryPartContent($('#orderid').val());
					}
				},
				error: function() {
					alert('Something went wrong, please try again!');
				}
			});
		}

		return false;
	});

	// 
	$('.send-order').on('click', function() {
		if($('input[name=delivery_type]:checked').val() == '3')
		{
			if($('#frm-user-address').length && $('input[name=user_address_id]:checked').length)
			{
				window.location.href = "{{url('order-view').'/'.$order->order_id}}";
			}
		}
		else
		{
			window.location.href = "{{url('order-view').'/'.$order->order_id}}";
		}
	});

	checkDefaultDeliveryType();
</script>
@endsection