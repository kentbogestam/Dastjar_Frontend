@extends('layouts.master')
@section('content')
@include('includes.headertemplate')

	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			
			<div class="table-content">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
					@foreach($orderDetails as $orderDetail)
						<tr>
							<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
						</tr>	
					@endforeach
				<tr class="last-row">	
					<td> </td>
					<td> </td>
					<td>  TOTAL:-    {{$order->currencies}} {{$order->order_total}}</td>
				</tr>
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
                data-email="{{Auth::user()->email}}"
                data-description="Dastjar"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-token="true"
                data-locale="auto"
                data-label="{{__('messages.Pay with card')}}"
                data-zip-code="false">
        </script>
    </form>

	
@include('includes.fixedfooter')

@endsection

@section('footer-script')

@endsection