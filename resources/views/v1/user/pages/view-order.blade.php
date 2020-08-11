@extends('v1.user.layouts.master')

@section('head-scripts')
<style>
    .rejectbox{
        background-color: #d57301;
        padding:20px;
        width:fit-content;
        color:white;
        text-align: center;
        margin: 20px auto;
    }
    .waitingtbox{
        background: linear-gradient(to bottom, rgba(249, 163, 34, 1) 0%, rgba(229, 80, 11, 1) 100%);
        padding:20px;
        width:fit-content;
        color:white;
        text-align: center;
        margin: 20px auto;
    }
    .acceptbox{
        background-color: #4caf50;
        padding:20px;
        width:fit-content;
        color:white;
        text-align: center;
        margin: 20px auto;
    }
    .cancel-button{
        margin: 10px auto;
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
        width:9%;
        height:10%;
	}
</style>
	@if(Request::server('HTTP_REFERER'))
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>
		<script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
		<script src="{{asset('notifactionJs/SiteTwo.js')}}"></script> 
		<script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>

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
	@endif
@endsection

@section('content')
	@include('includes.phone-modal')
	@include('includes.cancel-modal')

	<div class="order-summery-section">
		<div class="order-summery order-confirmation-block">
			@if($order->order_accepted)
				<p>{{ __('messages.Thanks for your order') }} </p>
				<p>{{ __('messages.Order Number') }} </p>
				<p class="large-text">{{$order->customer_order_id}}</p>
				<p>({{$order->store_name}})</p>
				@if( is_numeric($storeDetail->phone) )
					<p><i class="fa fa-phone" aria-hidden="true"></i> <span>{{ $storeDetail->phone }}</span></p>
				@endif

				@if($order->delivery_type == 3)
					@php
					if($order->order_response)
					{
						$times = array($order->order_delivery_time, $order->deliver_time, $storeDetail->extra_prep_time);
					}
					else
					{
						$times = array($order->deliver_time, $order->extra_prep_time);
					}
					
					$time = Helper::addTimes($times);

					// Add 'travelling time'
					if($order->distanceInSec)
					{
						$time = date("H:i", strtotime($time)+$order->distanceInSec);
					}

					$dateTime = date('Y-m-d H:i:s', strtotime($order->deliver_date.' '.$time));
					@endphp
					
					<p>
						@if($order->order_type == 'eat_now')
							{{ __('messages.deliveryDateTimeEatNow') }}
							{{ date('H:i', strtotime($dateTime)) }}
						@endif

						@if($order->driverapp == '1')
							<br><a href="{{ url('track-order/'.$order->order_id) }}" class="ui-btn ui-btn-inline track-order" data-ajax="false">{{ __('messages.trackOrder') }}</a>
						@endif
					</p>
				@else
					<p>
						@php
						if($order->order_response)
						{
							$time = $order->order_delivery_time;
							$time2 = $storeDetail->extra_prep_time;
						}
						else
						{
							$time = $order->deliver_time;
							$time2 = $order->extra_prep_time;
						}
						
						$secs = strtotime($time2)-strtotime("00:00:00");
						$result = date("H:i:s",strtotime($time)+$secs);
						@endphp

						@if($order->order_type == 'eat_now')
							{{ __('messages.Your order will be ready in about') }}
							@if($order->order_response) {{-- Automatic --}}
								@if(date_format(date_create($result), 'H')!="00")
									{{date_format(date_create($result), 'H')}} hours 						
								@endif
								{{date_format(date_create($result), 'i')}} mins
							@else {{-- Manual --}}
								{{ date_format(date_create($order->extra_prep_time), 'i') }} mins
							@endif
						@endif
					</p>
				@endif
			@else
				<p>{{ __('messages.waitForOrderConfirmation') }} </p>
				<p>({{$order->store_name}})</p>
				@if( is_numeric($storeDetail->phone) )
					<p><i class="fa fa-phone" aria-hidden="true"></i> <span>{{ $storeDetail->phone }}</span></p>
				@endif
			@endif
		</div>
		<div class="cart-list">
			<h4>{{ __('messages.ORDER DETAILS') }}</h4>
		</div>
		<div class="container-fluid">
			<div class="cart-table view-order">
				<div class="table-responsive">
					<table class="table">
						<tbody>
							@foreach($orderDetails as $orderDetail)
								<tr>
									<td colspan="2">{{$orderDetail->product_name}}</td>
									<td class="text-center">{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td>
									<td class="text-right">{{ number_format(($orderDetail->product_quality*$orderDetail->price), 2, '.', '') }}  {{$order->currencies}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="view-order-table">
				<div class="table-responsive">
					<table class="table table-borderless">
						<tbody>
							@if( isset($orderInvoice['discount']) )
								<tr>
									<td class="text-right" width="70%"><strong>DISCOUNT</strong></td>
									<td class="text-right" width="30%">
										<span style="font-weight: bold;">
											{{ number_format($orderInvoice['discount'], 2, '.', '') }}
										</span>
										<span><strong>{{$order->currencies}}</strong></span>
									</td>
								</tr>
							@endif
							@if( isset($orderInvoice['homeDelivery']) )
								<tr>
									<td class="text-right" width="70%"><strong>DELIVERY CHARGE</strong></td>
									<td class="text-right" width="30%">
										<span style="font-weight: bold;">
											{{ number_format($orderInvoice['homeDelivery'], 2, '.', '') }}
										</span>
										<span><strong>{{$order->currencies}}</strong></span>
									</td>
								</tr>
							@endif
							<tr class="row-total">
								<td class="text-right" width="70%"><strong>TOTAL</strong></td>
								<td class="text-right" width="30%">
									<span id="grandTotalDisplay" style="font-weight: bold;">
										{{ number_format((($order->final_order_total)), 2, '.', '') }}
									</span>
									<span><strong>{{$order->currencies}}</strong></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="view-order-table">
				<div class="table-responsive">
					<table class="table table-borderless">
						<tbody>
							<tr class="row-total">
								<td class="text-right" width="70%"><strong>{{ __('messages.deliverOn') }}</strong></td>
								<td class="text-right" width="30%"><strong>{{ @$order->check_deliveryDate }}
									{{ date("H:i a", strtotime(@$order->deliver_time)) }}</strong></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Loyalty -->
			@if( isset($orderInvoice['loyaltyOfferApplied']) )
				<div class="row row-loyalty-discount">
					<div class="col-md-12 text-center loyalty-discount-text">
						{!! $orderInvoice['loyaltyOfferApplied'] !!}
					</div>
				</div>
			@endif

			@if($order->order_type=="eat_later")
				<!-- if order type is eat leater -->
                @if($order->catering_order_status == '1')
                	<!-- catering order is rejected -->
                	{{-- rejection message --}}
                    <div class="rejectbox">
                        <p> {{ __('messages.rejectMsg') }} <br> {{ __('messages.welcomeAnyTime') }} </p>
                    </div>
                    @if($order->is_seen == "0")
                	<!-- if seen by user -->
		                {{-- okay order message --}}
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-primary" onclick="isSeenMyOrder();">{{ __('messages.okay') }}</button><br><br>
						</div>
					@endif
                @elseif($order->catering_order_status == '2')
                	<!-- catering order is accepted -->
                	@if($order->cancel == "0")
                    	<!-- if not cancelled -->
	                    @if($order->online_paid == "2")
                    	<!-- if not paid -->
	                        {{-- Proceed to pay when order accepted --}}
	                        @include('v1.user.elements.cart-proceed-to-pay')
			                {{-- Cancel order message --}}
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-danger" onclick="cancelMyOrder();">{{ __('messages.cancelMyOrder') }}</button><br><br>
							</div>
	                    @endif
	                    @if($order->online_paid == "1" || $order->online_paid == "4" )
                    	<!-- if paid -->
	                        <div class="acceptbox">
	                            <p> {{ __('messages.acceptMsg') }} </p>
	                        </div>
	                    @endif
	                @else
	                	{{-- Cancellation message --}}
	                    <div class="rejectbox">
	                        <p> {{ __('messages.orderCanceled', ['order_id' => $order->customer_order_id]) }} </p>
	                    </div>
	                    @if($order->is_seen == "0")
	                	<!-- if seen by user -->
			                {{-- okay order message --}}
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-primary" onclick="isSeenMyOrder();">{{ __('messages.okay') }}</button><br><br>
							</div>
						@endif
	                @endif
                @else
                	@if($order->cancel == "0")
                		@if($order->online_paid != "1")
	                    	<!-- if not cancelled and not paid-->
		                    {{-- waiting message --}}
		                    <div class="waitingtbox">
		                        <p> {{ __('messages.waitForOrderConfirmation') }} </p>
		                    </div>
			                {{-- Cancel order message --}}
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-danger" onclick="cancelMyOrder();">{{ __('messages.cancelMyOrder') }}</button><br><br>
							</div>
						@endif
					@else
	                	{{-- Cancellation message --}}
	                    <div class="rejectbox">
	                        <p> {{ __('messages.orderCanceled', ['order_id' => $order->customer_order_id]) }} </p>
	                    </div>
	                    @if($order->is_seen == "0")
	                	<!-- if seen by user -->
			                {{-- okay order message --}}
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-primary" onclick="isSeenMyOrder();">{{ __('messages.okay') }}</button><br><br>
							</div>
						@endif
					@endif
                @endif
            @else
            	@if($order->catering_order_status == '1')
                	<!-- catering order is rejected -->
                	{{-- rejection message --}}
                    <div class="rejectbox">
                        <p> {{ __('messages.rejectMsg') }} <br> {{ __('messages.welcomeAnyTime') }} </p>
                    </div>
                    @if($order->is_seen == "0")
                		<!-- if seen by user -->
		                {{-- okay order message --}}
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-primary" onclick="isSeenMyOrder();">{{ __('messages.okay') }}</button><br><br>
						</div>
					@endif
	            @endif
			@endif
		</div>
	</div>
	<img src="{{ asset('images/loading.gif') }}" id="loading-img" />
@endsection

@section('footer-script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#select-native-5").val('91');
		$("#select-native-5-button").find("span").html($( "#select-native-5 option:selected" ).text());
	});

	$("body").on('click',".accept-btn", function(){
		// $("#cancel-order-form").submit();
	});
    
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
    
    @if($order->order_type == 'eat_later' && $order->online_paid == '2' && $isPaymentPackageSubscribed)
        $('.btn-pay').prop('disabled',false);
    
		// Initialize Stripe and card element
		var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}');
		var stripe2;

		var elements = stripe.elements();
		var cardElement = elements.create('card', {
			hidePostalCode: true
		});
		cardElement.mount('#card-element');

		// Pay with Card
		// var cardholderName = document.getElementById('cardholder-name');
		var cardButton = document.getElementById('card-button');

		cardButton.addEventListener('click', function(ev) {
			$('#card-button').prop('disabled', true);
			$('.row-new-card').find('div.card-errors').html('');

			stripe.createPaymentMethod('card', cardElement).then(function(result) {
				if (result.error) {
					// Show error in payment form
					let message = result.error;
					if( typeof(result.error) == 'object' ) {
						message = result.error.message;
					}
					$('.row-new-card').find('div.card-errors').html(message);
					$('#card-button').prop('disabled', false);
				} else {
					let isSaveCard = $('#isSaveCard').is(':checked') ? 1 : 0;
					let data = {
						'_token': "{{ csrf_token() }}",
						'order_id': "{{ $order->order_id }}",
						'isSaveCard': isSaveCard,
						'payment_method_id': result.paymentMethod.id
					}
					// Otherwise send paymentMethod.id to your server (see Step 2)
					fetch('{{ url('confirm-payment') }}', {
						method: 'POST',
						body: JSON.stringify(data),
						headers: {
							'Content-Type': 'application/json'
						}
					}).then(function(result) {
						// Handle server response (see Step 3)
						result.json().then(function(json) {
							handleServerResponse(json);
							$('#card-button').prop('disabled', false);
						})
					});
				}
			});

			ev.preventDefault();
		});

		// Handle response when 'Pay with card'
		function handleServerResponse(response) {
			if (response.errorHeartbeat) {
				// Show store heartbeat alert
				$('#store-not-live-alert').modal('show');
			} else if (response.error) {
				// Show error from server on payment form
				let message = response.error;
				if( typeof(response.error) == 'object' ) {
					message = response.error.message;
				}
				$('.row-new-card').find('div.card-errors').html(message);
			} else if (response.requires_action) {
				// Use Stripe.js to handle required card action
				stripe2 = Stripe('{{ env('STRIPE_PUB_KEY') }}', {
					stripeAccount: response.stripeAccount
				});

				stripe2.handleCardAction(
					response.payment_intent_client_secret
				).then(function(result) {
					if (result.error) {
						// Show error in payment form
						let message = result.error;
						if( typeof(result.error) == 'object' ) {
							message = result.error.message;
						}
						$('.row-new-card').find('div.card-errors').html(message);
					} else {
						let isSaveCard = $('#isSaveCard').is(':checked') ? 1 : 0;
						
						let data = {
							'_token': "{{ csrf_token() }}",
							'order_id': "{{ $order->order_id }}",
							'isSaveCard': isSaveCard,
							'payment_intent_id': result.paymentIntent.id
						}
						// The card action has been handled
						// The PaymentIntent can be confirmed again on the server
						fetch('{{ url('confirm-payment') }}', {
							method: 'POST',
							body: JSON.stringify(data),
							headers: {
								'Content-Type': 'application/json'
							}
						}).then(function(confirmResult) {
							return confirmResult.json();
						}).then(handleServerResponse);
					}
				});
			} else {
				// Show success message
				$('.row-new-card').find('div.card-errors').html('');
				AskPhoneForInfo();
			}
		}

		// Pay with PaymentMethod
		$('#charging-saved-cards').on('click', function(ev) {
			if( $('input[name=payment_method_id]:checked').length )
			{
				$('#charging-saved-cards').prop('disabled', true);
				let payment_method_id = $('input[name=payment_method_id]:checked').val();
				let data = {
					'_token': "{{ csrf_token() }}",
					'order_id': "{{ $order->order_id }}",
					'chargingSavedCard': true,
					'payment_method_id': payment_method_id
				}
				// Otherwise send paymentMethod.id to your server (see Step 2)
				fetch('{{ url('confirm-payment') }}', {
					method: 'POST',
					body: JSON.stringify(data),
					headers: {
						'Content-Type': 'application/json'
					}
				}).then(function(result) {
					// Handle server response (see Step 3)
					result.json().then(function(json) {
						handleServerResponseSavedCard(json);
						$('#charging-saved-cards').prop('disabled', false);
					})
				});
			}
			else
			{
				alert('Please select card first!');
			}

			ev.preventDefault();
		});
		
		// Handle response when pay with 'saved card' 
		function handleServerResponseSavedCard(response) {
			if (response.errorHeartbeat) {
				// Show store heartbeat alert
				$('#store-not-live-alert').modal('show');
			} else if (response.error) {
				// Show error from server on payment form
				let message = response.error;
				if( typeof(response.error) == 'object' ) {
					message = response.error.message;
				}
				$('.row-saved-cards').find('div.card-errors').html(message);
			} else if (response.requires_action) {
				// Use Stripe.js to handle required card action
				stripe2 = Stripe('{{ env('STRIPE_PUB_KEY') }}', {
					stripeAccount: response.stripeAccount
				});

				stripe2.handleCardAction(
					response.payment_intent_client_secret
				).then(function(result) {
					if (result.error) {
						// Show error in payment form
						let message = result.error;
						if( typeof(result.error) == 'object' ) {
							message = result.error.message;
						}
						$('.row-saved-cards').find('div.card-errors').html(message);
					} else {
						let data = {
							'_token': "{{ csrf_token() }}",
							'order_id': "{{ $order->order_id }}",
							'payment_intent_id': result.paymentIntent.id
						}
						// The card action has been handled
						// The PaymentIntent can be confirmed again on the server
						fetch('{{ url('confirm-payment') }}', {
							method: 'POST',
							body: JSON.stringify(data),
							headers: {
								'Content-Type': 'application/json'
							}
						}).then(function(confirmResult) {
							return confirmResult.json();
						}).then(handleServerResponseSavedCard);
					}
				});
			} else {
				// Show success message
				$('.row-saved-cards').find('div.card-errors').html('');
				AskPhoneForInfo();
			}
		}

		// Delete source
    	function deleteSource(sourceId = null, This)
    	{
    		if( confirm('Are you sure you want to delete this card?') )
    		{
    			let $this = $(This);
            	showLoading();

            	// 
            	$.ajax({
					type: 'POST',
					url: "{{ url('delete-source') }}",
					data: {
						'_token': "{{ csrf_token() }}",
						'deleteSource': 1,
	                    'sourceId': sourceId
					},
					dataType: 'json',
					success: function(response) {
						if(!response.error)
	                    {
	                        $this.closest('.radio').remove();
	                    }
	                    else
	                    {
	                    	alert(response.error);
	                    }

	                    hideLoading('Processing...');
					}
				});
    		}
    	}

		// 
		$('input[name=payment_method_id]').on('click', function() {
			$('#charging-saved-cards').show();
			// Hide 'pay with card'
			$('#pay-options').prop('checked', false);
			$('.section-pay-with-card').addClass('hidden');
		});

		// 
		$('#pay-options').on('click', function() {
			$('input[name=payment_method_id]').prop('checked', false);
			$('#charging-saved-cards').hide();
			$('.section-pay-with-card').removeClass('hidden');

			$('html, body').animate({
		        scrollTop: $(".section-pay-with-card").offset().top
		    }, 'slow');
		});
        
		// Proceed to pay (show payment method)
		$('.btn-pay').on('click', function(e) {
			// 
			if( $('#frm-user-address').length && !$('#frm-user-address').valid())
			{
				return false;
			}

			//
			$('.row-confirm-payment').removeClass('hidden');

			$('html, body').animate({
		        scrollTop: $(".row-new-card").offset().top
		    }, 'slow');
		});
	@else
		$('.send-order').prop('disabled', false);
		$('.send-order').on('click', function() {
			orderConfirmationStatus({{$order->order_id}});
		});
	@endif

	function isSeenMyOrder()
	{
		$('#loading-img').css("display", "block");
		$.ajax({
			type: 'post',
			url: "{{ route('userCancelOrder') }}",
			data: {
				'_token': "{{ csrf_token() }}",
				'order_id': "{{$order->order_id}}",
				'order_number': "{{$order->customer_order_id}}",
			},
			dataType: 'json',
			success: function(response) {
				$('#loading-img').css("display", "none");
				window.location.href="{{ route('eatNow') }}";
			}
		});
	}

	function cancelMyOrder()
	{
		$('#myCancelBtn').trigger('click');
        $('.cancel-conti').on('click', function(){
        	$('.cancel-close').trigger('click');
        	$('#loading-img').css("display", "block");
			$.ajax({
				type: 'post',
				url: "{{ route('userCancelOrder') }}",
				data: {
					'_token': "{{ csrf_token() }}",
					'order_id': "{{$order->order_id}}",
					'phone_number_prifix': "{{$user->phone_number_prifix}}",
					'mobile_number': "{{$user->phone_number}}",
					'store_id': "{{$order->store_id}}",
					'order_number': "{{$order->customer_order_id}}",
				},
				dataType: 'json',
				success: function(response) {
					location.reload(true);
				}
			});
        });
	}

	function orderConfirmationStatus(order_id)
	{
		$.ajax({
			url : "{{url('order-confirmation-status').'/'.$order->order_id}}",
			type : 'get',
			data : {
				'eatLater' : '1'
			},
			success: function(data, status){
				AskPhoneForInfo();
			}
		});
	}

	function AskPhoneForInfo(){
		var delivery_type = '{{@$order->delivery_type}}';
		//send sms to user when its dine-in or take-away not home-delivery
		if(delivery_type != '3'){
			var nmbr;
			var phone_number = "{{@$order->phone_number}}";
			var phone_number_prifix = "{{@$order->phone_number_prifix}}";

			// if no phone number then ask number
			if(phone_number != '' && phone_number_prifix != ''){
				$('#loading-img').css("display", "block");
	        	$.ajax({
					url: "{{ url('smsOverPhone') }}",
					method: 'post',
					data:{
						'phone_number_prifix':$('#phone_number_prifix').val(),
						'phone_number':$('#phone_number').val(),
						'order_number':'{{$order->order_id}}',
					}
				});
	        	window.location.href = "{{ url('order-view/'.$order->order_id) }}";
			}else{

				$('#myPhoneBtn').trigger('click');
		        $('.phone-conti').on('click', function(){
		        	$('#loading-img').css("display", "block");
		        	$.ajax({
						url: "{{ url('smsOverPhone') }}",
						method: 'post',
						data:{
							'phone_number_prifix':$('#phone_number_prifix').val(),
							'phone_number':$('#phone_number').val(),
							'order_number':'{{$order->order_id}}',
						}
					});
		        	window.location.href = "{{ url('order-view/'.$order->order_id) }}";
		        });
		    }
	        $('.phone-close').on('click', function(){
	        	location.reload(true);
	        });
		}else{
			location.reload(true);
		}
	}
</script>
@endsection