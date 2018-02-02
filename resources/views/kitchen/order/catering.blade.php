@extends('layouts.blank')

@section('content')

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
		</div>
		<h3 class="ui-bar ui-bar-a order_background">Catering</h3>
	</div>
	<div role="main" class="ui-content">
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
		 	<thead>
		 		<tr class="ui-bar-d">
			  		<th data-priority="2">Orders</th>
			   		<th>Amount</th> 
			   		<th data-priority="3">Product</th>
			    	<th data-priority="1">Comment</th> 
			    	<th data-priority="5">Date and Time</th>
			     	<th data-priority="1">Pick up Time</th>
		      	</tr>
		    </thead>
		    <tbody id="orderDetailContianer">
		    	
		    </tbody>
		</table>
	</div>
	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer_container">
		<div class="ui-grid-a center">
			<div class="ui-block-a left-side_menu">
				<div class="ui-block-a active"><a  href = "{{ url('/admin') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-1.png')}}">
					</div>
					<span>Orders</span>
				</a></div>
				<div class="ui-block-b"><a href = "{{ url('kitchen/kitchen-detail') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-2.png')}}">
					</div>
					<span>kitchen</span>
				</a></div>
				<div class="ui-block-b block_div active">
					<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-3.png')}}">
					</div>
					<span>catering</span>
					</a>
				</div>
			</div>
			<div class="ui-block-b right-side_menu">
			
			
				<div class="ui-block-a drop_down"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-6.png')}}">
					</div>
				</a></div>
				<div class="ui-block-b middle-menu"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-5.png')}}">
					</div>
					<span>Admin</span>
				</a></div>
				<div class="ui-block-c"><a href = "{{ url('kitchen/kitchen-order-onside') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-4.png')}}">
					</div>
					<span>order onside</span>
				</a></div>
			</div>
		</div>
	</div>

@endsection

@section('footer-script')
	<script type="text/javascript">
		var list = Array();
		var totalCount = 0;

		$(function(){
			$.get("{{url('kitchen/catering-orders')}}",
			function(returnedData){
				console.log(returnedData["data"]);
				var count = 10;
				var temp = returnedData["data"];
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
		          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
		          		liItem += "<tr>";
		          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
		          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
		          		liItem += "<td>"+temp[i]["product_name"]+"</td>";
		          		liItem += "<td>"+temp[i]["product_description"]+"</td>";
		          		liItem += "<td>"+temp[i]["deliver_date"]+' '+temp[i]["deliver_time"]+"</td>";
		          		liItem += "<td>"+time+"</td>";
		          		liItem += "</tr>";
		          	}
	          	}else{

	          	}
	          	$("#orderDetailContianer").append(liItem);
			}); 
		});

		var ajaxCall = function(){
			$.get("{{url('kitchen/catering-orders')}}",
			function(returnedData){
				//console.log(returnedData["data"]);
				var count = 10;
				var temp = returnedData["data"];
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
		          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
		          		liItem += "<tr>";
		          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
		          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
		          		liItem += "<td>"+temp[i]["product_name"]+"</td>";
		          		liItem += "<td>"+temp[i]["product_description"]+"</td>";
		          		liItem += "<td>"+temp[i]["deliver_date"]+' '+temp[i]["deliver_time"]+"</td>";
		          		liItem += "<td>"+time+"</td>";
		          		liItem += "</tr>";
		          	}
	          	}else{

	          	}
	          	$("#orderDetailContianer").html(liItem);
			}); 
		}

		setInterval(ajaxCall, 10000);
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
			         tempCount += 10;
			         $.mobile.loading("hide");
			     },500);
	    	}
		});

		function  addMore(len){
			var liItem = "";
	    	var limit = 0;
	    	var countCheck = 1;
	    	//console.log(totalCount);
	    	//console.log(len);
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
	      	if(countCheck>limit){
	      		break;
	      	}
	      	var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
      		liItem += "<tr>";
      		liItem += "<th>"+list[i]["customer_order_id"]+"</th>";
      		liItem += "<td>"+list[i]["product_quality"]+"</td>";
      		liItem += "<td>"+list[i]["product_name"]+"</td>";
      		liItem += "<td>"+list[i]["product_description"]+"</td>";
      		liItem += "<td>"+list[i]["deliver_date"]+' '+list[i]["deliver_time"]+"</td>";
      		liItem += "<td>"+time+"</td>";
      		liItem += "</tr>";
	      	countCheck++;
	      }
	      $("#orderDetailContianer").append(liItem);	
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

		  return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2)
		}
	</script>
@endsection