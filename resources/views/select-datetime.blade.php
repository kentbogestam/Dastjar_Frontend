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
<script type="text/javascript" src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datetime/jquery.simple-dtpicker.js')}}"></script>
<link type="text/css" href="{{asset('js/datetime/jquery.simple-dtpicker.css')}}" rel="stylesheet" />


	<!-- <script type="text/javascript" src="{{asset('js/jquery.datetimepicker.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/dateFormat.min.js')}}"></script>-->
	<script type="text/javascript" src="{{asset('js/jquery-dateformat.min.js')}}"></script> 
	<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.min.js"></script>

<script type="text/javascript">

window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

/*$(document).ready(function(){
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
	    	$("#dateandtime").show();
	    }
});*/
</script>
@endsection
@section('content')
	<div data-role="page" data-theme="c">
		@include('includes.headertemplate')

		<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('eat-later') }}">
			{{ csrf_field() }}
			<div role="main" data-role="main-content" class="content" id="wrapper">
				<div class="">
		        	<!--<div id="demo1-2"></div>-->
		        	<div class="show-date-time">
				        <span id="date-value1-2" class="date_show_section" value = ""></span>
				        <input type="hidden" id="date-value1-23" name="dateorder" value="" />
		    			<div class="go-btn">
		    				<button type="button" id="ss" class="fa fa-chevron-right"></button>
		    			</div>
		    		</div>
  <div class="dateandtime" id="dateandtime">
		        	<!--<h4>Only allow future datetimes ("futureOnly": true)</h4>-->
		            
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
						 <input type="text" name="date16" id="date16" value="">
</div>

					<!--<div class="date_block">
					  Date and Time:
					  <input type="datetime-local" id="bdaytime" name="bdaytime" value="" min="">
					  <span>Please click on the date above to change the date</span>
					  <p class='error_apple_time'>Date and Time is not valid. </p>
					</div>-->

		    		
				</div>	
	    		
			</div>
		</form>

		@include('includes.fixedfooter')
	</div>

@endsection

@section('footer-script')


	<script type="text/javascript">
		/*$('.error_time').hide();	*/
		 $(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });

		var tz = moment.tz.guess();

		var date = new Date();
		date.setMonth(date.getMonth());
		date.setDate(date.getDate());
		date.setHours(00, 00, 00);

		var startDate = new Date();
		startDate.setMonth(startDate.getMonth());
		startDate.setDate(startDate.getDate());
		startDate.setHours(00, 00, 00);

		var curr_date = date.getDate();
		var curr_month = date.getMonth()+1;
		var curr_year = date.getFullYear();
		var hours = date.getHours(); //returns 0-23
		var minutes = date.getMinutes(); //returns 0-59
		var seconds = date.getSeconds(); //returns 0-59

		var d = new Date();
		var hr = d.getHours(); //returns 0-23
		var min = d.getMinutes(); //returns 0-59
		var sec = d.getSeconds(); //returns 0-59

		hours=hr;
		minutes=min;
		seconds=sec;

        dateVal=$.format.date(curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds, "E MMM dd yyyy HH:mm:ss");
        $('#date-value1-2').html(dateVal);
        $('#date-value1-23').val(date);

	 /*  $('#demo1-2').datetimepicker({
            date: date,
            startDate: startDate,
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

                $('#date-value1-2').text(dateVal);
                $('#date-value1-23').val(this.getValue());
            }
        });*/

         $("#ss").click(function(e){
	   		
	   			var timeHH = $('#timeH').val();
				var timeMM = $('#timeM').val();

				var curDate = new Date().getTime();
				var selDate = new Date($('#date16').val()).getTime();

			      var cur=  new Date();
			var sel = new Date($('#date16').val());

                 alert(cur.getDate());alert(sel.getDate());

				hdate = moment(selDate).toDate();
				utcdate = moment.utc(hdate);

               if(cur.getDate()==sel.getDate()){
					
                  $('.error_time3').show();
					
				
				}else{
					$('#date-value1-23').val(hdate);					
					$('.error_time').hide();
					$("#form").submit();
				}
	   		
		});


	   $(document).ready(function(){

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

	  $('.dateandtime').append("<p class='error_time'>Please enter PickUp time in 24 hours format. </p>"+"<p class='error_time2'>Date and Time is not valid. </p>"+"<p class='error_time3'>Order Date Cannot be Current Date </p>");
	// var lar_r =   $('.tt tbody').find('tr:first')
	// var bb = $(lar_r).append('<td class=""></td>');


	</script>
	<style type="text/css">
		.error_time{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
		.error_time2{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
		.error_time3{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
	</style>

@endsection
