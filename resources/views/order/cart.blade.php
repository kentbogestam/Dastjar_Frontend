@extends('layouts.master')
@section('styles')
	<style>
		.loyalty-discount-text {
			color: green;
		}

		.row-order-delivery-type .ui-btn {
			background-color: #f6f6f6 !important;
		}

		.row-order-delivery-type .ui-btn-active{
			background-color: #38c !important;
		    border-color: #38c !important;
		    color: #fff !important;
		    text-shadow: 0 1px 0 #059 !important;
		}

		button.btn-pay.ui-btn.ui-btn-inline.ui-mini {
		    background: #d9edf7;
		    border: none;
		    padding: 6px 12px;
		    display: inline-block;
		    width: 300px;
		    height: 40px;
		    font-size: 15px;
		}

		button.send-order.ui-btn.ui-mini {
			background: #d9edf7;
			border: none;
			padding: 6px 12px;
			display: inline-block;
			width: 300px;
			height: 40px;
			font-size: 15px;
		}

	.section-pay-with-card {
	    max-width: 500px;
	    margin: 0 auto;
	    padding: 30px;
	    box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
	}

	.section-pay-with-card button#card-button {
	    background-color: #38c;
	    border: none;
	    color: #fff;
	    font-weight: normal;
	    font-size: 15px;
	    border-radius: 5px;
	}

	.section-pay-with-card .ui-input-text, 
	.section-pay-with-card .ui-input-search {
	    margin: .5em 0;
	    border-width: 1px;
	    border-style: solid;
	    border-color: #ddd;
	}

	.ui-controlgroup-controls {
	    text-align: center;
	}

	.ui-controlgroup-controls>div {
		display: inline-block;
	}
	.section-pay-with-card ul {
	    padding: 0;
	    margin: 0;
	    list-style: none;
	    text-align: center;
	    padding-top: 20px;
	}
	.section-pay-with-card ul li {
	    display: inline-block;
	    font-size: 30px;
	    color: #ddd;
	}
	</style>
