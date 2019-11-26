@php
$requestPath = Request::path();
@endphp

@if(!Session::has('iFrameMenu'))
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
					<img src="{{ asset('v1/images/map-icon.png') }}" alt="">
				</a>
			</div>
		@elseif(Request::is('selectOrder-date'))
			<div class="logo-with-map">
				<a class="arrow" href="{{ url('/') }}"><i class="fa fa-angle-left"></i></a>
				<a class="logo" href="javascript:void(0);">
					<img src="{{ asset('v1/images/logo.png') }}" alt="">
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
				</a>
				<a class="map-icon map-btn user-link" href="{{ url('search-map-eatnow') }}">
					<img src="{{ asset('v1/images/map-icon.png') }}" alt="">
				</a>
			</div>
		@elseif(Request::is('search-map-eatnow'))
			<div class="logo-with-map">
				<a class="arrow" href="{{ url('/') }}"><i class="fa fa-angle-left"></i></a>
				<a class="logo" href="javascript:void(0);">
					<img src="{{ asset('v1/images/logo.png') }}" alt="">
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
				</a>
			</div>
		@elseif(Request::is('search-store-map'))
			<div class="logo-with-map">
				<a class="arrow" href="{{ url('restro-menu-list/'.$storedetails->store_id) }}"><i class="fa fa-angle-left"></i></a>
				<a class="logo" href="javascript:void(0);">
					<img src="{{ asset('v1/images/logo.png') }}" alt="">
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
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
					<img src="{{ asset('v1/images/map-icon.png') }}" alt="">
				</a>
			</div>
		@elseif(Request::is('cart') || Request::is('view-cart/*'))
			<div class="logo-with-map">
				<a class="logo" href="javascript:void(0);">
					<h1>{{ $storedetails->store_name }}</h1>
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
				</a>
			</div>
		@elseif(Request::is('order-view/*'))
			<div class="logo-with-map">
				<a class="logo" href="javascript:void(0);">
					<img src="{{ asset('v1/images/logo.png') }}" alt="">
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
				</a>
			</div>
		@elseif(Request::is('select-location'))
			<div class="logo-with-map">
				<ul>
					<li class="arrow">
						@if(isset($_GET['k']))
							<a href="{{ url('') }}"><i class="fa fa-angle-left"></i></a>
						@else
							<a href="{{ url('user-setting') }}"><i class="fa fa-angle-left"></i></a>
						@endif
					</li>
					<li class="location">{{ __('messages.Location') }}</li>
					<li class="location-cur">
						@if( ((strpos(\Request::server('HTTP_USER_AGENT'), 'Mobile/') !== false) && (strpos(\Request::server('HTTP_USER_AGENT'), 'Safari/') == false)) )
							<a href="javascript:void(0)" id="locationSave" onclick=requestGeoAddressToIosNative('locationSave')>
								<p><img src="{{ asset('v1/images/location.png') }}" alt=""></p>
								<p>{{ __('messages.Current Position') }}</p>
							</a>
						@else
							<a href="javascript:void(0)" id="locationSave" onclick=locationSave("{{url('saveCurrentlat-long/')}}")>
								<p><img src="{{ asset('v1/images/location.png') }}" alt=""></p>
								<p>{{ __('messages.Current Position') }}</p>
							</a>
						@endif
					</li>
					<li class="done">
						<a href="javascript:void(0)" id="dataSave" onclick="dataSave();">{{ __('messages.Done') }}</a>
					</li>
				</ul>
			</div>
		@elseif(Request::is('track-order/*'))
			<div class="logo-with-map">
				<a href="{{ url('order-view/'.$order->order_id) }}" class="arrow"><i class="fa fa-angle-left"></i></a>
				<a class="logo" href="javascript:void(0);">
					<img src="{{ asset('v1/images/logo.png') }}" alt="">
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
				</a>
			</div>
		@elseif(Request::is('ready-notification/*'))
			<div class="logo-with-map">
				<!-- Back button if redirected here automatically on order ready -->
				@if(Request::server('HTTP_REFERER'))
					<a href="{{ Request::server('HTTP_REFERER') }}" class="arrow"><i class="fa fa-angle-left"></i></a>
				@endif
				<a class="logo" href="javascript:void(0);">
					<h1>{{ $companydetails->store_name }}</h1>
					@auth
						<span>{{ Auth::user()->name}}</span>
					@endauth
				</a>
			</div>
		@elseif(Request::is('deliver-notification/*'))
			<div class="logo-with-map">
				<a class="logo" href="javascript:void(0);">
					<h1>{{ $companydetails->store_name }}</h1>
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
				<!-- <a class="map-icon map-btn user-link" href="{{ url('search-map-eatnow') }}">
					<img src="{{ asset('v1/images/map-icon.png') }}" alt="">
				</a> -->
			</div>
		@endif
	</div>
@else
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
@endif