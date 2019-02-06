@if(Auth::check())
	@if(count(Auth::user()->paidOrderList) == 0)
		<div class="ui-block-c">
			<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/notification-2.png')}}">
				</div>
				<!-- <span class="name-order">{{ __('messages.Orders') }}</span> -->
			</a>
		</div>
	@else
	<div class="ui-block-c order-active">
    	<a href="javascript:void(0)" class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec" data-ajax="false" onclick="orderPopup()">
	        <div class="img-container orderNumImg">
	        	<span class="order-number<?php echo !(count(Auth::user()->paidOrderList)) ? ' hidden' : ''; ?>">{{count(Auth::user()->paidOrderList)}}</span>
	        	<img src="{{asset('images/notification-2.png')}}">
	        </div>
        	<!-- <span class="name-order">{{ __('messages.Orders') }}</span> -->

        </a>

        <div id="order-popup" data-theme="a">
	      <ul data-role="listview">
	      	@foreach(Auth::user()->paidOrderList as $order)
				<li>
					<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->customer_order_id}}</a>
				</li>
			@endforeach
	      </ul>
	    </div>
    </div>
	@endif
@else

<div class="ui-block-c">
	<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
		<div class="img-container">
			<img src="{{asset('images/notification-2.png')}}">
		</div>
		<!-- <span class="name-order">{{ __('messages.Orders') }}</span> -->
	</a>
</div>

@endif