@stop
@section('content')
@include('includes.headertemplate')
<div role="main" data-role="main-content" class="content">
	<div class="inner-page-container">
		<div class="table-content">
			<div class="head_line">
				<h2>{{ __('messages.Order Details') }}</h2>
				<div class="delt-cart">
					<a href="javascript:void(0)" id="delete-cart" data-content="{{ __("messages.Delete Cart Order") }}" data-ajax="false">
						<img src="{{ url('images/dlt_icon.png') }}">
					</a>
				</div>
				</div>
			</div>
			<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
				<?php $j=1 ;?>
				<input type="hidden" name="redirectUrl" id="redirectUrl" value="{{ url('restro-menu-list/').'/'.$order->store_id }}"/>
				<input type="hidden" name="orderid" id="orderid" value="{{ $order->order_id }}" />
				<input type="hidden" name="baseUrl" id="baseUrl" value="{{ url('/')}}"/>
				{{ csrf_field() }}
				@php
				$cntCartItems = 0;
				@endphp
				@foreach($orderDetails as $value)
					@php
					$cntCartItems += $value->product_quality;
					@endphp
					<tr class="custom_row1" id="row_{{$j}}">				
						<td>
							<input type="hidden" name="prod[{{$j}}]" id="prod{{$j}}" value="{{ $value->product_id }}">
							<div class="cart_rowA">{{ $value->product_name }}</div>
							<div class="cart_rowB">
								<div class="colA g-text">{{ $value->price }} <input type="hidden" name="itemprice[{{$j}}]" id="itemprice{{$j}}" value="{{$value->price}}"/> {{ $order->currencies }}</div>
								<div class="colB">
									<div class="qty-sec">
										<input type="button" onclick="decrementCartValue('{{$j}}','{{ __("messages.Delete Product") }}')" value="-"  class="min" />
										<input type="text" name="product[{{$j}}][prod_quant]" value="{{ $value->product_quality }}" maxlength="2" readonly size="1" id="qty{{$j}}" />
										<input type="button" onclick="incrementCartValue('{{$j}}')" value="+" class="max" />
									</div>
								</div>
								<div class="colC">
									<span id="itemtotalDisplay{{$j}}">{{ number_format($value->price*$value->product_quality, 2, '.', '') }}</span>
									<input type="hidden" name="itemtotal[{{$j}}]" id="itemtotal{{$j}}" value="{{ $value->price*$value->product_quality }}" class="itemtotal"/> {{ $order->currencies }}
								</div>
							</div>
						</td>
					</tr>	
					<?php $j=$j+1 ;?>
				@endforeach
			</table>
			<div class="block-total">
				<div class="ui-grid-a row-sub-total">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a">SUB TOTAL</div>
					</div>
					<div class="ui-block-b">
						<div class="ui-bar ui-bar-a">
							<span id="sub-total">{{ number_format($order->order_total, 2, '.', '') }}</span> {{$order->currencies}}
						</div>
					</div>
				</div>
				@if($customerDiscount)
					<div class="ui-grid-a row-discount">
						<div class="ui-block-a">
							<div class="ui-bar ui-bar-a">DISCOUNT</div>
						</div>
						<div class="ui-block-b">
							<div class="ui-bar ui-bar-a">
								<span id="discount-amount">{{ number_format($orderInvoice['discount'], 2, '.', '') }}</span> {{ $order->currencies }}
							</div>
						</div>
					</div>
				@endif
				@if( in_array(3, $store_delivery_type) && Helper::isPackageSubscribed(12) )
					<div class="ui-grid-a row-delivery-charge {{ !isset($orderInvoice['homeDelivery']['delivery_charge']) ? 'hidden' : '' }}">
						<div class="ui-block-a">
							<div class="ui-bar ui-bar-a">DELIVERY CHARGE</div>
						</div>
						<div class="ui-block-b">
							<div class="ui-bar ui-bar-a">
								<span id="delivery-charge">{{ isset($orderInvoice['homeDelivery']['delivery_charge']) ? number_format($orderInvoice['homeDelivery']['delivery_charge'], 2, '.', '') : '0.00' }}</span> {{ $order->currencies }}
							</div>
						</div>
					</div>
				@endif
				<div class="ui-grid-a row-total">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a"><strong>TOTAL</strong></div>
					</div>
					<div class="ui-block-b">
						<div class="ui-bar ui-bar-a">
							<span id="grandTotalDisplay" style="font-weight: bold;">{{ number_format(($order->final_order_total), 2, '.', '') }}</span>
							<span><strong>{{$order->currencies}}</strong></span>
							<input type="hidden" name="grandtotal" id="grandtotal" value="{{$order->final_order_total}}"/>
						</div>
					</div>
				</div>
			</div>

			@if($storedetails->deliveryTypes->count() >= 1)
				<div class="ui-grid-solo row-order-delivery-type">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a text-center">
							<form>
								<fieldset data-role="controlgroup" data-type="horizontal">
									@foreach($storedetails->deliveryTypes as $row)
										@if($row->delivery_type == 1)
											<input type="radio" name="delivery_type" id="delivery_typea" value="1">
											<label for="delivery_typea">{{ __('messages.deliveryOptionDineIn') }}</label>
										@endif

										@if($row->delivery_type == 2)
											<input type="radio" name="delivery_type" id="delivery_typeb" value="2">
											<label for="delivery_typeb">{{ __('messages.deliveryOptionTakeAway') }}</label>
										@endif

										@if($row->delivery_type == 3 && Helper::isPackageSubscribed(12))
											<input type="radio" name="delivery_type" id="delivery_typec" value="3">
											<label for="delivery_typec">{{ __('messages.deliveryOptionHomeDelivery') }}</label>
										@endif
									@endforeach
								</fieldset>
							</form>
						</div>
					</div>
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
				<div class="block-address hidden"></div>
			@endif

			<div class="ui-grid-solo">
				<div class="ui-block-a">
					@if(Session::get('paymentmode') !=0 && Session::has('stripeAccount') && $order->final_order_total > 0)
						<div class="ui-grid-solo">
							<div class="ui-block-a">
								<div class="ui-bar ui-bar-a text-center">
									<button type="button" class="ui-btn ui-btn-inline ui-mini btn-pay" disabled="">{{ __('messages.proceedToPay') }}</button>
								</div>
							</div>
						</div>
						<div class="ui-grid-solo row-confirm-payment hidden">
							<div class="ui-block-a">
								<div class="ui-bar ui-bar-a">
									@php $isCardDefault = false; @endphp
									@if(isset($paymentMethod->data))
										@if( count($paymentMethod->data) == 1 )
                                        	@php $isCardDefault = true; @endphp
                                        @endif
										<div class="row-saved-cards">
											<form id="list-saved-cards" method="POST" action="{{ url('confirm-payment') }}" data-ajax="false">
												<fieldset data-role="controlgroup">
													@foreach($paymentMethod->data as $row)
														<input type="radio" name="payment_method_id" id="payment-method-{{ $loop->index }}" value="{{ $row->id }}" <?php echo ($isCardDefault) ? 'checked' : ''; ?>>
														<label for="payment-method-{{ $loop->index }}">
															<i class="fa fa-cc-visa" aria-hidden="true"></i>
															<i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i>
															{{ $row->card->last4 }}
														</label>
													@endforeach
												</fieldset>
												<div class="card-errors"></div>
												<button type="button" id="charging-saved-cards" class="ui-btn ui-mini" <?php echo ($isCardDefault == false) ? 'style="display: none"' : ''; ?>>{{ __('messages.paySecurely') }}</button>
											</form>
										</div>
									@endif
									<div class="row-new-card">
										<fieldset data-role="controlgroup">
											<input type="radio" name="pay-options" id="pay-options" <?php echo ($isCardDefault == false) ? 'checked' : ''; ?>>
											<label for="pay-options">{{ __('messages.payOptions') }}</label>
										</fieldset>
										<div class="section-pay-with-card<?php echo ($isCardDefault == false) ? '' : ' hidden'; ?>">
											<form id="payment-form" method="POST" action="{{ url('confirm-payment') }}" data-ajax="false">
												<!-- <input id="cardholder-name" type="text" placeholder="Cardholder name"> -->
												<!-- placeholder for Elements -->
												<div id="card-element"></div>
												<div class="card-errors"></div>
												<label>
													<input type="checkbox" name="isSaveCard" id="isSaveCard" checked="">
													{{ __('messages.saveCardInfo') }}
												</label>
												<button type="button" id="card-button" class="ui-btn ui-mini">{{__('messages.Pay with card')}}</button>
												<ul>
													<li><i class="fa fa-cc-stripe" aria-hidden="true"></i></li>
													<li><i class="fa fa-cc-amex" aria-hidden="true"></i></li>
													<li><i class="fa fa-cc-mastercard" aria-hidden="true"></i></li>
													<li><i class="fa fa-cc-visa" aria-hidden="true"></i></li>
												</ul>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					@else
						<button type="button" class="ui-btn ui-mini send-order" disabled="">{{ __('messages.send order and pay in restaurant') }}</button>
					@endif
				</div>
			</div>

			<!-- Loyalty -->
			<div class="ui-grid-solo text-center row-loyalty-discount">
				<div class="ui-block-a">
					<div class="ui-bar ui-bar-a loyalty-discount-text">
						{!! isset($orderInvoice['loyalty_quantity_free']) ? $orderInvoice['loyaltyOfferApplied'] : '' !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('includes.fixedfooter')
@endsection
@section('footer-script')
<!-- Delete cart popup -->
<div id="delete-cart-alert" class="actionBox">
	<div class="actionBox-content">
		<div class="mInner">
			<p>{{ __("messages.Delete Cart Order") }}</p>
			<div class="btnWrapper">
				<span class="close">{{ __('messages.Cancel') }}</span>
				<span onclick="deleteFullCart('{{ url("emptyCart/") }}','1','{{ __("messages.Delete Cart Order") }}')">{{ __('messages.Delete') }}</span>
			</div>
		</div>
	</div>
</div>

<!-- Delete cart item popup -->
<div id="delete-cart-item-alert" class="actionBox">
	<div class="actionBox-content">
		<div class="mInner">
			<p>{{ __("messages.Delete Product") }}</p>
			<div class="btnWrapper">
				<span class="close">{{ __('messages.Cancel') }}</span>
				<span class="delete">{{ __('messages.Delete') }}</span>
			</div>
		</div>
	</div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="{{ asset('plugins/validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
	// 
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': "{{ csrf_token() }}"
		}
	});

	@if(Session::get('paymentmode') !=0 && Session::has('stripeAccount') && $order->final_order_total > 0)
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
			showLoading();
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
					hideLoading('Processing...');
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
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						}
					}).then(function(result) {
						// Handle server response (see Step 3)
						result.json().then(function(json) {
							handleServerResponse(json);
							$('#card-button').prop('disabled', false);
							hideLoading('Processing...');
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
				$('.row-new-card').find('div.card-errors').html('');
				window.location.href = "{{ url('order-view/'.$order->order_id) }}";
			}
		}

		// Pay with PaymentMethod
		$('#charging-saved-cards').on('click', function(ev) {
			if( $('input[name=payment_method_id]:checked').length )
			{
				showLoading();
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
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					}
				}).then(function(result) {
					// Handle server response (see Step 3)
					result.json().then(function(json) {
						handleServerResponseSavedCard(json);
						$('#charging-saved-cards').prop('disabled', false);
						hideLoading('Processing...');
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
	$(document).on("collapsibleexpand", "#add-new-address[data-role=collapsible]", function () {
		var position = $(this).offset().top;
		$.mobile.silentScroll(position-200);
	});

	// 
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
		$('#delete-cart-alert').show();
	});

	// Close popup
	$('.actionBox .close').on('click', function() {
		$(this).closest('.actionBox').hide();
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
				$('.block-address').html(response.html).trigger('create');
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
						$('.block-address form#frm-user-address').after('<p class="error">'+response.msg+'</p>');
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