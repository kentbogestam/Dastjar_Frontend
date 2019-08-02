@extends('layouts.kitchenSetting')

<style type="text/css">
	.btn_blk{
		background-color: #fff;
		border-radius: 0.5em;
	}

	.btn_blk h2{
		text-align: left;
		padding-left: 2em;
	}
	
	.msg-lbl{
		color: #000; 
		padding-left: 0px !important;
	}

	.msg-txt{
		margin-bottom: 10px !important; 
		border: 1px solid #777 !important;
	}

	#msg{
		height: 200px !important;
/*		  resize: none;
*/
	}

	#contact-setting-list .ui-controlgroup{
		display: block !important;
	}

	.others_tabs li{
     	background-color: #420800;
    	color: white;
    	border-radius: 0.5em !important;
	}
	
	#form{
		margin-bottom: 0px;
	}

	#range-sec-controlgroup{
	/*	width: 100%;*/
	}
/*	#msg{height: 300px;}
*/
/*	#msg:focus {
color:red;
/*height: 300px;
*
/*
textarea.ui-input-text{
  height: auto !important
 }
*/
/*	html,body{ -webkit-overflow-scrolling : touch !important; overflow: auto !important; height: 100% !important; }
*/
/*	textarea.ui-input-text.ui-textinput-autogrow {
    	overflow: auto !important;
	}
*/
	textarea {
	    height: auto !important;
	    width: 100%
	}
</style>

@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<div class="order_background setting_head_container">
			<div class="ui-grid-b center">
				<div class="ui-block-a">
					<a href="{{ URL::to('kitchen/store') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false">
					<img src="{{asset('kitchenImages/backarrow.png')}}" width="11px">
				</a>
				</div>
				<div class="ui-block-b middle_section">
					<a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					{{ __('messages.Settings') }}
				</a>
				</div>
				<div class="ui-block-c">
					<a id="dataSave" class="done_btn ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline left_text" data-ajax="false">
					{{ __('messages.Done') }}
				</a>
				</div>
			</div>
		</div>
	</div>

		<div role="main" data-role="main-content" class="content">
			@if ($message = Session::get('success'))
				<div class="table-content sucess_msg">
					<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
					 @if(is_array($message))
			            @foreach ($message as $m)
			                {{ $languageStrings[$m] ?? $m }}
			            @endforeach
			        @else
			            {{  __("messages.$message") }}
			        @endif
			    </div>
			@endif
			<div class="setting-list">
				<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/save-kitchenSetting') }}">
				{{ csrf_field() }}
				<li data-role="collapsible" class="range-sec" title="{{ __('messages.iStoreSettingLanguage') }}"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.Language") }} <span>
					@if(Auth::guard('admin')->user()->language == 'ENG')
					English
					@elseif(Auth::guard('admin')->user()->language == 'SWE')
					Swedish
					@elseif(Auth::guard('admin')->user()->language == 'GER')
					German
					@endif</span></h2>
				    <fieldset data-role="controlgroup">
				        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Auth::guard('admin')->user()->language == 'ENG') checked="checked" @else checked="checked" @endif>
				        <label for="radio-choice-v-2a">English</label>
				        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Auth::guard('admin')->user()->language == 'SWE') checked="checked" @endif>
				        <label for="radio-choice-v-2b">Swedish</label>
				    </fieldset>
				</li>

				<li data-role="collapsible" class="range-sec">
					<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">
						{{ __("messages.orderResponse") }}
					</h2>
					<ul>
						<li data-role="collapsible" class="range-sec" title="{{ __('messages.iStoreSettingOrderResponse') }}">
							<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">
								{{ __("messages.orderResponse") }}
								<span>
									@if(!$store->order_response)
										Manual
									@else
										Automatic
									@endif
								</span>
							</h2>
							<fieldset data-role="controlgroup">
								<input type="radio" name="order_response" id="order-response-manual" value="0" @if($store->order_response == 0) checked="checked" @endif>
						        <label for="order-response-manual">Manual</label>
						        <input type="radio" name="order_response" id="order-response-automatic" value="1" @if($store->order_response == 1) checked="checked" @endif>
						        <label for="order-response-automatic">Automatic</label>
							</fieldset>
						</li>
						<li id="prep_time" class="range-sec btn_blk" title="{{ __('messages.iStoreSettingExtraPrepTime') }}">
							<h2 class="ui-btn">{{ __('messages.Extra Preparation Time') }}</h2>
						</li>
						<li data-role="collapsible" class="range-sec" title="{{ __('messages.iStoreSettingTextToSpeech') }}"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.Text To Speech") }} <span>
							@if(Auth::guard('admin')->user()->text_speech == 0)
							Off
							@elseif(Auth::guard('admin')->user()->text_speech == 1)
							On
							@endif</span></h2>
						    <fieldset data-role="controlgroup">
						        <input type="radio" name="text_speech" id="radio-choice-v-2d" value="0" @if(Auth::guard('admin')->user()->text_speech == 0) checked="checked" @else checked="checked" @endif>
						        <label for="radio-choice-v-2d">Off</label>
						        <input type="radio" name="text_speech" id="radio-choice-v-2e" value="1" @if(Auth::guard('admin')->user()->text_speech == 1) checked="checked" @endif>
						        <label for="radio-choice-v-2e">On</label>
						    </fieldset>
						</li>
					</ul>
				</li>
				<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.marketingTools") }} <span>
					</span></h2>
				    <ul data-role="controlgroup" class="others_tabs">
						<li id="link-discount" class="range-sec btn_blk {{ !Session::has('subscribedPlans.discount') ? 'ui-state-disabled' : '' }}" title="{{ __('messages.iStoreSettingDiscount') }}">
							<h2 class="ui-btn">{{ __('messages.Discount') }}</h2>
						</li>
						<li id="link-loyalty" class="range-sec btn_blk {{ !Session::has('subscribedPlans.loyalty') ? 'ui-state-disabled' : '' }}" title="{{ __('messages.iStoreSettingLoyalty') }}">
							<h2 class="ui-btn">{{ __('messages.loyalty') }}</h2>
						</li>
				    </ul>
				</li>

				<li data-role="collapsible" class="range-sec {{ !Session::has('subscribedPlans.homedelivery') ? 'ui-state-disabled' : '' }}">
					<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.deliveryOptionHomeDelivery") }}</h2>
				    <ul data-role="controlgroup" class="others_tabs">
						<li id="link-driver" class="range-sec btn_blk">
							<h2 class="ui-btn">{{ __('messages.driver') }}</h2>
						</li>
						<li id="link-delivery-price-model" class="range-sec btn_blk">
							<h2 class="ui-btn">{{ __('messages.delivery_price_model') }}</h2>
						</li>
				    </ul>
				    <ul>
				    	<li class="range-sec btn_blk">
							<div data-role="rangeslider">
								<label for="driver_range">{{ __('messages.driverRange') }}</label>
								<input type="range" name="driver_range" id="driver_range" min="0" max="20" value="{{ $store->driver_range }}">
							</div>
						</li>
						<li class="range-sec btn_blk">
							<div data-role="rangeslider">
								<label for="delivery_range">{{ __('messages.deliveryRange') }}</label>
								<input type="range" name="delivery_range" id="delivery_range" min="0" max="20" value="{{ $store->delivery_range }}">
							</div>
						</li>
						<li class="range-sec">
							<div data-role="rangeslider">
								<label for="buffer_time">{{ __('messages.buffer_time') }}</label>
								<input type="range" name="buffer_time" id="buffer_time" min="0" max="50" value="{{ $store->buffer_time }}">
							</div>
						</li>
				    </ul>
				</li>

				<li id="link-refund" class="range-sec btn_blk" title="{{ __('messages.iStoreSettingRefund') }}">
					<h2 class="ui-btn">{{ __('messages.refund') }}</h2>
				</li>

				<li data-role="collapsible" id="range-sec-controlgroup" class="range-sec">
					<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Support') }}
						<p class="ui-li-aside">
							
						</p>
					</h2>
					<div>
						<label class="msg-lbl"><h2>{{ __('messages.Message') }}</h2></label>
					</div>

					<div id="msg-controlgroup">
						<form id="support-form" method="post" action="{{ url('kitchen/support') }}" data-ajax="false">
							{{ csrf_field() }}
							<textarea id="msg" name="message" placeholder="{{ __('messages.Contact Us Placeholder') }}"  class="msg-txt"  data-ajax="false" required>
								</textarea>
							<button type="submit" class="btn btn-success">{{ __('messages.Send') }}</button>		
						</form>
					</div>
				</li>

				<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.Others") }} <span>
					</span></h2>
				    <ul data-role="controlgroup" class="others_tabs">
						 <li id="about_us" class="range-sec btn_blk">
							<h2 class="ui-btn">{{  __("messages.aboutDastjarAnar") }}</h2>
						</li>

						<li id="admin" class="range-sec btn_blk">
							<h2 class="ui-btn">{{  __("messages.aboutCompanyAdmin") }}</h2>
						</li>
				    </ul>
				</li>
				</form>
			</div>
		</div>
