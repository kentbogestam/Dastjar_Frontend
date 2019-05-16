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
			<!-- <div class="block-promocode">
				<div class="ui-grid-solo">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a">
							<i class="fa fa-gift"></i> <strong>Apply Promo Code</strong>
						</div>
					</div>
				</div>
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a">
							<input type="text" name="promocode" id="promocode" placeholder="Enter promocode here" data-mini="true">
						</div>
					</div>
					<div class="ui-block-b">
						<div class="ui-bar ui-bar-a">
							<input type="button" data-role="none" value="Apply" class="btn-apply-promocode">
						</div>
					</div>
				</div>
			</div> -->

			@if($storedetails->delivery_type == 0)
				<div class="ui-grid-solo row-order-delivery-type">
					<div class="ui-block-a">
						<div class="ui-bar ui-bar-a text-center">
							<form>
								<fieldset data-role="controlgroup" data-type="horizontal">
									<input type="radio" name="delivery_type" id="delivery_typea" value="1" checked="checked">
									<label for="delivery_typea">{{ __('messages.deliveryOptionDineIn') }}</label>
									<input type="radio" name="delivery_type" id="delivery_typeb" value="2" checked="">
									<label for="delivery_typeb">{{ __('messages.deliveryOptionTakeAway') }}</label>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			@endif

			@if(Session::get('paymentmode') !=0 && $order->final_order_total > 0)
				<form action="{{ url('/payment') }}" class="payment_form_btn" method="POST">
					{{ csrf_field() }} 
					<script
					src="https://checkout.stripe.com/checkout.js" class="stripe-button"
					data-key="{{env('STRIPE_PUB_KEY')}}"
					data-amount=""
					data-name="Stripe"
					data-email="{{Auth::user()->email}}"
					data-description="Dastjar"
					data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
					data-token="true"
					data-locale="auto"
					data-label="{{__('messages.Pay with card')}}"
					data-zip-code="false">
				</script>
				</form>
			@else
				<div id="saveorder">
					<!--<a href="{{url('save-order').'/?orderid='.$order->order_id}}" data-ajax="false">{{ __('messages.Send Order') }}</a>-->
					<a href="{{url('order-view').'/'.$order->order_id}}" data-ajax="false">{{ __('messages.send order and pay in restaurant') }}</a>
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

<script type="text/javascript">
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

	// 
	$('input[name=delivery_type]').on('change', function() {
		orderUpdateDeliveryType();
	});

	// Update order delivery type
	function orderUpdateDeliveryType()
	{
		if($('input[name=delivery_type]').length)
		{
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
	}

	orderUpdateDeliveryType();

	// Apply promocode
	/*$('.btn-apply-promocode').on('click', function() {
		var code = $('#promocode').val();

		if(code.length)
		{
			$.ajax({
				type: 'POST',
				url: "{{ url('apply-promocode') }}",
				data: {
					'_token': "{{ csrf_token() }}",
					'code': code
				},
				dataType: 'json',
				success: function(response) {
					console.log(response);
				}
			});
		}
	});*/
</script>
@endsection