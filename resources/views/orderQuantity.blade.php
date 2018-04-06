@if(Auth::check())
	@if(count(Auth::user()->paidOrderList) == 0)
		<div class="ui-block-c">
			<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_05.png')}}">
				</div>
				<span>{{ __('messages.Order') }}</span>
			</a>
		</div>
	@else
	<div class="ui-block-c order-active">
    	<a  class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec" data-ajax="false">
	        <div class="img-container">
	       		<!-- <img src="images/icons/select-store_05.png"> -->
	        	<img src="{{asset('images/icons/select-store_05-active.png')}}">
	        </div>
        	<span>{{ __('messages.Order') }}<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
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
			<img src="{{asset('images/icons/select-store_05.png')}}">
		</div>
		<span>{{ __('messages.Order') }}</span>
	</a>
</div>

@endif