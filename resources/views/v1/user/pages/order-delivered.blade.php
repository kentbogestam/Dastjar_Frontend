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
		<h4><strong>{{ __('messages.Thanks for your visit.') }}</strong></h4>
		<p><strong>{{ __('messages.Hope to seen you soon again') }}</strong></p>
		<br><br>
		<img src="{{asset('images/ready-chef.png')}}" style="width: 8em">
	</div>
@endsection