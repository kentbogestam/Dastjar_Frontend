@extends('layouts.master')
@section('head-scripts')
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('css/spinningwheel.css')}}" type="text/css" media="all">
	<script type="text/javascript" src="{{asset('js/spinningwheel-min.js')}}"></script>

	<style type="text/css">
		#wrapper{
			display: none;
			margin-top: -115px;
/*			height: 100vh !important;
*/		}

		
	</style>

<script src="{{asset('locationJs/currentLocation.js')}}"></script>

<script type="text/javascript">
function openWeight() {
	
	var numbers = { 0: 0, 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6, 7: 7, 8: 8, 9: 9 };
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot({ separator: '.' }, 'readonly shrink');
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot({ Kg: 'Kg', Lb: 'Lb', St: 'St' }, 'shrink');
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function openBirthDate() {
	var now = new Date();
	var days = { };
	var years = { };
	var months = { 1: 'Jan', 2: 'Feb', 3: 'Mar', 4: 'Apr', 5: 'May', 6: 'Jun', 7: 'Jul', 8: 'Aug', 9: 'Sep', 10: 'Oct', 11: 'Nov', 12: 'Dec' };
	
	for( var i = 1; i < 32; i += 1 ) {
		days[i] = i;
	}

	for(i = now.getFullYear(); i < now.getFullYear()+20; i += 1) {
		years[i] = i;
	}

	SpinningWheel.addSlot(years, 'right', 1999);
	SpinningWheel.addSlot(months, '', 4);
	SpinningWheel.addSlot(days, 'right', 12);
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function openTime() {
	var now = new Date();
	var minutes = { 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6, 7: 7, 8: 8, 9: 9, 10: 10, 11: 11, 12: 12, 13:13, 14:14, 15: 15, 16: 16, 17: 17, 18: 18, 19: 19, 20:20, 21: 21, 22: 22, 23: 23, 24: 24, 25: 25, 26: 26, 27: 27, 28: 28, 29: 29, 30:30, 31: 31, 32: 32, 33: 33, 34: 34, 35: 35, 36: 36, 37: 37, 38: 38, 39: 39, 40:40, 41: 41, 42: 42, 43: 43, 44: 44, 45: 45, 46: 46, 47: 47, 48: 48, 49: 49, 50:50, 51: 51, 52: 52, 53: 53, 54: 54, 55: 55, 56: 56, 57: 57, 58: 58, 59: 59, 60:60 };

	var hours = { 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6, 7: 7, 8: 8, 9: 9, 10: 10, 11: 11, 12: 12 };
	
/*	
	for( var i = 1; i < 60; i += 1 ) {
		minutes[i] = i;
	}
*/
	SpinningWheel.addSlot(hours, 'right', 12);
	SpinningWheel.addSlot(minutes, '', 12);
	
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

function done() {
	// SpinningWheel.close();
	SpinningWheel.close();
	openTime();

	var results = SpinningWheel.getSelectedValues();
	alert('values: ' + results.values.join(' ') + '<br />keys: ' + results.keys.join(', '));
	document.getElementById('result').innerHTML = 'values: ' + results.values.join(' ') + '<br />keys: ' + results.keys.join(', ');
}

function cancel() {
	document.getElementById('result').innerHTML = 'cancelled!';
}


window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

$(document).ready(function(){
	var ua= navigator.userAgent, tem, 
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(M[1]=="Safari"){
	    	$("#wrapper").empty();
	    	$("#wrapper").show();
			openBirthDate();
	    }else{
	    	$("#wrapper").show();
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

		        	<div id="demo1-2"></div>
		    		<div class="show-date-time">
				        <span id="date-value1-2" class="date_show_section" value = ""></span>
				        <input type="hidden" id="date-value1-23" name="dateorder" value="" />
		    			<div class="go-btn"><button id="ss" class="fa fa-chevron-right"></button></div>
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
	<script type="text/javascript">
		/*$('.error_time').hide();	*/
		 $(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });


		var date = new Date();
		date.setDate(date.getDate() + 1);
		date.setHours(00, 00, 00);
		var dateToday = new Date();


		// $('#date-text1-2').text(this.getText());
  //               $('#date-text-ymd1-2').text(this.getText('yyyy-MM-dd'));
                $('#date-value1-2').html(date);
                $('#date-value1-23').val(date);


	   $('#demo1-2').datetimepicker({
            date: date,
            startDate: date,
            viewMode: 'YMDHM',
            onDateChange: function(){
                $('#date-text1-2').text(this.getText());
                $('#date-text-ymd1-2').text(this.getText('yyyy-MM-dd'));
                $('#date-value1-2').text(this.getValue());
                $('#date-value1-23').val(this.getValue());
            }

        });

	   $("#ss").click(function(e){
			var timeHH = $('#timeH').val();
			var timeMM = $('#timeM').val();
			if(timeHH == 00 && timeMM == 00){
				$('.error_time').show();
				console.log(timeHH);
			}else if(timeHH == 00 && timeMM != 00){
				$('.error_time').hide();
				$("#form").submit();
			}else if(timeHH != 00 && timeMM == 00){
				$('.error_time').hide();
				$("#form").submit();
			}else{
				$('.error_time').hide();
				$("#form").submit();
			}
		
	})
	  $('.perfect-datetimepicker').append("<p class='error_time'>Please enter PickUp time in 24 hours format. </p>");
	// var lar_r =   $('.tt tbody').find('tr:first')
	// var bb = $(lar_r).append('<td class=""></td>');
	</script>
	<style type="text/css">
		.error_time{color: red; font-size: 14px; text-align: center;margin-top: 15px; display: none;}
	</style>

@endsection