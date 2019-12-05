@extends('v1.user.layouts.master')

@section('head-scripts')
	<style>
		.order-content {
			padding: 40px 0;
		}
	</style>
@endsection

@section('content')
	<div class="order-content text-center">
		@if(Session::get('order_already_cancelled') == 1)
			<h2 class="text-info">Order number {{$order_number}} has already been cancelled.</h2>
		@else
			<h2 class="text-success">Request to cancel order number {{$order_number}} has been placed.</h2>
		@endif
		<br>
		<a href="{{url('')}}" style="color:#1275ff" data-ajax="false">Go to Home</a>
	</div>
@endsection