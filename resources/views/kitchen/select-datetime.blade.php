@extends('layouts.blank')
@section('content')

	<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/kitchen-eat-later') }}">
			{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content">
			<div id="demo1-2"></div>
			<div class="show-date-time">
		        <span id="date-value1-2"></span>
		         <input type="hidden" id="date-value1-23" name="dateorder" value="" />
			</div>
			<div class="go-btn">
				<input type="button" value="Go" id="ss"/>
			</div>
		</div>
	</form>

@endsection

@section('footer-script')
	<script type="text/javascript" src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		 $(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });


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