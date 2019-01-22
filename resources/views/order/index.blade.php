@extends('layouts.master')
@section('head-scripts')
	<style>
	.ui-select {
	    border: 1px solid;
	}
	
	.ui-icon-carat-d:after {
	    background-repeat: no-repeat;
	}

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

		.country_code{
			max-width: 200px; 
			margin-left: auto; 
			margin-right: auto; 
			margin-top: 20px;
		}

		.country_code label{
			color: #1275ff;
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

			.grid-container {
			  display: grid;
			  grid-template-columns: auto auto;
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
  var type = jQuery.browser.name;
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
                    regID = sub.endpoint.replace(/ /g,'')
                }


        	$.post("{{url('store-device-token-order-view')}}", {_token: "{{ csrf_token() }}", email: "{{ Auth::user()->email}}", deviceToken: regID}, 
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
				<div class="text-content order-confirmation-block">
					@if($order->order_accepted)
						<p>{{ __('messages.Thanks for your order') }} </p>
						<p>{{ __('messages.Order Number') }} </p>
						<p class="large-text">{{$order->customer_order_id}}</p>
						<p>({{$order->store_name}})</p>
						@if( is_numeric($storeDetail->phone) )
							<p><i class="fa fa-phone" aria-hidden="true"></i> <span>{{ $storeDetail->phone }}</span></p>
						@endif
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
								@if(!$storeDetail->order_response && $order->extra_prep_time)
									{{ $order->extra_prep_time }} mins
								@else
									@if(date_format(date_create($result), 'H')!="00")
										{{date_format(date_create($result), 'H')}} hours 						
									@endif
									{{date_format(date_create($result), 'i')}} mins
								@endif
							@endif
						</p>
					@else
						<p>{{ __('messages.waitForOrderConfirmation') }} </p>
					@endif
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
	
	@include('includes.fixedfooter')

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
			<input type="hidden" name="store_id" value="{{$order->store_id}}">
			<input type="hidden" name="order_number" value="{{$order->customer_order_id}}">

			<div class="grid-container">
			<div class="country_code">
				<label for="select-native-5">Country Code</label>
				<select class="form-control" name="phone_number_prifix" id="select-native-5">
					<option class="text_field_new" selected="" value="US">United States - 1</option>
				   <option value="93">Afghanistan - 93</option>
				   <option value="355">Albania - 355</option>
				   <option value="213">Algeria - 213</option>
				   <option value="1684">American Samoa - 1684</option>
				   <option value="376">Andorra - 376</option>
				   <option value="244">Angola - 244</option>
				   <option value="1264">Anguilla - 1264</option>
				   <option value="672">Antarctica - 672</option>
				   <option value="1268">Antigua And Barbuda - 1268</option>
				   <option value="54">Argentina - 54</option>
				   <option value="374">Armenia - 374</option>
				   <option value="297">Aruba - 297</option>
				   <option value="61">Australia - 61</option>
				   <option value="43">Austria - 43</option>
				   <option value="994">Azerbaijan - 994</option>
				   <option value="1242">Bahamas - 1242</option>
				   <option value="973">Bahrain - 973</option>
				   <option value="880">Bangladesh - 880</option>
				   <option value="1246">Barbados - 1246</option>
				   <option value="32">Belgium - 32</option>
				   <option value="501">Belize - 501</option>
				   <option value="229">Benin - 229</option>
				   <option value="1441">Bermuda - 1441</option>
				   <option value="975">Bhutan - 975</option>
				   <option value="591">Bolivia - 591</option>
				   <option value="387">Bosnia and Herzegovina - 387</option>
				   <option value="267">Botswana - 267</option>
				   <option value="55">Brazil - 55</option>
				   <option value="1284">British Indian Ocean Territory - 1284</option>
				   <option value="673">Brunei Darussalam - 673</option>
				   <option value="359">Bulgaria - 359</option>
				   <option value="226">Burkina Faso - 226</option>
				   <option value="257">Burundi - 257</option>
				   <option value="855">Cambodia - 855</option>
				   <option value="237">Cameroon - 237</option>
				   <option value="1">Canada - 1</option>
				   <option value="238">Cape Verde - 238</option>
				   <option value="1345">Cayman Islands - 1345</option>
				   <option value="236">Central African Republic - 236</option>
				   <option value="235">Chad - 235</option>
				   <option value="56">Chile - 56</option>
				   <option value="86">China - 86</option>
				   <option value="618">Christmas Island - 618</option>
				   <option value="61">Cocos (Keeling) Islands - 61</option>
				   <option value="57">Colombia - 57</option>
				   <option value="506">Costa Rica - 506</option>
				   <option value="385">Croatia (Hrvatska) - 385</option>
				   <option value="357">Cyprus - 357</option>
				   <option value="420">Czech Republic - 420</option>
				   <option value="45">Denmark - 45</option>
				   <option value="253">Djibouti - 253</option>
				   <option value="1767">Dominica - 1767</option>
				   <option value="1809">Dominican Republic - 1809</option>
				   <option value="670">East Timor - 670</option>
				   <option value="593">Ecuador - 593</option>
				   <option value="20">Egypt - 20</option>
				   <option value="503">El Salvador - 503</option>
				   <option value="240">Equatorial Guinea - 240</option>
				   <option value="291">Eritrea - 291</option>
				   <option value="372">Estonia - 372</option>
				   <option value="251">Ethiopia - 251</option>
				   <option value="298">Faroe Islands - 298</option>
				   <option value="679">Fiji - 679</option>
				   <option value="358">Finland - 358</option>
				   <option value="33">France - 33</option>
				   <option value="594">French Guiana - 594</option>
				   <option value="689">French Polynesia - 689</option>
				   <option value="241">Gabon - 241</option>
				   <option value="220">Gambia - 220</option>
				   <option value="995">Georgia - 995</option>
				   <option value="49">Germany - 49</option>
				   <option value="233">Ghana - 233</option>
				   <option value="350">Gibraltar - 350</option>
				   <option value="30">Greece - 30</option>
				   <option value="299">Greenland - 299</option>
				   <option value="1473">Grenada - 1473</option>
				   <option value="590">Guadeloupe - 590</option>
				   <option value="1671">Guam - 1671</option>
				   <option value="502">Guatemala - 502</option>
				   <option value="224">Guinea - 224</option>
				   <option value="592">Guyana - 592</option>
				   <option value="509">Haiti - 509</option>
				   <option value="39">Holy See (Vatican City State) - 39</option>
				   <option value="504">Honduras - 504</option>
				   <option value="582">Hong Kong SAR, PRC - 852</option>
				   <option value="36">Hungary - 36</option>
				   <option value="354">Iceland - 354</option>
				   <option value="91">India - 91</option>
				   <option value="62">Indonesia - 62</option>
				   <option value="353">Ireland - 353</option>
				   <option value="972">Israel - 972</option>
				   <option value="39">Italy - 39</option>
				   <option value="1876">Jamaica - 1876</option>
				   <option value="81">Japan - 81</option>
				   <option value="962">Jordan - 962</option>
				   <option value="7">Kazakhstan - 7</option>
				   <option value="254">Kenya - 254</option>
				   <option value="82">Korea, Republic of - 82</option>
				   <option value="965">Kuwait - 965</option>
				   <option value="996">Kyrgyzstan - 996</option>
				   <option value="856">Lao, People's Dem. Rep. - 856</option>
				   <option value="371">Latvia - 371</option>
				   <option value="961">Lebanon - 961</option>
				   <option value="266">Lesotho - 266</option>
				   <option value="218">Libya - 218</option>
				   <option value="423">Liechtenstein - 423</option>
				   <option value="370">Lithuania - 370</option>
				   <option value="352">Luxembourg - 352</option>
				   <option value="853">Macau - 853</option>
				   <option value="389">Macedonia - 389</option>
				   <option value="261">Madagascar - 261</option>
				   <option value="265">Malawi - 265</option>
				   <option value="60">Malaysia - 60</option>
				   <option value="960">Maldives - 960</option>
				   <option value="223">Mali - 223</option>
				   <option value="356">Malta - 356</option>
				   <option value="692">Marshall Islands - 692</option>
				   <option value="596">Martinique - 596</option>
				   <option value="222">Mauritania - 222</option>
				   <option value="230">Mauritius - 230</option>
				   <option value="52">Mexico - 52</option>
				   <option value="373">Moldova, Republic Of - 373</option>
				   <option value="377">Monaco - 377</option>
				   <option value="976">Mongolia - 976</option>
				   <option value="382">Montenegro - 382</option>
				   <option value="1664">Montserrat - 1664</option>
				   <option value="212">Morocco - 212</option>
				   <option value="258">Mozambique - 258</option>
				   <option value="264">Namibia - 264</option>
				   <option value="977">Nepal - 977</option>
				   <option value="31">Netherlands - 31</option>
				   <option value="599">Netherlands Antilles - 599</option>
				   <option value="687">New Caledonia - 687</option>
				   <option value="64">New Zealand - 64</option>
				   <option value="505">Nicaragua - 505</option>
				   <option value="227">Niger - 227</option>
				   <option value="234">Nigeria - 234</option>
				   <option value="672">Norfolk Island - 672</option>
				   <option value="47">Norway - 47</option>
				   <option value="968">Oman - 968</option>
				   <option value="92">Pakistan - 92</option>
				   <option value="970">Palestine - 970</option>
				   <option value="507">Panama - 507</option>
				   <option value="595">Paraguay - 595</option>
				   <option value="51">Peru - 51</option>
				   <option value="63">Philippines - 63</option>
				   <option value="48">Poland - 48</option>
				   <option value="351">Portugal - 351</option>
				   <option value="1787">Puerto Rico - 1787</option>
				   <option value="974">Qatar - 974</option>
				   <option value="262">Reunion - 262</option>
				   <option value="40">Romania - 40</option>
				   <option value="7">Russia - 7</option>
				   <option value="250">Rwanda - 250</option>
				   <option value="1869">Saint Kitts And Nevis - 1869</option>
				   <option value="1758">Saint Lucia - 1758</option>
				   <option value="1784">Saint Vincent And the Grenadines - 1784</option>
				   <option value="658">Samoa - 685</option>
				   <option value="378">San Marino - 378</option>
				   <option value="966">Saudi Arabia - 966</option>
				   <option value="221">Senegal - 221</option>
				   <option value="381">Serbia - 381</option>
				   <option value="248">Seychelles - 248</option>
				   <option value="232">Sierra Leone - 232</option>
				   <option value="65">Singapore - 65</option>
				   <option value="421">Slovak Republic - 421</option>
				   <option value="386">Slovenia - 386</option>
				   <option value="27">South Africa - 27</option>
				   <option value="34">Spain - 34</option>
				   <option value="94">Sri Lanka - 94</option>
				   <option value="508">St. Pierre And Miquelon - 508</option>
				   <option value="597">Suriname - 597</option>
				   <option value="268">Swaziland - 268</option>
				   <option value="46" selected="selected">Sweden - 46</option>
				   <option value="41">Switzerland - 41</option>
				   <option value="886">Taiwan, Province of China - 886</option>
				   <option value="992">Tajikistan - 992</option>
				   <option value="255">Tanzania, United Republic Of - 255</option>
				   <option value="66">Thailand - 66</option>
				   <option value="228">Togo - 228</option>
				   <option value="676">Tonga - 676</option>
				   <option value="1868">Trinidad And Tobago - 1868</option>
				   <option value="216">Tunisia - 216</option>
				   <option value="90">Turkey - 90</option>
				   <option value="993">Turkmenistan - 993</option>
				   <option value="1649">Turks And Caicos Islands - 1649</option>
				   <option value="256">Uganda - 256</option>
				   <option value="380">Ukraine - 380</option>
				   <option value="971">United Arab Emirates - 971</option>
				   <option value="44">United Kingdom - 44</option>
				   <option value="598">Uruguay - 598</option>
				   <option value="998">Uzbekistan - 998</option>
				   <option value="58">Venezuela - 58</option>
				   <option value="84">Vietnam - 84</option>
				   <option value="1284">Virgin Islands (British) - 1284</option>
				   <option value="1340">Virgin Islands (US) - 1340</option>
				   <option value="967">Yemen - 967</option>
				   <option value="260">Zambia - 260</option>
				</select>
			</div>	
			<div class="mob_num" style="">
				<label>Mobile Number</label>
				<input type="tel" name="mobile_number" value="{{$user->phone_number}}" required>
			</div>

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
	$(document).ready(function(){
		$("#select-native-5").val('91');
		$("#select-native-5-button").find("span").html($( "#select-native-5 option:selected" ).text());
	});

	 /*$(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });*/

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

	@if(!$order->order_accepted)
		var intervalCheckIfOrderAccepted = null;

		// If 'order response' set to manual for store and order not accepted, check for order accepted
		var checkIfOrderAccepted = function() {
			$.get('{{ url('check-if-order-accepted').'/'.$order->order_id }}', function(result) {
				if(result.status)
				{
					$('.order-confirmation-block').html(result.responseStr);
					clearInterval(intervalCheckIfOrderAccepted);
				}
			});
		}

		intervalCheckIfOrderAccepted = setInterval(checkIfOrderAccepted, 5000);
	@endif
</script>

@endsection
