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
			<a class="ui-btn-right map-btn user-link" onClick="makeRedirection('{{url('search-map-eatnow')}}')"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
		</div>
		<div class="cat-btn">
			<div class="ui-grid-a top-btn">
				<div class="ui-block-a"><a href="" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">Eat Now</a></div>
				<div class="ui-block-b"><a onClick="makeRedirection('{{url('selectOrder-date')}}')" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">Eat Later</a></div>
			</div>
		</div>
	</div>
	<div role="main" data-role="main-content" id="content">

		<div class="cat-list-sec">
			<ul data-role="listview" data-inset="true" id="companyDetailContianer">

				<!-- @foreach($companydetails as $companydetail)
					<li>
						<a href="{{ url('restro-menu-list/'.$companydetail->company_id) }}">
							<img src="images/img-store-3.png">
							<h2>{{$companydetail->company_name}}</h2>
							<p>@foreach($companydetail->products as  $key => $product)
									@if(++$key <= 2)
										{{$product->product_name}}
									@endif
									@if(count($companydetail->products) >1 && ++$key <= 2)
									,
									@endif 
								@endforeach 
							@if(count($companydetail->products) >1)
							& more
							@endif</p>
						</a>
						<div class="ui-li-count">
							<span>{{ round($companydetail->distance, 2) }} km</span>
						</div>
					</li>
				@endforeach -->


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
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>send</span>
		</a></div>
		@if(count(Auth::user()->paidOrderList) == 0)
			<div class="ui-block-c">
				<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_05.png')}}">
					</div>
					<span>Order</span>
				</a>
			</div>
		@else
			<div class="ui-block-c order-active">
				<a href="#order-popup" data-transition="slideup" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline"  data-rel="popup">
					<div class="img-container">
						<!-- <img src="images/icons/select-store_05.png"> -->
						<img src="{{asset('images/icons/select-store_05-active.png')}}">
					</div>
					<span >Order<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
				</a>
			</div>
		@endif

		<div class="ui-block-d"><a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>
	<!-- pop-up -->
	<div data-role="popup" id="order-popup" class="ui-content" data-theme="a">
		<ul data-role="listview">
			@foreach(Auth::user()->paidOrderList as $order)
				<li>
					<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">Order id - {{$order->order_id}}</a>
				</li>
			@endforeach
		</ul>
	</div>

	

@endsection

@section('footer-script')

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


	function makeRedirection(link){
		window.location.href = link;
	}

	 $(function(){


	

	$.get("{{url('lat-long')}}", { lat: getCookie("latitude"), lng : getCookie("longitude")}, 
    function(returnedData){

    	//console.log(returnedData["data"]);

          var temp = returnedData["data"];


          var liItem = "";
          for (var i=0;i<temp.length;i++){
          	console.log(temp[i]["store_id"]);

          	liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
          	liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href='{{ url('restro-menu-list/'.$companydetail->company_id) }}'>";
          	liItem += "<img src='images/img-store-3.png'>";
          	liItem += "<h2>{{$companydetail->company_name}}</h2>";
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



          	console.log(liItem);

          }

          $("#companyDetailContianer").append(liItem);	

		});
	});

</script>

@endsection