@extends('layouts.master')
@section('content')
@include('includes.headertemplate')
<div role="main" data-role="main-content" class="content">
	<div class="inner-page-container">
		<div class="table-content">
			<div class="head_line">
				<h2>{{ __('messages.Order Details') }}</h2>
				<div class="delt-cart"><a href="#" data-ajax="false" onclick="deleteFullCart('{{ url("emptyCart/") }}','1','{{ __("messages.Delete Cart Order") }}')"><img src="images/dlt_icon.png"></a></div>
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
									<span id="itemtotalDisplay{{$j}}">{{ $value->price*$value->product_quality }}</span>
									<input type="hidden" name="itemtotal[{{$j}}]" id="itemtotal{{$j}}" value="{{ $value->price*$value->product_quality }}" class="itemtotal"/> {{ $order->currencies }}
								</div>
							</div>
						</td>
					</tr>	
					<?php $j=$j+1 ;?>
				@endforeach
				<tr class="last-row" id="last-row">	
					<td class="cart-total">  TOTAL <span id="grandTotalDisplay"> {{$order->order_total}}</span> {{$order->currencies}}
						<input type="hidden" name="grandtotal" id="grandtotal" value="{{$order->order_total}}"/>
					</td>
				</tr>
			</table>
			@if(Session::get('paymentmode') !=0)
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
		</div>
	</div>
</div>
@include('includes.fixedfooter')
@endsection
@section('footer-script')
<script type="text/javascript">
	// Update value in basket
	var cntCartItems = "{{ $cntCartItems }}";
	$('.cart-badge').html(cntCartItems);
	$('.cart-badge').removeClass('hidden');
</script>
@endsection