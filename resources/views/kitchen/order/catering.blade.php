@extends('layouts.blank')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

<style type="text/css">
	.grey{
		color: #515151;
	}

	.red{
		color: #a90810;		
	}

	.red:hover{
		cursor: pointer;
	}

	.ready_notification{
		display: none;
	}

	#delete_user_block{
		    display: inline-block;
		    position: absolute;
		    bottom: 10px;
		    left: 50%;
		    margin-left: -55px;
	}

	.ui-dialog{
		background-color: #fff !important;
	}

	.ui-controlgroup, #dialog-confirm + fieldset.ui-controlgroup {
    	width: 100%;
	}

	#dialog-confirm{
		display: none;
	} 

	.ui-dialog .ui-dialog-buttonpane{
		text-align: center;
	}

	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
		float: none;
	}
	
	#dialog-confirm .ui-icon{
		float:left; 
		margin:12px 12px 20px 0;
		color: #fff;
	}

	#dialog-confirm .ui-icon-alert{
		color: #fff;
	}

	.ui-widget-overlay{
	    opacity: 0.5 !important;		
	}

	.dialog-no{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%) !important;
		color: #fff !important;
	}

	.dialog-no:hover{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%);
		color: #fff;
	}

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
			top: 50%;
			left: 50%;
			-moz-transform: translate(-50%);
			-webkit-transform: translate(-50%);
			-o-transform: translate(-50%);
			-ms-transform: translate(-50%);
			transform: translate(-50%);
			z-index: 99999;
	}
</style>
@stop