</div>

@endsection

@section('footer-script')
<script src="https://code.jquery.com/jquery-migrate-1.3.0.js"></script>

	<script type="text/javascript">
		$("#dataSave").click(function(e){
			var flag = true;

			if(flag){
				$("#form").submit();
			} else{
				alert("Please fill some value");	
				e.preventDefault();
			}
		});

		$('#about_us').click(function(){
			window.open("https://dastjar.com/?page_id=71");
		});

		$('#admin').click(function(){
			window.open("https://admin-dev.dastjar.com/");
		});

		$('#prep_time').click(function(){
			location.replace("{{url('kitchen/extra-prep-time')}}");
		});

		// Go discount page
		$('#link-discount').click(function() {
			window.location = "{{ url('kitchen/discount/list') }}";
		});

		// Go to Loyalty
		$('#link-loyalty').click(function() {
			window.location = "{{ url('kitchen/loyalty/list') }}";
		});

		// Go to delivery-price
		$('#link-delivery-price-model').click(function() {
			window.location = "{{ url('kitchen/delivery-price-model/list') }}";
		});

		// Go to driver
		$('#link-driver').click(function() {
			window.location = "{{ url('kitchen/driver/list') }}";
		});

		// Go to Loyalty
		$('#link-refund').click(function() {
			window.open('https://dashboard.stripe.com/payments', '_blank');
		});

		$(document).ready(function(){
   				$('#msg').on("focus", function () {
				   $('.setting-page').animate({scrollTop:$(document).height()}, 'slow');
				});	

			// $("textarea").removeClass('ui-input-text');
			// $.mobile.silentScroll(0);

		});

$(document).bind("mobileinit", function () {
    // $.mobile.ajaxEnabled = false;
});

	</script>

@endsection