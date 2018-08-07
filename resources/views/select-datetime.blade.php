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
		}

		
	</style>

<script src="{{asset('locationJs/currentLocation.js')}}"></script>

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

			    if(day.length < 2) { day = "0" + day; }
			    if(mon.length < 2) { mon = "0" + mon; }

			    var date = new String( yr + '-' + mon + '-' + day );
	    	alert(date);

	    	$("#bdaytime").disabled = false; 
			$("#bdaytime").setAttribute('min', date);

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
				<a class="ui-btn-right map-btn user-link" href="#left-side-bar"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>

		<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('eat-later') }}">
			{{ csrf_field() }}
			<div role="main" data-role="main-content" class="content" id="wrapper">
				<div class="">
		        	<div id="demo1-2"></div>

					<div class="date_block">
					  Date and Time:
					  <input type="datetime-local" id="bdaytime" name="bdaytime" value="{{strtotime('now')}}">
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

	<script type="text/javascript">
		/*$('.error_time').hide();	*/
		 $(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });


		var date = new Date();
		date.setMonth(date.getMonth()+1);
		date.setDate(date.getDate());
		date.setHours(00, 00, 00);

		var startDate = new Date();
		startDate.setMonth(startDate.getMonth()+1);
		startDate.setDate(startDate.getDate());
		startDate.setHours(00, 00, 00);

		var curr_date = date.getDate();
		var curr_month = date.getMonth();
		var curr_year = date.getFullYear();
		var hours = date.getHours(); //returns 0-23
		var minutes = date.getMinutes(); //returns 0-59
		var seconds = date.getSeconds(); //returns 0-59

        dateVal=$.format.date(curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds, "E MMM dd yyyy HH:mm:ss");
        $('#date-value1-2').html(dateVal+" GMT+05:30 (India Standard Time)");
        $('#date-value1-23').val(date);

	   $('#demo1-2').datetimepicker({
            date: date,
            // startDate: startDate,
            viewMode: 'YMDHM',
            onDateChange: function(){
                $('#date-text1-2').text(this.getText());
                $('#date-text-ymd1-2').text(this.getText('yyyy-MM-dd'));
                dateNew = this.getValue();
		
				curr_date = dateNew.getDate();
				curr_month = dateNew.getMonth()+1;
				curr_year = dateNew.getFullYear();
				hours = dateNew.getHours(); //returns 0-23
				minutes = dateNew.getMinutes(); //returns 0-59
				seconds = dateNew.getSeconds(); //returns 0-59
                dateVal=$.format.date(curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds, "E MMM dd yyyy HH:mm:ss");

                $('#date-value1-2').text(dateVal+" GMT+05:30 (India Standard Time)");
                $('#date-value1-23').val(this.getValue());
            }
        });

	   $("#ss").click(function(e){
	   		if($("#demo1-2").css('display') == 'block'){
	   			var timeHH = $('#timeH').val();
				var timeMM = $('#timeM').val();

				var curDate = new Date().getTime();
				var selDate = new Date($('#date-value1-23').val()).getTime();

				if(timeHH == 00 && timeMM == 00){
					$('.error_time').show();
					console.log(timeHH);
				}else if(selDate<curDate){
					$('.error_time2').show();
					console.log(timeHH);
				}
				else if(timeHH == 00 && timeMM != 00){
					$('.error_time').hide();
					$("#form").submit();
				}else if(timeHH != 00 && timeMM == 00){
					$('.error_time').hide();
					$("#form").submit();
				}else{
					$('.error_time').hide();
					$("#form").submit();
				}
	   		}else{
				dateVal = new Date($("#bdaytime").val());
                $('#date-value1-2').text(dateVal);
                $('#date-value1-23').val(dateVal);
				$("#form").submit();
	   		}
		});


	  $('.perfect-datetimepicker').append("<p class='error_time'>Please enter PickUp time in 24 hours format. </p>"+"<p class='error_time2'>Date and Time is not valid. </p>");
	// var lar_r =   $('.tt tbody').find('tr:first')
	// var bb = $(lar_r).append('<td class=""></td>');
	</script>
	<style type="text/css">
		.error_time{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
		.error_time2{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
	</style>

@endsection