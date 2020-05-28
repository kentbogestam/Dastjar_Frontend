@extends('layouts.blank')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

<style type="text/css">
	.grey{
		color: #515151;
	}

    .new{
        background-color: #87ceebbf !important;
    }

    .rejectnew{
       background-color: lightgray !important;
    }
    
    .acceptnew{
       background-color: white !important;
    }
    
	.red{
		color: #a90810;		
	}
    
	.red:hover{
		cursor: pointer;
	}

	.ready_notifications{
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
    .acceptit,.rejectit{
        cursor:pointer;
    }
</style>
@stop

@section('content')

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<h3 class="ui-bar ui-bar-a order_background"><span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<div class="ready_notifications">
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
				<span></span>
		    </div>
		</div>

		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow ui-responsive table_size" >
		 	<thead>
		 		<tr class="ui-bar-d">
			  		<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th data-priority="3">{{ __('messages.name') }}</th>
			    	<th data-priority="5">{{ __('messages.Date and Time') }}</th>
			    	<th data-priority="5">{{ __('messages.status') }}</th>
			    	<th data-priority="1">{{ __('messages.accept') }}</th> 
			    	<th data-priority="1">{{ __('messages.price') }}</th> 
			     	<th data-priority="1">{{ __('messages.Pick up Time') }}</th>
			     	<th data-priority="1">{{ __('messages.deliveryType') }}</th>
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

	<div id="overlay" onclick="off()"></div>

    <div data-role="popup" id="popupCloseRight" class="ui-content" style="max-width:100%;border: none;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" style="background-color:#000;border-color: #000;">Close</a>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
			<thead>
				<tr class="ui-bar-d">
					<th data-priority="2">{{ __('messages.name') }}</th>
			   		<th class="qty-loyalty-offer">{{ __('messages.qtyLoyaltyOffer') }}</th>
			   		<th data-priority="4">{{ __('messages.price') }}</th>
			   		<th data-priority="4">{{ __('messages.Amount') }}</th>
			   		<th data-priority="4">{{ __('messages.subTotal') }}</th>
			    </tr>
			</thead>
			<tbody id="specificOrderDetailContianer"></tbody>
		</table>
    </div>

    <div data-role="popup" id="popupCloseRight2" class="ui-content" style="max-width:100%;border: none;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" style="background-color:#000;border-color: #000;">Close</a>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
			<thead>
				<tr class="ui-bar-d">
					<th data-priority="2" colspan="2">{{ __('messages.pleaseSelectOne') }}</th>
			    </tr>
			</thead>
			<tbody id="specificOrderDetailContianer2"></tbody>
		</table>
    </div>

    <div data-role="popup" id="popupCloseRight3" class="ui-content" style="max-width:100%;border: none;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" style="background-color:#000;border-color: #000;">Close</a>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
			<thead>
				<tr class="ui-bar-d">
					<th data-priority="2">{{ __('messages.name') }}</th>
					<th data-priority="2">{{ __('messages.email') }}</th>
					<th data-priority="2">{{ __('messages.phone') }}</th>
					<th data-priority="2">{{ __('messages.address') }}</th>
			    </tr>
			</thead>
			<tbody id="specificOrderDetailContianer3"></tbody>
		</table>
    </div>
@endsection

@section('footer-script')

	<script type="text/javascript">
		var list = Array();
		var totalCount = 0;
		var totallength = 0;
		var storeId = "{{Session::get('kitchenStoreId')}}";

        function getOrderDetail(orderId){
            var liItem = "";
            $.get("{{url('kitchen/catering/orderCateringOrderDetail')}}/"+orderId,
            function(returnedData){
                $("#specificOrderDetailContianer").html(returnedData);
    			$("#popupCloseRight").popup("open");
            });
        }
        
        function getUserDetail(userId){
            var liItem = "";
            $.get("{{url('kitchen/catering/orderCateringUserDetail')}}/"+userId,
            function(returnedData){
                $("#specificOrderDetailContianer3").html(returnedData);
    			$("#popupCloseRight3").popup("open");
            });
        }
      
		function acceptRejectOrder(orderId)
        {
            var liItem = '';
            liItem += "<tr>";
            liItem += "<td class='acceptit' onclick='acceptit("+orderId+")'>Accept</td>";
            liItem += "<td class='rejectit' onclick='rejectit("+orderId+");'>Reject</td>";
            liItem += "</tr>";
                
            $("#specificOrderDetailContianer2").html(liItem);
            $("#popupCloseRight2").popup("open");
        }
            
        function acceptit(id){
            var status = '2';
            $("#popupCloseRight2").popup("close");
            $('#overlay').css("display", "block");
            $('#loading-img').css("display", "block");
            $.get("{{url('kitchen/catering/orderCateringRejectAccept')}}/"+id+"/"+status,
                function(returnedData){  
                    if(returnedData != ''){
                        $(".order_id_"+id).removeClass('new');
                        $(".order_id_"+id).addClass('acceptnew');
                        $(".order_id_"+id+" .acceptRejectStatus img").attr("src","{{asset('kitchenImages/right_sign.png')}}");
                        $(".order_id_"+id+" .acceptRejectStatus img").attr("onclick","");
                        $('#loading-img').css("display", "none");
                        $('#overlay').css("display", "none");
                        $('.ready_notifications span').html('Order Accepted Successfully.');
                        $('.ready_notifications').show();

                        setTimeout(
                            function(){ 
                                $('.ready_notifications').hide();
                        }, 3000);
                    }
                }
            );
        }
            
        function rejectit(id){
            var status = '1';
            if(confirm("Do you really wants to reject ?")){
                $("#popupCloseRight2").popup("close");
                $('#overlay').css("display", "block");
                $('#loading-img').css("display", "block");
                $.get("{{url('kitchen/catering/orderCateringRejectAccept')}}/"+id+"/"+status,
                    function(returnedData){  
                        if(returnedData != ''){
                            $(".order_id_"+id+" .acceptRejectStatus img").attr("onclick","");
                            $('#loading-img').css("display", "none");
                            $('#overlay').css("display", "none");
                            $(".order_id_"+id).remove();
                            $('.ready_notifications span').html('Order Rejected Successfully.');
                            $('.ready_notifications').show();

                            setTimeout(
                                function(){ 
                                    $('.ready_notifications').hide();
                            }, 3000);
                        }
                    }
                );
            }
        }
        
		function removeOrder(orderID,user_id){
            if(confirm("Do you really wants to delete ?")){
                $('#overlay').css("display", "block");
                $('#loading-img').css("display", "block");
                $.post("{{url('kitchen/remove-order')}}",
                    {"_token":"{{ csrf_token() }}","order_id":orderID,"user_id":user_id},
                    function(returnedData){                    
                        $(".order_id_"+orderID).remove();
                        $('#loading-img').css("display", "none");
                        $('#overlay').css("display", "none");
                        $('.ready_notifications span').html('Order Cancelled Successfully.');
                        $('.ready_notifications').show();

                        setTimeout(
                            function(){
                                $('.ready_notifications').hide();
                        }, 3000);
                    }
                );
            }
		}
        
        $(function(){
          ajaxCall();
        });

		var ajaxCall = function(){
			$.get("{{url('kitchen/catering/catering-orders')}}/" + storeId,
			function(returnedData){
//				console.log(returnedData["data"]);
				var count = 18;
				var temp = returnedData["data"];
	          	list = temp;
	          	var liItem = "";
	          	if(temp.length != 0){
	          		totalCount = temp.length;

		          	if(temp.length < count){
		          		count = temp.length
		          	}

		          	totalCount -= 10;
                    start = 0;
                    liItem += htmlData(start,count,temp) 
	          	}
	          	$("#orderDetailContianer").html(liItem);
			}); 
		}

        function htmlData(start,count,temp)
        {
            var liItem;
            for (var i=0;i<count;i++){
                var time = addTimes(temp[i]["order_delivery_time"],temp[i]["deliver_time"]);
                var orderCreate = temp[i]["created_at"];
                var timeOrder = addTimes("00:00:00",temp[i]["deliver_time"]);
                var leng = temp[i]['orderdetail_detail'].length;
                var totalprice = 0;
                var isNew;
                var paid_status;
                var order_status;
                for(var j=0;j<leng;j++){
                    totalprice += (parseInt(temp[i]['orderdetail_detail'][j]['product_quality']) - parseInt(temp[i]['orderdetail_detail'][j]['quantity_free'])) * parseInt(temp[i]['orderdetail_detail'][j]['price']);
                }
                
                var catering_paid_status = list[i]["online_paid"];
                if(catering_paid_status == '0'){
                    paid_status = '<img src="{{asset('kitchenImages/subs_sign.png')}}">';
                }else if(catering_paid_status == '1'){
                    paid_status = '<img src="{{asset('kitchenImages/right_sign.png')}}">';
                }else{
                    paid_status = '<img src="{{asset('kitchenImages/subs_sign.png')}}">';
                }
                
                var catering_order_status = list[i]["catering_order_status"];
                if(catering_order_status == '0'){
                    isNew = 'new';
                    if(temp[i]["cancel"] > 1){
                        order_status = '<img src="{{asset('kitchenImages/red_right_sign.png')}}">';
                    }else{
                        order_status = '<img src="{{asset('kitchenImages/red_right_sign.png')}}" onclick="acceptRejectOrder('+temp[i]['order_id']+')">';
                    }
                    
                }

                if(catering_order_status == '2'){
                    isNew = 'acceptnew';
                    order_status = '<img src="{{asset('kitchenImages/right_sign.png')}}">';
                }

                if(temp[i]["cancel"] > 1){
                    isNew = 'rejectnew';
                }

                // Add new class on tr if order added recently
                liItem += "<tr class='order_id_"+temp[i]["order_id"]+" "+isNew+"'>";
                
                liItem += "<th><a href='javascript:getOrderDetail("+temp[i]["order_id"]+")' data-rel='popup'>" +temp[i]["customer_order_id"]+"</a></th>";
                liItem += "<th><a href='javascript:getUserDetail("+temp[i]["user_id"]+")' data-rel='popup'>" +temp[i]["customer_detail"][0]["name"]+"</a></th>";
                liItem += "<td>"+orderCreate+"</td>";
                liItem += "<td class='paidStatus'><a href='javascript:void(0)'>"+paid_status+"</a></td>";
                liItem += "<td class='acceptRejectStatus'><a href='javascript:void(0)'>"+order_status+"</a></td>";
                liItem += "<td>"+totalprice+" SEK </td>";
                liItem += "<td>"+temp[i]["deliver_date"]+' '+timeOrder+"</td>";
                var deliveryType = '';
                if( temp[i]['delivery_type'] == 1 ){
                    deliveryType = '{{ __('messages.deliveryOptionDineIn') }}';
                }else if( temp[i]['delivery_type'] == 2 ){
                    deliveryType = '{{ __('messages.deliveryOptionTakeAway') }}';
                }else if( temp[i]['delivery_type'] == 3 ){
                    deliveryType = '<span>{{ __('messages.deliveryOptionHomeDelivery') }}</span>';
                    if(temp[i]['delivery_at_door'] == '1'){
                        deliveryType += '<br><span>{{ __('messages.deliveryAtDoor') }}</span>';
                    } 
                    deliveryType += '<br><span>'+temp[i]["customer_full_detail"][0]["street"]+' '+temp[i]["customer_full_detail"][0]["city"]+'</span>';
                }

                liItem += "<td>"+deliveryType+"</td>";

                if(temp[i]["cancel"] > 1){
                    liItem += "<td>"+"<span class='fa fa-times-circle fa-2x red' onclick='removeOrder("+temp[i]["order_id"]+","+temp[i]["user_id"]+")'>"+"</span>"+"</td>";	
                }else{
                    liItem += "<td>"+"<span class='fa fa-times-circle fa-2x grey'>"+"</span>"+"</td>";
                }
                liItem += "</tr>";
            }
            return liItem;
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

		function addMore(len){
			var liItem = "";
	    	var limit = 0;
            
			if(totalCount > 10){
			 	limit = 10;
			 	totalCount -= 10;
			} else if(totalCount<=0){
			 	return;
			} else{
			 	limit = totalCount;
			 	totalCount -= totalCount;
			}
            start = len
            count = len+10
            liItem += htmlData(start,count,temp)
            
	        $("#orderDetailContianer").append(liItem);	
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