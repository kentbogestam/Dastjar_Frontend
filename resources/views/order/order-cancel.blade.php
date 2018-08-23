@extends('layouts.master')
@section('head-scripts')
	<style>
		.cancel-order-btn{
			background: #1275ff; 
			color: #FFFFFF !important; 
			width: auto !important; 
			margin-left: auto;
		    margin-right: auto;
		}

		.submit_btn {
			position: relative;
			display: block;
			margin: 15px auto;
			padding: 10px;
			width: 100%;
			overflow: hidden;
			border-width: 0;
			outline: none;
			border-radius: 2px;
			/* box-shadow: 0 1px 4px rgba(0, 0, 0, .6); */
			box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);

			background-color: #ff7f50;
			color: #ecf0f1;			
			transition: background-color .3s;
		}

		.submit_btn > * {
			position: relative;
		}

		.submit_btn span {
			display: block;
			padding: 12px 24px;
		}

		.submit_btn:before {
			content: "";			
			position: absolute;
			top: 50%;
			left: 50%;
			
			display: block;
			width: 0;
			padding-top: 0;
				
			border-radius: 100%;			
			background-color: rgba(236, 240, 241, .3);
			
			-webkit-transform: translate(-50%, -50%);
			-moz-transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			-o-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		.accept-btn{
			font-family: "ProximaNova-Regular";
			display: inline-block;
			/* float: right; */
			max-width: 100px;		
			background-color: #7ebe12;
			color: #fff !important;	
		}

		.pop_up {
			position: fixed;
			display: none;
			font-family: 'Roboto';
			top: 0;
			left: 0;
			max-height: 95vw;
			width: 80vw;
			top: 50%;
			left: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			-webkit-animation: fadeIn 500ms linear;
			animation: fadeIn 500ms linear;
			z-index: 9999;
		}

		.pop_up_inner {
			max-height: 95vw;
			color: #333;
			background-color: #FFFFFF;
			border-radius: 5px;
			-webkit-box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.5);
					box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.5);
			overflow-y: auto;
		}

		.pop_up h2 {
			font-family: "ProximaNova-Regular";
			text-align: center;
			color: #7ebe12;
		}

		.pop_up p {
			font-family: "ProximaNova-Regular";
			text-align: justify;
		}

		.pop_up-footer{
			text-align: center;
		}

		.popup-close1 {
			width: 30px;
			height: 26px;
			padding-top: 4px;
			display: inline-block;
			position: absolute;
			top: 0px;
			right: 0px;
			-webkit-transition: ease 0.25s all;
			transition: ease 0.25s all;
			-webkit-transform: translate(50%, -50%);
			transform: translate(50%, -50%);
			border-radius: 100% !important;
			background: #7ebe12;
			font-family: Arial, Sans-Serif;
			font-size: 20px;
			text-align: center;
			line-height: 0.8;
			color: #fff;
			cursor: pointer;
			padding-left: 0px;
			z-index: 999;
		}

		.popup-close1:hover {
			text-decoration: none;
		}

		/*  Ripple */
		.ripple {
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.4);
			transform: scale(0);
			position: absolute;
			opacity: 1;
		}

		.rippleEffect {
			/* box-shadow: 0 3px 7px rgba(0, 0, 0, .6); */
			animation: rippleDrop .6s linear;
		}
		
		@keyframes rippleDrop {
			100% {
			transform: scale(2);
			opacity: 0;
			}
		}

		@media only screen and (min-width: 768px) {
			.pop_up_inner {
				padding: 30px;
			}
		}

		@media only screen and (max-width: 768px) {
			.pop_up {
				margin-top: -50px;
			}

			.pop_up_inner {
				padding: 20px;
				max-height: 145vw;
			}

			.pop_up h2 {
				font-size: 25px;
			}
		}

		#overlay {
    		position: fixed;
    		display: none;
    		width: 100vw;
    		height: 100vh;
		    top: 0;
		    left: 0;
		    right: 0;
    		bottom: 0;
	    	background-color: rgba(0,0,0,0.5);
	    	z-index: 999;
		}

		#loading-img{
			display: none;
			position: absolute;
			top: 50%;
			left: 50%;
			-moz-transform: translate(-50%);
			-webkit-transform: translate(-50%);
			-o-transform: translate(-50%);
			-ms-transform: translate(-50%);
			transform: translate(-50%);
			z-index: 99999;
		}
	</style>


@endsection      

@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			<div class="inner-logo">
				<img src="{{asset('images/logo.png')}}"> 
				<span>{{ Auth::user()->name}}</span>
			</div>
		</div>
		<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content" style="text-align: center;">
		<div class="inner-page-container" style="margin-top: 100px; margin-bottom:100px; color: #7ebe12; font-size: 25px;">
			@if(Session::get('order_already_cancelled') == 1)
				Order Number {{$order_number}} Has Been Already Cancelled
			@else
				Cancel Request For Order Number {{$order_number}} Has Been Placed
			@endif
		</div>
		<div>
			<a href="{{url('')}}" style="color:#1275ff" data-ajax="false">Go To Home</a>
		</div>
	</div>
	
	<div data-role="footer" class="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
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



	  <img src="{{ asset('images/loading.gif') }}" id="loading-img" />

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')

<script type="text/javascript">
	 $(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });

	 $(".cancel-order-btn").click(function(){
		$('#overlay').show();
		$(".pop_up").show();
	});

	$("body").on('click',".accept-btn", function(){
			// $("#cancel-order-form").submit();
	});

	function off(){
		$("#loading-img").hide();
		$(".pop_up").hide();
		$('#overlay').hide();
	}
</script>

@endsection
