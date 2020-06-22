@extends('v1.user.layouts.master')

@section('content')
	@include('includes.confirm-modal')
	@include('v1.user.elements.store-delivery-service')
	<div id="cart-wrapper">
		<div class="cart-list">
			<h4>{{ __('messages.Order Details') }}</h4>
			<a href="javascript:void(0)" id="delete-cart" data-content="{{ __('messages.Delete Cart Order') }}"><i class="fa fa-trash"></i></a>
		</div>
		<div class="container-fluid">
			<div class="cart-table">
				<div class="table-responsive">
					<table class="table" id="table-custom-2">
						@if(!Session::has('iFrameMenu'))
							<input type="hidden" name="redirectUrl" id="redirectUrl" value="{{ url('restro-menu-list/').'/'.$order->store_id }}"/>
						@else
							<input type="hidden" name="redirectUrl" id="redirectUrl" value="{{ url('iframe/restro-menu-list/').'/'.$order->store_id }}"/>
						@endif
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
							@if( in_array(3, $store_delivery_type) && $isHomeDeliveryPackageSubscribed )
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
          
			{{-- Delivery Type --}}
			@include('v1.user.elements.cart-order-delivery-type')
          
			{{-- If store support home delivery --}}
			@if( in_array(3, $store_delivery_type) && $isHomeDeliveryPackageSubscribed )
				<div class="row block-address hidden"></div>
			@endif
			
			<!-- check whether if delivery time is less than 24 hours -->
			@if($order->order_type == 'eat_later' && ($order->delivery_timestamp > strtotime('+1 day')))
            	{{-- Send Order For Confirmation --}}
                <div class="col-md-12 text-center"> 
                    <br><button class="btn btn-primary send-order-confirmation">{{ __('messages.sendorderforconfirmation') }}</button><br>
                </div>
            @else
                {{-- Proceed to pay --}}
			    @include('v1.user.elements.cart-proceed-to-pay')
			@endif

			{{-- Modals --}}
			@include('v1.user.elements.cart-modals')
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
    
    $(function(){
        // if home delivery clicked then show delivery at door otherwise not
        $('.delivery_type_3').on('click', function(){
            rel = $(this).attr('rel');
            if(rel == "3"){
                $('.delivery_at_door').css("display","block")
            }else{
                $('.delivery_at_door').css("display","none")
            }
        });
        
        // if delivery door clicked then change value
        $('#delivery_at_door').on('click', function(){
            if (this.checked) {
                $('#delivery_at_door').val('1');
            }else{
                $('#delivery_at_door').val('0');
            }
            orderUpdateDeliveryType();
        });
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

	$(document).on('shown.bs.collapse', '.add-address-form', function(){
		$('html, body').animate({
	        scrollTop: $(".add-address-form").offset().top
	    }, 'slow');
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
			$('.send-order-confirmation').prop('disabled', false);
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
				'delivery_type': $('input[name=delivery_type]:checked').val(),
				'delivery_at_door': $('#delivery_at_door').val(),
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
		$('.block-address').find('div.error').remove();
		$('.btn-pay').prop('disabled', true);
		$('.send-order-confirmation').prop('disabled', true);
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
						$('.block-address form#frm-user-address').after('<div class="col-md-12 alert alert-danger text-center error clear">'+response.msg+'</div>');
					}
					else
					{
						// 
						if(response.distanceBasedDeliveryPrice)
						{
							callUpdateCartManually();
						}

						$('.btn-pay').prop('disabled', false);
						$('.send-order-confirmation').prop('disabled', false);
						$('.send-order').prop('disabled', false);
					}
				}
			});
		}
	}

	// call 'updateCart' on action
	function callUpdateCartManually()
	{
		id = 1;
		var qty = parseInt($('#qty'+id).val(), 10);
		var prod = $('#prod'+id).val();

		updateCart(qty, prod, 0, 0, false);
	}

	// 
	function saveUserAddress()
	{
		$('#confirm-address-alert').modal('hide');
		var formData = $('#save-address').serialize();

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
				if(result.status == 0)
				{
					$('.add-address-form').collapse('hide');
					$('#address-verification-alert .verification-code-sent-at').html(result.msg);
					$('#address-verification-alert #address_id').val(result.addressId);
					$('#address-verification-alert').modal('show');
				}
				else if(result.status == 1)
				{
					getHomeDeliveryPartContent($('#orderid').val());
				}
				else if(result.status == 2)
				{
					$('.add-address-form').collapse('hide');
					$('#warning-address-alert').modal('show');
				}
			},
			error: function() {
				alert('Something went wrong, please try again!');
			}
		});

		return false;
	}

	// 
	function editUserAddressModal(id)
	{
		$.ajax({
			url: "{{ url('edit-user-address') }}/"+id,
			success: function(response) {
				if(response.address != null)
				{
					$('#update-address').find('input[name=address_id]').val(response.address.id);
					$('#update-address').find('input[name=full_name]').val(response.address.full_name);
					$('#update-address').find('select[name=phone_prefix]').val(response.address.phone_prefix);
					$('#update-address').find('input[name=mobile]').val(response.address.mobile);
					$('#update-address').find('input[name=entry_code]').val(response.address.entry_code);
					$('#update-address').find('input[name=apt_no]').val(response.address.apt_no);
					$('#update-address').find('input[name=company_name]').val(response.address.company_name);
					$('#update-address').find('input[name=other_info]').val(response.address.other_info);
					$('#update-address').find('input[name=street]').val(response.address.street);
					$('#update-address').find('input[name=zipcode]').val(response.address.zipcode);
					$('#update-address').find('input[name=city]').val(response.address.city);
					$('#update-address').find('input[name=country]').val(response.address.country);
					
					$('#update-user-address').modal('show');
				}

				hideLoading('Processing...');
			},
			error: function() {
				alert('Something went wrong, please try again!');
				hideLoading('Processing...');
			}
		});

		return false;
	}

	// Update existing address of user
	function updateUserAddress()
	{
		var formData = $('#update-address').serialize();

		// Send data to server through the Ajax call
		$.ajax({
			type: 'POST',
			url: "{{ url('update-user-address') }}",
			data: formData,
			async: 'true',
			dataType: 'json',
			beforeSend: function() {
				showLoading();
			},
			complete: function() {
				hideLoading('Processing...');
				$('#update-user-address').modal('hide');
			},
			success: function(result) {
				if(result.status == 0)
				{
					$('#address-verification-alert .verification-code-sent-at').html(result.msg);
					$('#address-verification-alert #address_id').val(result.addressId);
					$('#address-verification-alert').modal('show');
				}
				else if(result.status == 1)
				{
					getHomeDeliveryPartContent($('#orderid').val());
				}
			},
			error: function() {
				alert('Something went wrong, please try again!');
			}
		});
	}

	// 
	function resendVerificationCode()
	{
		var formData = $('#frm-address-verification').serialize();

		// Send data to server through the Ajax call
		$.ajax({
			type: 'POST',
			url: "{{ url('resend-address-verification-code') }}",
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
					$('#address-verification-alert .verification-code-sent-at').html(result.msg);
				}
			},
			error: function() {
				alert('Something went wrong, please try again!');
			}
		});

		return false;
	}

	// 
	function addressVerification()
	{
		var formData = $('#frm-address-verification').serialize();

		// Send data to server through the Ajax call
		$.ajax({
			type: 'POST',
			url: "{{ url('address-verify') }}",
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
					$('#address-verification-alert').modal('hide');
					getHomeDeliveryPartContent($('#orderid').val());
				}
				else
				{
					$('#address-verification-alert #verification-error').html(result.msg);
					$('#address-verification-alert #verification-error').show();
				}
			},
			error: function() {
				alert('Something went wrong, please try again!');
			}
		});

		return false;
	}

	// 
	function deleteUserAddress(id)
	{
		if( confirm('Are you sure you want to delete this address?') )
		{
			showLoading();

			$.ajax({
				url: "{{ url('delete-user-address') }}/"+id,
				success: function(response) {
					if(response.status)
					{
						getHomeDeliveryPartContent($('#orderid').val());
					}
					else
					{
						alert('Address not found!');
					}

					hideLoading('Processing...');
				},
				error: function() {
					alert('Something went wrong, please try again!');

					hideLoading('Processing...');
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
			$('#confirm-address-alert').modal('show');
		}

		return false;
	});

	// Update address
	$(document).on('submit', '#update-address', function(e) {
		e.preventDefault();

		// Form validate
		if($('#update-address').valid())
		{
			updateUserAddress();
		}

		return false;
	});

	// Check address verification code
	$(document).on('submit', '#frm-address-verification', function(e) {
		e.preventDefault();

		// Form validate
		if($('#frm-address-verification').valid())
		{
			addressVerification();
		}

		return false;
	});
	
	function orderConfirmationStatus(order_id)
	{
		$.ajax({
			url : "{{url('order-confirmation-status').'/'.$order->order_id}}",
			type : 'get',
			data : {
				'eatLater' : '0'
			},
			success: function(data, status){
				window.location.href = "{{url('order-view').'/'.$order->order_id}}";
			}
		});
	}

	// 
	$('.send-order').on('click', function() {
		if($('input[name=delivery_type]:checked').val() == '3'){
			if($('#frm-user-address').length && $('input[name=user_address_id]:checked').length){
				orderConfirmationStatus({{$order->order_id}});
			}
		}else{
			orderConfirmationStatus({{$order->order_id}});
		}
	});

	// 
	$('.send-order-confirmation').on('click', function() {
		if($('input[name=delivery_type]:checked').val() == '3'){
			if($('#frm-user-address').length && $('input[name=user_address_id]:checked').length){
				orderConfirmationStatus({{$order->order_id}});
			}
		}else{
			orderConfirmationStatus({{$order->order_id}});
		}
	});

	checkDefaultDeliveryType();

    @if( $isPaymentPackageSubscribed )
		// Initialize Stripe and card element
		var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}');
		var stripe2;

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
						'order_id': "{{ $order->order_id }}",
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
			if (response.errorHeartbeat) {
				// Show store heartbeat alert
				$('#store-not-live-alert').modal('show');
			} else if (response.error) {
				// Show error from server on payment form
				let message = response.error;
				if( typeof(response.error) == 'object' ) {
					message = response.error.message;
				}
				$('.row-new-card').find('div.card-errors').html(message);
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
						$('.row-new-card').find('div.card-errors').html(message);
					} else {
						let isSaveCard = $('#isSaveCard').is(':checked') ? 1 : 0;
						
						let data = {
							'_token': "{{ csrf_token() }}",
							'order_id': "{{ $order->order_id }}",
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
					'order_id': "{{ $order->order_id }}",
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
			if (response.errorHeartbeat) {
				// Show store heartbeat alert
				$('#store-not-live-alert').modal('show');
			} else if (response.error) {
				// Show error from server on payment form
				let message = response.error;
				if( typeof(response.error) == 'object' ) {
					message = response.error.message;
				}
				$('.row-saved-cards').find('div.card-errors').html(message);
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
						$('.row-saved-cards').find('div.card-errors').html(message);
					} else {
						let data = {
							'_token': "{{ csrf_token() }}",
							'order_id': "{{ $order->order_id }}",
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
						}).then(handleServerResponseSavedCard);
					}
				});
			} else {
				// Show success message
				$('.row-saved-cards').find('div.card-errors').html('');
				window.location.href = "{{ url('order-view/'.$order->order_id) }}";
			}
		}

		// Delete source
    	function deleteSource(sourceId = null, This)
    	{
    		if( confirm('Are you sure you want to delete this card?') )
    		{
    			let $this = $(This);
            	showLoading();

            	// 
            	$.ajax({
					type: 'POST',
					url: "{{ url('delete-source') }}",
					data: {
						'_token': "{{ csrf_token() }}",
						'deleteSource': 1,
	                    'sourceId': sourceId
					},
					dataType: 'json',
					success: function(response) {
						if(!response.error)
	                    {
	                        $this.closest('.radio').remove();
	                    }
	                    else
	                    {
	                    	alert(response.error);
	                    }

	                    hideLoading('Processing...');
					}
				});
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

			$('html, body').animate({
		        scrollTop: $(".section-pay-with-card").offset().top
		    }, 'slow');
		});

		// Proceed to pay (show payment method)
		$('.btn-pay').on('click', function(e) {
			// 
			if( $('#frm-user-address').length && !$('#frm-user-address').valid())
			{
				return false;
			}

			//
			$('.row-confirm-payment').removeClass('hidden');

			$('html, body').animate({
		        scrollTop: $(".row-new-card").offset().top
		    }, 'slow');
		});
	@endif
</script>
@endsection