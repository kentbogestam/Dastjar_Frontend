@extends('layouts.master')
@section('head-scripts')
<script src="{{asset('locationJs/currentLocation.js')}}"></script>
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
		    			<div class="go-btn"><input type="button" value="{{ __('messages.Go') }}" id="ss"/></div>
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