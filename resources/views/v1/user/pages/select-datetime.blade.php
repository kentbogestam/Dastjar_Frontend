@extends('v1.user.layouts.master')

@section('head-scripts')
	<style type="text/css">
		.show-date-time {
			padding: 10px 10px;
		}
		.dateandtime .datepicker{width: 100%;}
		.datepicker > .datepicker_inner_container > .datepicker_calendar > .datepicker_table > tbody > tr > td.today{border: 2px solid #bd2b2b !important;}
		.datepicker > .datepicker_inner_container > .datepicker_calendar > table{width: 100%;}
		.datepicker > .datepicker_inner_container > .datepicker_calendar{width: 80% !important;}
		.datepicker > .datepicker_inner_container > .datepicker_timelist{width: 19% !important;text-align: center !important;}
		.datepicker > .datepicker_inner_container > .datepicker_calendar > .datepicker_table > tbody > tr > td.active{background-color: #bd2b2b !important;border-bottom: none !important;}
		.datepicker > .datepicker_inner_container > .datepicker_calendar > .datepicker_table > tr > th {
		    color: #646464;
		    width: 18px;
		    font-size: small;
		    font-weight: normal;
		    text-align: center;
		}
	</style>
	<script type="text/javascript" src="{{asset('js/datetime/jquery.simple-dtpicker.js')}}"></script>
	<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
	<script type="text/javascript" src="{{asset('js/datetime/select-date-time.js')}}"></script>
	<link type="text/css" href="{{asset('css/dateandtime/jquery.simple-dtpicker.css')}}" rel="stylesheet" />
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<script type="text/javascript">
		// 
		if(ios && (!standalone && !safari))
		{
			requestGeoAddressToIosNative('setCurrentLatLong');
		}

		window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);
	</script>
@endsection

@section('content')
	<div class="">
		<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('eat-later') }}">
			{{ csrf_field() }}
			<div class="container-fluid">

				<div class="row">
					<br>
			   <p class="font-weight-bold"><b>{{ __('messages.CalendarText') }}</b></p>
					<div class="col-md-12 show-date-time">

				        <span id="date-value1-2" class="date_show_section" value = ""></span>
				        <input type="hidden" id="date-value1-23" name="dateorder" value="" />
					</div>
				</div>

				<div class="alert alert-danger hidden error_time">
					<p>{{ __('messages.eatLaterTimeError1') }}</p>
				</div>
				<div class="alert alert-danger hidden error_time2">
			 		<p>{{ __('messages.eatLaterTimeError2') }}</p>
				</div>
				<div class="alert alert-danger hidden error_time3">
			 		<p>{{ __('messages.eatLaterTimeError3') }}</p>
				</div>
			</div>
	 	 	<div class="dateandtime" id="dateandtime">
				<input type="hidden" name="date16" id="date16" value="" onchange="setDateTime()"/>
			</div>
			<div class="form-group text-center">
				<button class="btn btn-danger" type="button" id="ss" onclick="checkDate()">{{ __('messages.continue') }}</button>
			</div>
		</form>
	</div>
@endsection

@section('footer-script')
<script type="text/javascript">
	// Create date object 
	var todayDate = eatLaterDate = new Date();
	// eatLaterDate.setDate(todayDate.getDate() + 1); // Add 1 day in current date
	eatLaterDate.setHours(eatLaterDate.getHours()+2);
	// Get minutes in 15 min of interval
	minutes = eatLaterDate.getMinutes();
	interval = 15;
	minutes = Math.ceil(minutes/interval)*interval;
	eatLaterDate.setMinutes(minutes);
	
	$(function(){
		// Initialize datepicker
		$('*[name=date16]').appendDtpicker({
			"inline": true,
			"futureOnly": true,
			"todayButton": false,
			"minuteInterval": 15,
			"locale": "{{ (Session::get('applocale') === 'sv') ? 'sv' : 'en' }}",
			"dateFormat": "DD.MM.YY H:mmTT",
			"onInit": function(handler) {
				// handler.setDate(new Date(eatLaterDate.getFullYear(), eatLaterDate.getMonth(), eatLaterDate.getDate(), 12, 30, 0, 0));
				handler.setDate(new Date(eatLaterDate.getFullYear(), eatLaterDate.getMonth(), eatLaterDate.getDate(), eatLaterDate.getHours(), eatLaterDate.getMinutes(), 0, 0));
			}
		});
	});
</script>
@endsection