@extends('layouts.blank')

@section('head-scripts') 

@endsection
@section('content')

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
			<a href = "{{ url('kitchen/logout') }}"  class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
			</a>
		</div>
		<h3 class="ui-bar ui-bar-a order_background">{{ __('messages.Kitchen') }} <span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<div id="audio"></div>
		<div class="ready_notification">
			@if ($message = Session::get('success'))
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
				 @if(is_array($message))
		            @foreach ($message as $m)
		                {{ $languageStrings[$m] or $m }}
		            @endforeach
		        @else
		            {{ $languageStrings[$message] or $message }}
		        @endif
		    </div>
		@endif
		</div>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
		 	<thead>
		 		<tr class="ui-bar-d">
			  		<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th>{{ __('messages.Amount') }}</th> 
			   		<th data-priority="3">{{ __('messages.Product') }}</th>
			    	<th data-priority="1">{{ __('messages.Comment') }}</th> 
			    	<th data-priority="5">{{ __('messages.Date and Time') }}</th>
			     	<th data-priority="3">{{ __('messages.Started') }}</th>
			      	<th data-priority="3">{{ __('messages.Ready') }}</th>
			     	<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
		      	</tr>
		    </thead>
		    <tbody id="orderDetailContianer">
		    	
		    </tbody>
		</table>
	</div>
	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer_container">
		<div class="ui-grid-a center">
			<div class="ui-block-a left-side_menu">
				<div class="ui-block-a block_div "><a  href = "{{ url('/admin') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-1.png')}}">
					</div>
					<span>{{ __('messages.Orders') }}</span>
				</a></div>
				<div class="ui-block-b block_div active" ><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-2.png')}}">
					</div>
					<span>{{ __('messages.Kitchen') }}</span>
				</a></div>
				<div class="ui-block-b">
					<a href = "{{ url('kitchen/catering') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-3.png')}}">
					</div>
					<span>{{ __('messages.Catering') }}</span>
					</a>
				</div>
			</div>
			<div class="ui-block-b right-side_menu">
			
			
				<div class="ui-block-a drop_down"><a href = "{{ url('kitchen/kitchen-setting') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-6.png')}}">
					</div>
				</a></div>
				<div class="ui-block-b middle-menu"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-5.png')}}">
					</div>
					<span>{{ __('messages.Admin') }}</span>
				</a></div>
				<div class="ui-block-c"><a href = "{{ url('kitchen/kitchen-order-onsite') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-4.png')}}">
					</div>
					<span>{{ __('messages.Order Onsite') }}</span>
				</a></div>
			</div>
		</div>
	</div>

@endsection

