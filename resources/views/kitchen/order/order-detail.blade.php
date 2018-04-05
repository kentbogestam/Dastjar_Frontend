@extends('layouts.blank')

@section('content')
	<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
		<img src="{{asset('kitchenImages/logo-img.png')}}">
		</div>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="order_display">
			<div class="order_bg">
				<div class="order-ready-text">
					<p>{{ __('messages.Thanks for your order') }} </p>
					<p>{{ __('messages.Order Number') }} </p>
					<p class="order-no">{{$order->customer_order_id}}</p>
					<p>({{$order->store_name}})</p>
					<p>{{ __('messages.Your order will be ready on') }} {{$order->order_delivery_time}} mins
						@if($order->order_type == 'eat_later')
						{{$order->deliver_date}}
						@endif
					</p>
				</div>
			</div>
			<div class="table-wrap">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive ui-table">
					<tbody>
						@foreach($orderDetails as $orderDetail)
							<tr>
								<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
							</tr>	
						@endforeach
						<tr class="last-row">
							<td> </td>
							<td>         </td>
							<td>  {{ __('messages.TOTAL') }}    {{$order->currencies}} {{$order->order_total}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer_container">
		<div class="ui-grid-a center">
			<div class="ui-block-a left-side_menu">
				<div class="ui-block-a active"><a href = "{{ url('kitchen/store') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-1.png')}}">
					</div>
					<span>{{ __('messages.Orders') }}</span>
				</a></div>
				<div class="ui-block-b"><a href = "{{ url('kitchen/kitchen-detail') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-2.png')}}">
					</div>
					<span>{{ __('messages.Kitchen') }}</span>
				</a></div>
			</div>
			<div class="ui-block-b right-side_menu">
				<div class="ui-block-a drop_down"><a href = "{{ url('kitchen/kitchen-setting') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-6.png')}}">
					</div>
				</a></div>
				<div class="ui-block-b middle-menu"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-5.png')}}">
					</div>
					<span>{{ __('messages.Admin') }}</span>
				</a></div>
				<div class="ui-block-c"><a href = "{{ url('kitchen/kitchen-order-onsite') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-4.png')}}">
					</div>
					<span>{{ __('messages.Order Onsite') }}</span>
				</a></div>
			</div>
		</div>
	</div>
@endsection

@section('footer-script')


@endsection