@section('content')

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
			<a  href = "{{ url('kitchen/logout') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
			</a>
		</div>
		<h3 class="ui-bar ui-bar-a order_background"><span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<div class="ready_notification">
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
				Order Cancelled Successfully.
		    </div>
		</div>

		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
		 	<thead>
		 		<tr class="ui-bar-d">
			  		<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th>{{ __('messages.Amount') }}</th> 
			   		<th data-priority="3">{{ __('messages.Product') }}</th>
			    	<th data-priority="1">{{ __('messages.Comments') }}</th> 
			    	<th data-priority="5">{{ __('messages.Date and Time') }}</th>
			     	<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
			     	<th data-priority="1">{{ __('messages.Remove') }}</th>
		      	</tr>
		    </thead>
		    <tbody id="orderDetailContianer">
		    	
		    </tbody>
		</table>
	</div>
	
	@include('includes.kitchen-footer-menu')

	<?php 
		$lan = "eng";
	?>

	@if(Auth::check())
		@if(Auth::user()->language != 'ENG')
		<?php 
			$lan = "swe";
		?>
		@endif
	@else
		@if(Session::get('browserLanguageWithOutLogin') != 'ENG')
		<?php 
			$lan = "swe";
		?>
		@endif
	@endif

	<div id="dialog-confirm" title="Delete Account">
		@if($lan == "eng")
			<p>Do you really want to delete this order.<br/><br/>Yes / No</p>
		@else
			<p>Vill du verkligen ta bort denna beställning.<br/><br/>Ja / Nej</p>	
		@endif	
	</div>

		<img src="{{ asset('images/loading.gif') }}" id="loading-img" />

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script type="text/javascript">
		var list = Array();
		var totalCount = 0;
		var totallength = 0;
		var storeId = "{{Session::get('storeId')}}";

		function removeOrder(orderID,user_id){
				$("#dialog-confirm").dialog({
					resizable: false,
					modal: true,
					buttons: [						
						{
							text: "No",
							"class": 'dialog-no',
							click: function() {
								$(this).dialog("close");
							}					
						},
						{
							text: "Yes",
							"class": 'dialog-yes',
							click: function() {
								$(this).dialog("close");
								$('#overlay').css("display", "block");
								$('#loading-img').css("display", "block");

								$.post("{{url('kitchen/remove-order')}}",
									{"_token":"{{ csrf_token() }}","order_id":orderID,"user_id":user_id},
									function(returnedData){
										$(".order_id_"+orderID).remove();
										$('#loading-img').css("display", "none");
										$('#overlay').css("display", "none");
										$('.ready_notification').show();
										
										setTimeout(
											function(){ 
												$('.ready_notification').hide();
										}, 3000);
									}
								);
							}
						}
		        ]
				
			});
		}

		// If order is new then it update the order status
		function updateOrderDetailStatus(This, id) {
			$this = $(This);

			if($this.hasClass('new'))
			{
				$.post("{{url('kitchen/update-order-detail-status')}}",
					{"_token": "{{ csrf_token() }}", "id": id},
					function(returnedData){
						$this.removeClass('new');
						$this.removeAttr('onclick');
					}
				);
			}
		}

		$(function(){
			

			$.get("{{url('api/v1/kitchen/catering-orders')}}/" + storeId,
			function(returnedData){
				console.log(returnedData["data"]);
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
		          		var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
		          		var orderCreate = orderCreateTime(temp[i]["created_at"]);
		          		var timeOrder = addTimes("00:00:00",temp[i]["deliver_time"]);
		          		
		          		// Add new class on tr if order added recently
		          		if(temp[i]['is_new'])
		          		{
		          			liItem += "<tr class='order_id_"+temp[i]["order_id"]+" new' onclick='updateOrderDetailStatus(this, "+temp[i]['id']+")'>";
		          		}
		          		else
		          		{
		          			liItem += "<tr class='order_id_"+temp[i]["order_id"]+"'>";
		          		}
		          		
		          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
		          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
		          		liItem += "<td>"+temp[i]["product_name"]+"</td>";
		          		if(temp[i]["product_description"] != null){
		          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
		          		}else{
		          			liItem += "<td>"+''+"</td>";
		          		}
		          		liItem += "<td>"+orderCreate+"</td>";
		          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";
		          		if(temp[i]["cancel"]==2){
   			          		liItem += "<td>"+"<span class='fa fa-times-circle fa-2x red' onclick='removeOrder("+temp[i]["order_id"]+","
   			          		+temp[i]["user_id"]+")'>"+"</span>"+"</td>";	
		          		}else{
			          		liItem += "<td>"+"<span class='fa fa-times-circle fa-2x grey'>"+"</span>"+"</td>";					          			
		          		}
		          		liItem += "</tr>";
		          	}
	          	}else{

	          	}
	          	$("#orderDetailContianer").append(liItem);
			}); 
		});

		var ajaxCall = function(){
			$.get("{{url('api/v1/kitchen/catering-orders')}}/" + storeId,
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
		          		var orderCreate = orderCreateTime(temp[i]["created_at"]);
		          		var timeOrder = addTimes("00:00:00",temp[i]["deliver_time"]);
		          		var isNew = temp[i]['is_new'] ? ' new' : '';

		          		// Add new class on tr if order added recently
		          		if(temp[i]['is_new'])
		          		{
		          			liItem += "<tr class='order_id_"+temp[i]["order_id"]+" new' onclick='updateOrderDetailStatus(this, "+temp[i]['id']+")'>";
		          		}
		          		else
		          		{
		          			liItem += "<tr class='order_id_"+temp[i]["order_id"]+"'>";
		          		}
		          		
		          		liItem += "<th>"+temp[i]["customer_order_id"]+"</th>";
		          		liItem += "<td>"+temp[i]["product_quality"]+"</td>";
		          		liItem += "<td>"+temp[i]["product_name"]+"</td>";
		          		if(temp[i]["product_description"] != null){
		          			liItem += "<td>"+temp[i]["product_description"]+"</td>";
		          		}else{
		          			liItem += "<td>"+''+"</td>";
		          		}
		          		liItem += "<td>"+orderCreate+"</td>";
		          		liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";
		          		if(temp[i]["cancel"]==2){
   			          		liItem += "<td>"+"<span class='fa fa-times-circle fa-2x red' onclick='removeOrder("+temp[i]["order_id"]+","
   			          			+temp[i]["user_id"]+")'>"+"</span>"+"</td>";	
		          		}else{
			          		liItem += "<td>"+"<span class='fa fa-times-circle fa-2x grey'>"+"</span>"+"</td>";					          			
		          		}
		          		liItem += "</tr>";
		          	}
	          	}else{

	          	}
	          	$("#orderDetailContianer").html(liItem);
			}); 
		}

		setInterval(ajaxCall, 30000);
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
	      	if(i>=totallength){
	      		tempCount = 18;
	      		break;
	      	}
	      	if(countCheck>limit){
	      		break;
	      	}
	      	var time = addTimes(list[i]["order_delivery_time"],list[i]["deliver_time"]);
		    var orderCreate = orderCreateTime(list[i]["created_at"]);
	      	var timeOrder = addTimes("00:00:00",list[i]["deliver_time"]);
      		liItem += "<tr>";
      		liItem += "<th>"+list[i]["customer_order_id"]+"</th>";
      		liItem += "<td>"+list[i]["product_quality"]+"</td>";
      		liItem += "<td>"+list[i]["product_name"]+"</td>";
      		if(list[i]["product_description"] != null){
      			liItem += "<td>"+list[i]["product_description"]+"</td>";
      		}else{
      			liItem += "<td>"+ +"</td>";
      		}
      		liItem += "<td>"+orderCreate+"</td>";
		    liItem += "<td>"+list[i]["deliver_date"]+' '+timeOrder+"</td>";
      		liItem += "</tr>";
	      	countCheck++;
	      }
	      $("#orderDetailContianer").append(liItem);	
		}

		function orderCreateTime(time){
			var date = convertUTCDateToLocalDate(new Date(time));
			// var date = new Date(time + " UTC");

			var dd = date.toString();
			var ddd = dd.split(" ");
			var ddddd = ddd[4].split(":");
			var dddd = ddd[0]+" "+ddd[1]+" "+ddd[2]+" "+ddd[3]+" "+ddddd[0]+":"+ddddd[1];

			return dddd;
		}

		function convertUTCDateToLocalDate(date) {
		    var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);

		    var offset = date.getTimezoneOffset() / 60;
		    var hours = date.getHours();

		    newDate.setHours(hours - offset);

		    return newDate;   
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
@endsection