@section('footer-script')
	<script type="text/javascript">
		var list = Array();
		var totalCount = 0;
		var textSpeach = 0;
		var totallength = 0;
		var url = "{{url('kitchen/order-started')}}";
		var urlReady = "{{url('kitchen/order-readyKitchen')}}";
		var textSpeachDone = "{{url('kitchen/textSpeachDone')}}";

		$(function(){
			$.get("{{url('kitchen/kitchen-orders')}}",
			function(returnedData){
				console.log(returnedData["data"]);
				textSpeach = returnedData["user"];
				var count = 18;
				var temp = returnedData["data"];
	          	list = temp;
	          	console.log(temp.length);
	          	var liItem = "";
	          	totallength = temp.length;
	          	if(temp.length != 0){
	          		totalCount = temp.length;

		          	if(temp.length < count){
		          		count = temp.length
		          	}

		          	totalCount -= 10;
		          	for (var i=0;i<count;i++){
		          		if(i>=totallength){
				      		break;
				      	}
				      	(function (i) {
						    setTimeout(function () {
			          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
			          		var timeOrder = addTimes("00:00::",temp[i]["deliver_time"]);
			          		liItem += "<tr>";
			          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
			          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
			          		liItem += "<td>"+temp[i]["product_name"]+
			          		"</td>";
			          		if(textSpeach == 1 && temp[i]['is_speak'] == 0){
				          		liItem += "<td>"+temp[i]["product_description"]+test(temp[i]["product_quality"]+temp[i]["product_name"]+temp[i]["product_description"])+updateSpeak(temp[i]['id'])+"</td>";
			          		}else{
			          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
			          		}
			          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";

			          		if(temp[i]["order_started"] == 0){
				          		liItem += "<td >"
				          		liItem += "<a data-ajax='false' href="+url+"/"+temp[i]['id']+" >"
				          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}else{
			          			liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}

			          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
				          		liItem += "<td>"
				          		liItem += "<a data-ajax='false' >"
				          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          		}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
				          		liItem += "<td>"
				          		liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['id']+" >"
				          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";

				          		}else{
				          		liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}
			          		liItem += "<td>"+time+"</td>";
			          		liItem += "</tr>"
					    	$("#orderDetailContianer").append(liItem);
					    	liItem = null;
						     }, 3000*i);
					    })(i);
		          	}
	          	}else{
	          		liItem += "<div class='table-content'>";
		        	liItem += "<p>";
		        	//liItem += '{{ __('messages.Order is not available.') }}';
		        	liItem += "</p>";
		        	liItem += "</div>";
	          	}
	          	$("#orderDetailContianer").append(liItem);
			}); 
		});

		var ajaxCall = function(){
			$.get("{{url('kitchen/kitchen-orders')}}",
			function(returnedData){
				console.log(returnedData["data"]);
				var count = 18;
				var temp = returnedData["data"];
				textSpeach = returnedData["user"];
				totallength = temp.length;
	          	list = temp;
	          	console.log(temp.length);
	          	var liItem = "";
	          	if(temp.length != 0){
	          		totalCount = temp.length;

		          	if(temp.length < count){
		          		count = temp.length
		          	}

		          	totalCount -= 10;
		          	for (var i=0;i<count;i++){
		          		if(i>totallength){
				      		break;
				      	}
				      	(function (i) {
						    setTimeout(function () {
			          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
			          		var timeOrder = addTimes("00:00::",temp[i]["deliver_time"]);
			          		liItem += "<tr>";
			          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
			          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
			          		liItem += "<td>"+temp[i]["product_name"]+
			          		"</td>";
			          		if(textSpeach == 1 && temp[i]['is_speak'] == 0){
				          		liItem += "<td>"+temp[i]["product_description"]+test(temp[i]["product_quality"]+temp[i]["product_name"]+temp[i]["product_description"])+updateSpeak(temp[i]['id'])+"</td>";
			          		}else{
			          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
			          		}
			          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";

			          		if(temp[i]["order_started"] == 0){
				          		liItem += "<td >"
				          		liItem += "<a data-ajax='false' href="+url+"/"+temp[i]['id']+" >"
				          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}else{
			          			liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}

			          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
				          		liItem += "<td>"
				          		liItem += "<a data-ajax='false' >"
				          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          		}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
				          		liItem += "<td>"
				          		liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['id']+" >"
				          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";

				          		}else{
				          		liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}
			          		liItem += "<td>"+time+"</td>";
			          		liItem += "</tr>"
					    	$("#orderDetailContianer").html(liItem);
						     }, 3000*i);
					    })(i);
		          	}
	          	}else{
	          		liItem += "<div class='table-content'>";
		        	liItem += "<p>";
		        	//liItem += '{{ __('messages.Order is not available.') }}';
		        	liItem += "</p>";
		        	liItem += "</div>";
	          	}
	          	$("#orderDetailContianer").html(liItem);
			}); 
		}

		setInterval(ajaxCall, 30000);

		var tempCount = 10;
		$(document).on("scrollstop", function (e) {
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
			         if(tempCount<totallength){
			         	tempCount += 10;
			         }else{
			         	tempCount = 18;
			         }
			         $.mobile.loading("hide");
			     },500);
	    	}
		});

		function  addMore(len){
			var liItem = "";
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
	      //console.log(returnedData["data"]);
	      //console.log("len="+len);
	     // console.log("i="+i);
	      console.log("totallength="+totallength);
	      	if(i>=totallength){
	      		tempCount = 10;
	      		break;
	      	}
	      	if(countCheck>limit){
	      		break;
	      	}
	      	 (function (i) {
			    setTimeout(function () {
			      	var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
			      	var timeOrder = addTimes("00:00::",list[i]["deliver_time"]);
		      		liItem += "<tr>";
		      		liItem += "<th>"+list[i]["customer_order_id"]+"</th>";
		      		liItem += "<td>"+list[i]["product_quality"]+"</td>";
		      		liItem += "<td>"+list[i]["product_name"]+"</td>";
		      		if(textSpeach == 1 && list[i]['is_speak'] == 0){
		          		liItem += "<td>"+list[i]["product_description"]+test(list[i]["product_quality"]+list[i]["product_name"]+list[i]["product_description"])+updateSpeak(list[i]['id'])+"</td>";
	          		}else{
	          			liItem += "<td>"+list[i]["product_description"]+"</td>";
	          		}
		      		liItem += "<td>"+list[i]["deliver_date"]+' '+timeOrder+"</td>";
		      		if(list[i]["order_started"] == 0){
		          		liItem += "<td >"
		          		liItem += "<a data-ajax='false' href="+url+"/"+list[i]['id']+" >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
		          		liItem +="</a></td>";
		      		}else{
		      			liItem += "<td>"
		          		liItem += "<a>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
		          		liItem +="</a></td>";
		      		}
		      		if(list[i]["order_ready"] == 0 && list[i]["order_started"] == 0){
		          		liItem += "<td>"
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
		          		liItem +="</a></td>";
		          		}else if(list[i]["order_ready"] == 0 && list[i]["order_started"] == 1){
		          		liItem += "<td>"
		          		liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['id']+" >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
		          		liItem +="</a></td>";

		          		}else{
		          		liItem += "<td>"
		          		liItem += "<a>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
		          		liItem +="</a></td>";
		      		}
		      		liItem += "<td>"+time+"</td>";
		      		liItem += "</tr>";
			      	countCheck++;
			      	$("#orderDetailContianer").append(liItem);
			      	liItem = null;
			     }, 3000*i);
		    })(i);
	      }
	      $("#orderDetailContianer").append(liItem);	
		}

		function updateSpeak(id){
			//console.log('ssssssssssssssssssssssssssssssssssssssssssssssssssssss');
			var url = '{{url('kitchen/updateTextspeach')}}'+'/'+id;
			//console.log('urlurl'+url);
			$.ajax({
	            url: url, //This is the current doc
	            type: "GET",//variables should be pass like this
	            success: function(data){
	               //console.log('fff');
	            }
	        }); 
		}



		function addTimes (startTime, endTime) {
		  var times = [ 0, 0, 0 ]
		  var max = times.length

		  var a = (startTime || '').split(':')
		  var b = (endTime || '').split(':')

		  // normalize time values
		  for (var i = 0; i < max; i++) {
		    a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i])
		    b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i])
		  }

		  // store time values
		  for (var i = 0; i < max; i++) {
		    times[i] = a[i] + b[i]
		  }

		  var hours = times[0]
		  var minutes = times[1]
		  var seconds = times[2]

		  if (seconds > 60) {
		    var m = (seconds / 60) << 0
		    minutes += m
		    seconds -= 60 * m
		  }

		  if (minutes > 60) {
		    var h = (minutes / 60) << 0
		    hours += h
		    minutes -= 60 * h
		  }

		  return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2)
		}
	</script>
	<script src="{{asset('kitchenJs/speekJs.js')}}"></script>
@endsection