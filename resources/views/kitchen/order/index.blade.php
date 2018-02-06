@extends('layouts.blank')

@section('content')
<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
		<img src="{{asset('kitchenImages/logo-img.png')}}">
		</div>
		<h3 class="ui-bar ui-bar-a order_background">Orders <span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
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
			 		<th data-priority="2">Orders</th>
			   		<th>Alias</th> 
			   		<th data-priority="3">Date and Time</th>
			    	<th data-priority="1">Ready</th> 
			    	<th data-priority="5">Delivered</th> <th data-priority="3">Paid</th>
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
				<div class="ui-block-a block_div active">
					<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-1.png')}}">
					</div>
					<span>Orders</span>
					</a>
				</div>
				<div class="ui-block-b">
					<a href = "{{ url('kitchen/kitchen-detail') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-2.png')}}">
					</div>
					<span>kitchen</span>
					</a>
				</div>
				<div class="ui-block-b">
					<a href = "{{ url('kitchen/catering') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
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
	var totallength = 0;
	var url = "{{url('kitchen/order-ready')}}";
	var urldeliver = "{{url('kitchen/order-deliver')}}";
	$(function(){
		$.get("{{url('kitchen/order-detail')}}",
		function(returnedData){
			console.log(returnedData["data"]);
			var count = 18;
			var temp = returnedData["data"];
          	list = temp;
          	//console.log(temp.length);
          	var liItem = "";
          	totallength = temp.length;
          	if(temp.length != 0){
          		totalCount = temp.length;

	          	if(temp.length < count){
	          		count = temp.length
	          	}

	          	totalCount -= 10;
	          	console.log();
	          	for (var i=0;i<count;i++){
	          		if(i>=totallength){
			      		break;
			      	}
	          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
	          		liItem += "<tr>";
	          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
	          		liItem += "<td>"+temp[i]["name"]+"</td>";
	          		liItem += "<td>"+temp[i]["deliver_date"]+' '+temp[i]["deliver_time"]+"</td>";
	          		liItem += "<td>"
			  		if(list[i]["order_ready"] == 0 && list[i]["ready_notifaction"] == 0){
	          			liItem += "<a data-ajax='false'>"
			  				liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
			  		}else if(list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 0) {
	          			liItem += "<a data-ajax='false' href="+url+"/"+list[i]['customer_order_id']+" >"
			  				liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
			  		}else{
	          			liItem += "<a data-ajax='false'>"
			  				liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
			  		}
	          		liItem +="</a></td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0 && list[i]["ready_notifaction"] == 0){
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 0){
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 1){
		          		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
		          		liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
	          		}
	          		else{
		          		liItem += "<a data-ajax='false'>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
	          		}
	          		liItem +="</a></td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 1){
	          			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
	          		}else{
	          			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
	          		}
	          		liItem += "<td>"+time+"</td>";
	          		liItem += "</tr>";
	          	}
          	}else{
          		liItem += "<div class='table-content'>";
	        	liItem += "<p>";
	        	liItem += 'No any Order Available.';
	        	liItem += "</p>";
	        	liItem += "</div>";
          	}
          	$("#orderDetailContianer").append(liItem);
		}); 
	});

	var ajaxCall = function(){
		$.get("{{url('kitchen/order-detail')}}",
		function(returnedData){
			//console.log(returnedData["data"]);
			var count = 18;
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
	          		if(i>totallength){
			      		break;
			      	}
	          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
	          		liItem += "<tr>";
	          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
	          		liItem += "<td>"+temp[i]["name"]+"</td>";
	          		liItem += "<td>"+temp[i]["deliver_date"]+' '+temp[i]["deliver_time"]+"</td>";
	          		liItem += "<td>"
			  		if(list[i]["order_ready"] == 0 && list[i]["ready_notifaction"] == 0){
	          			liItem += "<a data-ajax='false'>"
			  			liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
			  		}else if(list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 0) {
	          			liItem += "<a data-ajax='false' href="+url+"/"+list[i]['customer_order_id']+" >"
			  			liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
			  		}else{
	          			liItem += "<a data-ajax='false'>"
			  			liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
			  		}
	          		liItem +="</a></td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0 && list[i]["ready_notifaction"] == 0){
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 0){
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 1){
		          		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
		          		liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
	          		}
	          		else{
		          		liItem += "<a data-ajax='false'>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
	          		}
	          		liItem +="</a></td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 1){
	          			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
	          		}else{
	          			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
	          		}
	          		liItem += "<td>"+time+"</td>";
	          		liItem += "</tr>";
	          	}
          	}else{
          		liItem += "<div class='table-content'>";
	        	liItem += "<p>";
	        	liItem += 'No any Order Available.';
	        	liItem += "</p>";
	        	liItem += "</div>";
          	}
          	$("#orderDetailContianer").html(liItem);
		}); 
	}

	setInterval(ajaxCall, 10000);

	var tempCount = 18;
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
      //console.log(returnedData["data"]);console.log("len="+len);
      console.log("i="+i);
      console.log("totallength="+totallength);
      	if(i>=totallength){
      		tempCount = 18;
      		break;
      	}
      	if(countCheck>limit){
      		break;
      	}
      	var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
      	liItem += "<tr>";
  		liItem += "<th>"+list[i]["customer_order_id"]+"</th>";
  		liItem += "<td>"+list[i]["name"]+"</td>";
  		liItem += "<td>"+list[i]["deliver_date"]+' '+list[i]["deliver_time"]+"</td>";
  		liItem += "<td>"
  		if(list[i]["order_ready"] == 0 && list[i]["ready_notifaction"] == 0){
  			liItem += "<a data-ajax='false'>"
  			liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
  		}else if(list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 0) {
	        liItem += "<a data-ajax='false' href="+url+"/"+list[i]['customer_order_id']+" >"
  			liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
  		}else{
  			liItem += "<a data-ajax='false'>"
  			liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
  		}
  		liItem +="</a></td>";
  		liItem += "<td>"
  		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0 && list[i]["ready_notifaction"] == 0){
      		liItem += "<a data-ajax='false' >"
      		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
  		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 0){
      		liItem += "<a data-ajax='false' >"
      		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
  		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1 && list[i]["ready_notifaction"] == 1){
      		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
      		liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
  		}
  		else{
      		liItem += "<a data-ajax='false'>"
      		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
  		}
  		liItem +="</a></td>";
  		liItem += "<td>"
  		liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
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