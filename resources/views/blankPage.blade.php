@extends('layouts.master')

@section('head-scripts')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>


    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
	<script>
	  $(document).ready(function () {
	      App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
	      App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

	      App42.initialize("{{env('APP42_API_KEY')}}","{{env('APP42_API_SECRET')}}");
	      App42.enableEventService(true);
	      var userName;
	      new Fingerprint2().get(function(result, components){
	          userName = "{{ Auth::user()->email}}";
	          console.log("Username : " + userName); //a hash, representing your device fingerprint
	          App42.setLoggedInUser(userName);
	          getDeviceToken();
	      });
	  });
	</script>
@endsection

@section('content')
<div data-role="header" class="header" id="nav-header"  data-position="fixed">
	<div class="nav_fixed">
		<div class="logo">
			<div class="inner-logo">
				<img src="{{asset('images/logo.png')}}">
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
</div>

	<div style="margin-top: 50px;">
		@if(isset($message))
			<p style="text-align: center">{{ $message }}</p>
		@endif
	</div>

	<div data-role="footer" id="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="{{ Session::get('route_url') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@include('orderQuantity')
		

		<div class="ui-block-d">
			<a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_07.png')}}">
				</div>
			</a>
		</div>
		</div>
	</div>

	<div id="login-popup" style="display: none;" class="login-popup" data-theme="a">
	  <div class="inner-popup">
	        <div class="pop-body">
	           <p class="text-center">{{ __('messages.Please activate Location Services in your mobile') }}</p>
	           <p class="text-center"><a href="{{ url('select-location?k=home') }}" class="ui-btn ui-corner-all ui-btn-inline">{{ __('messages.OK') }}</a></p>
	        </div>
	  </div>
	</div>
@endsection


@section('footer-script')

<script>
	$(document).ready(function () {
	  //  window.location.href = "{{url('/')}}";
	});
</script>

@endsection
