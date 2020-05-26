@extends('layouts.blank')

@section('content')

<style>
	.news{
		background-color: #87ceebbf !important;
	}
</style>

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
		                {{ $languageStrings[$m] ?? $m }}
		            @endforeach
		        @else
		            {{ $languageStrings[$message] ?? $message }}
		        @endif
		    </div>
			@endif
		</div>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow ui-responsive table_size" >
			<thead>
			 	<tr class="ui-bar-d">
			 		<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th>{{ __('messages.Alias') }}</th> 
			   		<th data-priority="3">{{ __('messages.Date and Time') }}</th>
			   		@if( !Session::has('subscribedPlans.kitchen') )
						<th data-priority="3">{{ __('messages.Started') }}</th>
					    <th data-priority="1">{{ __('messages.Ready') }}</th>
					@endif
			    	<th data-priority="5">{{ __('messages.Delivered') }}</th>
			    	<th data-priority="3" width="15%">{{ __('messages.Paid') }}</th>
			     	<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
			     	<th data-priority="1">{{ __('messages.deliveryType') }}</th>
			    </tr>
			</thead>
		    <tbody id="orderDetailContianer"></tbody>
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
			   		<th class="qty-loyalty-offer">{{ __('messages.qtyLoyaltyOffer') }}</th>
			   		<th data-priority="3">{{ __('messages.Product') }}</th>
			    	<th data-priority="1">{{ __('messages.Comments') }}</th> 
			    </tr>
			</thead>
			<tbody id="specificOrderDetailContianer"></tbody>
		</table>
	</div>

	@include('includes.kitchen-popup-add-manual-preparation-time')
@endsection

