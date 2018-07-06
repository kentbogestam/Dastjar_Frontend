@extends('layouts.blank')

@section('head-scripts')
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
     <script>
          $(document).ready(function () {
              App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
              App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

              App42.initialize("{{env('APP42_API_KEY')}}","{{env('APP42_API_SECRET')}}");
              App42.enableEventService(true);
              var userName;
              new Fingerprint2().get(function(result, components){
                  userName = "{{Auth::guard('admin')->user()->email}}";
                  console.log("Username : " + userName); //a hash, representing your device fingerprint
                  App42.setLoggedInUser(userName);
//                  getDeviceToken();
              });
          });
	</script>
	
	<style>		
		#dialog-confirm{
			display: none;
		} 

		.ui-dialog{
			background-color: #fff !important;
			
		}

		.ui-widget-overlay{
		    opacity: 0.8;		
		}

		.ui-widget-header{
			background-color: #a2080f;
		    color: #fff;
		}

		.dialog-yes{
			background-color: #a2080f;
		    color: #fff;			
		}

		#mobile-num{
			border: 1px solid #000;
		}

		#logout_link{
			display: none;
		}

		@media(max-width: 480px) {
			.product_image {
				height: 38px;
			}
			.single-restro-list-sec .ui-listview>li .list-content {
				padding: 0em !important; 
			    margin-top: 50px;
			}
			.qty-sec {
				margin-top: -20px;
			    margin-left: 20px;			
			}
			.extra-btn {
				top: 45px;
			}

			.top_two-menu .ui-grid-a>.ui-block-a, .top_two-menu .ui-grid-a>.ui-block-b {
				width: 30%;
			}
		}
	</style>
