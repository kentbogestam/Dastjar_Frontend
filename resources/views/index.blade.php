@extends('layouts.master')
@section('head-scripts')
	<style type="text/css">
		#overlay {
    		position: fixed;
    		display: none;
    		width: 100vw;
    		height: 100vh;
		    top: 0;
		    left: 0;
		    right: 0;
    		bottom: 0;
	    	background-color: rgba(0,0,0,0.5);
	    	z-index: 999;
		}

		#loading-img{
			display: none;
			position: absolute;
			top: 50vh;
			left: 50vw;
			z-index: 99999;
		}

		.popup-close1 {
			width: 30px;
			height: 26px;
			padding-top: 4px;
			display: inline-block;
			position: absolute;
			top: 5px;
			right: 5px;
			-webkit-transition: ease 0.25s all;
			transition: ease 0.25s all;
			-webkit-transform: translate(50%, -50%);
			transform: translate(50%, -50%);
			border-radius: 100% !important;
			background: #7ebe12;
			font-family: Arial, Sans-Serif;
			font-size: 20px;
			text-align: center;
			line-height: 0.8;
			color: #fff;
			cursor: pointer;
			padding-left: 0px;
			z-index: 999;
		}

		.popup-close1:hover {
			text-decoration: none;
		}
	</style>

    <script src="//cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>

    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    <script src="{{asset('browserShortcutJs/comlink.global.js')}}"></script>
    <script src="{{asset('browserShortcutJs/messagechanneladapter.global.js')}}"></script>
    <script src="{{asset('addToHomeIphoneJs/addtohomescreen.js')}}"></script>
    
    <script type="text/javascript">
	  navigator.sayswho= (function(){
	    var ua= navigator.userAgent, tem, 
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(/trident/i.test(M[1])){
	        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	        return 'IE '+(tem[1] || '');
	    }
	    if(M[1]=== 'Chrome'){
	        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
	        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
	    }
	    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);

	    //console.log("browserVersion=" + M.join(' '));
	    var browserVersion = M.join(' ');
	    var getBrowser = browserVersion.split(" ");
	    var browser = getBrowser[0];
	    document.cookie="iphonePopupcount=" + 1;
	    document.cookie="browser=" + browser;
	    document.cookie="browserVersion=" + M.join(' ');
	    var string = M.join(' ');
	    string = string.split(" ");
	    if(string[0] == 'Safari'){
	     $('#facebook-hide').hide();
	     $('#google-hide').hide();
	    }
	})();
	</script>
	
	<script>
	  $(document).ready(function () {
	        registerSwjs();
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
	<div class="cat-btn">
		<div class="ui-grid-a top-btn">
			<div class="ui-block-a"><a href="#" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
			<div class="ui-block-b"><a href="{{url('selectOrder-date')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive" data-ajax="false"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">{{ __('messages.Eat Later') }}</a></div>
		</div>
	</div>

	<div role="main" data-role="main-content" id="content">
		<div class="cat-list-sec">
			<input type="hidden" id="browserCurrentTime" name="browserCurrentTime" value="" />
			<ul data-role="listview" data-inset="true" id="companyDetailContianer">

		
			</ul>
		</div>
	</div>	

	<div data-role="footer" id="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="javascript:void(0)" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
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
	        <div id="cancel-popup" class="cross"><span class="popup-close1">x</span></div>
	        <div class="pop-body">
	           <p>{{ __('messages.Please activate Location Services in your mobile') }}</p>
	        </div>
	  </div>
	</div>

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon-loading"></span><h1>loading</h1></div>

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')
	<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.min.js"></script>

<script type="text/javascript">
	var loc_lat;
	var loc_lng;
	var loc_flag=0;
	var resExist=0;
</script>

<?php
	$helper = new Helper();
	// $helper->logs("1 " . Session::get('with_login_lat') . " 2 " . Session::get('with_login_lng') . " 3 " . Session::get('with_out_login_lat') . " 4 " . Session::get('with_out_login_lng') . " 5 " . Session::get('address'));

	if(Auth::check()){
			if(Session::get('with_login_address') != null){
				?>
	<script type="text/javascript">
				loc_lat = "{{Session::get('with_login_lat')}}";
				loc_lng = "{{Session::get('with_login_lng')}}";
	</script>				
				<?php
			}else if(Session::get('with_out_login_lat') != null){
				?>
		<script type="text/javascript">
				loc_lat = "{{Session::get('with_out_login_lat')}}";
				loc_lng = "{{Session::get('with_out_login_lng')}}";
		</script>
				<?php
			}else{
				?>
		<script type="text/javascript">
				loc_lat = "";
				loc_lng = "";
		</script>
				<?php
			}
		}
		else{
			if(Session::get('with_out_login_lat') != null){
				?>
		<script type="text/javascript">
				loc_lat = "{{Session::get('with_out_login_lat')}}";
				loc_lng = "{{Session::get('with_out_login_lng')}}";
		</script>
				<?php
			}
		}
?>
<script type="text/javascript">
	var setTimezone = "{{url('set-timezone')}}";
	var replace_url = "{{url('select-location?k=home')}}";
	var checkloginUrl = "{{url('checkUserLogin')}}"; 
	var latLongUrl = "{{url('lat-long')}}"; 
	var userLoginUrl = "{{url('userLogin')}}"; 
	var restrolinkUrl  = "{{url('restro-menu-list/')}}";
</script>
<script src="{{asset('locationJs/index.js')}}"></script>
<!--<script src="{{asset('locationJs/currentLocation.js?2')}}"></script>-->
@endsection
