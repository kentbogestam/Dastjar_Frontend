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
					<p>
						<?php
							$time = $order->order_delivery_time;
							$time2 = $storeDetail->extra_prep_time;
							$secs = strtotime($time2)-strtotime("00:00:00");
							$result = date("H:i:s",strtotime($time)+$secs);
						?>

						@if($order->order_type == 'eat_later')
						{{ __('messages.Your order will be ready on') }}
						{{$order->deliver_date}}
						{{date_format(date_create($order->deliver_time), 'G:i')}} 
						@else
						{{ __('messages.Your order will be ready in about') }}
							@if(date_format(date_create($result), 'H')!="00")
							{{date_format(date_create($result), 'H')}} hours 						
							@endif
						{{date_format(date_create($result), 'i')}} mins
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

	@include('includes.kitchen-footer-menu')
@endsection

@section('footer-script')


@endsection