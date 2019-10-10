@extends('v1.user.layouts.master')

@section('content')
	<div class="container-fluid text-center">
		<br>
		@if($status == 0)
			<div class="alert alert-danger alert-dismissible">
				<a href="#" class="close" title="close">×</a>
				<span>{{ __('messages.invalidDiscount') }}</span>
			</div>
		@elseif($status == 1)
			<div class="alert alert-success alert-dismissible">
				<a href="#" class="close" title="close">×</a>
				<span>{{ __('messages.discountAddedSuccessfully') }}</span>
			</div>
		@elseif($status == 2)
			<div class="alert alert-info alert-dismissible">
				<a href="#" class="close" title="close">×</a>
				<span>{{ __('messages.discountAlreadyApplied') }}</span>
			</div>
		@endif
	</div>
@endsection