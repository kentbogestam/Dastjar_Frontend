@extends('layouts.master')
@section('head-scripts')
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!-- 	<link rel="stylesheet" href="{{asset('css/spinningwheel.css')}}" type="text/css" media="all">
	<script type="text/javascript" src="{{asset('js/spinningwheel.js')}}"></script> -->

	<style type="text/css">
		#demo1-2{
			display: none;
		}

		.date_block{
			display: none;
			margin-top: 20px;
			height: 100px;
		}

		.error_apple_time {
		    color: red;
		    font-size: 14px;
		    text-align: center;
		    margin-top: 15px;
		    display: none;
		}		
	</style>

<script type="text/javascript">
window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

$(document).ready(function(){
	var ua= navigator.userAgent, tem, 
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];

	    if(M[1]=="Safari"){
	    	$(".date_block").show();
	    	  var today = new Date();
			  // Set month and day to string to add leading 0
			  var day = new String(today.getDate());
			  var mon = new String(today.getMonth()+1); //January is 0!
			  var yr = today.getFullYear();

			  var hors = new String(today.getHours()); //returns 0-23
			  var mintes = new String(today.getMinutes()); //returns 0-59
 			  var secnds = new String(today.getSeconds()); //returns 0-59

			    if(day.length < 2) { day = "0" + day; }
			    if(mon.length < 2) { mon = "0" + mon; }
			    if(hors.length < 2) { hors = "0" + hors; }
			    if(mintes.length < 2) { mintes = "0" + mintes; }
			    if(secnds.length < 2) { secnds = "0" + secnds; }

			    var appleDate = new String( yr + '-' + mon + '-' + day + 'T');
			    var appleDateTime = new String( yr + '-' + mon + '-' + day + 'T' + hors   + ':' + mintes + ':' + secnds);

	  //   	$("#bdaytime").disabled = false; 
			$("#bdaytime").attr("min", appleDateTime);
			$("#bdaytime").val(appleDate+"00:00:00");
 			// $("#bdaytime").setNow();

	    }else{
	    	$("#demo1-2").show();
	    }
});
</script>
@endsection
@section('content')
	<div data-role="page" data-theme="c">
		<div data-role="header" class="header" data-position="fixed" id="nav-header"  data-position="fixed" data-tap-toggle="false"> 
			<div class="nav_fixed">
				<a href="{{ url('eat-now') }}" data-ajax="false" class="ui-btn-left text-left backarrow-btn"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>
				<div class="logo">
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a class="ui-btn-right map-btn user-link" 
				href="{{url('search-map-eatnow')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>

		<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('eat-later') }}">
			{{ csrf_field() }}
			<div role="main" data-role="main-content" class="content" id="wrapper">
				<div class="">
		        	<div id="demo1-2"></div>

					<div class="date_block">
					  Date and Time:
					  <input type="datetime-local" id="bdaytime" name="bdaytime" value="" min="">
					  <p class='error_apple_time'>Please enter PickUp time in 24 hours format(2 hours in advance from current time). </p>
					</div>

		    		<div class="show-date-time">
				        <span id="date-value1-2" class="date_show_section" value = ""></span>
				        <input type="hidden" id="date-value1-23" name="dateorder" value="" />
		    			<div class="go-btn">
		    				<button type="button" id="ss" class="fa fa-chevron-right"></button>
		    			</div>
		    		</div>
				</div>	
	    		
			</div>
		</form>


		<div data-role="footer" class="footer" data-position="fixed" data-tap-toggle="false">
			<div class="ui-grid-c inner-footer center">
				<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_01.png')}}">
					</div>
					<span>{{ __('messages.Restaurant') }}</span>
				</a></div>
				<div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_03.png')}}">
					</div>
					<span>{{ __('messages.Send') }}</span>
				</a></div>
				@include('orderQuantity')
				<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
				</a></div>
			</div>
		</div>
	</div>

@endsection

