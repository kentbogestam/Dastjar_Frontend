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


<script type="text/javascript">
	var now = new Date();
	var yearVal = now.getFullYear();
  	var monthVal = now.getMonth()+1;
  	var dayVal = now.getDate();
  	var hourVal = 00;
  	var minuteVal = 00;


function openDate() {
	var days = { };
	var years = { };
	var months = { 1: 'Jan', 2: 'Feb', 3: 'Mar', 4: 'Apr', 5: 'May', 6: 'Jun', 7: 'Jul', 8: 'Aug', 9: 'Sep', 10: 'Oct', 11: 'Nov', 12: 'Dec' };
	
	for( var i = 1; i < 32; i += 1 ) {
		days[i] = i;
	}

	for(i = now.getFullYear(); i < now.getFullYear()+20; i += 1) {
		years[i] = i;
	}



	SpinningWheel.addSlot(years, 'right', yearVal);
	SpinningWheel.addSlot(months, '', monthVal);
	SpinningWheel.addSlot(days, 'right', dayVal);
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(openTimePicker);
	
	SpinningWheel.open();
	$("#sw-cancel").hide();

}

function openTime() {
	$("#sw-cancel").show();

	var minutes = {00:00,  01: 01, 02: 02, 03: 03, 04: 04, 05:05, 06:06, 07:07, 08:08, 09:09, 10: 10, 11: 11, 12: 12, 13:13, 14:14, 15: 15, 16: 16, 17: 17, 18: 18, 19: 19, 20:20, 21: 21, 22: 22, 23: 23, 24: 24, 25: 25, 26: 26, 27: 27, 28: 28, 29: 29, 30:30, 31: 31, 32: 32, 33: 33, 34: 34, 35: 35, 36: 36, 37: 37, 38: 38, 39: 39, 40:40, 41: 41, 42: 42, 43: 43, 44: 44, 45: 45, 46: 46, 47: 47, 48: 48, 49: 49, 50:50, 51: 51, 52: 52, 53: 53, 54: 54, 55: 55, 56: 56, 57: 57, 58: 58, 59: 59, 60:60 };

	var hours = { 00:00, 01: 01, 02: 02, 03: 03, 04: 04, 05: 05, 06: 06, 07: 07, 
		08: 08, 09: 09, 10: 10, 11: 11, 12: 12 };
	

	SpinningWheel.addSlot(hours, 'right', hourVal);
	SpinningWheel.addSlot(minutes, '', minuteVal);
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function openOneSlot() {
	SpinningWheel.addSlot({1: 'Ichi', 2: 'Ni', 3: 'San', 4: 'Shi', 5: 'Go'});
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function openTimePicker() {
	var results = SpinningWheel.getSelectedValues();

	yearVal=results.keys[0];
	monthVal=results.keys[1];
	dayVal=results.keys[2];

	SpinningWheel.removeAllSlot();
	SpinningWheel.close();

	openTime();
}

function done() {
	var results = SpinningWheel.getSelectedValues();

	hourVal=results.keys[0];
	minuteVal=results.keys[1];

			if(hourVal == 00 && minuteVal == 00){
				$('.error_time').show();
				console.log(timeHH);
			}else{
				$('.error_time').hide();
				var selDate = new Date(yearVal+"-"+monthVal+"-"+dayVal+" "+hourVal+":"+minuteVal);
                $('#date-value1-2').text(selDate);
                $('#date-value1-23').val(selDate);				
				// $("#form").submit();
			}

}

function cancel() {
	var results = SpinningWheel.getSelectedValues();

	hourVal=results.keys[0];
	minuteVal=results.keys[1];

	SpinningWheel.removeAllSlot();
	SpinningWheel.close();
	openDate();
}


window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

$(document).ready(function(){
	var ua= navigator.userAgent, tem, 
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(M[1]=="Safari"){
	    	$(".date_block").show();
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
            startDate: date,
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