@endsection
@section('content')
	<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
			<a href = "{{ url('kitchen/logout') }}" id="logout_link" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
			</a>
		</div>
			<h3 class="ui-bar ui-bar-a order_background">
				<a href="{{ URL::previous() }}" data-ajax="false" class="text-left ui-link ui-btn back_btn"><img src="{{asset('kitchenImages/backarrow.png')}}" width="11px"></a>

				{{$storedetails->store_name}}
			</h3>
		<div class="top_two-menu">
			<div class="ui-grid-a center">
				<div class="ui-block-a"><a href="#order-popup" data-rel="popup" data-transition="turn" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<img src="{{asset('kitchenImages/order-img.png')}}">
					</div>
					<span>{{ __('messages.Orders') }}<span class="order_number">{{count(Auth::guard('admin')->user()->kitchenPaidOrderList)}}</span></span>
				</a></div>
				<!-- <div class="ui-block-b"><a onClick="makeRedirection('{{url('kitchen/selectOrder-dateKitchen')}}')" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/eat-icon.png')}}">
					</div>
					<span>Eat Later</span>
				</a></div>
				<div class="ui-block-c"><a onClick="makeRedirection('{{url('kitchen/kitchen-order-onsite')}}')" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/eat-icon.png')}}">
					</div>
					<span>Eat Now</span>
				</a></div> -->
			</div>
		</div>
	</div>
	<div data-role="popup" id="order-popup" data-theme="b">
        <ul data-role="listview" data-inset="true" style="min-width:210px;">
            @foreach(Auth::guard('admin')->user()->kitchenPaidOrderList as $order)
            	<li><a href="{{ url('kitchen/kitchen-order-view/'.$order->order_id) }}">{{ __('messages.Orders') }} - {{$order->customer_order_id}}</a></li>
            @endforeach
        </ul>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/kitchen-order-save') }}">
		{{ csrf_field() }}
	
		<div role="main" data-role="main-content" class="content">
		
			<div class="cat-list-sec single-restro-list-sec">
				<div class="ui-grid-a">
					<input type="hidden" id="mobilePrefix" name="phone_number_prifix"/>
					<input type="hidden" id="mobileNo" name="mobileNo"/>
					<input type="hidden" id="browserCurrentTime" name="browserCurrentTime" value="" />
					<div class="ui-block-a left_part_kit">
						@if($menuDetails == null || $menuTypes == null)
							<p>{{ __('messages.Menu is not available.') }}</p>
						@else
						<?php $i =0 ?>
						<?php $j =1 ?>
						@foreach($menuTypes as $menuType)
							@if($i == 0)
							<div data-role="collapsible" data-iconpos="right" > <h3 class="">{{$menuType->dish_name}}</h3>
								@foreach($menuDetails as $productDetail)
									@foreach($productDetail->storeProduct as $menuDetail)
										@if($menuType->dish_id == $menuDetail->dish_type)
										<ul data-role="listview" data-inset="true">
											<li>
												<img class="product_image" src="{{$menuDetail->small_image}}">
												<div class="list-content">
													<h2>{{$menuDetail->product_name}}</h2>
												<p>{{$menuDetail->product_description}}</p>
													<p class="price">
														{{$companydetails->currencies}} {{$productDetail->price}}
												</p>
												</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" value="0" maxlength="2" max="10" size="1" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>
												<div class="extra-btn">
														<label><img src="{{asset('kitchenImages/icon-wait-time.png')}}" width="15px">
														@if(date_create($menuDetail->preparation_Time) != false){{'00:'.date_format(date_create($menuDetail->preparation_Time), 'i')}}@else{{$menuDetail->preparation_Time}}@endif</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('kitchenImages/icon-add-comments.png')}}" width="18px">{{ __('messages.Add Comments') }}</a></label>
														<input type="hidden" id="orderDetail{{$menuDetail->product_id}}" name="product[{{$j}}][prod_desc]" value="" />
												</div>
											</li>
											<?php $j =$j+1 ?>
										</ul>
										@endif
									@endforeach
								@endforeach
							</div>
							<?php $i =1 ?>
							@else
							<div data-role="collapsible" data-iconpos="right"> <h3 class="">{{$menuType->dish_name}}</h3>
								@foreach($menuDetails as $productDetail)
									@foreach($productDetail->storeProduct as $menuDetail)
										@if($menuType->dish_id == $menuDetail->dish_type)
										<ul data-role="listview" data-inset="true">
											<li>
												<img class="product_image" src="{{$menuDetail->small_image}}">
												<div class="list-content">
													<h2>{{$menuDetail->product_name}}</h2>
												<p>{{$menuDetail->product_description}}</p>
												<p class="price">{{$companydetails->currencies}} {{$productDetail->price}}
												 </p> 
												</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" value="0" maxlength="2" max="10" size="1" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>
												<div class="extra-btn">
														<label><img src="{{asset('kitchenImages/icon-wait-time.png')}}" width="15px">@if(date_create($menuDetail->preparation_Time) != false){{'00:'.date_format(date_create($menuDetail->preparation_Time), 'i')}}@else{{$menuDetail->preparation_Time}}@endif</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('kitchenImages/icon-add-comments.png')}}" width="18px">{{ __('messages.Add Comments') }}</a></label>
														<input type="hidden" id="orderDetail{{$menuDetail->product_id}}" name="product[{{$j}}][prod_desc]" value="" />
												</div>
											</li>
											<?php $j =$j+1 ?>
										</ul>
										@endif
									@endforeach
								@endforeach
							</div>
							@endif
						@endforeach
						@endif
					</div>
					<div class="ui-block-b second-part">
						<div class="seprate_qr">
							<div class="mid_para">
								<h2>{{ __('messages.Gain Time by Ordering on the go') }}</h2>
								<h4><img src="{{asset('kitchenImages/600px-Black_check.svg.png')}}">{{ __('messages.Your food is ready by the time of arrival') }}</h4>
								<h3>{{ __('messages.Get notification, when your order is ready') }}</h3>
							</div>
							<h3 class="no-margin"><strong>{{ __('messages.DOWNLOAD') }}</strong> {{ __('messages.the App') }}  <span>{{ __('messages."anar"') }}</span> {{ __('messages.and get the benefites') }} </h3>
							<h3 class="blue_link">{{ __('messages.Requires no extra Memory!') }}</h3>
							<div class="">
								<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=https://anar.dastjar.com/">
							</div>
							<a class="blue_link" href="">anar.dastjar.com</a>
							<h3>{{ __('messages.OR') }}</h3>
							<h3 class="blue_link">{{ __('messages.Enter your mobile number here, to get the app') }}</h3>
							
							<div class="ui-field-contain search_container"> <input type="number" data-clear-btn="false" name="number-1" id="number-1" value=""> <button  class="ui-shadow ui-btn ui-corner-all ui-mini" disabled>ok</button> </div>
							
							<!-- <h3>{{ __('messages.OR') }}</h3>
							<h3 class="blue_link">{{ __('messages.use the QR-code below') }}</h3> -->
						</div>
					</div>
				</div>
			</div>
		</div>
		
				<!-- popup section -->

		<div data-role="popup" id="transitionExample" class="ui-content comment-popup" data-theme="a">
			<div class="pop-header">
			<a href="#" data-rel="back"  class="cancel-btn ui-btn ui-btn-left ui-corner-all ui-shadow ui-btn-a">{{ __('messages.Cancel') }}</a>
			<label>{{ __('messages.Add Comments') }}</label>
			
			</div>
			<div class="pop-body">
				
				<textarea name="textarea-1" id="textarea-1" placeholder="{{ __('messages.Add Comments') }}"></textarea>
				<a id="submitId" href="" data-ajax="false" class="submit-btn ui-btn ui-btn-right ui-corner-all ui-shadow ui-btn-a">{{ __('messages.Submit') }}</a>
			</div>
		</div>

	</form>
	



	<div data-role="footer" class="footer_container" data-position="fixed" data-tap-toggle="false">
		<div class="ui-grid-a inner-footer center">
		<div class="ui-block-a"><a id="menudataSave" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('kitchenImages/send_icon.png')}}">
				</div>
				<span>{{ __('messages.Sent') }}</span>
		</a></div>
		</div>
	</div>

	<div id="dialog-confirm" title="Mobile Number">
		{{-- <form id="mobile-num-form"> --}}
				<label for="select-native-5">Country Phone Number Prefix</label>
				<select name="phone_number_prifix" id="select-native-5">
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

			<input id="mobile-num" type="number" name="mobileNo" placeholder="Enter Your Number" required/>
			{{-- <button type="submit" class="mobile-num-submit" style="display:none">Submit</button>
		</form> --}}
	</div>
