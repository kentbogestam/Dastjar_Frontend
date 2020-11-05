@extends('v1.user.layouts.master')

@section('head-scripts')
	@if(Session::has('msg'))
		{{-- Add to homescreen script if comes from 'Apply user discount promotion view' --}}
		<script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
	    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
	    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>

	    <script>
	    	$(document).ready(function() {
	    		registerSwjs();
	    	});
	    </script>
	    {{-- End --}}
	@endif
@endsection

@section('content')
	@include('v1.user.elements.store-delivery-service')

	@php
	$time = null;

	if( Session::has('order_date') )
	{
		$openCloseTime = Helper::getStoreOpenCloseTime($storedetails->store_open_close_day_time_catering);
	}
	else
	{
		$openCloseTime = Helper::getStoreOpenCloseTime($storedetails->store_open_close_day_time);
	}

	if(!empty($openCloseTime))
	{
		$time = date('H:i', strtotime($openCloseTime[0]));
	}
	@endphp
	<div class="alert alert-warning alert-store-closed collapse" style="margin-top: 20px">
		<a href="#" class="close" aria-label="close">&times;</a>
		<i class="fa fa-warning"></i> {{ __('messages.alertStoreClosed', ['closeTime' => $time]) }}
	</div>

	@if( !empty($menuTypes) )
		<form id="form" class="form-horizontal" method="post" action="{{ url('cart') }}">
			{{ csrf_field() }}
			<div class="{{ ($styleType || $storedetails->menu_style_type) ? 'row' : 'hotel-service-list' }}">
				@php
				$strMenuDetail = "";
				@endphp
				@foreach($menuTypes as $menuType)
					@php
					$strLoyaltyOffer = "";
					@endphp

					{{-- Logic to calculate loyalty offer --}}
					@if( $promotionLoyalty && in_array($menuType->dish_id, explode(',', $promotionLoyalty->dish_type_ids)) )
						@php
						$quantity_to_buy = $promotionLoyalty->quantity_to_buy;
						$quantity_get = $promotionLoyalty->quantity_get;
						$end_date = $promotionLoyalty->end_date;

						// If not logged-in or loyalty validity is 0 or customer doesn't get loyalty yet
						if( !Auth::check() || ((!$promotionLoyalty->validity) || ($promotionLoyalty->validity > $orderCustomerLoyalty->cnt)) )
						{
							if($styleType || $storedetails->menu_style_type)
							{
								$strLoyaltyOffer = "<span class='loyalty-offer'>".__('messages.loyaltyOfferMsgGrid', ['quantity_to_buy' => $quantity_to_buy, 'quantity_get' => $quantity_get, 'valid_till' => $end_date])."</span>";
							}
							else
							{
								$strLoyaltyOffer = "<span class='loyalty-offer'>".__('messages.loyaltyOfferMsg', ['quantity_to_buy' => $quantity_to_buy, 'quantity_get' => $quantity_get, 'valid_till' => $end_date])."</span>";
							}
						}
						@endphp

						@if(Auth::check())
							@if($customerLoyalty)
								@php
								$quantity_bought = $customerLoyalty->quantity_bought;

								// Calculate if 'loyalty' already have been applied
								// $quantity_bought -= ($quantity_to_buy*$orderCustomerLoyalty->cnt);

								//
								if($quantity_to_buy > $quantity_bought)
								{
									$final_quantity_to_buy = $quantity_to_buy-$quantity_bought;

									if($styleType || $storedetails->menu_style_type)
									{
										$strLoyaltyOffer = "<span class='loyalty-offer'>".__('messages.loyaltyOfferMsgGrid', ['quantity_to_buy' => $final_quantity_to_buy, 'quantity_get' => $quantity_get, 'valid_till' => $end_date])."</span>";
									}
									else
									{
										$strLoyaltyOffer = "<span class='loyalty-offer'>".__('messages.loyaltyOfferMsg', ['quantity_to_buy' => $final_quantity_to_buy, 'quantity_get' => $quantity_get, 'valid_till' => $end_date])."</span>";
									}
								}
								else
								{
									$quantity_offered = floor($quantity_bought/$quantity_to_buy)*$quantity_get;

									if($quantity_offered > 0)
									{
										$strLoyaltyOffer = "<span class='loyalty-offer loyalty-offer-apply'>".__('messages.loyaltyOfferOnApply', ['quantity_offered' => $quantity_offered])."</span>";
									}
								}
								@endphp
							@endif
						@endif
					@endif

					@if($styleType || $storedetails->menu_style_type)
						@php
						$strMenuDetail .= "<div class='col-xs-12 collapse menu-detail' id='menu-detail-{$menuType->dish_id}'></div>";
						@endphp
						<div class="col-xs-6 text-center restaurant-box">
							<a href="javascript:void(0);" onclick="getMenuDetail(this, {{ $menuType->dish_id }}, 1, '{{ $storedetails->store_id }}')">
								@if( !is_null($menuType->dish_image) )
									<div class="box-img">
										<img src="https://s3.eu-west-1.amazonaws.com/dastjar-coupons/{{ $menuType->dish_image }}" alt="{{ $menuType->dish_name }}">
									</div>
								@else
									<div class="box-img"><img src="{{ asset('v1/images/img-pizza.jpg') }}" alt="{{ $menuType->dish_name }}"></div>
								@endif

								<div class="restaurant-contant">
									@if($strLoyaltyOffer != '')
										<div class="text-center row-loyalty-offer">
											<small>{!! $strLoyaltyOffer !!}</small><br>
										</div>
									@endif
									<h4 class="text-center">{{ $menuType->dish_name }}</h4>
								</div>
							</a>
						</div>

						@if($loop->iteration % 2 == 0 || $loop->last)
							{!! $strMenuDetail !!}
							@php
							$strMenuDetail = "";
							@endphp

							@if(!$loop->last)
								</div><div class="row">
							@endif
						@endif
					@else
						<div class="hotel-ser{{ ($strLoyaltyOffer != '') ? ' row-loyalty-offer' : '' }}">
							<a href="#menu-{{ $menuType->dish_id }}" onclick="getMenuDetail(this, {{ $menuType->dish_id }}, 1, '{{ $storedetails->store_id }}')" data-toggle="collapse">
								<span>
									{{ $menuType->dish_name }} 
									{!! $strLoyaltyOffer !!}
								</span> 
								<!-- <span class="icon-fa-angle-right"><i class="fa fa-angle-right"></i></span> -->
							</a>
							<div class="collapse menu-detail" id="menu-{{ $menuType->dish_id }}">
								<div class="text-center"><i class="fa fa-spinner" aria-hidden="true"></i></div>
							</div>
						</div>
					@endif
				@endforeach
			</div>
			<input type="hidden" id="browserCurrentTime" name="browserCurrentTime" value="" />
			<input type="hidden" name="storeID" value="{{ $storedetails->store_id }}" />
			<input type="hidden" name="browser" id="browser" value="">
			@if($storedetails->deliveryTypes->count() == 1)
				@if($storedetails->deliveryTypes[0]['delivery_type'] == 3 && Helper::isPackageSubscribed(12))
					<input type="hidden" name="delivery_type" value="{{ $storedetails->deliveryTypes[0]['delivery_type'] }}" />
				@elseif($storedetails->deliveryTypes[0]['delivery_type'] != 3)
					<input type="hidden" name="delivery_type" value="{{ $storedetails->deliveryTypes[0]['delivery_type'] }}" />
				@endif
			@endif
		</form>
		
		<!-- Popup add comment -->
		<div id="transitionExample" class="modal fade" role="dialog">
			<div class='modal-dialog'>
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">{{ __('messages.Add Comments') }}</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<textarea name="textarea-1" id="textarea-1" placeholder="{{ __('messages.Add Comments') }}" class="form-control" rows="2"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
						<button type="button" class="btn btn-primary submit-btn" id="submitId">{{ __('messages.Submit') }}</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Popup GDPR -->
		<div class="modal fade pop_up popgdpr" role="dialog">
			<div class='modal-dialog'>
				<div class="modal-content">
					<div class="modal-header text-center">
						<button type="button" class="close popup-close1" onclick="off()">&times;</button>
						<h3 class="modal-title">GDPR</h3>
					</div>
					<div class="modal-body">
						{!! __('messages.gdprModalText') !!}
						<div class="text-center">
							<button type="button" class="btn btn-success accept-btn submit_btn">Accept</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default" style="display: none;">
			<span class="ui-icon-loading"></span><h1>loading</h1>
		</div>

		<div id="overlay" onclick="off()" style="display: none;"></div>
	@else
		<p class="text-center">{{ __('messages.Menu is not available.') }}</p>
	@endif
