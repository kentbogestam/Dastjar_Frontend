@extends('layouts.blank')

@section('head-scripts') 

@endsection

@section('content')

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<h3 class="ui-bar ui-bar-a order_background"><span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<div id="audio"></div>
		<div class="ready_notification">
			@if ($message = Session::get('success'))
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
				 @if(is_array($message))
		            @foreach ($message as $m)
		                {{ $languageStrings[$m] ?? $m }}
		            @endforeach
		        @else
		            {{ $languageStrings[$message] ?? $message }}
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
			    	<th data-priority="1">{{ __('messages.Comments') }}</th> 
			    	<th data-priority="5">{{ __('messages.Date and Time') }}</th>
			     	<th data-priority="3">{{ __('messages.Started') }}</th>
			      	<th data-priority="3">{{ __('messages.Ready') }}</th>
			     	<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
			     	<th data-priority="1">{{ __('messages.deliveryType') }}</th>
		      	</tr>
		    </thead>
		    <tbody id="orderDetailContianer">
		    	
		    </tbody>
		</table>
	</div>

	@include('includes.kitchen-footer-menu')
	
	<!-- <div data-role="popup" id="popupNotifaction" class="ui-content" style="max-width:280px;padding: 15px;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
	<p style="color: #0c780c;line-height: 22px;margin: 0;">{{ __('messages.Order Ready Notification Send Successfully.') }}</p>
	</div> -->
	<!-- <div data-role="popup" id="popupCloseRight" class="ui-content" style="max-width:280px; padding: 15px;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
	<p style="color: #0c780c;line-height: 22px;margin: 0;">{{ __('messages.Order Ready Successfully.') }}</p>
	</div> -->
	
	@include('includes.kitchen-popup-add-manual-preparation-time')

@endsection

