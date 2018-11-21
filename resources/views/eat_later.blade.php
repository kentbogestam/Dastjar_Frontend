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
	</style>
	<script src="{{asset('locationJs/currentLocation.js')}}"></script>
@endsection

@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed"><!--  -->
		<div class="nav_fixed">
			<div class="logo">
				<div class="inner-logo">
					<img src="{{asset('images/logo.png')}}">
					@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
				</div>
			</div>
			<a class="ui-btn-right map-btn user-link" href="{{url('search-map-eatlater')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
		</div>
	</div>
	<div class="cat-btn">
		<div class="ui-grid-a top-btn">
			<div class="ui-block-a"><a href="{{url('eat-now')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive active" data-ajax="false"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
			<div class="ui-block-b"><a href="{{url('selectOrder-date')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" data-ajax="false"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">{{ __('messages.Eat Later') }}</a></div>
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
		<div class="ui-block-b"><a href = "#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@include('orderQuantity')
		<div class="ui-block-d"><a href = "{{url('user-setting')}}"  class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon-loading"></span><h1>loading</h1></div>

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')

<script type="text/javascript">

	$(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });

	var list = Array();
	var totalCount = 0;

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
			if(checkTime(temp[i]["store_open_close_day_time"])){

				liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
				liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+list[i]['store_id']+" data-ajax='false'>";
				liItem += "<img src="+"'"+temp[i]["store_image"]+ "' onerror='this.src=\""+"{{url('images/placeholder-image.png')}}\""+"'" +">";
				liItem += "<h2>"+list[i]["store_name"]+"</h2>";
				liItem += "<p>";
			
			// Code added to display tagline of restaurant	
			if(temp[i]["tagline"]){
             
              liItem += temp[i]["tagline"];

			}else{

				//liItem += "&nbsp;&more";
			}

			// End of code added to dispaly tagline of restaurant
			// old Code commented by saurabh to display the tag line			
			/*for (var j=0;j<temp[i]["products"].length;j++){
				if(j <= 1){
					liItem += temp[i]["products"][j]["product_name"];
				}   
				if(temp[i]["products"].length > 1 && j <= 1){
					liItem += ",&nbsp;";
				}
			}

			if(temp[i]["products"].length > 1){
				liItem += "&nbsp;&more";
			} */

			// End Old Code commented by saurabh to display the tag line	

			liItem += "</p>";
				liItem += "<div class='ui-li-count ui-body-inherit'>";
				liItem += "<span>"+list[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

				liItem += "</div></a></li>";
			}
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

	$.get("{{url('eat-later-data')}}",
    function(returnedData){
			$("#loading-img").hide();
    		$("#overlay").hide();

    	var count = 10;
    	console.log(returnedData["data"]);
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
        	liItem += 'Restaurants are not available';
        	liItem += "</p>";
        	liItem += "</div>";
          }
          	//console.log(liItem);

          

          $("#companyDetailContianer").append(liItem);	

          

		});
	});

	// function checkTime($time){
 //        var d = new Date();
 //        var currentTime1 = d.toLocaleTimeString();
 //        var currentTime2  = currentTime1.split(',');
 //        var currentTime3  =  currentTime2[0].split(' '); 
 //        var currentTime4  =  (currentTime3[0]).trim();
 //        var currentTime  =  (currentTime4.replace(':', '')).replace(':', '');
 //        var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
 //        var todayDay = days[d.getDay()];
 //        // console.log(currentTime);
 //        // console.log('todayDay'+todayDay);
 //        var time = $time;
 //        var day = time.split(' :: ')
 //        var checkday = time.split(',');
 //        if(day[0] == 'All'){
 //                var timeSplit = day[1].split(' to ');
 //                var openTime = (timeSplit[0].replace(':', '')).replace(':', '');
 //                var closeTime = ((timeSplit[1].replace(':', '')).replace(':', '')).trim();
 //                var diff = closeTime - currentTime;
 //                if(openTime < currentTime && diff > 1){
 //                        return true;
 //                }else{
 //                        return false;
 //                }
 //        }else{
 //            if(day.length == 2){
 //                    if(day[0] == todayDay){
 //                            var timeSplit = day[1].split(' to ');   
 //                            var openTime = (timeSplit[0].replace(':', '')).replace(':', '');
 //                            var closeTime = ((timeSplit[1].replace(':', '')).replace(':', '')).trim();
 //                            var diff = closeTime - currentTime;
 //                            if(openTime < currentTime && diff > 1){
 //                                    return true;
 //                            }else{
 //                                    return false;
 //                            }
 //                    }else{
 //                            return false;
 //                    }
 //            }else{
 //                for(i=0;i<checkday.length;i++){
 //                    var getDay = checkday[i].split(' :: ');
 //                    if(getDay[0] == todayDay){
 //                        var timeSplit = getDay[1].split(' to ');
 //                        var openTime = (timeSplit[0].replace(':', '')).replace(':', '');
 //                        var closeTime = ((timeSplit[1].replace(':', '')).replace(':', '')).trim();
 //                        var diff = closeTime - currentTime;
 //                        if(openTime < currentTime && diff  > 1){
 //                                return true;
 //                        }else{
 //                                return false;
 //                        }
 //                    }
 //                }
 //            }
 //        }
 //        return false;
	// } 

	 function checkTime($time){
	 	if("{{ Session::get('order_date') }}"){	 
	 		var d = new Date("{{ Session::get('order_date') }}");
			var dd = (d.toString()).split(' ');
			var currentTime = dd[4];
			var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
			var todayDay = days[d.getDay()];

	 	}else{
			var d = new Date();
			var dd = (d.toString()).split(' ');
			var currentTime = dd[4];
			var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
			var todayDay = days[d.getDay()];
	 	}


		var time = $time;
		var day = time.split(' :: ')
		var checkday = time.split(',')
		if(day[0] == 'All'){
			var timeSplit = day[1].split(' to ');
			var openTime = timeSplit[0];
			var closeTime = timeSplit[1];

			console.log('currentTime '+currentTime);
			console.log('openTime ' + openTime);
			console.log('closeTime ' + closeTime);
			console.log('todayDay '+todayDay);
			console.log('$time '+$time);

			if(openTime <= currentTime && closeTime >= currentTime){
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
					if(openTime <= currentTime && closeTime >= currentTime){
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
						if(openTime <= currentTime && closeTime >= currentTime){
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
console.log('dharmveer test');
</script>

@endsection
