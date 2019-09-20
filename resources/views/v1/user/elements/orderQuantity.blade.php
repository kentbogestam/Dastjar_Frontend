@if(Auth::check())
	@if(count(Auth::user()->paidOrderList) == 0)
		<li>
			<a href="javascript:void(0)">
				<i class="fa fa-file-text"></i> 
			</a>
		</li>
	@else
		<li>
			<a href="javascript:void(0)" class="ordersec" onclick="orderPopup()">
				<i class="fa fa-file-text"></i> 
				<sup><span class="badge sCartBage order-number<?php echo !(count(Auth::user()->paidOrderList)) ? ' hidden' : ''; ?>">{{count(Auth::user()->paidOrderList)}}</span></sup>
			</a>
		</li>
		<div id="order-popup" class="hidden">
			<ul>
				@foreach(Auth::user()->paidOrderList as $order)
					<li>
						<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">
							{{ __('messages.Order id') }} - {{$order->customer_order_id}}
						</a>
					</li>
				@endforeach
			</ul>
		</div>
	@endif
@else
	<li>
		<a href="javascript:void(0)">
			<i class="fa fa-file-text"></i> 
		</a>
	</li>
@endif