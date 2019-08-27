@php
$requestPath = Request::path();
@endphp

<div class="head-section position-fixed">
	@if($requestPath == '/' || $requestPath == 'home' || $requestPath == 'eat-now' || $requestPath == 'eat-later' || $requestPath == 'save-order')
		<div class="logo-with-map">
			<a class="logo" href="javascript:void(0);">
				<img src="{{ asset('v1/images/logo.png') }}" alt="">
				@auth
					<span>{{ Auth::user()->name}}</span>
				@endauth
			</a>
			<a class="map-icon map-btn user-link" href="{{ url('search-map-eatnow') }}">
				<img src="{{ asset('v1/images/map-icon') }}.png" alt="">
			</a>
		</div>
	@elseif(Request::is('restro-menu-list/*'))
		<div class="logo-with-map">
			<a class="logo" href="javascript:void(0);">
				<h1>{{ $storedetails->store_name }}</h1>
				@auth
					<span>{{ Auth::user()->name}}</span>
				@endauth
			</a>
			<a class="map-icon map-btn user-link" href="{{ url('search-store-map') }}">
				<img src="{{ asset('v1/images/map-icon') }}.png" alt="">
			</a>
		</div>
	@elseif(Request::is('cart'))
		<div class="logo-with-map">
			<a class="logo" href="javascript:void(0);">
				<h1>{{ $storedetails->store_name }}</h1>
				@auth
					<span>{{ Auth::user()->name}}</span>
				@endauth
			</a>
			<a class="map-icon map-btn user-link" href="{{ url('search-map-eatnow') }}">
				<img src="{{ asset('v1/images/map-icon') }}.png" alt="">
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
			<a class="map-icon map-btn user-link" href="{{ url('search-map-eatnow') }}">
				<img src="{{ asset('v1/images/map-icon') }}.png" alt="">
			</a>
		</div>
	@endif
</div>