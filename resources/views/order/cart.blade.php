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

			@if($storedetails->deliveryType->count() > 1)
				<div class="ui-grid-solo row-order-delivery-type">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a text-center">
							<form>
								<fieldset data-role="controlgroup" data-type="horizontal">
									@foreach($storedetails->deliveryType as $row)
										@if($row->delivery_type == 1)
											<input type="radio" name="delivery_type" id="delivery_typea" value="1">
											<label for="delivery_typea">{{ __('messages.deliveryOptionDineIn') }}</label>
										@endif

										@if($row->delivery_type == 2)
											<input type="radio" name="delivery_type" id="delivery_typeb" value="2">
											<label for="delivery_typeb">{{ __('messages.deliveryOptionTakeAway') }}</label>
										@endif

										@if($row->delivery_type == 3)
											<input type="radio" name="delivery_type" id="delivery_typec" value="3">
											<label for="delivery_typec">{{ __('messages.deliveryOptionHomeDelivery') }}</label>
										@endif
									@endforeach
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			@endif

			<div class="block-address hidden">
				<form method="post" id="frm-user-address" data-ajax="false">
					@if($user->addresses->count() > 1)
						<div class="ui-grid-a">
							@foreach($user->addresses as $address)
								<div class="{{ ($loop->iteration % 2 != 0) ? "ui-block-a" : "ui-block-b" }}">
									<div class="ui-bar ui-bar-a">
										<label for="{{ $address->id }}">{{ Helper::convertAddressToStr($address) }}</label>
										<input type="radio" name="user_address_id" id="{{ $address->id }}" value="{{ $address->id }}" checked="">
									</div>
								</div>
							@endforeach
						</div>
					@endif
				</form>
				<div class="ui-grid-solo">
					<div class="ui-block-a">
						<div id="add-new-address" data-role="collapsible">
							<h4>{{ __('messages.addAddress') }}</h4>
							<div class="add-address-form">
								<div class="ui-bar ui-bar-a">
									<form method="post" id="save-address" data-ajax="false">
										<input type="text" name="full_name" id="full_name" placeholder="{{ __('messages.fullName') }}*" data-mini="true" data-rule-required="true">
										<input type="number" name="mobile" id="mobile" placeholder="{{ __('messages.mobileNumber') }}*" data-mini="true" data-rule-required="true">
										<input type="number" name="zipcode" id="zipcode" placeholder="Zipcode" data-mini="true">
										<input type="text" name="address" id="address" placeholder="{{ __('messages.address1') }}*" data-mini="true" data-rule-required="true">
										<input type="text" name="street" id="street" placeholder="{{ __('messages.address2') }}*" data-mini="true" data-rule-required="true">
										<input type="text" name="city" id="city" placeholder="{{ __('messages.city') }}*" data-mini="true" data-rule-required="true">
										<fieldset data-role="controlgroup">
											<label for="is_permanent">{{ __('messages.saveAddress') }}</label>
											<input type="checkbox" name="is_permanent" value="1" checked="" id="is_permanent">
										</fieldset>
										<input type="submit" data-inline="true" value="{{ __('messages.save') }}" data-theme="b">
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			@if(Session::get('paymentmode') !=0 && $order->final_order_total > 0)
				<form action="{{ url('/payment') }}" class="payment_form_btn" id="orderPaymentForm" method="POST" data-ajax="false">
					{{ csrf_field() }} 
					<input type="hidden" id="stripeToken" name="stripeToken">
					<button type="button" class="ui-btn ui-mini btn-pay" disabled="">{{__('messages.Pay with card')}}</button>
				</form>
			@else
				<div id="saveorder">
					<a href="{{url('order-view').'/'.$order->order_id}}" class="send-order" data-ajax="false">{{ __('messages.send order and pay in restaurant') }}</a>
				</div>
			@endif

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

<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript" src="{{ asset('plugins/validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
	// 
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': "{{ csrf_token() }}"
		}
	});

	// 
	var handler = StripeCheckout.configure({
		key: '{{env('STRIPE_PUB_KEY')}}',
		image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		locale: 'auto',
		name: 'Stripe',
		email: '{{Auth::user()->email}}',
		description: "Dastjar",
		token: function(token) {
			$('#stripeToken').val(token.id);
			$('#orderPaymentForm').submit();
		}
	});

	// 
	$('.btn-pay').on('click', function(e) {
		if($('#frm-user-address').valid() != false)
		{
			var totalAmount = parseFloat('{{ Session::get('paymentAmount') }}');

			handler.open({
                currency: 'sek',
                amount: (totalAmount*100)
            });
		}

		e.preventDefault();
	});

	// Close Checkout on page navigation:
	window.addEventListener('popstate', function() {
		handler.close();
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
	$('input[name=delivery_type]').on('change', function() {
		orderUpdateDeliveryType();
	});

	// Update user address
	$('input[name=user_address_id]').on('change', function() {
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

			orderUpdateDeliveryType();
		}
	}

	// Update order delivery type
	function orderUpdateDeliveryType()
	{
		// 
		if($('input[name=delivery_type]:checked').val() == '3')
		{
			$('.block-address').removeClass('hidden');
			updateOrderUserAddress();

			if($('input[name=user_address_id]:checked').length)
			{
				$('.btn-pay').prop('disabled', false);
			}
			else
			{
				$('.btn-pay').prop('disabled', true);
			}
		}
		else
		{
			$('.block-address').addClass('hidden');
			$('.btn-pay').prop('disabled', false);
		}
		
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
				console.log(response);
			}
		});
	}

	// Update order 'user_address_id'
	function updateOrderUserAddress()
	{
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
					console.log(response);
				}
			});
		}
		else
		{
			console.log('Something went wrong!');
		}
	}

	// Save address
	$('#save-address').on('submit', function(e) {
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
						$('form#frm-user-address').html(result.addresses);
						$('#add-new-address').collapsible('collapse');
						$('#save-address').trigger('reset');
						$('.btn-pay').prop('disabled', false);
						updateOrderUserAddress();
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
		if(!$('#frm-user-address').valid())
		{
			return false;
		}
	});

	checkDefaultDeliveryType();
</script>
@endsection