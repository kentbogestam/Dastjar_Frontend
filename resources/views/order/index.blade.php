@extends('layouts.master')
@section('content')
	<div data-role="header" class="header">
		<div class="logo">
			<div class="inner-logo">
				<img src="images/logo.png">
				<span>Kent</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"  data-ajax="false"><img src="images/icons/map-icon.png" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			<div class="wait-bg-img">
				<div class="text-content">
					<p>We are preaparing your order </p>
					<p>Order Number </p>
					<p class="large-text">{{$order->order_id}}</p>
					<p>To be ready in {{$order->order_delivery_time}} mins</p>
				</div>
			</div>
			<div class="table-content">
				<h2>ORDER DETAILS</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
					@foreach($orderDetails as $orderDetail)
						<tr>
							<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>$ {{$orderDetail->product_quality*$orderDetail->price}}</td>
						</tr>	
					@endforeach
				<tr class="last-row">	<td> </td><td>         </td><td>  TOTAL    ${{$order->order_total}}</td></tr>
				</tr>
				</table>
			</div>
		</div>
	</div>


	<div data-role="footer" class="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="index.html" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="images/icons/select-store_01.png">
			</div>
			<span>Restaurant</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="images/icons/select-store_03.png">
			</div>
			<span>send</span>
		</a></div>
		<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="images/icons/select-store_05.png">
			</div>
			<span>Order</span>
		</a></div>
		<div class="ui-block-d"><a href="setting.html" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="images/icons/select-store_07.png"></div>
		</a></div>
		</div>
	</div>

@endsection

@section('footer-script')



@endsection