@extends('layouts.master')

@section('head-scripts')
	
	<script src="{{asset('js/restolist/restroListCommon.js')}}"></script>
    <script src="{{asset('js/restolist/restroListEatLater.js')}}"></script>
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

@include('includes.headertemplate')

	<div class="cat-btn">
		<div class="ui-grid-a top-btn">
			<div class="ui-block-a"><a href="{{url('eat-now')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive active" onclick="iconEatInactive()" data-ajax="false"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
			<div class="ui-block-b"><a href="{{url('selectOrder-date')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" data-ajax="false"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">{{ __('messages.Eat Later') }}</a></div>
		</div>
	</div>
	<div role="main" data-role="main-content" id="content">
		<div class="cat-list-sec">
			<ul data-role="listview" data-inset="true" id="companyDetailContianer">

				
			</ul>
		</div>


	</div>	
	@include('includes.fixedfooter')

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon-loading"></span><h1>loading</h1></div>

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')

<script type="text/javascript">

	$(document).on("scrollstop", function (e) {
		onscroll("{{url('restro-menu-list/')}}",noImageUrl,"{{Session::get('order_date')}}")
});

</script>

@endsection
