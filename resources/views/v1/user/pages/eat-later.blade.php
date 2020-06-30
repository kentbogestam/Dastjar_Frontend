@extends('v1.user.layouts.master')

@section('head-scripts')
	<script src="{{asset('js/restolist/restroListCommon.js')}}"></script>
    <script src="{{asset('js/restolist/restroListEatLater.js?v=11')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    <script src="{{asset('browserShortcutJs/comlink.global.js')}}"></script>
    <script src="{{asset('browserShortcutJs/messagechanneladapter.global.js')}}"></script>
    <script src="{{asset('addToHomeIphoneJs/addtohomescreen.js')}}"></script>
	<script src="{{asset('locationJs/currentLocation.js')}}"></script>

	 <script type="text/javascript">
	  var noImageUrl = "{{url('images/placeholder-image.png')}}";
	  registerSwjs();
		  $(function(){
		 
	        add("{{url('eat-later-data')}}","{{url('restro-menu-list/')}}",noImageUrl,"{{Session::get('order_date')}}");

	      });
	      window.onload = function () { browserPhoneSetting(); }// function is required the ios add to shortcut popup will work after full page is loaded.
    </script>
@endsection

@section('content')
	<div class="button-section">
		<a href="{{ url('eat-now') }}" class="inactive">
			<img src="{{ asset('images/icons/icon-eat-now-active.png') }}" alt="" class="active">
			<img src="{{ asset('images/icons/icon-eat-now-inactive.png') }}" alt="" class="inactive"> {{ __('messages.Eat Now') }}
		</a>
		<a href="{{ url('selectOrder-date') }}" class="active">
			<img src="{{ asset('images/icons/icon-eat-later-active.png') }}" alt="" class="active">
			<img src="{{ asset('images/icons/icon-eat-later-inactive.png') }}" alt="" class="inactive"> {{ __('messages.Eat Later') }}
		</a>
	</div>

	<div class="container">
		<div class="hotel-list" id="companyDetailContianer"></div>
	</div>

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default">
		<span class="ui-icon-loading"></span><h1>loading</h1>
	</div>

	<div id="overlay" onclick="off()" style="display: none;"></div>
@endsection

@section('footer-script')
<script type="text/javascript">
	/*$(window).load(function() {
		$(document).on("scrollstop", function (e) {
			onscroll("{{url('restro-menu-list/')}}",noImageUrl,"{{Session::get('order_date')}}")
		});
	})*/
</script>
@endsection