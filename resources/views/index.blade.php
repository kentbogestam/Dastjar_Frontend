@extends('layouts.master')
@section('head-scripts')

	<script src="{{asset('js/restolist/fingerprint2.min.js')}}"></script>
    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    <script src="{{asset('browserShortcutJs/comlink.global.js')}}"></script>
    <script src="{{asset('browserShortcutJs/messagechanneladapter.global.js')}}"></script>
    <script src="{{asset('addToHomeIphoneJs/addtohomescreen.js')}}"></script>
    <script src="{{asset('kitchenJs/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('js/restolist/moment-timezone-with-data.min.js')}}"></script>
    <script src="{{asset('js/restolist/restrolist.js')}}"></script>
    <script src="{{asset('js/restolist/restroListCommon.js')}}"></script>
	<script src="{{asset('locationJs/currentLocation.js?2')}}"></script>
    
    <script type="text/javascript">
	  var noImageUrl = "{{url('images/placeholder-image.png')}}";
    </script>
    
@endsection


@section('content')

@include('includes.headertemplate')

	<div class="cat-btn">
		<div class="ui-grid-a top-btn">
			<div class="ui-block-a"><a href="#" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
			<div class="ui-block-b"><a href="{{url('selectOrder-date')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive" onclick="iconEatInactive()" data-ajax="false"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">{{ __('messages.Eat Later') }}</a></div>
		</div>
	</div>

	<div role="main" data-role="main-content" id="content">
		<div class="cat-list-sec">
			<input type="hidden" id="browserCurrentTime" name="browserCurrentTime" value="" />
			<ul data-role="listview" data-inset="true" id="companyDetailContianer">

		
			</ul>
		</div>
	</div>	

	@include('includes.fixedfooter')

	<div id="login-popup" style="display: none;" class="login-popup" data-theme="a">
	  <div class="inner-popup">
	        <div id="cancel-popup" class="cross" onclick=closeLocationPopup("{{url('select-location?k=home')}}")><span class="popup-close1">x</span></div>
	        <div class="pop-body">
	           <p>{{ __('messages.Please activate Location Services in your mobile') }}</p>
	        </div>
	  </div>
	</div>

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon-loading"></span><h1>loading</h1></div>

	<div id="overlay" onclick="off()"></div>

@endsection

@section('footer-script')
	
<?php
	$helper = new Helper();

	if(Auth::check()){
			
      if(Session::get('with_login_lat') != null){

		?>
        <script type="text/javascript">
         
         setLngLat("{{Session::get('with_login_lat')}}","{{Session::get('with_login_lng')}}");
           
        </script>
		<?php
	}else if(Session::get('with_out_login_lat') != null){

		?>
        <script type="text/javascript">
        
 		setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");

        </script>
		<?php
	}else{
		?>
        <script type="text/javascript">
        	
			setLngLat(null,null);

        </script>
		<?php
	}
	}
	else{
		if(Session::get('with_out_login_lat') != null){
	?>
	<script type="text/javascript">
			
	   setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");

	</script>
			<?php
		}
	}
?>

<script type="text/javascript">

	getTimeZone("{{url('set-timezone')}}");
	
	var curDate = new Date();
	curTimezoneOffset = curDate.getTimezoneOffset();

	$(document).on("scrollstop", function (e) {
		onScroll("{{url('restro-menu-list/')}}");
		
	});

	$(function(){

		getPos("{{url('lat-long')}}","{{url('restro-menu-list/')}}",noImageUrl);
		checkUserLogin("{{url('checkUserLogin')}}");
		
	});
</script>

@endsection

