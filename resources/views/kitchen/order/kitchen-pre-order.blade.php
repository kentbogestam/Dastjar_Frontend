@extends('layouts.blank')
@section('head-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>


    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
     <script>
          $(document).ready(function () {
              App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
              App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

              App42.initialize("cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2","297b31b7c66e206b39598260e6bab88e701ed4fa891f8995be87f786053e9946");
              App42.enableEventService(true);
              var userName;
              new Fingerprint2().get(function(result, components){
                  userName = "{{Auth::guard('admin')->user()->email}}";
                  console.log("Username : " + userName); //a hash, representing your device fingerprint
                  App42.setLoggedInUser(userName);
                  getDeviceToken();
              });
          });
      </script>
@endsection
@section('content')
	<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
		</div>
			<h3 class="ui-bar ui-bar-a order_background">
				<a href="{{ URL::previous() }}" data-ajax="false" class="text-left ui-link ui-btn back_btn"><img src="http://localhost/dast-jar-frontend/public/images/icons/backarrow.png" width="11px"></a>

				{{$storedetails->store_name}}
			</h3>
		<div class="top_two-menu">
			<div class="ui-grid-a center">
				<div class="ui-block-a"><a href="#order-popup" data-rel="popup" data-transition="turn" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<img src="{{asset('kitchenImages/order-img.png')}}">
					</div>
					<span>Order<span class="order_number">{{count(Auth::guard('admin')->user()->kitchenPaidOrderList)}}</span></span>
				</a></div>
				<div class="ui-block-b"><a onClick="makeRedirection('{{url('kitchen/selectOrder-dateKitchen')}}')" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/eat-icon.png')}}">
					</div>
					<span>Eat Later</span>
				</a></div>
				<div class="ui-block-c"><a onClick="makeRedirection('{{url('kitchen/kitchen-pre-order')}}')" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/eat-icon.png')}}">
					</div>
					<span>Eat Now</span>
				</a></div>
			</div>
		</div>
	</div>
	<div data-role="popup" id="order-popup" data-theme="b">
        <ul data-role="listview" data-inset="true" style="min-width:210px;">
            <!-- <li data-role="list-divider">Choose an action</li> -->
            @foreach(Auth::guard('admin')->user()->kitchenPaidOrderList as $order)
            	<li><a href="{{ url('kitchen/kitchen-order-view/'.$order->order_id) }}">Order - {{$order->customer_order_id}}</a></li>
            @endforeach
        </ul>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/kitchen-order-save') }}">
		{{ csrf_field() }}
	
		<div role="main" data-role="main-content" class="content">
		
			<div class="cat-list-sec single-restro-list-sec">
				<div class="ui-grid-a">
					<div class="ui-block-a">
						@if($menuDetails == null || $menuTypes == null)
							<p>Menu is not available.</p>
						@else
						<?php $i =0 ?>
						<?php $j =1 ?>
						@foreach($menuTypes as $menuType)
							@if($i == 0)
							<div data-role="collapsible" data-iconpos="right" data-collapsed="false"> <h3 class="">{{$menuType->dish_name}}</h3>
								@foreach($menuDetails as $productDetail)
									@foreach($productDetail->storeProduct as $menuDetail)
										@if($menuType->dish_id == $menuDetail->dish_type)
										<ul data-role="listview" data-inset="true">
											<li>
												<img class="product_image" src="{{asset('kitchenImages/pizza1.png')}}">
												<div class="list-content">
													<h2>{{$menuDetail->product_name}}</h2>
												<p>{{$menuDetail->product_description}}</p>
													<p class="price">
														${{$productDetail->price}}
												</p>
												</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" value="0" maxlength="2" max="10" size="1" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>
												<div class="extra-btn">
														<label><img src="{{asset('kitchenImages/icon-wait-time.png')}}" width="15px">{{$menuDetail->preparation_Time}}</label></label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('kitchenImages/icon-add-comments.png')}}" width="18px">Add Comments</a></label>
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
												<img class="product_image" src="{{asset('kitchenImages/pizza1.png')}}">
												<div class="list-content">
													<h2>{{$menuDetail->product_name}}</h2>
												<p>{{$menuDetail->product_description}}</p>
												<p class="price">${{$productDetail->price}}
												 </p>
												</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" value="0" maxlength="2" max="10" size="1" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>
												<div class="extra-btn">
														<label><img src="{{asset('kitchenImages/icon-wait-time.png')}}" width="15px">{{$menuDetail->preparation_Time}}</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('kitchenImages/icon-add-comments.png')}}" width="18px">Add Comments</a></label>
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
							<h3>Download the Web App to your Mobile and get 10% discount on your order</h3>
							<div class="mid_para">
								<p>To gain instant information to when your oreder is ready</p>
								<p>To order ahead of your arrival so tha food is ready when you get here</p>
							</div>
							<h3 class="no-margin">Pick it up from here</h3>
							<a href="">dastjar.com/download</a>
							<h3>Or</h3>
							<h3>enter your mobile number here to get a message with the app</h3>
							<form>
							<div class="ui-field-contain search_container"> <input type="text" data-clear-btn="true" data-mini="true" name="text-15" id="text-15" value=""> <button type="submit" id="submit-6" class="ui-shadow ui-btn ui-corner-all ui-mini">ok</button> </div>
							</form>
							<h3>or use the QR code to get the App</h3>
							<div class="scan_code">
								<img src="http://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=https://dastjar.com/dbuzzu/public/">
							</div>
					</div>
				</div>
			</div>
		</div>
				<!-- popup section -->

		<div data-role="popup" id="transitionExample" class="ui-content comment-popup" data-theme="a">
			<div class="pop-header">
			<a href="#" data-rel="back"  class="cancel-btn ui-btn ui-btn-left ui-corner-all ui-shadow ui-btn-a">cancel</a>
			<label>Add comment</label>
			
			</div>
			<div class="pop-body">
				
				<textarea name="textarea-1" id="textarea-1" placeholder="Add a comment"></textarea>
				<a id="submitId" href="" data-ajax="false" class="submit-btn ui-btn ui-btn-right ui-corner-all ui-shadow ui-btn-a">submit</a>
			</div>
		</div>

	</form>
	



	<div data-role="footer" class="footer_container" data-position="fixed" data-tap-toggle="false">
		<div class="ui-grid-a inner-footer center">
		<div class="ui-block-a"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container" id = "menudataSave">
				<img src="{{asset('kitchenImages/send_icon.png')}}">
			</div>
			<span>send</span>
		</a></div>
		</div>
	</div>
@endsection

@section('footer-script')

	<script type="text/javascript">

		var id ;
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
				$("#form").submit();
			} else{
				alert("Please fill some value");	
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
		    if(value>1){
		        value--;
		            document.getElementById(id).value = value;
		    }

		}

		function makeRedirection(link){
			window.location.href = link;
		}
	</script>

@endsection