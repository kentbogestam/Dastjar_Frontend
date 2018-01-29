@extends('layouts.master')
@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			<div class="inner-logo">
				<img src="{{asset('images/logo.png')}}">
				<span>{{ Auth::user()->name}}</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"  data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			<div class="wait-bg-img">
				<div class="text-content">
					<p>We are preaparing your order </p>
					<p>Order Number </p>
					<p class="large-text">{{$order->customer_order_id}}</p>
					<p>To be ready in {{$order->order_delivery_time}} mins
						@if($order->order_type == 'eat_later')
						{{$order->deliver_date}}
						@endif
					</p>
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
			<span>send</span>
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
	    	<a  class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec">
		        <div class="img-container">
		       		<!-- <img src="images/icons/select-store_05.png"> -->
		        	<img src="{{asset('images/icons/select-store_05-active.png')}}">
		        </div>
	        	<span>Order<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
	        </a>
	        <div id="order-popup" data-theme="a">
		      <ul data-role="listview">
		      	@foreach(Auth::user()->paidOrderList as $order)
					<li>
						<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">Order id - {{$order->customer_order_id}}</a>
					</li>
				@endforeach
		      </ul>
		    </div>
	    </div>
		@endif
		<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>

@endsection

@section('footer-script')

<script type="text/javascript">
	 $(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });
</script>

@endsection