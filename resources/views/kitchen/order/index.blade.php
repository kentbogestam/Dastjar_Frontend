@extends('layouts.blank')

@section('content')
	<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<h3 class="ui-bar ui-bar-a order_background"><span>{{$storeName}}</span></h3>
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
			 		<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th>{{ __('messages.Alias') }}</th> 
			   		<th data-priority="3">{{ __('messages.Date and Time') }}</th>
			   		@if( !Session::has('subscribedPlans.kitchen') )
						<th data-priority="3">{{ __('messages.Started') }}</th>
			      		<th data-priority="1">{{ __('messages.Ready') }}</th> 
			      	@else
			      		<th data-priority="1">{{ __('messages.Ready') }}</th> 
					@endif
			    	<th data-priority="5">{{ __('messages.Delivered') }}</th>
			    	<th data-priority="3">{{ __('messages.Paid') }}</th>
			     	<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
			    </tr>
			</thead>
		    <tbody id="orderDetailContianer">
		    	

		    </tbody>
		</table>
	</div>
	
	@include('includes.kitchen-footer-menu')

	<div data-role="popup" id="popupCloseRight" class="ui-content" style="max-width:100%;border: none;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" style="background-color:#000;border-color: #000;">Close</a>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
			<thead>
				<tr class="ui-bar-d">
					<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th>{{ __('messages.Amount') }}</th> 
			   		<th data-priority="3">{{ __('messages.Product') }}</th>
			    	<th data-priority="1">{{ __('messages.Comments') }}</th> 
			    </tr>
			</thead>
			<tbody id="specificOrderDetailContianer">
				<tr>
					

				</tr> 
			</tbody>
		</table>
	</div>
@endsection