@endsection

@section('footer-script')
<script type="text/javascript">
	var id;
	var cntCartItems = 0;

	@if(Session::has('order_date'))
		var store_open_close_day_time = '{{ $storedetails->store_open_close_day_time_catering }}';
	@else
		var store_open_close_day_time = '{{ $storedetails->store_open_close_day_time }}';
	@endif

	$(function() {
		$(document).on('click', '.extra-btn a', function() {
			id=$(this).attr('id');
			comment = $('#orderDetail'+id).val();
			$('textarea#textarea-1').val(comment);
		});

		// Scroll to top on menu open
		$(".collapse").on('shown.bs.collapse', function(){
			var position = $(this).offset().top;
			$('html, body').stop().animate({ scrollTop : (position-100) }, 200);
		});

		$('#submitId').click(function(){
			var text = $('textarea#textarea-1').val();
			$('#orderDetail'+id).val(text);

			if(text.trim()!=""){
				$('#orderDetail'+id).parent().find('.add_comment').hide();
				$('#orderDetail'+id).parent().find('.edit_comment').show();
			}else{
				$('#orderDetail'+id).parent().find('.edit_comment').hide();
				$('#orderDetail'+id).parent().find('.add_comment').show();
			}

			$('#transitionExample').modal("hide");
			$('#textarea-1').val("");
		});

		// 
		$("#menudataSave").click(function(e){
			if( !checkTime(store_open_close_day_time, '{{ Session::get('order_date') }}') )
			{
				$('.alert-store-closed').removeClass('collapse').addClass('collapse-in');

				$('html, body').animate({
					scrollTop: ($(".alert-store-closed").offset().top - 50)
				}, 'slow');
			}
			else
			{
				var d = new Date();
				//console.log(d);
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
					send_btn();
				} else{
					alert("Please select item from the menu.");
					e.preventDefault();
				}
			}
			console.log('menudataSave');
		});

		$("body").on('click',".accept-btn", function(){
			$.ajax({
				url: "{{ url('accept-gdpr') }}", 
				type: "POST",
				data: {
					"_token": "{{ csrf_token() }}"
				},
				success: function(result){
					//console.log(result);
	    			if(result == 0){
						off();
					}else{
						off();
						$("#form").submit();
					}
				}
			});
		});
	});

	// 
	function getMenuDetail(This, dishType, level, storeId)
	{
		// 
		This = $(This);
		let url = '{{ url('get-menu-detail') }}/'+dishType+'/'+level+'/'+storeId;

		@if($styleType || $storedetails->menu_style_type)
			// 
			if(level == 1)
			{
				if(This.closest('.col-xs-6').hasClass('active'))
				{
					$('.col-xs-6').removeClass('active');
					This.closest('.row').find('.menu-detail').collapse('hide');
					return false;
				}
				else
				{
					$('.col-xs-6').removeClass('active');
					$('.menu-detail').removeClass('in');
					This.closest('.col-xs-6').addClass('active');
					This.closest('.row').find('#menu-detail-'+dishType).collapse('show');

					if(This.closest('.row').find('#menu-detail-'+dishType+' .list-menu-items').length || This.closest('.row').find('#menu-detail-'+dishType+' .sub-menu-detail').length)
					{
						return false;
					}
					else
					{
						This.closest('.row').find('#menu-detail-'+dishType).html('{{ __('messages.loadingText') }}');
					}
				}
			}
			else
			{
				if(This.closest('.product-sub').find('#sub-menu-'+dishType+' .sub-menu-detail').length || This.closest('.product-sub').find('#sub-menu-'+dishType+' .list-menu-items').length || This.closest('.product-sub').find('#sub-menu-'+dishType+' .no-product').length)
				{
					return false;
				}
			}

			// 
			$.ajax({
				url: url,
				dataType: 'json',
				success: function(response) {
					if(response.status)
					{
						if(level == 1)
						{
							This.closest('.row').find('#menu-detail-'+dishType).html(response.html);
						}
						else
						{
							This.next('.sub-menu-detail').html(response.html);
						}
					}
				},
				error: function() {
					alert('Something went wrong!');
				}
			});
		@else
			// 
			if(This.next('.menu-detail').find('.list-menu-items').length || This.next('.sub-menu-detail').find('.list-menu-items').length)
			{
				return false;
			}

			// 
			$.ajax({
				url: url,
				dataType: 'json',
				success: function(response) {
					if(response.status)
					{
						if(level == 1)
						{
							This.next('.menu-detail').html(response.html);
						}
						else
						{
							This.next('.sub-menu-detail').html(response.html);
						}
					}
				},
				error: function() {
					alert('Something went wrong!');
				}
			});
		@endif
	}

	function send_btn(){
		$('#overlay').css("display", "block");
		$('#loading-img').css("display", "block");
		
		var checkList = [];
		$('.product .product_input_quantity').each(function(){
			if($(this).val() > 0){
				checkList.push($(this).attr('rel'));
			}
		});

		$.ajax({
			url: "{{ url('gdprExtra') }}", 
			type: "POST",
			data: {
				"_token": "{{ csrf_token() }}",
				'checkList' : checkList,
				'storeID' : $('input[name=storeID]').val(),
			},
			success: function(result){
				// console.log(result);
				if(result.extra_product == '1'){
					$("#form").attr('action',"{{ route('extraMenuList') }}"); 
					$("#form").submit();
				}else{
					if(result.gdpr_val == 0){
						$("#loading-img").hide();
						$('#overlay').show();
						$('.pop_up').modal("show");
					}else{
						$("#form").submit();
					}
				}
			}
		});		
	}

	function off(){
		$("#loading-img").hide();
		$('.pop_up').modal("hide");
		$('#overlay').hide();
	}	

	function makeRedirection(link){
		window.location.href = link;
	}

	// 
	if( !checkTime(store_open_close_day_time, '{{ Session::get('order_date') }}') )
	{
		$('.alert-store-closed').removeClass('collapse').addClass('collapse-in');
	}

	$('.alert-store-closed .close').on('click', function() {
		$('.alert-store-closed').removeClass('collapse-in').addClass('collapse');
	});

	navigator.sayswho= (function(){
        var ua= navigator.userAgent, tem, 
        M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1])){
            tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
            return 'IE '+(tem[1] || '');
        }
        if(M[1]=== 'Chrome'){
            tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
            if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
        }
    
        M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
        if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);

        document.getElementById('browser').value = M.join(' ');
        
    })();
</script>
@endsection