@section('footer-script')
<script type="text/javascript">
	var list = Array();
	var totalCount = 0;
	var totallength = 0;
	var storeId = "{{Session::get('kitchenStoreId')}}";
	var urldeliver = "{{url('kitchen/order-deliver')}}";
	var urlReadyOrder = "{{url('kitchen/make-order-ready')}}";
	var imageUrlLoad = "{{asset('kitchenImages/red_blink_image.png')}}";
	var speakOrderItemList = [];
	var driverapp = "{{ Session::get('driverapp') }}";

	$(function(){
		ajaxCall();
	});

    $('body').on('mouseover', '.image_clicked', function(){
        $(this).css("padding","2px");
    });
    $('body').on('mouseout', '.image_clicked', function(){
        $(this).css("padding","0px");
    });
    $('body').on('click', '.image_clicked', function(){
        $(this).css("padding","0px");
    });
    
	function getList(orderId){
		var liItem = "";
		$.get("{{url('api/v1/kitchen/orderSpecificOdrderDetail')}}/"+orderId,
		function(returnedData){
			var temp = returnedData["data"];
			var isQuantityFree = 0;

			for (var i=0;i<temp.length;i++){
				liItem += "<tr>";
				liItem += "<td>"+temp[i]["customer_order_id"]+"</td>";
				liItem += "<td>"+temp[i]["product_quality"]+"</td>";
				liItem += "<td class='qty-loyalty-offer'>"+temp[i]["quantity_free"]+"</td>";
				liItem += "<td>"+temp[i]["product_name"]+"</td>";
				if(temp[i]["product_description"] != null){
					liItem += "<td>"+temp[i]["product_description"]+"</td>";
				}else{
					liItem += "<td>"+' '+"</td>";
				}
				liItem += "</tr>";

				if(temp[i]["quantity_free"])
				{
					isQuantityFree = 1;
				}
			}
			$("#specificOrderDetailContianer").html(liItem);

			// Show/hide loyalty column in 'order detail' popup
			if(!isQuantityFree)
			{
				$('.qty-loyalty-offer').hide();
			}
			else
			{
				$('.qty-loyalty-offer').show();
			}

			$("#popupCloseRight").popup("open");
			var liItem = "";
		});
	}

	var ajaxCall = function(){
		$.get("{{url('api/v1/kitchen/order-detail')}}/" + storeId,
		function(returnedData){
			var count = 18;
			var temp = returnedData["data"];
			var orderItems = returnedData['orderItems'];
			extra_prep_time = returnedData["extra_prep_time"];
          	list = temp;
          	var liItem = "";
          	var aString = '';
          	totallength = temp.length;
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

			      	if(temp[i]['order_response'])
			      	{
			      		var time = addTimes(temp[i]['order_delivery_time'],temp[i]['deliver_time'],extra_prep_time);
			      	}
			      	else
			      	{
			      		var time = addTimes(temp[i]['deliver_time'], temp[i]['extra_prep_time']);
			      	}
                    
//                    blink image time caculator getting time based on pick up and current time
                    var today = new Date(); 
                    old_hour = time.substr(0,2);
                    old_mins = time.substr(3,5);
                    var old_time = parseInt(old_hour)*60 + parseInt(old_mins);
                    var new_time = parseInt(today.getHours())*60 + parseInt(today.getMinutes())
                    
	          		var timeOrder = addTimes("00:00:00",temp[i]["deliver_time"]);
	          		var orderIdSpecific = temp[i]["order_id"] ;

	          		if(temp[i]["order_type"] == "eat_now"){
	          			var orderStatus = temp[i]["order_started"] == 0 ? 'new' : ''; // Add class 'new' until order 'started'
	          		}else{
	          			var orderStatus = temp[i]["order_started"] == 0 ? 'news' : '';// Add class 'news' until order 'started'
	          		}

	          		if(temp[i]['orderDeliveryStatus'] == '0')
	          		{
	          			orderStatus += orderStatus.length ? ' not-accepted' : 'not-accepted';
	          		}

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
		          			ids = temp[i]['order_id'];

		          			if(temp[i]['order_response'])
		          			{
		          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='startOrder("+ids+", this)'>";
		          			}
		          			else
		          			{
		          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='isManualPrepTimeForOrder("+ids+", false, this)'>";
		          			}

			          		liItem += "<td>"
			          		liItem += aString
			          		liItem += "<img id='"+ids+"' class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>"
			          		liItem +="</a></td>";
		          		}else{
		          			liItem += "<td>"
			          		liItem += "<a>"
			          		liItem +="</a>";
			          		liItem +="</td>";
		          		}

		          		// Order Ready
		          		if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 0){
		          			ids = temp[i]['order_id'];
			          		liItem += "<td class='ready_class'>";
			          		liItem +="</td>";
			          	}else if(temp[i]["order_ready"] == 0 && temp[i]["order_started"] == 1){
			          		// flash image based on pick up time will
			          		if(old_time < new_time){
			                    var flashImg = "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.gif')}}'>";
			                }else{
			                    var flashImg = "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>";
			                }

			          		if(temp[i]["delivery_type"] == 3 && driverapp)
			          		{
		          				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", false)'>";
			          		}
		          			else
		          			{
                                aString = "<a data-ajax='false' href="+urlReadyOrder+"/"+temp[i]['order_id']+">";
		          			}

		          			aString += flashImg;

			          		liItem += "<td>"
			          		liItem += aString
			          		liItem +="</a>";
			          		liItem +="</td>";
			          	}else{
			          		liItem += "<td>"
			          		liItem += "<a>"
			          		liItem +="</a></td>";
		          		}
	          		@endif
	          		
	          		liItem += "<td>"
	          		if( (list[i]["paid"] == 0 && list[i]["order_ready"] == 0) || (list[i]["delivery_type"] == 3 && driverapp) ){
	          		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1){
		          		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
                        if(old_time < new_time){
                            liItem += "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.gif')}}'>"
                        }else{
                            liItem += "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>"
                        }
		          		liItem += "</a>"
	          		}
	          		else{
		          		liItem += "<a data-ajax='false'>"
		          		liItem += "</a>"
	          		}
	          		liItem +="</td>";
	          		liItem += "<td>"
	          		if(list[i]["paid"] == 1 || list[i]["online_paid"] == 1 || list[i]["online_paid"] == 3){
	          			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
	          		}else if(list[i]["online_paid"] == 0){
	          			liItem += "<input class='no_check' type='button' value='Pay Manual' data-role='none' onclick='orderPayManually("+list[i]["order_id"]+", this);'>";

	          			// Check if discount applied on order
	          			if( list[i]["discount_id"] )
	          			{
	          				discountAmount = (list[i]["order_total"] * list[i]['discount_value']) / 100;
	          				liItem += '<div class="show-total"><strong><span class="discounted-total">'+(list[i]["order_total"] - discountAmount).toFixed(2)+' (SEK)</span></strong></div>';
	          			}
	          		}else{
	          			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
	          		}

	          		// If order belongs to 'Loyalty'
	          		if(list[i]['cntLoyaltyUsed'])
	          		{
	          			liItem += '<div class="show-total"><strong>Loyalty</strong></div>';
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
	          			deliveryType = '<span>{{ __('messages.deliveryOptionHomeDelivery') }}</span>';
	          			deliveryType += '<br><a href="javascript:void(0)" onclick="getOrderDeliveryAddress('+temp[i]['user_address_id']+')"><span>'+temp[i]['street']+'</span></a>';

	          			if(driverapp)
	          			{
	          				deliveryType += "<br><a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", false, false)'>Assign Driver</a>";
	          			}
	          		}

	          		liItem += "<td>"+deliveryType+"</td>";

	          		liItem += "</tr>";
	          	}

	          	// Speech text
	          	@if( !Session::has('subscribedPlans.kitchen') )
	          		if(orderItems.length)
	          		{
	          			var message = [];

	          			for(var j = 0; j < orderItems.length; j++)
	          			{
	          				if(speakOrderItemList.indexOf(orderItems[j]['id']) == -1)
	          				{
	          					speakOrderItemList.push(orderItems[j]['id']);

		          				@if($textSpeech)
		          					if(orderItems[j]["product_description"] != null){
				          				message[j] = orderItems[j]["product_quality"]+orderItems[j]["product_name"]+orderItems[j]["product_description"];
				          			}else{
				          				message[j] = orderItems[j]["product_quality"]+orderItems[j]["product_name"];
				          			}

				          			// speakText(message);
				          		@else
				          			speakText("{{ __('messages.kitchenTextToSpeechDefault') }}", 1);
		          				@endif
	          				}
	          			}

	          			// Loop speech
	          			if(message.length)
	          			{
	          				var k = 0;
		          			speakTextInterval = setInterval(function() {
		          				speakText(message[k]);
		          				k++;

		          				if(k >= j)
		          				{
		          					clearInterval(speakTextInterval);
		          				}
		          			}, 4000);
	          			}
	          		}
	          	@endif
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
      	if(i>=totallength){
      		tempCount = 18;
      		break;
      	}
      	if(countCheck>limit){
      		break;
      	}
      	if(list[i]['order_response'])
      	{
      		var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
      	}
      	else
      	{
      		var time = addTimes(list[i]["deliver_time"], list[i]['extra_prep_time']);
      	}

