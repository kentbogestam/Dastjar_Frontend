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
		@if(Session::has('errorHeartbeat'))
			<div class="alert alert-warning">
				{{ __('messages.storeNotLive') }}
			</div>
		@else
			@if(Session::get('order_already_cancelled') == 1)
				<h2 class="text-info">{{ __('messages.orderCanceled', ['order_id' => $order_number]) }}</h2>
			@else
				<h2 class="text-success">{{ __('messages.orderCanceled', ['order_id' => $order_number]) }}</h2>
			@endif
		@endif
		<br>
		<button type="button" class="btn btn-primary"><a href="{{ url('') }}" style="color:white;">{{ __('messages.okay') }}</a></button>
	</div>
@endsection