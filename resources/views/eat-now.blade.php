@extends('layouts.master')

@section('head-scripts')

@stop

@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="nav_fixed">
			<div class="logo">
				<div class="inner-logo">
					<img src="{{asset('images/logo.png')}}">
					@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
				</div>
			</div>
			<a class="ui-btn-right map-btn user-link" href="{{url('search-map-eatnow')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
		</div>
		<div class="cat-btn">
			<div class="ui-grid-a top-btn">
				<div class="ui-block-a"><a href="" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
				<div class="ui-block-b"><a href="{{url('selectOrder-date')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive" data-ajax="false"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">{{ __('messages.Eat Later') }}</a></div>
			</div>
		</div>
	</div>
	<div role="main" data-role="main-content" id="content">

		<div class="cat-list-sec">
			<ul data-role="listview" data-inset="true" id="companyDetailContianer">

			</ul>
		</div>


	</div>	
	<div data-role="footer" id="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="javascript:void(0)" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@if(count(Auth::user()->paidOrderList) == 0)
			<div class="ui-block-c">
				<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_05.png')}}">
					</div>
					<span>{{ __('messages.Order') }}</span>
				</a>
			</div>
		@else
			<div class="ui-block-c order-active">
				<a href="#order-popup" data-rel="popup" data-transition="slideup" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_05-active.png')}}">
					</div>
					<span>{{ __('messages.Order') }}<span class="order_number">{{count(Auth::user()->paidOrderList)}}</span></span>
				</a>
				<div data-role="popup" id="order-popup" data-theme="a">
				     <ul data-role="listview" data-inset="true" style="min-width:210px;">
				        @foreach(Auth::user()->paidOrderList as $order)
							<li>
								<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->customer_order_id}}</a>
							</li>
						@endforeach
				     </ul>
				 </div>
		    </div>
		@endif

		<div class="ui-block-d"><a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>
	@include('includes.fixedfooter')
	<!-- pop-up -->
	<div data-role="popup" id="order-popup" class="ui-content" data-theme="a" >
		<ul data-role="listview">
			@foreach(Auth::user()->paidOrderList as $order)
				<li>
					<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->customer_order_id}}</a>
				</li>
			@endforeach
		</ul>
	</div>

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon-loading"></span><h1>loading</h1></div>

	  <div id="overlay" onclick="off()">
	  </div>

@endsection

@section('footer-script')

<?php
	$helper = new Helper();
	// $helper->logs("1 " . Session::get('with_login_lat') . " 2 " . Session::get('with_login_lng') . " 3 " . Session::get('with_out_login_lat') . " 4 " . Session::get('with_out_login_lng') . " 5 " . Session::get('address'));

	if(Auth::check()){
			//if(Session::get('with_login_address') != null){
		  if(Session::get('with_login_lat') != null){
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

	<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.min.js"></script>
	
<script type="text/javascript">
	var list = Array();
	var totalCount = 0;

	var tz = moment.tz.guess();
	$.get("{{url('set-timezone')}}",{'tz':tz});

	// $.get("{{url('writeLogs')}}",{'log':'eat now page'});


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
	        console.log(list);
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

	          	liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
	          	liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+list[i]['store_id']+" data-ajax='false'>";
	          	liItem += "<img src="+"'"+list[i]["store_image"]+"'"+">";
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
	          	countCheck++;
	          }
	            $("#companyDetailContianer").append(liItem);	
	}



	 $(function(){
	 	    $("#overlay").show();
    		$("#loading-img").show();

    	$(".icon-eat-inactive").click(function(){
    		eatActive = $(".icon-eat-active");
    		eatInactive = $(".icon-eat-inactive");

    		eatActive.removeClass('icon-eat-active');
    		eatActive.addClass('icon-eat-inactive');

    		eatInactive.removeClass('icon-eat-inactive');
    		eatInactive.addClass('icon-eat-active');
    	});

	 	var extraclass = document.body;
		extraclass.classList.add("disableClass");
	
	if (typeof loc_lat === "undefined" || loc_lat == "") {			
	navigator.geolocation.getCurrentPosition(function(position) { 
	    document.cookie="latitude=" + position.coords.latitude;
	    document.cookie="longitude=" + position.coords.longitude;
	    var extraclass = document.body;
		extraclass.classList.remove('disableClass');
		//location.reload ();
	},function(error){
		if (typeof loc_lat === "undefined" || loc_lat == "") {
		   $('.login-inner-section a').attr('href','javascript:void(0)');
		   $('#login-popup').show();	    			
			// $.get("{{url('writeLogs')}}",{'log':'eat now location 1'});
		}else{
		    document.cookie="latitude=" + loc_lat;
		    document.cookie="longitude=" + loc_lng;		
		} 
	});
			}else{
				loc_flag=3;
			    document.cookie="latitude=" + loc_lat;
			    document.cookie="longitude=" + loc_lng;	
				add();			    
		}


	$.get("{{url('lat-long')}}", { lat: getCookie("latitude"), lng : getCookie("longitude")}, 
    function(returnedData){
    		$("#loading-img").hide();
    		$("#overlay").hide();

    	var count = 10;

    	//console.log(returnedData["data"]);
    	var url = "{{url('restro-menu-list/')}}";

          var temp = returnedData["data"];
          list = temp;
          //console.log(temp);
           //console.log(temp.length);
          var liItem = "";
	          if(temp.length != 0){
	          	totalCount = temp.length;

	          	if(temp.length < count){
	          		count = temp.length
	          	}

	          	totalCount -= 10;

	          for (var i=0;i<count;i++){
	          	//console.log(temp[i]["store_id"]);

	          	liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
	          	liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+temp[i]['store_id']+" data-ajax='false'>";
	          	liItem += "<img src="+"'"+temp[i]["store_image"]+"'"+">";
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
        }else{

        	liItem += "<div class='table-content'>";
        	liItem += "<p>";
        	liItem += 'Restaurants are not available';
        	liItem += "</p>";
        	liItem += "</div>";
          }
          	//console.log(liItem);
          $("#companyDetailContianer").append(liItem);	         
		});
	});


</script>

<script type="text/javascript">
	  navigator.sayswho= (function(){
	    var ua= navigator.userAgent, tem, 
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(/trident/i.test(M[1])){
	        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	        return 'IE '+(tem[1] || '');
	    }
	    
	    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);

	    //console.log("browserVersion=" + M.join(' '));
	    var browserVersion = M.join(' ');
	    var getBrowser = browserVersion.split(" ");
	    var browser = getBrowser[0];
	    document.cookie="browser=" + browser;
	    document.cookie="browserVersion=" + M.join(' ');

	    device = (navigator.userAgent).match(/Android|iPhone|iPad|iPod/i); 

	    if (/iPhone/i.test(device) ) { 
	        deviceType = 'phone'; 
	        osVersion = (navigator.userAgent).match(/OS\s+([\d\_]+)/i); 
	        osVersion = osVersion[0]; 
	        osVersion = osVersion.replace(/_/g, '.'); 
	        osVersion = osVersion.replace('OS ', ''); 
			document.cookie="osVersion=" + osVersion;		

		    if(osVersion >= 10){
		    	$('.footer').css({'padding-top':'10px', 'padding-bottom':'10px'});
		    }			
	    } 
	})();
	</script>

@endsection