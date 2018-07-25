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

		.mob_num{
			max-width: 200px; 
			margin-left: auto; 
			margin-right: auto; 
			margin-top: 20px;
		}

		.mob_num label{
			color: #1275ff;
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

<script src="{{asset('locationJs/currentLocation.js')}}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>
<script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
<script src="{{asset('notifactionJs/SiteTwo.js')}}"></script> 
<script src="{{asset('notifactionJs/serviceWorker.js')}}"></script> 

<script>
	  $(document).ready(function () {
	      // App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
	      // App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

	      // App42.initialize(API_KEY,SECERT_KEY);
	      // App42.enableEventService(true);
	      // var userName,device_token;
	      // new Fingerprint2().get(function(result, components){
	      //     userName = "{{ Auth::user()->email}}";
	      //     console.log("Username : " + userName); //a hash, representing your device fingerprint
	      //     App42.setLoggedInUser(userName);
	      //    /// getDeviceToken();
	      // });
	  });
</script>

<script src="{{asset('notifactionJs/newNotifaction/App42.js')}}"></script>
<script src="{{asset('notifactionJs/newNotifaction/jQuery.js')}}"></script>
<script src="{{asset('notifactionJs/newNotifaction/browser.js')}}"></script>
<script type="text/javascript">
'use strict';
var API_KEY = "{{env('APP42_API_KEY')}}";
var SECERT_KEY = "{{env('APP42_API_SECRET')}}";

var userName = "{{ Auth::user()->email}}";
if ('serviceWorker' in navigator) {
	alert(userName);

  var type = jQuery.browser.name;
  var jsAddress = "{{asset('notifactionJs/chrome-worker.js')}}";

  if(type== "Firefox"){
      jsAddress = "{{asset('notifactionJs/firefox-worker.js')}}";
  }else if(type== "Safari"){
      jsAddress = "{{asset('notifactionJs/safari-worker.js')}}";
  }

  navigator.serviceWorker.register(jsAddress).then(function(reg) {
     reg.pushManager.getSubscription().then(function(sub) {  
    var regID ;
      if (sub === null) {
        reg.pushManager.subscribe({userVisibleOnly: true}).then(function(sub) {
            regID = sub.endpoint
                if(type=="Chrome"){
                    var idD = regID.substring(regID.indexOf("d/")+1);
                    regID =  idD.substring(idD.indexOf("/")+1);
                }else if(type=="Firefox" || type=="Safari"){
                    var idD = regID.substring(regID.indexOf("v1/")+ 1);
                    regID = sub.endpoint.replace(/ /g,'')
                }


        	$.post("{{url('store-device-token')}}", {_token: "{{ csrf_token() }}", email: "{{ Auth::user()->email}}", deviceToken: regID}, 
                        function(data, status){
                        console.log(data);
            });
                registerDeviceWithApp42(regID,type.toUpperCase())   
          }).catch(function(e) {
            // Handle Exception here
            console.log(e.message);
          });
      } else {
       regID = sub.endpoint
        if(type=="Chrome"){
            var idD = regID.substring(regID.indexOf("d/")+1);
            regID =  idD.substring(idD.indexOf("/")+1);
        }else if(type=="Firefox" || type=="Safari"){
            var idD = regID.substring(regID.indexOf("v1/")+ 1);
            regID = sub.endpoint.replace(/ /g,'')
        }

        	$.post("{{url('store-device-token')}}", {_token: "{{ csrf_token() }}", email: "{{ Auth::user()->email}}", deviceToken: regID}, 
                        function(data, status){
                       console.log(data);
            });
        registerDeviceWithApp42(regID,type.toUpperCase())   
      }
    });
  })
   .catch(function(err) {
    console.log('Service Worker registration failed: ');
  });
}

function registerDeviceWithApp42(token,type ){
    var pushNotificationService  = new App42Push();
    App42.initialize(API_KEY, SECERT_KEY);
    pushNotificationService.storeDeviceToken(userName,token,type,{  
        success: function(object) 
        {  
            // window.close();
        },
        error: function(error) {  
            window.close();
        }  
    });  
}
</script>
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
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			@if ($message = Session::get('success'))
				<div class="table-content sucess_msg">
					<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
					 @if(is_array($message))
			            @foreach ($message as $m)
			                {{ $languageStrings[$m] or $m }}
			            @endforeach
			        @else
			            {{  __("messages.$message") }}
			        @endif
			    </div>
			@endif
			<div class="wait-bg-img">
				<div class="text-content">
					<p>{{ __('messages.Thanks for your order') }} </p>
					<p>{{ __('messages.Order Number') }} </p>
					<p class="large-text">{{$order->customer_order_id}}</p>
					<p>({{$order->store_name}})</p>
					<p>
						<?php
							$time = $order->order_delivery_time;
							$time2 = $storeDetail->extra_prep_time;
							$secs = strtotime($time2)-strtotime("00:00:00");
							$result = date("H:i:s",strtotime($time)+$secs);
						?>

						@if($order->order_type == 'eat_later')
						{{ __('messages.Your order will be ready on') }}
						{{$order->deliver_date}}
						{{date_format(date_create($order->deliver_time), 'G:i')}} 
						@else
						{{ __('messages.Your order will be ready in about') }}
							@if(date_format(date_create($result), 'H')!="00")
							{{date_format(date_create($result), 'H')}} hours 						
							@endif
						{{date_format(date_create($result), 'i')}} mins
						@endif
					</p>
				</div>
			</div>
			<div class="table-content">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
					@foreach($orderDetails as $orderDetail)
						<tr>
							<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
						</tr>	
					@endforeach
				<tr class="last-row">	<td> </td><td>         </td><td>  TOTAL:-    {{$order->currencies}} {{$order->order_total}}</td></tr>
				</tr>
				</table>
			</div>

			@if($order->order_type=="eat_later")
			<div>
				<button style="" class="cancel-order-btn">Cancel Order Request</button>
			</div>	
			@endif

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

<div class="pop_up">   
		<div class="pop_up_inner">   
		  <article class="pop_up_content">
		  	<form action="{{url('cancel-order')}}" method="post" id="cancel-order-form" data-ajax="false">
			@if(Auth::check())
				@if(Auth::user()->language == 'ENG')
					<?php $lan = "eng" ?>
				@elseif(Auth::user()->language == 'SWE')
					<?php $lan = "swe" ?>
				@endif
			@else
				@if(Session::get('browserLanguageWithOutLogin') == 'ENG')
					<?php $lan = "eng" ?>
				@elseif(Session::get('browserLanguageWithOutLogin') == 'SWE')
					<?php $lan = "swe" ?>
				@endif
			@endif

			@if($lan == "eng")
				<div>
					<p>
						An order can only be canceled, not later than 24 hours prior to the delivery time. Leave your mobile number here, then the restaurant will call you back.
					</p>
					<p>
						Note: Confirmation is only valid after restaurant callback.
					</p>
				</div>
			@elseif($lan == "swe")
				<div>
					<p>
						An order can only be canceled, not later than 24 hours prior to the delivery time. Leave your mobile number here, then the restaurant will call you back.
					</p>
					<p>
						Note Confirmation is only valid after restaurant callback.
					</p>
				</div>
			@endif

			<input type="hidden" name="order_id" value="{{$order->order_id}}">
			<input type="hidden" name="order_number" value="{{$order->customer_order_id}}">

			<div class="mob_num" style="">
				<label>Mobile Number</label>
				<input type="tel" name="mobile_number" value="{{$user->phone_number}}" required>
			</div>

			{{csrf_field()}}

			<div class="pop_up-footer">
				<button type="submit" class="accept-btn submit_btn">OK</button>
			</div>
			</form>
			</article>        
			  <a class="popup-close1" onclick="off()">x</a>
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
