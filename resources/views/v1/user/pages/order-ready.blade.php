@extends('v1.user.layouts.master')

@section('head-scripts')
	<style>
		.order-content {
			padding: 40px;
		}
	</style>
@endsection

@section('content')
	<div class="order-content text-center">
		<img src="{{asset('images/ready-chef.png')}}" style="width: 8em"><br><br>
		@if($orderDetail->delivery_type == 3)
			<p>
				<strong>
					{{ __('messages.Your order Number') }} {{$orderID}}<br><span>{{ __('messages.alertOrderReadyOnHomeDelivery') }}!</span>
				</strong>
			</p>
		@else
			<p>
				<strong>
					{{ __('messages.Your order Number') }} {{$orderID}} {{ __('messages.is') }} <span>{{ __('messages.Order Ready To Pick Up') }}!</span>
				</strong>
			</p>
		@endif
	</div>
@endsection