@endsection

@section('footer-script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script type="text/javascript">

		window.addEventListener('load', function() {
		  window.history.pushState({ noBackExitsApp: true }, '')
		});

		window.addEventListener('popstate', function(event) {
		    window.history.pushState({ noBackExitsApp: true }, '')
		});

		var id ;

		$('#mobile-num-form').submit(function(){
			$("#dialog-confirm").dialog("close");
			$("#form").submit();
		});

		function submitMobileNumber(){
			$( "#dialog-confirm" ).dialog({
					resizable: false,
					modal: true,
					buttons: [						
						{
							text: "Submit",
							"class": 'dialog-yes',
							click: function() {
								// $('.mobile-num-submit').trigger("click");
								validateMobileNum();
							}
						}
		        	]
				
			}); 
		}

          function validateMobileNum(){
                var mobNum = $('#mobile-num').val();
                var filter = /^\d*(?:\.\d{1,2})?$/;

                  if (filter.test(mobNum)) {
                    if(mobNum.length==9 || mobNum.length==10){
 						  $("#dialog-confirm").dialog("close");
						   $('#mobilePrefix').val($('#select-native-5').val());
						   $('#mobileNo').val(mobNum);
	    				  $("#form").submit();
                          return true;
                     } else {
                        alert('Please put 9 or 10 digit mobile number');
                       $("#folio-invalid").removeClass("hidden");
                       $("#mobile-valid").addClass("hidden");
                        return false;
                      }
                    }
                    else {
                      alert('Not a valid number');
                      $("#folio-invalid").removeClass("hidden");
                      $("#mobile-valid").addClass("hidden");
                      return false;
                   }            
          }

		$(".extra-btn a").click(function(){
			id=$(this).attr('id');
		});
		
		$('#submitId').click(function(){ 
		
			var text = $('textarea#textarea-1').val();
			$('#orderDetail'+id).val(text);
			$('#transitionExample').popup( "close" );
			document.getElementById("textarea-1").value = "";
		});

		$("#menudataSave").click(function(e){
			var d = new Date();
			$("#browserCurrentTime").val(d);
			var flag = false;
			var x = $('form input[type="text"]').each(function(){
	        // Do your magic here
	        	var checkVal = parseInt($(this).val());
	        	console.log(checkVal);
	        	if(checkVal > 0){
	        		flag = true;
	        		return flag;
	        	}
			});

			if(flag){
				submitMobileNumber();
			} else{
				alert("Please select item from the menu");	
				e.preventDefault();
			}
		});

		function incrementValue(id)
		{
		    var value = parseInt(document.getElementById(id).value, 10);
		    value = isNaN(value) ? 0 : value;
		    if(value<10){
		        value++;
		            document.getElementById(id).value = value;
		    }
		}
		function decrementValue(id)
		{
		    var value = parseInt(document.getElementById(id).value, 10);
		    value = isNaN(value) ? 0 : value;
		    if(value>0){
		        value--;
		            document.getElementById(id).value = value;
		    }

		}

		function makeRedirection(link){
			window.location.href = link;
		}
	</script>

@endsection