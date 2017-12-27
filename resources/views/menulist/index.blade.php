@extends('layouts.master')
@section('content')

	<div data-role="header" class="header">
		<div class="logo">
			<div class="inner-logo">
				<span class="rest-title">{{$companydetails->company_name}}</span>
				<span>{{ Auth::user()->name}}</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" onClick="makeRedirection('{{url('search-store-map')}}')" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-order') }}">
		{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content">
			<div class="cat-list-sec single-restro-list-sec">
				<input type="hidden" name="storeID" value="{{$storeId}}" />
					<?php $i =0 ?>
					<?php $j =1 ?>
					@foreach($menuTypes as $menuType)
						@if($i == 0)
							<div data-role="collapsible" data-iconpos="right" data-collapsed="false"> <h3>{{$menuType->dish_name}}</h3> <p>
							@foreach($menuDetails as $productDetail)
								@foreach($productDetail->storeProduct as $menuDetail)
									@if($menuType->dish_id == $menuDetail->dish_type)
										<ul data-role="listview" data-inset="true" >
											<li>
													<img src="{{asset('images/img-item-1.png')}}">
													<div class="list-content">
														<h2>{{$menuDetail->product_name}}</h2>
													<p>{{$menuDetail->product_description}}</p>
													<p class="price">
														@foreach($productDetail->menuPrice as  $key => $menuPrices)
															$ {{$menuPrices->price}}
														@endforeach
													</p>
													</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" maxlength="2" max="10" size="1" value = 0 id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>
												
												<div class="extra-btn">
														<label><img src="{{asset('images/icons/icon-wait-time.png')}}" width="15px">{{$menuDetail->preparation_Time}}</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('images/icons/icon-add-comments.png')}}" width="18px">Add Comments</a></label>
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
													<img src="{{asset('images/img-item-1.png')}}">
													<div class="list-content">
														<h2>{{$menuDetail->product_name}}</h2>
													<p>{{$menuDetail->product_description}}</p>
													<p class="price">
														@foreach($productDetail->menuPrice as  $key => $menuPrices)
															$ {{$menuPrices->price}}
														@endforeach
													</p>
													</div>
												<input type="hidden" name="product[{{$j}}][id]" value="{{$menuDetail->product_id}}" />
												<div class="qty-sec">
													<input type="button" onclick="decrementValue('{{$menuDetail->product_id}}')" value="-"  class="min" />
													<input type="text" name="product[{{$j}}][prod_quant]" value="0" maxlength="2" max="10" size="1" id="{{$menuDetail->product_id}}" />
													<input type="button" onclick="incrementValue('{{$menuDetail->product_id}}')" value="+" class="max" />
												</div>

												<div class="extra-btn">
														<label><img src="{{asset('images/icons/icon-wait-time.png')}}" width="15px">{{$menuDetail->preparation_Time}}</label>
														<label><a id="{{$menuDetail->product_id}}" href="#transitionExample" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="popup"><img src="{{asset('images/icons/icon-add-comments.png')}}" width="18px">Add Comments</a></label>
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
				<a href="#" data-rel="back"  class="cancel-btn ui-btn ui-btn-left ui-corner-all ui-shadow ui-btn-a">cancel</a>
				<label>Add comment</label>
				
				</div>
				<div class="pop-body">
					
						<textarea name="textarea-1" id="textarea-1" placeholder="Add a comment"></textarea>
						<a id="submitId" href="" data-ajax="false" class="submit-btn ui-btn ui-btn-right ui-corner-all ui-shadow ui-btn-a">submit</a>

					
				</div>
			</div>
		</div>



		<div data-role="footer" class="footer" data-position="fixed">
			<div class="ui-grid-c inner-footer center">
			<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_01.png')}}">
				</div>
				<span>Restaurant</span>
			</a></div>
			<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_03.png')}}">
				</div>
				<input type="button" value="Submit" id="dataSave"/>
			</a></div>
			@if(count(Auth::user()->paidOrderList) == 0)
				<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_05.png')}}">
					</div>
					<span>Order</span>
				</a></div>
			@else
				<div class="ui-block-c order-active">
					<a href="#order-popup" data-transition="slideup" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline"  data-rel="popup">
						<div class="img-container">
							<!-- <img src="images/icons/select-store_05.png"> -->
							<img src="{{asset('images/icons/select-store_05-active.png')}}">
						</div>
						<span >Order<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
					</a>
				</div>
			@endif
			<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
			</a></div>
			</div>
		</div>
	</form>
	<!-- pop-up -->
	<div data-role="popup" id="order-popup" class="ui-content" data-theme="a">
		<ul data-role="listview">
			@foreach(Auth::user()->paidOrderList as $order)
				<li>
					<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">Order id - {{$order->order_id}}</a>
				</li>
			@endforeach
		</ul>
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

	$("#dataSave").click(function(e){

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
	})

	function makeRedirection(link){
		window.location.href = link;
	}
	
</script>
@endsection