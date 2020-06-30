@extends('v1.user.layouts.master')

@section('head-scripts')
<script src="{{asset('js/restolist/resturantSelection.js?v=11')}}"></script>

<script src="{{asset('js/restolist/fingerprint2.min.js')}}"></script>
<script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
<script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
<script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
<script src="{{asset('browserShortcutJs/comlink.global.js')}}"></script>
<script src="{{asset('browserShortcutJs/messagechanneladapter.global.js')}}"></script>
<script src="{{asset('addToHomeIphoneJs/addtohomescreen.js')}}"></script>
<script src="{{asset('kitchenJs/moment-with-locales.min.js')}}"></script>
<script src="{{asset('js/restolist/moment-timezone-with-data.min.js')}}"></script>
<script src="{{asset('js/restolist/restrolist.js?v=1')}}"></script>
<script src="{{asset('js/restolist/restroListCommon.js')}}"></script>
<script src="{{ asset('locationJs/currentLocation.js') }}"></script>

<script type="text/javascript">
	var noImageUrl = "{{ url('images/placeholder-image.png') }}";
	var constUrlLatLng = "{{ url('lat-long') }}";
	var constUrlRestaurantMenu = "{{ url('restro-menu-list/') }}";

	// Call geolocation API and set updated position
	if(!getCookie("latitude") && !getCookie("longitude"))
	{
		setCurrentLatLong("{{ url('update-location') }}");

		// Set default type store 'eatnow/eatlater'
		setResttype("{{ url('setResttype') }}", 'eatnow');
	}
</script>
@endsection

@section('content')
	<div class="button-section">
		<a href="javascript:void(0)" class="active">
			<img src="{{ asset('images/icons/icon-eat-now-active.png') }}" alt="" class="active">
			<img src="{{ asset('images/icons/icon-eat-now-inactive.png') }}" alt="" class="inactive"> {{ __('messages.Eat Now') }}
		</a>
		<a href="{{ url('selectOrder-date') }}" class="inactive">
			<img src="{{ asset('images/icons/icon-eat-later-active.png') }}" alt="" class="active">
			<img src="{{ asset('images/icons/icon-eat-later-inactive.png') }}" alt="" class="inactive"> {{ __('messages.Eat Later') }}
		</a>
	</div>
	<div class="container">
		<div class="hotel-list" id="companyDetailContianer"></div>
	</div>
	<input type="hidden" id="browserCurrentTime" name="browserCurrentTime" value="" />
	

	<!-- Popup if location not allowed -->
	<div id="login-popup" class="modal fade login-popup" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class='modal-dialog'>
			<div class="modal-content">
				<div class="modal-body text-center">
					<p>{{ __('messages.Please activate Location Services in your mobile') }}</p><br>
	           		<a href="javascript:void(0)" class="btn btn-primary" onclick=closeLocationPopup("{{url('select-location?k=home')}}")>{{ __('messages.OK') }}</a>
				</div>
			</div>
		</div>
	</div>

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default">
		<span class="ui-icon-loading"></span><h1>loading</h1>
	</div>

	<div id="overlay" onclick="off()" style="display: none;"></div>
@endsection

@section('footer-script')
<script type="text/javascript">
	// Update global variable call 'lat/lng' value
	@if(Auth::check())
		@if(Session::get('with_login_lat') != null)
			setLngLat("{{Session::get('with_login_lat')}}","{{Session::get('with_login_lng')}}");
		@elseif(Session::get('with_out_login_lat') != null)
			setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");
		@else
			setLngLat(null,null);
		@endif
	@else
		@if(Session::get('with_out_login_lat') != null)
			setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");
		@endif
	@endif

	//
	getTimeZone("{{url('set-timezone')}}");
	
	var curDate = new Date();
	curTimezoneOffset = curDate.getTimezoneOffset();

	$(document).on("scrollstop", function (e) {
		onScroll("{{url('restro-menu-list/')}}");
	});

	$(function(){
		if( (getCookie("latitude") && getCookie("longitude")) || (loc_lat && loc_lng) )
		{
			getPos("{{url('lat-long')}}","{{url('restro-menu-list/')}}",noImageUrl);
		}
		
		checkUserLogin("{{url('checkUserLogin')}}");
	});

	window.onload = function () { browserPhoneSetting(); } // function is required the ios add to shortcut popup will work after full page is loaded.
</script>
@endsection