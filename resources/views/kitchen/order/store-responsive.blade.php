@extends('layouts.blank')

@section('content')

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<style>
	.news{
		background-color: #87ceebbf !important;
	}
	.new{
		background-color: #dbe473 !important;
	}
	.ready_notifications{
		display: none;
	}
	.gr-en{
		color:#4caf50 !important;
	}
</style>

@include('includes.confirm-modal')

	<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<h3 class="ui-bar ui-bar-a order_background"><span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow ui-responsive table_size" >
			<thead>
			 	<tr class="ui-bar-d">
			 		<th data-priority="2">{{ __('messages.Orders') }}</th>
			 		<th data-priority="3">Status</th>
			 		<th data-priority="3" width="15%">{{ __('messages.Paid') }}</th>
			 		<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
			     	<th data-priority="1">{{ __('messages.deliveryType') }}</th>
			    </tr>
			</thead>
		    <tbody id="orderDetailContianer"></tbody>
		</table>
	</div>	

	@include('includes.kitchen-footer-menu')
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

			      	if(temp[i]['order_type'] == "eat_now"){
				      	if(temp[i]['order_response']){
				      		var time = addTimes(temp[i]['order_delivery_time'],temp[i]['deliver_time'],extra_prep_time);
				      	}else{
				      		var time = addTimes(temp[i]['deliver_time'], temp[i]['extra_prep_time']);
				      	}
			      	}else{
			      		var time = addTimes(temp[i]['deliver_time']);
			      	}
                    
                   	// blink image time calculator getting time based on pick up and current time
                    var today = new Date(); 
                    old_hour = time.substr(0,2);
                    old_mins = time.substr(3,5);
                    var old_time = parseInt(old_hour)*60 + parseInt(old_mins);
                    var new_time = parseInt(today.getHours())*60 + parseInt(today.getMinutes())
                    
	          		var orderIdSpecific = temp[i]["order_id"] ;

					const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
					var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
					var orderCreateDate = new Date(temp[i]["created_at"]+' UTC');
	                var hours = ("0" + orderCreateDate.getHours()).slice(-2);
	                var minutes = ("0" + orderCreateDate.getMinutes()).slice(-2);
	                var orderCreate = '<small>'+days[orderCreateDate.getDay()]+' '+monthNames[orderCreateDate.getMonth()]+' '+orderCreateDate.getDate()+' '+orderCreateDate.getFullYear()+'</small><br>'+hours+':'+minutes;
	          		if(temp[i]["order_type"] == "eat_now"){
	          			var orderStatus = temp[i]["order_started"] == 0 ? 'new' : ''; // Add class 'new' until order 'started'
	          		}else{
	          			var orderStatus = temp[i]["order_started"] == 0 ? 'news' : '';// Add class 'news' until order 'started'
	          		}

	          		if(temp[i]['orderDeliveryStatus'] == '0')
	          		{
	          			orderStatus += orderStatus.length ? ' not-accepted' : 'not-accepted';
	          		}

	          		liItem += "<tr class='order_id_"+orderIdSpecific+" "+orderStatus+"'>";
	          		liItem += "<th>"
	          		liItem += "<a href='javascript:getList("+orderIdSpecific+")' data-rel='popup'>"
	          		liItem += temp[i]["customer_order_id"]
	          		liItem += "</a></th>";

	          		if (temp[i]["name"] == null){
	          			temp[i]["name"] = "";
	          		}

	          		liItem += "<td><a><img src='{{asset('kitchenImages/right_sign.png')}}'></a></td>";

	          		// 
	          		liItem += "<td>"
	          		if(temp[i]["paid"] == 1 || temp[i]["online_paid"] == 1 || temp[i]["online_paid"] == 3){
	          			liItem += "<input class='yes_check'  type='button' data-role='none' value='yes' name=''>"
	          		}else if(temp[i]["online_paid"] == 0){
	          			liItem += "<input class='no_check' type='button' value='Pay Manual' data-role='none' onclick='orderPayManually("+temp[i]["order_id"]+", this);'>";

	          			// Check if discount applied on order
	          			if( temp[i]["discount_id"] )
	          			{
	          				discountAmount = (temp[i]["order_total"] * temp[i]['discount_value']) / 100;
	          				liItem += '<div class="show-total"><strong><span class="discounted-total">'+(temp[i]["order_total"] - discountAmount).toFixed(2)+' (SEK)</span></strong></div>';
	          			}
	          		}else{
	          			liItem += "<input class='no_check'  type='button' data-role='none' value='no' name=''>"
	          		}

	          		// If order belongs to 'Loyalty'
	          		if(temp[i]['cntLoyaltyUsed'])
	          		{
	          			liItem += '<div class="show-total"><strong>Loyalty</strong></div>';
	          		}
                    
	          		liItem += "<td><small>"+temp[i]['deliver_date']+"</small><br>"+time+"</td>";

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
	          			if(temp[i]['delivery_at_door'] == '1'){
	                        deliveryType += '<br><b><span>{{ __('messages.deliveryAtDoor') }}</span></b>';
	                    }
	          			deliveryType += '<br><a href="javascript:void(0)" onclick="getOrderDeliveryAddress('+temp[i]['user_address_id']+')"><span>'+temp[i]['street']+'</span></a>';

	          			if(driverapp)
	          			{
	          				deliveryType += "<br><a data-ajax='false' href='javascript:void(0)' onclick='popupOrderAssignDriver("+temp[i]['order_id']+", false, false)'>Assign Driver</a>";
	          			}
	          		}

	          		liItem += "<td>"+deliveryType+"</td>";

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
          	if(returnedData['catCount'] > 0){
          		$('.catering-badge').html(returnedData['catCount']);
          	}else{
          		$('.catering-badge').html('');
          	}
		});
	}

	setInterval(ajaxCall, 10000);

	var tempCount = 18;

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

		if (seconds >= 60) {
			var m = (seconds / 60) << 0
			minutes += m
			seconds -= 60 * m
		}

		if (minutes >= 60) {
			var h = (minutes / 60) << 0
			hours += h
			minutes -= 60 * h
		}

		if(hours >= 24) {
			hours = hours %24;
			hours = hours < 0 ? 24 + hours : +hours;
		}

		return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2)
	}
</script>
@endsection