@section('footer-script')
<script type="text/javascript">
	var list = Array();
	var totalCount = 0;
	var totallength = 0;
	var storeId = "{{Session::get('storeId')}}";
	var url = "{{url('kitchen/order-ready')}}";
	var urldeliver = "{{url('kitchen/order-deliver')}}";
	var urlReady = "{{url('kitchen/order-readyKitchen')}}";
	var imageUrl = "{{asset('kitchenImages/right_sign.png')}}";

	$(function(){
		$.get("{{url('api/v1/kitchen/order-detail')}}/" + storeId,
		function(returnedData){
			// console.log(returnedData["data"]);
			var count = 18;
			var temp = returnedData["data"];
			extra_prep_time = returnedData["extra_prep_time"];
          	list = temp;
          	// console.log(temp.length);
          	var liItem = "";
          	totallength = temp.length;
          	if(temp.length != 0){
          		totalCount = temp.length;

	          	if(temp.length < count){
	          		count = temp.length
	          	}

	          	totalCount -= 10;
	          	// console.log();
	          	for (var i=0;i<count;i++){
	          		if(i>=totallength){
			      		break;
			      	}
	          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"],extra_prep_time);
	          		var timeOrder = addTimes("00:00:00",temp[i]["deliver_time"]);
	          		var orderIdSpecific = temp[i]["order_id"] ;
	          		var orderStatus = temp[i]["order_started"] == 0 ? 'new' : ''; // Add class 'new' until order 'started'

	          		liItem += "<tr class='"+orderStatus+"'>";
	          		liItem += "<th>";
	          		liItem += "<a href='javascript:getList("+orderIdSpecific+")' data-rel='popup'>";
	          		liItem += temp[i]["customer_order_id"]; 
	          		liItem += "</a></th>";

	          		if (temp[i]["name"] == null){
	          			temp[i]["name"] = "";
	          		}

	          		liItem += "<td>"+temp[i]["name"]+"</td>";
	          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";

	          		// Add additional column if 'kitchen' module not subscribed
	          		@if( !Session::has('subscribedPlans.kitchen') )
	          			// Order Started
	          			if(temp[i]["order_started"] == 0){
		          			ids = temp[i]['order_details_id'];
			          		liItem += "<td>"
			          		liItem += "<a data-ajax='false' href='javascript:void(0)'  onclick='orderReadyStarted("+ids+", this)'>"
			          		liItem += "<img id='"+ids+"' src='{{asset('kitchenImages/subs_sign.png')}}'>"
			          		liItem +="</a></td>";
			          		
		          		}else{
		          			liItem += "<td>"
			          		liItem += "<a>"
			          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
			          		liItem +="</a></td>";
		          		}

		          		// Order Ready
		          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
		          			ids = temp[i]['order_details_id'];
			          		liItem += "<td>"
			          		liItem += "<a data-ajax='false' href='javascript:void(0)' >"
			          		liItem += "<img id='"+ids+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
			          		liItem +="</a></td>";
			          	}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
			          		liItem += "<td>"
			          		liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['order_details_id']+" >"
			          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
			          		liItem +="</a></td>";

			          	}else{
			          		liItem += "<td>"
			          		liItem += "<a>"
			          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
			          		liItem +="</a></td>";
		          		}
		          	@else
		          		liItem += "<td>"
				  		if(list[i]["order_ready"] == 0){
		          			liItem += "<a data-ajax='false'>"
				  				liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				  		}else{
		          			liItem += "<a data-ajax='false'>"
				  				liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				  		}
		          		liItem +="</a></td>";
	          		@endif
	          		
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0){
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1){
		          		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
		          		liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
	          		}
	          		else{
		          		liItem += "<a data-ajax='false'>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
	          		}
	          		liItem +="</a></td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 1 || list[i]["online_paid"] == 1 || list[i]["online_paid"] == 3){
	          			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
	          		}else if(list[i]["online_paid"] == 0){
	          			liItem += "<input class='no_check' type='button' value='Pay Manual' data-role='none' onclick='orderPayManually("+list[i]["order_id"]+", this);'>"
	          		}else{
	          			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
	          		}

	          		liItem += "<td>"+time+"</td>";
	          		liItem += "</tr>";
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

	function getList(orderId){
		var liItem = "";
		$.get("{{url('api/v1/kitchen/orderSpecificOdrderDetail')}}/"+orderId,
		function(returnedData){
			// console.log(returnedData["data"]);
			var temp = returnedData["data"];
			for (var i=0;i<temp.length;i++){
				liItem += "<tr>";
				liItem += "<td>"+temp[i]["customer_order_id"]+"</td>";
				liItem += "<td>"+temp[i]["product_quality"]+"</td>";
				liItem += "<td>"+temp[i]["product_name"]+"</td>";
				if(temp[i]["product_description"] != null){
					liItem += "<td>"+temp[i]["product_description"]+"</td>";
				}else{
					liItem += "<td>"+' '+"</td>";
				}
				liItem += "</tr>";
			}
			$("#specificOrderDetailContianer").html(liItem);
			$("#popupCloseRight").popup("open");
			var liItem = "";
		});
	}

	var ajaxCall = function(){
		$.get("{{url('api/v1/kitchen/order-detail')}}/" + storeId,
		function(returnedData){
			// console.log(returnedData["data"]);
			var count = 18;
			var temp = returnedData["data"];
			extra_prep_time = returnedData["extra_prep_time"];
          	list = temp;
          	// console.log(temp.length);
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
	          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"],extra_prep_time);
	          		var timeOrder = addTimes("00:00:00",temp[i]["deliver_time"]);
	          		var orderIdSpecific = temp[i]["order_id"] ;
	          		var orderStatus = temp[i]["order_started"] == 0 ? 'new' : ''; // Add class 'new' until order 'started'

	          		liItem += "<tr class='"+orderStatus+"'>";
	          		liItem += "<th>"
	          		liItem += "<a href='javascript:getList("+orderIdSpecific+")' data-rel='popup'>"
	          		liItem += temp[i]["customer_order_id"]
	          		liItem += "</a></th>";

	          		if (temp[i]["name"] == null){
	          			temp[i]["name"] = "";
	          		}
	          		
	          		liItem += "<td>"+temp[i]["name"]+"</td>";
	          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";

	          		// Add additional column if 'kitchen' module not subscribed
	          		@if( !Session::has('subscribedPlans.kitchen') )
	          			// Order Started
	          			if(temp[i]["order_started"] == 0){
		          			ids = temp[i]['order_details_id'];
			          		liItem += "<td>"
			          		liItem += "<a data-ajax='false' href='javascript:void(0)'  onclick='orderReadyStarted("+ids+", this)'>"
			          		liItem += "<img id='"+ids+"' src='{{asset('kitchenImages/subs_sign.png')}}'>"
			          		liItem +="</a></td>";
			          		
		          		}else{
		          			liItem += "<td>"
			          		liItem += "<a>"
			          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
			          		liItem +="</a></td>";
		          		}

		          		// Order Ready
		          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
		          			ids = temp[i]['order_details_id'];
			          		liItem += "<td>"
			          		liItem += "<a data-ajax='false' href='javascript:void(0)' >"
			          		liItem += "<img id='"+ids+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
			          		liItem +="</a></td>";
			          	}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
			          		liItem += "<td>"
			          		liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['order_details_id']+" >"
			          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
			          		liItem +="</a></td>";

			          	}else{
			          		liItem += "<td>"
			          		liItem += "<a>"
			          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
			          		liItem +="</a></td>";
		          		}
		          	@else
		          		liItem += "<td>"
				  		if(list[i]["order_ready"] == 0){
		          			liItem += "<a data-ajax='false'>"
				  			liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
				  		}else{
		          			liItem += "<a data-ajax='false'>"
				  			liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				  		}
		          		liItem +="</a></td>";
	          		@endif
	          		
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0){
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1){
		          		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
		          		liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
	          		}
	          		else{
		          		liItem += "<a data-ajax='false'>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
	          		}
	          		liItem +="</a></td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 1 || list[i]["online_paid"] == 1 || list[i]["online_paid"] == 3){
	          			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
	          		}else if(list[i]["online_paid"] == 0){
	          			liItem += "<input class='no_check' type='button' value='Pay Manual' data-role='none' onclick='orderPayManually("+list[i]["order_id"]+", this);'>"
	          		}else{
	          			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
	          		}

	          		liItem += "<td>"+time+"</td>";
	          		liItem += "</tr>";
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
		        // console.log(list);
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
      // console.log(returnedData["data"]);console.log("len="+len);
      // console.log("i="+i);
      // console.log("totallength="+totallength);
      	if(i>=totallength){
      		tempCount = 18;
      		break;
      	}
      	if(countCheck>limit){
      		break;
      	}
      	var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
      	var timeOrder = addTimes("00:00:00",list[i]["deliver_time"]);
      	var orderIdSpecific = list[i]["order_id"] ;
      	var orderStatus = temp[i]["order_started"] == 0 ? 'new' : ''; // Add class 'new' until order 'started'

      	liItem += "<tr class='"+orderStatus+"'>";
  		liItem += "<th>"
  		liItem += "<a href='javascript:getList("+orderIdSpecific+")' data-rel='popup'>"
  		liItem += temp[i]["customer_order_id"]
  		liItem += "</a></th>";
  		liItem += "<td>"+list[i]["name"]+"</td>";
  		liItem += "<td>"+list[i]["deliver_date"]+' '+timeOrder+"</td>";
  		
  		// Add additional column if 'kitchen' module not subscribed
  		@if( !Session::has('subscribedPlans.kitchen') )
  			// Order Started
  			if(temp[i]["order_started"] == 0){
      			ids = temp[i]['order_details_id'];
          		liItem += "<td>"
          		liItem += "<a data-ajax='false' href='javascript:void(0)'  onclick='orderReadyStarted("+ids+", this)'>"
          		liItem += "<img id='"+ids+"' src='{{asset('kitchenImages/subs_sign.png')}}'>"
          		liItem +="</a></td>";
          		
      		}else{
      			liItem += "<td>"
          		liItem += "<a>"
          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
          		liItem +="</a></td>";
      		}

      		// Order Ready
      		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
      			ids = temp[i]['order_details_id'];
          		liItem += "<td>"
          		liItem += "<a data-ajax='false' href='javascript:void(0)' >"
          		liItem += "<img id='"+ids+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
          		liItem +="</a></td>";
          	}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
          		liItem += "<td>"
          		liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['order_details_id']+" >"
          		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
          		liItem +="</a></td>";

          	}else{
          		liItem += "<td>"
          		liItem += "<a>"
          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
          		liItem +="</a></td>";
      		}
      	@else
      		liItem += "<td>"
	  		if(list[i]["order_ready"] == 0){
	  			liItem += "<a data-ajax='false'>"
	  			liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
	  		}else{
	  			liItem += "<a data-ajax='false'>"
	  			liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
	  		}
	  		liItem +="</a></td>";
  		@endif
  		
  		liItem += "<td>"
  		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0 ){
      		liItem += "<a data-ajax='false' >"
      		liItem += "<img src='{{asset('kitchenImages/subs_sign.png')}}'>"
  		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1){
      		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
      		liItem += "<img src='{{asset('kitchenImages/yellow_right_sign.png')}}'>"
  		}
  		else{
      		liItem += "<a data-ajax='false'>"
      		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
  		}
  		liItem +="</a></td>";
  		liItem += "<td>"
  		if(list[i]["paid"] == 1 || list[i]["online_paid"] == 1 || list[i]["online_paid"] == 3){
  			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
  		}else if(list[i]["online_paid"] == 0){
  			liItem += "<input class='no_check' type='button' value='Pay Manual' data-role='none' onclick='orderPayManually("+list[i]["order_id"]+", this);'>"
  		}else{
  			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
  		}

  		liItem += "<td>"+time+"</td>";
  		liItem += "</tr>";
      	countCheck++;
      }
      $("#orderDetailContianer").append(liItem);	
	}

	function addTimes (startTime, endTime, extra_prep_time) {
	  var times = [ 0, 0, 0 ]
	  var max = times.length

	  var a = (startTime || '').split(':')
	  var b = (endTime || '').split(':')
	  var c = (extra_prep_time || '').split(':')

	  // normalize time values
	  for (var i = 0; i < max; i++) {
	    a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i])
	    b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i])
	    c[i] = isNaN(parseInt(c[i])) ? 0 : parseInt(c[i])
	  }

	  // store time values
	  for (var i = 0; i < max; i++) {
	    times[i] = a[i] + b[i] + c[i]
	  }

	  var hours = times[0]
	  var minutes = times[1]
	  var seconds = times[2]

	  if (seconds > 59) {
	    var m = (seconds / 60) << 0
	    minutes += m
	    seconds -= 60 * m
	  }

	  if (minutes > 59) {
	    var h = (minutes / 60) << 0
	    hours += h
	    minutes -= 60 * h
	  }

	  return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2)
	}

	// Update the status of 'order_started' to 1
	function orderReadyStarted(id, This) {
		$This = $(This);			
		$.get("{{url('kitchen/orderStartedKitchen')}}/"+id,
		function(returnedData){
			// console.log(returnedData["data"]);
			$('body').find('#'+id).attr('src',imageUrl);
			$('body').find('#'+id).parent("a").attr('onclick',' ');
			$('body').find('#'+id+'ready').parent("a").attr('onclick','onReady('+id+')');
			$This.closest('tr').removeClass('not-started');
		});
	}

	// Update the status of 'order_ready' to 1
	function onReady(id) {		
		$('body').find('#'+id+'ready').attr('src',imageUrl);
		$('body').find('#'+id+'ready').parent("a").attr('onclick',' ');

		$.get("{{url('kitchen/order-readyKitchen')}}/"+id,
		function(returnedData){
			// console.log(returnedData["data"]);
			$('body').find('#'+id+'ready').parents("tr").remove();
			if(returnedData["status"] == 'ready'){
				$("#popupCloseRight").popup("open");
			}else{
				$("#popupNotifaction").popup("open");	
			}
		});

	}

	// Make payment manually as cash at store;
	function orderPayManually(id, This)
	{
		$This = $(This);

		$.get("{{url('kitchen/order-pay-manually')}}/"+id,
		function(returnedData){
			console.log(returnedData);
			if(returnedData["status"])
			{
				$This.val('Yes');
				$This.removeClass('no_check').addClass('yes_check');
				$This.removeAttr('onclick');
			}
			else
			{
				alert('Something went wrong!');
			}
		});
	}
</script>

@endsection