@section('footer-script')

	<script type="text/javascript" src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.datetimepicker.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/dateFormat.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery-dateformat.min.js')}}"></script>
	<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.min.js"></script>

	<script type="text/javascript">
		/*$('.error_time').hide();	*/
		 $(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });

		var tz = moment.tz.guess();

		var date = new Date();
		//date.setDate(date.getDate());
		//date.setMonth(date.getMonth());
		//date.setDate(date.getDate());
		//date.setHours(00, 00, 00);
		
		date.setHours(date.getHours());

		var startDate = new Date();
		//startDate.setDate(startDate.getDate());
		//startDate.setMonth(startDate.getMonth());
		//startDate.setDate(startDate.getDate());
		//startDate.setHours(00, 00, 00);
		startDate.setHours(startDate.getHours());

		var curr_date = date.getDate();
		var curr_month = date.getMonth()+1;
		var curr_year = date.getFullYear();
		var hours = date.getHours(); //returns 0-23
		var minutes = date.getMinutes(); //returns 0-59
		var seconds = date.getSeconds(); //returns 0-59

		
		if(curr_month < '10')
		{
			curr_month= '0'+curr_month;
		}
		
		if(curr_date < '10')
		{
			curr_date= '0'+curr_date;
		}
		
		if(hours < 10)
		{
			hours ='0'+hours;
		}
		if(minutes < 10)
		{
			minutes ='0'+minutes;
		}
		
        var input_date = curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds;
        dateVal=$.format.date(input_date, "E MMM dd yyyy HH:mm:ss");
        $('#date-value1-2').html(startDate);
        $('#date-value1-23').val(input_date);

	   $('#demo1-2').datetimepicker({
            date: date,
            startDate: startDate,
            viewMode: 'YMDHM',
            onDateChange: function(){
                $('#date-text1-2').text(this.getText());
                $('#date-text-ymd1-2').text(this.getText('yyyy-MM-dd'));
                dateNew = this.getValue();
		        $('.error_time').hide();
				$('#ss').attr('disabled',false)
				curr_date = dateNew.getDate();
				curr_month = dateNew.getMonth()+1;
				curr_year = dateNew.getFullYear();
				hours = dateNew.getHours(); //returns 0-23
				minutes = dateNew.getMinutes(); //returns 0-59
				seconds = dateNew.getSeconds(); //returns 0-59
				
				if(curr_month < '10')
				{
					curr_month= '0'+curr_month;
				}
				
				if(curr_date < '10')
				{
					curr_date= '0'+curr_date;
				}
				if(hours < 10)
				{
					hours ='0'+hours;
				}
				if(minutes < 10)
				{
					minutes ='0'+minutes;
				}
					var input_date = curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds;
					dateVal=$.format.date(input_date, "E MMM dd yyyy HH:mm:ss");
					$('#date-value1-2').html(dateNew);
					$('#date-value1-23').val(input_date);
				     //$('#date-value1-23').val(this.getValue());
            }
        });

	   $("#bdaytime").on('change', function(){
				dateVal = moment($("#bdaytime").val()).local();
				dateVal = new Date(dateVal);

				curr_date = dateVal.getDate();
				curr_month = dateVal.getMonth()+1;
				curr_year = dateVal.getFullYear();
				hours = dateVal.getHours(); //returns 0-23
				minutes = dateVal.getMinutes(); //returns 0-59
				seconds = dateVal.getSeconds(); //returns 0-59
                dateVal=$.format.date(curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds, "E MMM dd yyyy HH:mm:ss");

                $('#date-value1-2').text(dateVal);

				if(dateVal<new Date()){
				    $('.error_apple_time').show();
				}else{
	 			    $('.error_apple_time').hide();
				}
	   });

	   $("#ss").click(function(e){		  
	   		if($("#demo1-2").css('display') == 'block'){
	   			var timeHH = $('#timeH').val();
				var timeMM = $('#timeM').val();

				var curDate = new Date().getTime();
				var selDate = new Date($('#date-value1-23').val()).getTime();

				hdate = moment(selDate).toDate();
				utcdate = moment.utc(hdate);

				if(timeHH == 00 && timeMM == 00){
					$('.error_time').show();
				}else if(selDate<curDate){
					$('.error_time').show();
					console.log(timeHH);
				}
				else if(timeHH == 00 && timeMM != 00){
					$('#date-value1-23').val(utcdate);					
					$('.error_time').hide();
					$("#form").submit();
				}else if(timeHH != 00 && timeMM == 00){
					$('#date-value1-23').val(utcdate);					
					$('.error_time').hide();
					$("#form").submit();
				}else{
					$('#date-value1-23').val(utcdate);					
					$('.error_time').hide();
					$("#form").submit();
				}
	   		}else{
				dateVal = new Date($("#bdaytime").val());

				if(dateVal<new Date()){
				    $('.error_apple_time').show();
				}else{
					$('#date-value1-23').val(moment.utc($('#date-value1-23').val()).format('DD/MM/YYYY HH:mm'));				

					selDate = dateVal.getTime();
					hdate = moment(selDate).toDate();
					utcdate = moment.utc(hdate);

	 			    $('.error_apple_time').hide();
	                $('#date-value1-23').val(utcdate);
					$("#form").submit();
				}
	   		}
		});


	   $(document).ready(function(){
	   		$("td.day:contains('"+curr_date+"')").addClass("today selected");

	   	    $("td.day.today").parent().prevAll().andSelf().find("td.oday").css({"pointer-events":"none"});

	   	    $('.prevm').click(function(){
		   	    $("td.day").parent().prevAll().andSelf().find("td.oday").css({"pointer-events":"auto"});	   	    	
		   	    $("td.day.today").parent().prevAll().andSelf().find("td.oday").css({"pointer-events":"none"});
	   	    });

	   	    $('.nextm').click(function(){
		   	    $("td.day").parent().prevAll().andSelf().find("td.oday").css({"pointer-events":"auto"});	   	    	
		   	    $("td.day.today").parent().prevAll().andSelf().find("td.oday").css({"pointer-events":"none"});
	   	    });
	   });

	  $('.perfect-datetimepicker').append("<p class='error_time'>Please enter PickUp time in 24 hours format(2 hours in advance from current time). </p>");
	// var lar_r =   $('.tt tbody').find('tr:first')
	// var bb = $(lar_r).append('<td class=""></td>');
    function validateDate(obj)
	{
		//default date should be
		var date = new Date();
		date.setHours(date.getHours());
        var orderTime_H =date.getHours() ;
		
		var orderTime_M =date.getMinutes() ;

		var H = $('#timeH').val();
		var M = $('#timeM').val();
		var selected_date = $('#date-value1-23').val();
		var date_only = selected_date.split(' ');
		var date_token = date_only[0].split('-');
		var selected_y = date_token[0];
		var selected_m = date_token[1];
		var selected_d = date_token[2];
		curr_date = date.getDate();
		curr_month = date.getMonth()+1;
		curr_year = date.getFullYear();
		hours = date.getHours(); //returns 0-23
		minutes = date.getMinutes(); //returns 0-59
		seconds = date.getSeconds(); //returns 0-59
		
		//convert in correct format
		if(curr_month < '10')
		{
			curr_month= '0'+curr_month;
		}
		
		if(curr_date < '10')
		{
			curr_date= '0'+curr_date;
		}
		
		if(hours < 10)
		{
			hours ='0'+hours;
		}
		if(minutes < 10)
		{
			minutes ='0'+minutes;
		}
		
	    SetTimeInMinute = parseInt(M)+parseInt(H)*60;
		OrderTimeInMinute = parseInt(orderTime_M)+parseInt(orderTime_H)*60;
		var chkflag = 1;
		console.log('date val > '+parseInt(selected_d) +'=='+ parseInt(curr_date))
		console.log('date > '+(parseInt(selected_d) == parseInt(curr_date)))
		
		if(parseInt(selected_d) == parseInt(curr_date))
		{
			
			console.log('time val > '+parseInt(SetTimeInMinute) +'>'+ parseInt(OrderTimeInMinute))
			console.log('time > '+(parseInt(SetTimeInMinute) > parseInt(OrderTimeInMinute)))
			
			if(parseInt(OrderTimeInMinute) > parseInt(SetTimeInMinute))
			{ 
			  chkflag = 0;
			}
			else
			{
				 chkflag = 1;
			}
		}
		//correct the format
		if(orderTime_H < 10)
		{
			orderTime_H ='0'+orderTime_H;
		}
		if(orderTime_M < 10)
		{
			orderTime_M ='0'+orderTime_M;
		}
		
		var input_date = curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds;		
		if(chkflag == 0)
		{
		   $('.error_time').show();
		   $('#timeH').val(orderTime_H);
		   $('#timeM').val(orderTime_M);
		   $('#date-value1-2').html(date);
		   $('#date-value1-23').val(input_date);
		   $('#ss').attr('disabled',true)
		}
		else
		{
			$('.error_time').hide();
			$('#ss').attr('disabled',false)
		}
	}
	</script>
	<style type="text/css">
		.error_time{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
		.error_time2{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
	</style>

@endsection
