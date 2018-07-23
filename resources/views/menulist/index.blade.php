@extends('layouts.master')

@section('styles')
	<style>
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
			width: auto !important;		
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
@stop

@section('content')
	<div data-role="header" class="header"  data-position="fixed" data-tap-toggle="false">
		<div class="logo">
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="{{url('search-store-map')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	@if(count($menuTypes) == '0')
	<div class="table-content">
		<p>{{ __('messages.Menu is not available in your selected language.') }} </p>
	</div>
	@endif
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-order') }}">
		{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content">
			<div class="cat-list-sec single-restro-list-sec">
				<input type="hidden" id="browserCurrentTime" name="browserCurrentTime" value="" />
				<input type="hidden" name="storeID" value="{{$storeId}}" />
					<?php $i =0 ?>
					<?php $j =1 ?>
					@foreach($menuTypes as $menuType)
						@if($i == 0)
							<div data-role="collapsible" data-iconpos="right" >
							 <h3>{{$menuType->dish_name}}</h3> <p>
							@foreach($menuDetails as $productDetail)
								@foreach($productDetail->storeProduct as $menuDetail)
									@if($menuType->dish_id == $menuDetail->dish_type)
										<ul data-role="listview" data-inset="true" >
											<li>
													<img src="{{$menuDetail->small_image}}">
													<div class="list-content">
														<h2>{{$menuDetail->product_name}}</h2>
													<div class="fulldiscription"><p>{{$menuDetail->product_description}}</p></div>
													<p class="price">
														{{$companydetails->currencies}} {{number_format((float)$productDetail->price, 2, '.', '')}}
													</p>
													</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" readonly name="product[{{$j}}][prod_quant]" maxlength="2" size="1" value="0" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>
												
												<div class="extra-btn">
														<label><img src="{{asset('images/icons/icon-wait-time.png')}}" width="15px">
															<?php
																$time = $menuDetail->preparation_Time;
																if(isset($storedetails->extra_prep_time)){
																$time2 = $storedetails->extra_prep_time;
																}else{
																$time2 = "00:00:00";
																}
																$secs = strtotime($time2)-strtotime("00:00:00");
																$result = date("H:i:s",strtotime($time)+$secs);
															?>
															@if(date_create($result) != false)
															{{date_format(date_create($result), 'H').':'.date_format(date_create($result), 'i')}}
															@else
																{{$result}}
															@endif
														</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('images/icons/icon-add-comments.png')}}" width="18px">{{ __('messages.Add Comments') }}</a></label>
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
							<div data-role="collapsible" data-iconpos="right"> <h3>{{$menuType->dish_name}}</h3> <p>
							@foreach($menuDetails as $productDetail)
							
								@foreach($productDetail->storeProduct as $menuDetail)
									@if($menuType->dish_id == $menuDetail->dish_type)
										<ul data-role="listview" data-inset="true" >
											<li>
													<img src="{{$menuDetail->small_image}}">
													<div class="list-content">
														<h2>{{$menuDetail->product_name}}</h2>
													<div class="fulldiscription"><p>{{$menuDetail->product_description}}</p></div>
													<p class="price">
														{{$companydetails->currencies}} {{number_format((float)$productDetail->price, 2, '.', '')}}
													</p>
													</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" value="0" maxlength="2" readonly size="1" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>

												<div class="extra-btn">
														<label><img src="{{asset('images/icons/icon-wait-time.png')}}" width="15px">
															<?php
																$time = $menuDetail->preparation_Time;
																if(isset($storedetails->extra_prep_time)){
																$time2 = $storedetails->extra_prep_time;
																}else{
																$time2 = "00:00:00";
																}
																$secs = strtotime($time2)-strtotime("00:00:00");
																$result = date("H:i:s",strtotime($time)+$secs);
															?>
															@if(date_create($result) != false)
															{{date_format(date_create($result), 'H').':'.date_format(date_create($result), 'i')}}
															@else
																{{$result}}
															@endif
														</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('images/icons/icon-add-comments.png')}}" width="18px">{{ __('messages.Add Comments') }}</a></label>
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
		</div>



		<div data-role="footer" class="footer" data-tap-toggle="false" data-position="fixed">
			<div class="ui-grid-c inner-footer center">
			<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_01.png')}}">
				</div>
				<span>{{ __('messages.Restaurant') }}</span>
			</a></div>
			<div class="ui-block-b">
				<a href="javascript:void(0)" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container" id="menudataSave">
						<img src="{{asset('images/icons/select-store_03.png')}}">
					</div>
					<input type="button" value="{{ __('messages.Send') }}" id="dataSave"/>
				</a>
			</div>
			@include('orderQuantity')
			<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
			</a></div>
			</div>
		</div>
	</form>
	
	  <div class="pop_up">   
		<div class="pop_up_inner">   
		  <article class="pop_up_content">
				<div>
					<h2>GDPR</h2>                   
				</div>

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
						We protect your personal data in accordance with EU's GDPR (General Data Protection Regulations).
					</p>
					<p>
						This is a location-based service! It will save your time and serve you best based on your location! To manage to do so, we need your permission to store your login data.
						This will help us to secure that your orders are delivered to you and no one else.
					</p>
				</div>
			@elseif($lan == "swe")
				<div>
					<p>
						Vi skyddar dina personliga uppgifter i enighet med EUs GDPR (General Data Protection Regulations).
					</p>
					<p>
						Detta är en positionsbaserad tjänst. Den spar din tid och tjänar dig bäst där du finns. För att kunna göra så behöver vi ditt tillstånd att lagra dina inloggningsuppgifter. Det hjälper oss att säkerställa att din beställning levereras till just dig och ingen annan.
					</p>
				</div>
			@endif

			<div class="pop_up-footer">
				<button type="button" class="accept-btn submit_btn">Accept</button>
			</div>
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
		var id;

		$(".extra-btn a").click(function(){
			id=$(this).attr('id');
			comment = $('#orderDetail'+id).val();
			$('textarea#textarea-1').val(comment);			
		});
		
	$('#submitId').click(function(){ 
		var text = $('textarea#textarea-1').val();
		$('#orderDetail'+id).val(text);
		$('#transitionExample').popup("close");
		$('#textarea-1').val("");					
	});

	$("#menudataSave").click(function(e){
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
		
	});

	function send_btn(){
		$('#overlay').css("display", "block");
		$('#loading-img').css("display", "block");

		$.ajax({
			url: "{{ url('gdpr') }}", 
			type: "POST",
			data: {
				"_token": "{{ csrf_token() }}"
			},
			success: function(result){
				console.log(result);
				if(result == 0){
					$("#loading-img").hide();
					$('#overlay').show();
					$(".pop_up").show();
				}else{
					$("#form").submit();
				}
			}
		});		
	}

	$("body").on('click',".accept-btn", function(){
		$.ajax({
			url: "{{ url('accept-gdpr') }}", 
			type: "POST",
			data: {
				"_token": "{{ csrf_token() }}"
			},
			success: function(result){
				console.log(result);
    			if(result == 0){
					off();
				}else{
					off();
					$("#form").submit();
				}
			}
		});		
	});
	
	function off(){
		$("#loading-img").hide();
		$(".pop_up").hide();
		$('#overlay').hide();
	}	

	function makeRedirection(link){
		window.location.href = link;
	}

	$(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });
	
	 $("body").on('click', ".submit_btn", function (e) {        
        // Remove any old one
        $(".ripple").remove();
      
        // Setup
        var posX = $(this).offset().left,
            posY = $(this).offset().top,
            buttonWidth = $(this).width(),
            buttonHeight =  $(this).height();
        
        // Add the element
        $(this).prepend("<span class='ripple'></span>");     
        
       // Make it round!
        if(buttonWidth >= buttonHeight) {
          buttonHeight = buttonWidth;
        } else {
          buttonWidth = buttonHeight; 
        }
        
        // Get the center of the element
        var x = e.pageX - posX - buttonWidth / 2;
        var y = e.pageY - posY - buttonHeight / 2;
               
        // Add the ripples CSS and start the animation
        $(".ripple").css({
          width: buttonWidth,
          height: buttonHeight,
          top: y + 'px',
          left: x + 'px'
        }).addClass("rippleEffect");
      });
</script>
@endsection
