@php
$requestPath = Request::path();
@endphp

<div class="head-section position-fixed">
	@if(Request::is('iframe/restro-menu-list/*'))
		<div class="logo-with-map">
			<a class="logo" href="javascript:void(0);">
				<h1>{{ $storedetails->store_name }}</h1>
				@auth
					<span>{{ Auth::user()->name}}</span>
				@endauth
			</a>
		</div>
	@else
		<div class="logo-with-map">
			<a class="logo" href="javascript:void(0);">
				<img src="{{ asset('v1/images/logo.png') }}" alt="">
				@auth
					<span>{{ Auth::user()->name}}</span>
				@endauth
			</a>
		</div>
	@endif
</div>