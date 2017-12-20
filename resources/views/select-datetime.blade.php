@extends('layouts.master')
@section('content')
	<div data-role="page" data-theme="c">
		<div data-role="header" class="header" data-position="fixed"> 
			<div class="nav_fixed">
				<a href="home.html" data-ajax="false" class="ui-btn-left text-left backarrow-btn"><img src="images/icons/backarrow.png" width="11px"></a>
				<div class="logo">
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						<span>Kent</span>
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
				        <span id="date-value1-2" value = ""></span>
				        <input type="hidden" id="date-value1-23" name="dateorder" value="" />
		    		</div>
		    		<div class="go-btn">
		    			<input type="button" value="Go" id="ss"/>
		    		</div>
			</div>
		</form>
		<div data-role="footer" class="footer" data-position="fixed">
			<div class="ui-grid-c inner-footer center">
				<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_01.png')}}">
					</div>
					<span>Restaurant</span>
				</a></div>
				<div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_03.png')}}">
					</div>
					<span>send</span>
				</a></div>
				<div class="ui-block-c"><a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_05.png')}}">
						<!-- <img src="images/icons/select-store_05-active.png"> -->
					</div>
					<span >Order<!-- <span class="order-number">3</span> --></span>
				</a></div>
				<div class="ui-block-d"><a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
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

		var date = new Date();
		
		date.setDate(date.getDate() + 1);
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

		
        	$("#form").submit();
		
	})
	</script>

@endsection