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
			-moz-transform: translate(-50%);
			-webkit-transform: translate(-50%);
			-o-transform: translate(-50%);
			-ms-transform: translate(-50%);
			transform: translate(-50%);
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
			<div class="ui-block-a"><a href="" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
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

	<img src="{{ asset('images/loading.gif') }}" id="loading-img" />

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')

<script type="text/javascript">
	var loc_lat;
	var loc_lng;
	var loc_flag=0;
	var resExist=0;
</script>

<?php
	$helper = new Helper();
	$helper->logs("1 " . Session::get('with_login_lat') . " 2 " . Session::get('with_login_lng') . " 3 " . Session::get('with_out_login_lat') . " 4 " . Session::get('with_out_login_lng') . " 5 " . Session::get('address'));

	if(Auth::check()){
			if(Session::get('with_login_address') != null){
				?>
	<script type="text/javascript">
				loc_lat = "{{Session::get('with_login_lat')}}";
				loc_lng = "{{Session::get('with_login_lng')}}";
	</script>				
				<?php
			}else if(Session::get('address') != null){
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
			if(Session::get('address') != null){
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
	$("#cancel-popup").click(function () {
      $('#login-popup').hide();
      var extraclass = document.body;
	  extraclass.classList.add("disableClass");
	  window.location.replace("{{url('select-location?k=home')}}");
    });

	$(".ordersec").click(function(){
		$("#order-popup").toggleClass("hide-popup");
	});

	var list = Array();
	var totalCount = 0;

	function getCookie(cname) {
	    var name = cname + "=";
	    var decodedCookie = decodeURIComponent(document.cookie);
	    var ca = decodedCookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}


	function makeRedirection(link){
		window.location.href = link;
	}


	$(document).on("scrollstop", function (e) {
		var tempCount = 10;
    	var activePage = $.mobile.pageContainer.pagecontainer("getActivePage"),
        screenHeight = $.mobile.getScreenHeight(),
        contentHeight = $(".ui-content", activePage).outerHeight(),
        header = $(".ui-header", activePage).outerHeight() - 1,
        scrolled = $(window).scrollTop(),
        footer = $(".ui-footer", activePage).outerHeight() - 1,
        scrollEnd = contentHeight - screenHeight + header + footer;

    	$(".ui-btn-left", activePage).text("Scrolled: " + scrolled);
    	//$(".ui-btn-right", activePage).text("ScrollEnd: " + scrollEnd);
    	
    	//if in future this page will get it, then add this condition in and in below if activePage[0].id == "home" 
    	if (scrolled >= scrollEnd) {
	        //console.log(list);
	        $.mobile.loading("show", {
	        text: "loading more..",
	        textVisible: true,
	        theme: "b"
	    	});
	    	setTimeout(function () {
		        addMore(tempCount);
		        tempCount += 10;
		        $.mobile.loading("hide");
		    },500);
    	}
	});

	function  addMore(len){
		var liItem = "";
    	var url = "{{url('restro-menu-list/')}}";
    	var limit = 0;
    	var countCheck = 1;
		if(totalCount > 10){
			limit = 10;
			totalCount -= 10;
		} else if(totalCount<=0){
			return;
		} else{
			limit = totalCount;
			totalCount -= totalCount;
		}


		for (var i=len;i<len + 10;i++){

			if(countCheck>limit){
				break;
			}
			if(checkTime(temp[i]["store_open_close_day_time"])){
				
				liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
				liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+list[i]['store_id']+" data-ajax='false'>";
				liItem += "<img src="+"'"+temp[i]["store_image"]+ "' onerror='this.src=\""+"{{url('images/placeholder-image.png')}}\""+"'" +">";
				liItem += "<h2>"+list[i]["store_name"]+"</h2>";
				liItem += "<p>";
				
				for (var j=0;j<list[i]["products"].length;j++){
					if(j <= 1){
						liItem += list[i]["products"][j]["product_name"];
					}   
					if(list[i]["products"].length > 1 && j <= 1){
						liItem += ",&nbsp;";
					}
				}

				if(list[i]["products"].length > 1){
					liItem += "&nbsp;&more";
				} 
			liItem += "</p>";
				liItem += "<div class='ui-li-count ui-body-inherit'>";
				liItem += "<span>"+list[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

				liItem += "</div></a></li>";

			}
			countCheck++;
		}
		$("#companyDetailContianer").append(liItem);	
	}

	function add(){
		var d = new Date();
		//console.log(d);
		$("#browserCurrentTime").val(d);
		if(resExist==0){
			resExist=1;
			$.get("{{url('lat-long')}}", { lat: getCookie("latitude"), lng : getCookie("longitude"), currentdateTime : d, browserVersion : getCookie("browserVersion")}, 
		    	function(returnedData){
		    		loc_flag=4;
		    		$('#login-popup').hide();
	    			$("#loading-img").hide();
		    		$("#overlay").hide();

			    	var count = 10;
			    	//console.log(returnedData["data"]);
			    	var url = "{{url('restro-menu-list/')}}";
					var temp = returnedData["data"];
					list = temp;
					var liItem = "";
					if(temp.length != 0){
						totalCount = temp.length;
						if(temp.length < count){
							count = temp.length
						}
						totalCount -= 10;

						for (var i=0;i<count;i++){
							if(checkTime(temp[i]["store_open_close_day_time"])){

								liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
								liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+temp[i]['store_id']+" data-ajax='false'>";
								liItem += "<img src="+"'"+temp[i]["store_image"]+ "' onerror='this.src=\""+"{{url('images/placeholder-image.png')}}\""+"'" +">";
								liItem += "<h2>"+temp[i]["store_name"]+"</h2>";
								liItem += "<p>";
								
								for (var j=0;j<temp[i]["products"].length;j++){
									if(j <= 1){
										liItem += temp[i]["products"][j]["product_name"];
									}   
									if(temp[i]["products"].length > 1 && j <= 1){
										liItem += ",&nbsp;";
									}
								}

								if(temp[i]["products"].length > 1){
									liItem += "&nbsp;&more";
								} 
								liItem += "</p>";
								liItem += "<div class='ui-li-count ui-body-inherit'>";
								liItem += "<span>"+temp[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

								liItem += "</div></a></li>";
							}
						}
					}else{
						liItem += "<div class='table-content'>";
						liItem += "<p>";
						liItem += '';
						liItem += "</p>";
						liItem += "</div>";
					}
			  		$("#companyDetailContianer").append(liItem);
				});
		}
	}



	$(function(){
    	$("#overlay").show();
    	$("#loading-img").show();

		var extraclass = document.body;

	setInterval(getPosAgain,3000);

	function getPosAgain(){
		if(loc_flag==0){
			getPos();
		}
	}

	if (navigator.geolocation) {
		getPos();
	}else{
		// $('.login-inner-section a').attr('href','javascript:void(0)');
		// $('#login-popup').show();	
	}

	var d = new Date();

	$("#browserCurrentTime").val(d);

	$.get("{{url('checkUserLogin')}}", 
	    function(returnedData){
	    	var temp = returnedData["data"];
	    	if(temp){
	    		 document.cookie="userId=" + temp;
   	    		 localStorage.setItem("userId", temp);
	    	}else{
	    		if(localStorage.getItem("userId")){
	    			console.log('logoutloginId='+localStorage.getItem("userId"));
	    			$.get("{{url('userLogin')}}", { usetId : localStorage.getItem("userId")}, 
	    				function(returnedData){
	    					// console.log(returnedData["data"]);
	    					// location.reload();
	    				});
	    		}else{
	    			// console.log('logout');
	    		}
	    	}
	    });

	});


	function checkTime($time){
		var d = new Date();
		var dd = (d.toString()).split(' ');
		var currentTime = dd[4];
		var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
		var todayDay = days[d.getDay()];
		var time = $time;
		var day = time.split(' :: ')
		var checkday = time.split(',')
		if(day[0] == 'All'){
			var timeSplit = day[1].split(' to ');
			var openTime = timeSplit[0];
			var closeTime = timeSplit[1];
			if(openTime < currentTime && closeTime > currentTime){
				return true;
			}else{
				return false;
			}
		}else{
			if(day.length == 2){
				if(day[0] == todayDay){
					var timeSplit = day[1].split(' to ');
					var openTime = timeSplit[0];
					var closeTime = timeSplit[1];
					if(openTime < currentTime && closeTime > currentTime){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				for(i=0;i<checkday.length;i++){
					var getDay = checkday[i].split(' :: ');
					if(getDay[0] == todayDay){
						var timeSplit = getDay[1].split(' to ');
						var openTime = timeSplit[0];
						var closeTime = timeSplit[1];
						if(openTime < currentTime && closeTime > currentTime){
							return true;
						}else{
							return false;
						}
					}
				}
			}
		}
		return false;
	}

	function getPos(){
		navigator.geolocation.getCurrentPosition(function(position) { 
			loc_flag=1;
		    document.cookie="latitude=" + position.coords.latitude;
		    document.cookie="longitude=" + position.coords.longitude;
		    var extraclass = document.body;
			extraclass.classList.remove('disableClass');
			//location.reload ();
			$.get("{{url('writeLogs')}}",{'log':'location 1'});
			add();
		},function(error){
			loc_flag=2;
			if (typeof loc_lat === "undefined" || loc_lat == "") {
	    		$("#loading-img").hide();
	    		$("#overlay").hide();
			    $('.login-inner-section a').attr('href','javascript:void(0)');
 			    $('#login-popup').show();	
				$.get("{{url('writeLogs')}}",{'log':'location 2'});
			}else{
			    document.cookie="latitude=" + loc_lat;
			    document.cookie="longitude=" + loc_lng;		
				$.get("{{url('writeLogs')}}",{'log':'location 3'});
				add();
			} 
		},{maximumAge:0,timeout:5000});
	}

	$('#login-popup').bind('beforeShow', function() {
      alert('beforeShow');
    }); 

</script>

<script type="text/javascript">
	function getCookie(cname) {
	    var name = cname + "=";
	    var decodedCookie = decodeURIComponent(document.cookie);
	    var ca = decodedCookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	var count = getCookie("iphonePopupcount") + getCookie("iphonePopupcountIncrease");

	var IphoneVersion;
    var deviceDetection = function () { 
    var osVersion, 
    device, 
    deviceType, 
    userAgent, 
    isSmartphoneOrTablet; 

    device = (navigator.userAgent).match(/Android|iPhone|iPad|iPod/i); 

    if ( /Android/i.test(device) ) { 
        if ( !/mobile/i.test(navigator.userAgent) ) { 
            deviceType = 'tablet'; 
        } else { 
            deviceType = 'phone'; 
        } 

        osVersion = (navigator.userAgent).match(/Android\s+([\d\.]+)/i); 
        osVersion = osVersion[0]; 
        osVersion = osVersion.replace('Android ', ''); 

    } else if ( /iPhone/i.test(device) ) { 
        deviceType = 'phone'; 
        osVersion = (navigator.userAgent).match(/OS\s+([\d\_]+)/i); 
        osVersion = osVersion[0]; 
        osVersion = osVersion.replace(/_/g, '.'); 
        osVersion = osVersion.replace('OS ', ''); 

    } else if ( /iPad/i.test(device) ) { 
        deviceType = 'tablet'; 
        osVersion = (navigator.userAgent).match(/OS\s+([\d\_]+)/i); 
        osVersion = osVersion[0]; 
        osVersion = osVersion.replace(/_/g, '.'); 
        osVersion = osVersion.replace('OS ', ''); 
    } 
    isSmartphoneOrTablet = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent); 
    userAgent = navigator.userAgent; 
    IphoneVersion = osVersion;
    return { 'isSmartphoneOrTablet': isSmartphoneOrTablet, 
             'device': device, 
             'osVersion': osVersion, 
             'userAgent': userAgent, 
             'deviceType': deviceType 
            }; 
    }();
    //console.log('IphoneVersion='+IphoneVersion);

	if(getCookie("browser") == 'Safari' && count == 1){
		document.cookie="iphonePopupcountIncrease=" + 2;
		var ath = addToHomescreen({
		    debug: 'ios',           // activate debug mode in ios emulation
		    skipFirstVisit: false,	// show at first access
		    startDelay: 0,          // display the message right away
		    lifespan: 0,            // do not automatically kill the call out
		    displayPace: 0,         // do not obey the display pace
		    privateModeOverride: true,	// show the message in private mode
		    maxDisplayCount: 0      // do not obey the max display count
		});
	}
</script>

<script src="{{asset('locationJs/currentLocation.js?2')}}"></script>
@endsection