@section('footer-script')
	<script type="text/javascript">
		var list = Array();
		var totalCount = 0;
		var textSpeech = 0;
		var totallength = 0;
		//var url = "{{url('kitchen/order-started')}}";
		var urlReady = "{{url('kitchen/order-readyKitchen')}}";
		// var textSpeachDone = "{{url('kitchen/textSpeachDone')}}";
		var lastOrderId;
		var imageUrl = "{{asset('kitchenImages/right_sign.png')}}";
		// var intervalSpeakText = 0;

		function orderReadyStarted(id, This) {
			$This = $(This);			
			$.get("{{url('kitchen/orderStartedKitchen')}}/"+id,
			function(returnedData){
				// console.log(returnedData["data"]);
				$('body').find('#'+id).attr('src',imageUrl);
				$('body').find('#'+id).parent("a").attr('onclick',' ');
				if(returnedData.order.delivery_type == 3)
				{
					$('body').find('#'+id+'ready').parent("a").attr('onclick','popupOrderAssignDriver('+returnedData.order.order_id+', '+id+')');
				}
				else
				{
					$('body').find('#'+id+'ready').parent("a").attr('onclick','onReady('+id+')');
				}
				$This.closest('tr').removeClass('not-started');

				// Update item as speak
				updateSpeak(id);
			});
		}

		function onReady(id) {		
			$('body').find('#'+id+'ready').attr('src',imageUrl);
			$('body').find('#'+id+'ready').parent("a").attr('onclick',' ');

			$.get("{{url('kitchen/order-readyKitchen')}}/"+id,
			function(returnedData){
				$('body').find('#'+id+'ready').parents("tr").remove();
			});
		}

		$(function(){
			$.get("{{url('kitchen/kitchen-orders')}}",
			function(returnedData){
				// console.log(returnedData);
				textSpeech = returnedData["user"];
				extra_prep_time = returnedData["extra_prep_time"];
				// order_response = returnedData["order_response"];
				var count = 18;
				
				var temp = returnedData["data"];
	          	list = temp;
	          	// console.log(temp.length);
	          	var liItem = "";
	          	var ids = '';
	          	var aString = '';
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
				      	lastOrderId = temp[i]["id"];
				      	(function (i) {
						    setTimeout(function () {
			          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"],extra_prep_time);
			          		var timeOrder = addTimes("00:00::",temp[i]["deliver_time"]);
			          		var clsStatus = temp[i]["order_started"] == 0 ? 'not-started' : '';
			          		liItem += "<tr class='"+clsStatus+"'>";
			          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
			          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
			          		liItem += "<td>"+temp[i]["product_name"]+
			          		"</td>";
			          		if(textSpeech == 1 && temp[i]['is_speak'] == 0){
			          			if(temp[i]["product_description"] != null){
			          				var message = temp[i]["product_quality"]+temp[i]["product_name"]+temp[i]["product_description"];
			          			}else{
			          				var message = temp[i]["product_quality"]+temp[i]["product_name"];
			          			}

			          			speakText(message);
			          			
			          			if(temp[i]["product_description"] != null){
				          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
				          		}else{
				          			liItem += "<td>"+''+"</td>";
				          		}
			          		}else{
			          			// Default 'text to speech' if 'text to speech' is off and not already spoken
			          			if(temp[i]['is_speak'] == 0)
			          			{
			          				speakText("{{ __('messages.kitchenTextToSpeechDefault') }}", 1);

			          				// test("{{ __('messages.kitchenTextToSpeechDefault') }}");
				          			// updateSpeak(temp[i]['id']);
			          			}

			          			if(temp[i]["product_description"] != null){
				          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
				          		}else{
				          			liItem += "<td>"+''+"</td>";
				          		}
			          		}
			          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";

			          		if(temp[i]["order_started"] == 0){
			          			ids = temp[i]['id'];

			          			@if($store->order_response)
			          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='orderReadyStarted("+ids+", this)'>";
			          			@else
			          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='isManualPrepTimeForOrder("+temp[i]['order_id']+", "+ids+", this)'>";
			          			@endif

				          		liItem += "<td >"
				          		liItem += aString
				          		liItem += "<img id='"+ids+"' src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          		
			          		}else{
			          			liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}

			          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
			          			ids = temp[i]['id'];
				          		liItem += "<td>"
				          		liItem += "<a data-ajax='false' href='javascript:void(0)' >"
				          		liItem += "<img id='"+ids+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          	}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
				          		if(temp[i]["delivery_type"] == 3)
				          		{
				          			aString = "<a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", "+temp[i]['id']+")'>";
				          		}
				          		else
				          		{
				          			aString = "<a data-ajax='false' href="+urlReady+"/"+temp[i]['id']+">";
				          		}
				          		liItem += "<td>"
				          		// liItem += "<a data-ajax='false' href="+urlReady+"/"+temp[i]['id']+" >"
				          		liItem += aString
				          		liItem += "<img id='"+temp[i]['id']+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          	}else{
				          		liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}
			          		liItem += "<td>"+time+"</td>";

			          		var deliveryType = '';
			          		if( temp[i]['delivery_type'] == 1 )
			          		{
			          			deliveryType = '{{ __('messages.deliveryOptionDineIn') }}';
			          		}
			          		else if( temp[i]['delivery_type'] == 2 )
			          		{
			          			deliveryType = '{{ __('messages.deliveryOptionTakeAway') }}';
			          		}
			          		else if( temp[i]['delivery_type'] == 3 )
			          		{
			          			deliveryType = '{{ __('messages.deliveryOptionHomeDelivery') }}';
			          			deliveryType += '<br><a href="javascript:void(0)" onclick="getOrderDeliveryAddress('+temp[i]['user_address_id']+')"><span>'+temp[i]['street']+'</span></a>';
			          			deliveryType += "<br><a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", "+temp[i]['id']+", false)'>Assign Driver</a>";
			          		}

			          		liItem += "<td>"+deliveryType+"</td>";
			          		
			          		liItem += "</tr>";
					    	$("#orderDetailContianer").append(liItem);
					    	liItem = null;
						     }, 4000*i);
					    })(i);
		          	}
	          	}else{
	          		/*liItem += "<div class='table-content'>";
		        	liItem += "<p>";
		        	//liItem += '{{ __('messages.Order is not available.') }}';
		        	liItem += "</p>";
		        	liItem += "</div>";*/
	          	}
	          	
	          	$("#orderDetailContianer").append(liItem);
			}); 
		});
		// console.log('lastOrderId'+lastOrderId);
		var ajaxCall = function(){
			$.get("{{url('kitchen/kitchen-orders-new')}}/"+lastOrderId,
			function(returnedData){
				// console.log(returnedData["data"]);
				var count = 18;
				var temp = returnedData["data"];
				textSpeech = returnedData["user"];
				extra_prep_time = returnedData["extra_prep_time"];
				totallength = temp.length;
	          	list = temp;
	          	// console.log(temp.length);
	          	var liItem = "";
	          	var ids = '';
	          	var aString = '';
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
				      	lastOrderId = temp[i]["id"];
				      	
				      	(function (i) {
						    setTimeout(function () {
						    // console.log('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii'+i);
			          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"],extra_prep_time);
			          		var timeOrder = addTimes("00:00::",temp[i]["deliver_time"]);
			          		var clsStatus = temp[i]["order_started"] == 0 ? 'not-started' : '';
			          		liItem += "<tr class='"+clsStatus+"'>";
			          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
			          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
			          		liItem += "<td>"+temp[i]["product_name"]+
			          		"</td>";
			          		if(textSpeech == 1 && temp[i]['is_speak'] == 0){
			          			if(temp[i]["product_description"] != null){
			          				var message = temp[i]["product_quality"]+temp[i]["product_name"]+temp[i]["product_description"];
			          			}else{
			          				var message = temp[i]["product_quality"]+temp[i]["product_name"];
			          			}

			          			speakText(message);
				          		
				          		if(temp[i]["product_description"] != null){
				          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
				          		}else{
				          			liItem += "<td>"+''+"</td>";
				          		}
			          		}else{
			          			// Default 'text to speech' if 'text to speech' is off and not already spoken
								if(temp[i]['is_speak'] == 0)
								{
									speakText("{{ __('messages.kitchenTextToSpeechDefault') }}", 1);
								}

			          			if(temp[i]["product_description"] != null){
				          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
				          		}else{
				          			liItem += "<td>"+''+"</td>";
				          		}
			          		}
			          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";

			          		if(temp[i]["order_started"] == 0){
				          		ids = temp[i]['id'];

				          		@if($store->order_response)
			          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='orderReadyStarted("+ids+", this)'>";
			          			@else
			          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='isManualPrepTimeForOrder("+temp[i]['order_id']+", "+ids+", this)'>";
			          			@endif

				          		liItem += "<td >"
				          		liItem += aString
				          		liItem += "<img id='"+ids+"' src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}else{
			          			liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}

			          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
				          		ids = temp[i]['id'];
				          		liItem += "<td>"
				          		liItem += "<a data-ajax='false' href='javascript:void(0)' >"
				          		liItem += "<img id='"+ids+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          	}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
				          		if(temp[i]["delivery_type"] == 3)
				          		{
				          			aString = "<a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", "+temp[i]['id']+")'>";
				          		}
				          		else
				          		{
				          			aString = "<a data-ajax='false' href="+urlReady+"/"+temp[i]['id']+">";
				          		}
				          		liItem += "<td>"
				          		liItem += aString
				          		liItem += "<img id='"+temp[i]['id']+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
				          		liItem +="</a></td>";
				          	}else{
				          		liItem += "<td>"
				          		liItem += "<a>"
				          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
				          		liItem +="</a></td>";
			          		}
			          		liItem += "<td>"+time+"</td>";

			          		var deliveryType = '';
			          		if( temp[i]['delivery_type'] == 1 )
			          		{
			          			deliveryType = '{{ __('messages.deliveryOptionDineIn') }}';
			          		}
			          		else if( temp[i]['delivery_type'] == 2 )
			          		{
			          			deliveryType = '{{ __('messages.deliveryOptionTakeAway') }}';
			          		}
			          		else if( temp[i]['delivery_type'] == 3 )
			          		{
			          			deliveryType = '{{ __('messages.deliveryOptionHomeDelivery') }}';
			          			deliveryType += '<br><a href="javascript:void(0)" onclick="getOrderDeliveryAddress('+temp[i]['user_address_id']+')"><span>'+temp[i]['street']+'</span></a>';
			          			deliveryType += "<br><a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", "+temp[i]['id']+", false)'>Assign Driver</a>";
			          		}

			          		liItem += "<td>"+deliveryType+"</td>";

			          		liItem += "</tr>";
					    	$("#orderDetailContianer").append(liItem);
					    	var liItem = "";
						     }, 4000*i);
					    })(i);
		          	}
	          	}else{
	          		liItem += "<div class='table-content'>";
		        	liItem += "<p>";
		        	//liItem += '{{ __('messages.Order is not available.') }}';
		        	liItem += "</p>";
		        	liItem += "</div>";
	          	}
	          	//$("#orderDetailContianer").append(liItem);
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
			        // console.log(list);
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
	        var ids = '';
	        var aString = '';
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
	      // console.log("totallength="+totallength);
	      	if(i>=totallength){
	      		tempCount = 10;
	      		break;
	      	}
	      	if(countCheck>limit){
	      		break;
	      	}
	      	// console.log('iiiiiiiiissssssssssssssssss'+i);
	      	 (function (i) {
			    setTimeout(function () {
			      	var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
			      	var timeOrder = addTimes("00:00::",list[i]["deliver_time"]);
		      		liItem += "<tr>";
		      		liItem += "<th>"+list[i]["customer_order_id"]+"</th>";
		      		liItem += "<td>"+list[i]["product_quality"]+"</td>";
		      		liItem += "<td>"+list[i]["product_name"]+"</td>";
		      		if(textSpeech == 1 && list[i]['is_speak'] == 0){
		      			if(list[i]["product_description"] != null){
	          				var message = list[i]["product_quality"]+list[i]["product_name"]+list[i]["product_description"];
	          			}else{
	          				var message = list[i]["product_quality"]+list[i]["product_name"];
	          			}

	          			speakText(message);
		          		
		          		if(list[i]["product_description"] != null){
		          			liItem += "<td>"+list[i]["product_description"]+"</td>";
		          		}else{
		          			liItem += "<td>"+''+"</td>";
		          		}
	          		}else{
	          			// Default 'text to speech' if 'text to speech' is off and not already spoken
						if(list[i]['is_speak'] == 0)
						{
							speakText("{{ __('messages.kitchenTextToSpeechDefault') }}", 1);
						}

	          			if(list[i]["product_description"] != null){
		          			liItem += "<td>"+list[i]["product_description"]+"</td>";
		          		}else{
		          			liItem += "<td>"+''+"</td>";
		          		}
	          		}
		      		liItem += "<td>"+list[i]["deliver_date"]+' '+timeOrder+"</td>";
		      		if(list[i]["order_started"] == 0){
		      			ids = list[i]['id'];

		      			@if($store->order_response)
	          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='orderReadyStarted("+ids+", this)'>";
	          			@else
	          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='isManualPrepTimeForOrder("+temp[i]['order_id']+", "+ids+", this)'>";
	          			@endif

		          		liItem += "<td >"
		          		liItem += aString
		          		liItem += "<img id='"+ids+"' src='{{asset('kitchenImages/subs_sign.png')}}'>"
		          		liItem +="</a></td>";
		      		}else{
		      			liItem += "<td>"
		          		liItem += "<a>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
		          		liItem +="</a></td>";
		      		}
		      		if(list[i]["order_ready"] == 0 && list[i]["order_started"] == 0){
		          		ids = list[i]['id'];
		          		liItem += "<td>"
		          		liItem += "<a data-ajax='false' >"
		          		liItem += "<img id='"+ids+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
		          		liItem +="</a></td>";
		          	}else if(list[i]["order_ready"] == 0 && list[i]["order_started"] == 1){
		          		if(list[i]["delivery_type"] == 3)
		          		{
		          			aString = "<a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+list[i]['order_id']+", "+list[i]['id']+")'>";
		          		}
		          		else
		          		{
		          			aString = "<a data-ajax='false' href="+urlReady+"/"+list[i]['id']+">";
		          		}
		          		liItem += "<td>"
		          		liItem += aString
		          		liItem += "<img id='"+list[i]['id']+"ready' src='{{asset('kitchenImages/subs_sign.png')}}'>"
		          		liItem +="</a></td>";
		          	}else{
		          		liItem += "<td>"
		          		liItem += "<a>"
		          		liItem += "<img src='{{asset('kitchenImages/right_sign.png')}}'>"
		          		liItem +="</a></td>";
		      		}
		      		liItem += "<td>"+time+"</td>";

		      		var deliveryType = '';
	          		if( temp[i]['delivery_type'] == 1 )
	          		{
	          			deliveryType = '{{ __('messages.deliveryOptionDineIn') }}';
	          		}
	          		else if( temp[i]['delivery_type'] == 2 )
	          		{
	          			deliveryType = '{{ __('messages.deliveryOptionTakeAway') }}';
	          		}
	          		else if( temp[i]['delivery_type'] == 3 )
	          		{
	          			deliveryType = '{{ __('messages.deliveryOptionHomeDelivery') }}';
	          			deliveryType += '<br><a href="javascript:void(0)" onclick="getOrderDeliveryAddress('+temp[i]['user_address_id']+')"><span>'+temp[i]['street']+'</span></a>';
	          			deliveryType += "<br><a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", "+temp[i]['id']+", false)'>Assign Driver</a>";
	          		}

	          		liItem += "<td>"+deliveryType+"</td>";

		      		liItem += "</tr>";
			      	countCheck++;
			      	$("#orderDetailContianer").append(liItem);
			      	var liItem = "";
			     }, 4000*i);
		    })(i);
	      }
	      $("#orderDetailContianer").append(liItem);	
		}

		function addTimes (startTime, endTime, extra_prep_time) {
		  var times = [ 0, 0, 0 ];
		  var max = times.length;

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
	</script>
@endsection