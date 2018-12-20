@extends('layouts.master')
@section('head-scripts')
	

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

<script type="text/javascript" src="{{asset('js/datetime/jquery.simple-dtpicker.js')}}"></script>
<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="{{asset('js/datetime/select-date-time.js')}}"></script>
<link type="text/css" href="{{asset('css/dateandtime/jquery.simple-dtpicker.css')}}" rel="stylesheet" />
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<script type="text/javascript">

window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

</script>
@endsection
@section('content')
	<div data-role="page" data-theme="c">
		@include('includes.headertemplate')

		<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('eat-later') }}">
			{{ csrf_field() }}
			<div role="main" data-role="main-content" class="content" id="wrapper">
				<div class="main-select-date">
		        	<div class="show-date-time">
				        <span id="date-value1-2" class="date_show_section" value = ""></span>
				        <input type="hidden" id="date-value1-23" name="dateorder" value="" />
		    			<div class="go-btn">
		    				<button type="button" id="ss" class="fa fa-chevron-right" onclick="checkDate()"></button>
		    			</div>
		    		</div>
	    		 <div class="error-show" id="error-show"></div>
             	 <div class="dateandtime" id="dateandtime">
		            
						<script type="text/javascript">
							$(function(){
								$('*[name=date16]').appendDtpicker({
									"inline": true,
									"futureOnly": true,
									"amPmInTimeList": true,
									"todayButton": false,
									"locale": "sv"
								});
							});
						</script>
						 <input type="hidden" name="date16" id="date16" value="" onchange="setDateTime()">
				</div>	
			</div>	
	    		
			</div>
		</form>

		@include('includes.fixedfooter')
	</div>

@endsection

@section('footer-script')


	<script type="text/javascript">	 	
		
		 $(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });


	  $('.error-show').append("<p class='error_time'>Please enter PickUp time in 24 hours format. </p>"+"<p class='error_time2'>Date and Time is not valid. </p>"+"<p class='error_time3'>Order Date Cannot be Current Date </p>");

	</script>
	<style type="text/css">
		.error_time{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
		.error_time2{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
		.error_time3{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
	</style>

@endsection
