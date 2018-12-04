@extends('layouts.master')

@section('head-scripts')

<?php 
// if(Auth::check()){
//     $username = Auth::user()->email;
// }else{
    // $username = explode("-",$_GET['m'])[1];
//}
?>
<script src="{{asset('notifactionJs/newNotifaction/App42.js')}}"></script>
<script src="{{asset('notifactionJs/newNotifaction/jQuery.js')}}"></script>
<script src="{{asset('notifactionJs/newNotifaction/browser.js')}}"></script>
<script type="text/javascript">
    'use strict';
var API_KEY = "{{env('APP42_API_KEY')}}"
var SECERT_KEY = "{{env('APP42_API_SECRET')}}"

var userName = <?php echo "'" . $username . "'"; ?>;
var type = jQuery.browser.name;

    $.post("{{url('update-browser')}}", {_token: "{{ csrf_token() }}", email: userName, browser: type}, 
        function(data, status){
        console.log("Data: " + data + "\nStatus: " + status);
    });

if ('serviceWorker' in navigator) {
  var jsAddress = "{{asset('notifactionJs/chrome-worker.js')}}";
  if(type== "Firefox"){
      jsAddress = "{{asset('notifactionJs/firefox-worker.js')}}";
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
                    regID = sub.endpoint.replace(/ /g,'');
                }

        @if(isset($_GET['m']))
            $.post("{{url('store-device-token')}}", {_token: "{{ csrf_token() }}", email: "{{$_GET['m']}}", deviceToken: regID}, 
                        function(data, status){
                        console.log(data);
            });
        @endif    

                registerDeviceWithApp42(regID,type.toUpperCase())   
          }).catch(function(e) {
            // Handle Exception here
          });
      } else {
       regID = sub.endpoint

        if(type=="Chrome"){
            var idD = regID.substring(regID.indexOf("d/")+1);
            regID =  idD.substring(idD.indexOf("/")+1);
        }else if(type=="Firefox" || type=="Safari"){
            var idD = regID.substring(regID.indexOf("v1/")+ 1);
            regID = sub.endpoint.replace(/ /g,'');
        }
        @if(isset($_GET['m']))
            $.post("{{url('store-device-token')}}", {_token: "{{ csrf_token() }}", phone: "{{$_GET['m']}}", deviceToken: regID}, 
                        function(data, status){
                        console.log(data);
            });
        @endif
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
            // window.close();
        }  
    });  
}
</script>

 @endsection      

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('kitchenCss/style.css')}}">
<style>
</style>

<div data-role="header" class="header" id="nav-header"  data-position="fixed"><!--  -->
    <div class="nav_fixed">
        <div class="logo">
            <div class="inner-logo">
                <img src="{{asset('images/logo.png')}}">
                @if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
            </div>
        </div>
        <a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
    </div>
</div>

	<div role="main" data-role="main-content" class="content">
		<div class="order_display">
			<div class="order_bg">
				<div class="order-ready-text">
					<p>{{ __('messages.Thanks for your order') }} </p>
					<p>{{ __('messages.Order Number') }} </p>
					<p class="order-no">{{$order->customer_order_id}}</p>
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
			<div class="table-wrap">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive ui-table">
					<tbody>
						@foreach($orderDetails as $orderDetail)
							<tr>
								<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
							</tr>	
						@endforeach
						<tr class="last-row">
							<td> </td>
							<td>         </td>
							<td>  {{ __('messages.TOTAL') }}    {{$order->currencies}} {{$order->order_total}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

    <div data-role="footer" id="footer" data-position="fixed">
            <div class="ui-grid-c inner-footer center">
            <div class="ui-block-a"><a href="{{ Session::get('route_url')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
                <div class="img-container">
                    <img src="{{asset('images/icons/select-store_01.png')}}">
                </div>
                <span>{{ __('messages.Restaurant') }}</span>
            </a></div>
            <div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
                <div class="img-container">
                    <img src="{{asset('images/icons/select-store_03.png')}}">
                </div>
                <span>{{ __('messages.Send') }}</span>
            </a></div>
            @include('orderQuantity')
            
    
            <div class="ui-block-d">
                <a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
                    <div class="img-container">
                        <img src="{{asset('images/icons/select-store_07.png')}}">
                    </div>
                </a>
            </div>
            </div>
    </div>
@endsection

@section('footer-script')


@endsection