@extends('layouts.master')
@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			<div class="inner-logo">
				<img src="{{asset('images/logo.png')}}">
				<span>{{ Auth::user()->name}}</span>
			</div>
		</div>
		<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			<!-- <div class="wait-bg-img">
				<div class="text-content">
					<p>{{ __('messages.Thanks for your order') }} </p>
					<p>{{ __('messages.Order Number') }} </p>
					<p class="large-text">{{$order->customer_order_id}}</p>
					<p>({{$order->store_name}})</p>
					<p>{{ __('messages.Your order will be ready on') }}
						@if($order->order_type == 'eat_later')
						{{$order->deliver_date}}
						{{date_format(date_create($order->deliver_time), 'G:i')}} 
						@else
						{{date_format(date_create($order->order_delivery_time), 'i')}} mins
						@endif
					</p>
				</div>
			</div> -->
			<div class="table-content">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
					@foreach($orderDetails as $orderDetail)
						<tr>
							<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
						</tr>	
					@endforeach
				<tr class="last-row">	<td> </td><td>         </td><td>  TOTAL:-    {{$order->currencies}} {{$order->order_total}}</td></tr>
				</tr>
				</table>
			</div>
		</div>
	</div>



	<form action="{{ url('/payment') }}" class="payment_form_btn" method="POST">
        {{ csrf_field() }} 
        <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="{{env('STRIPE_PUB_KEY')}}"
                data-amount=""
                data-name="Stripe"
                data-description="Dastjar"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-zip-code="false">
        </script>
    </form>

	<div data-role="footer" class="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@include('orderQuantity')
		<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>

@endsection

@section('footer-script')

<script type="text/javascript">
	 $(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });
</script>

@endsection