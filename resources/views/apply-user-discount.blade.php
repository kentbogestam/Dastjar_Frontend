@extends('layouts.master')

@section('content')
	@include('includes.headertemplate')
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			<div class="ui-grid-solo text-center">
				<div class="ui-block-a">
					<div class="ui-bar ui-bar-a">
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
				</div>
			</div>
		</div>
	</div>
	@include('includes.fixedfooter')
@endsection