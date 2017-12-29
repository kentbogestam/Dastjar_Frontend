@extends('layouts.master')
@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed"><!--  -->
		<div class="nav_fixed">
			<div class="logo">
				<div class="inner-logo">
					<img src="{{asset('images/logo.png')}}">
					<span>{{ Auth::user()->name}}</span>
				</div>
			</div>
			<a class="ui-btn-right map-btn user-link" onClick="makeRedirection('{{url('search-map-eatlater')}}')"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
		</div>
		<div class="cat-btn">
			<div class="ui-grid-a top-btn">
				<div class="ui-block-a"><a onClick="makeRedirection('{{url('eat-now')}}')" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">Eat Now</a></div>
				<div class="ui-block-b"><a href="#" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">Eat Later</a></div>
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
		<div class="ui-block-a"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>Restaurant</span>
		</a></div>
		<div class="ui-block-b"><a href = "#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>send</span>
		</a></div>
		@if(count(Auth::user()->paidOrderList) == 0)
			<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_05.png')}}">
				</div>
				<span>Order</span>
			</a></div>
		@else
			<div class="ui-block-c order-active">
		    	<a  class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec">
			        <div class="img-container">
			       		<!-- <img src="images/icons/select-store_05.png"> -->
			        	<img src="images/icons/select-store_05-active.png">
			        </div>
		        	<span>Order<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
		        </a>
		        <div id="order-popup" data-theme="a">
			      <ul data-role="listview">
			      	@foreach(Auth::user()->paidOrderList as $order)
						<li>
							<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">Order id - {{$order->order_id}}</a>
						</li>
					@endforeach
			      </ul>
			    </div>
		    </div>
		@endif
		<div class="ui-block-d"><a href = "{{url('user-setting')}}"  class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
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

			liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
			liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+list[i]['store_id']+">";
			liItem += "<img src='images/img-store-3.png'>";
			liItem += "<h2>"+list[i]["store_name"]+"</h2>";
			liItem += "<p>";
			
			for (var j=0;j<list[i]["products"].length;j++){
				console.log(list[i]["products"][j]);
				;
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
	$.get("{{url('eat-later-data')}}",
    function(returnedData){

    	var count = 10;
    	//console.log(returnedData["data"]);
    	var url = "{{url('restro-menu-list/')}}";

          var temp = returnedData["data"];
          list = temp;
          console.log(temp);
           console.log(temp.length);
          var liItem = "";
	          if(temp.length != 0){
	          	totalCount = temp.length;

	          	if(temp.length < count){
	          		count = temp.length
	          	}

	          	totalCount -= 10;

	          for (var i=0;i<count;i++){
	          	console.log(temp[i]["store_id"]);

	          	liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
	          	liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+temp[i]['store_id']+">";
	          	liItem += "<img src='images/img-store-3.png'>";
	          	liItem += "<h2>"+temp[i]["store_name"]+"</h2>";
	          	liItem += "<p>";
	          	
	          	for (var j=0;j<temp[i]["products"].length;j++){
	          		console.log(temp[i]["products"][j]);
	          		;
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
          	console.log(liItem);

          

          $("#companyDetailContianer").append(liItem);	

          

		});
	});

</script>

@endsection