//      blink image time caculator getting time based on pick up and current time
        var today = new Date(); 
        old_hour = time.substr(0,2);
        old_mins = time.substr(3,5);
        var old_time = parseInt(old_hour)*60 + parseInt(old_mins);
        var new_time = parseInt(today.getHours())*60 + parseInt(today.getMinutes())
      	var timeOrder = addTimes("00:00:00",list[i]["deliver_time"]);
      	var orderIdSpecific = list[i]["order_id"] ;

      	if(list[i]["order_type"] == "eat_now"){
  			var orderStatus = list[i]["order_started"] == 0 ? 'new' : ''; // Add class 'new' until order 'started'
  		}else{
  			var orderStatus = list[i]["order_started"] == 0 ? 'news' : '';// Add class 'news' until order 'started'
  		}

      	if(list[i]['orderDeliveryStatus'] == '0')
  		{
  			orderStatus += orderStatus.length ? ' not-accepted' : 'not-accepted';
  		}

      	liItem += "<tr class='"+orderStatus+"'>";
  		liItem += "<th>"
  		liItem += "<a href='javascript:getList("+orderIdSpecific+")' data-rel='popup'>"
  		liItem += list[i]["customer_order_id"]
  		liItem += "</a></th>";
  		liItem += "<td>"+list[i]["name"]+"</td>";
  		liItem += "<td>"+list[i]["deliver_date"]+' '+timeOrder+"</td>";
  		
  		// Add additional column if 'kitchen' module not subscribed
  		@if( !Session::has('subscribedPlans.kitchen') )
  			// Order Started
  			if(list[i]["order_started"] == 0){
  				// Speech text
  				@if($textSpeech)
      				speakText("{{ __('messages.kitchenTextToSpeechDefault') }}");
      			@else
      				speakText("{{ __('messages.kitchenTextToSpeechDefault') }}", 1);
      			@endif

  				//
      			ids = list[i]['order_id'];

      			if(list[i]['order_response'])
      			{
	  				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='startOrder("+ids+", this)'>";
      			}
	  			else
	  			{
	  				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='isManualPrepTimeForOrder("+ids+", false, this)'>";
	  			}

          		liItem += "<td>"
          		liItem += aString
          		liItem += "<img id='"+ids+"' class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>"
          		liItem +="</a></td>";
      		}else{
      			liItem += "<td>"
          		liItem += "<a>"
          		liItem +="</a></td>";
      		}

      		// Order Ready
      		if(list[i]["order_ready"] == 0 && list[i]["order_started"] == 0){
      			ids = list[i]['order_id'];
          		liItem += "<td class='ready_class'>"
          		liItem +="</td>";
          	}else if(list[i]["order_ready"] == 0 && list[i]["order_started"] == 1){
          		// flash image based on pick up time will
          		if(old_time < new_time){
                    var flashImg = "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.gif')}}'>";
                }else{
                    var flashImg = "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>";
                }

          		if(list[i]["delivery_type"] == 3 && driverapp)
          		{
      				aString = "<a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+list[i]['order_id']+", false)'>";
          		}
      			else
                {
                	aString = "<a data-ajax='false' href="+urlReadyOrder+"/"+list[i]['order_id']+">";
                }

                aString += flashImg;

                liItem += "<td>"
                liItem += aString
                liItem +="</a>";
                liItem +="</td>";
                
          	}else{
          		liItem += "<td>"
          		liItem += "<a>"
          		liItem +="</a></td>";
      		}
      	@else
      		liItem += "<td>"
	  		if(list[i]["order_ready"] == 0){
	  			liItem += "<a data-ajax='false'>"
	  		}else{
	  			liItem += "<a data-ajax='false'>"
	  		}
	  		liItem +="</a></td>";
  		@endif
  		
  		liItem += "<td>"
  		if(list[i]["paid"] == 0 && list[i]["order_ready"] == 0 ){
  		}else if(list[i]["paid"] == 0 && list[i]["order_ready"] == 1){
      		liItem += "<a data-ajax='false' href="+urldeliver+"/"+list[i]['customer_order_id']+" >"
      		if(old_time < new_time){
                liItem += "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.gif')}}'>"
            }else{
                liItem += "<img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>"
            }
      		liItem += "</a>"
  		}
  		else{
      		liItem += "<a data-ajax='false'>"
      		liItem += "</a>"
  		}
  		liItem +="</td>";
  		liItem += "<td>"
  		if(list[i]["paid"] == 1 || list[i]["online_paid"] == 1 || list[i]["online_paid"] == 3){
  			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
  		}else if(list[i]["online_paid"] == 0){
  			liItem += "<input class='no_check' type='button' value='Pay Manual' data-role='none' onclick='orderPayManually("+list[i]["order_id"]+", this);'>";

  			// Check if discount applied on order
  			if( list[i]["discount_id"] )
  			{
  				discountAmount = (list[i]["order_total"] * list[i]['discount_value']) / 100;
  				liItem += '<div class="show-total"><strong><span class="discounted-total">'+(list[i]["order_total"] - discountAmount).toFixed(2)+' (SEK)</span></strong></div>';
  			}
  		}else{
  			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
  		}

  		// If order belongs to 'Loyalty'
  		if(list[i]['cntLoyaltyUsed'])
  		{
  			liItem += '<div class="show-total"><strong>Loyalty</strong></div>';
  		}

  		liItem += "<td>"+time+"</td>";

  		var deliveryType = '';
  		if( list[i]['delivery_type'] == 1 )
  		{
  			deliveryType = '{{ __('messages.deliveryOptionDineIn') }}';
  		}
  		else if( list[i]['delivery_type'] == 2 )
  		{
  			deliveryType = '{{ __('messages.deliveryOptionTakeAway') }}';
  		}
  		else if( list[i]['delivery_type'] == 3 )
  		{
  			deliveryType = '{{ __('messages.deliveryOptionHomeDelivery') }}';
  			deliveryType += '<br><a href="javascript:void(0)" onclick="getOrderDeliveryAddress('+list[i]['user_address_id']+')"><span>'+list[i]['street']+'</span></a>';

  			if(driverapp)
  			{
  				deliveryType += "<br><a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+list[i]['order_id']+", false, false)'>Assign Driver</a>";
  			}
  		}

  		liItem += "<td>"+deliveryType+"</td>";
  		
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

	// Update all the order items status 'order_started' to 1
	function startOrder(id, This) {
		$This = $(This);
		$.get("{{url('kitchen/start-order')}}/"+id,
		function(returnedData){
			if(returnedData.status)
			{
				$('body').find('#'+id).parent("a").attr('onclick',' ');
				if(returnedData.order.delivery_type == 3 && driverapp)
				{
					$('body').find('#'+id+'ready').parent("a").attr('onclick','popupOrderAssignDriver('+id+', false)');
				}
				else
				{
					$('body').find('#'+id+'ready').parent("a").attr('onclick','makeOrderReady('+id+')');
				}
				$This.closest('tr').removeClass('not-started');
                $This.closest('tr').removeClass('news');
             	// change red blinker to gray circle and remove class new form its table row tr element
                $This.parents('tr').removeClass('new');
                $This.parents('tr').find('.ready_class').html("<a data-ajax='false' href="+urlReadyOrder+"/"+id+"><img class='image_clicked' src='{{asset('kitchenImages/red_blink_image.png')}}'>");
				$('body').find('#'+id).remove();
				clearSpeakTextInterval();
			}
		});
	}

	// Update the order and order items
	function makeOrderReady(id) {
		$('body').find('#'+id+'ready').parent("a").attr('onclick',' ');
		$('body').find('#'+id+'ready').remove();

		$.get("{{url('kitchen/make-order-ready')}}/"+id,
		function(returnedData){
			window.location.reload();
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