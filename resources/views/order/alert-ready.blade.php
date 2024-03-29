@extends('layouts.master')
@section('content')
<div data-role="page" data-theme="c">
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<!-- Back button if redirected here automatically on order ready -->
		@if(Request::server('HTTP_REFERER'))
			<a href="{{ Request::server('HTTP_REFERER') }}" data-ajax="false" style="padding: 15px 0 0px 10px;">
				<img src="{{asset('images/icons/backarrow.png')}}" width="11px">
			</a>
		@endif

		<div class="logo">
			<div class="inner-logo">
				<span class="rest-title">{{$companydetails->store_name}}</span>
				<!-- <img src="{{asset('images/logo.png')}}"> -->
				<span>{{ $user->name}}</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"  data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			<div class="alt-msg">
				<img src="{{asset('images/ready-chef.png')}}">
				<div class="text-msg">
					@if($orderDetail->delivery_type == 3)
						<p>
							{{ __('messages.Your order Number') }} {{$orderID}}
							<br><span>{{ __('messages.alertOrderReadyOnHomeDelivery') }}!</span>
						</p>
					@else
						<p>{{ __('messages.Your order Number') }} {{$orderID}} {{ __('messages.is') }} <span>{{ __('messages.Order Ready To Pick Up') }}!</span></p>
					@endif
				</div>
			</div>
		</div>
	</div>

	@include('includes.fixedfooter')

	<!-- <div data-role="footer" class="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="{{ Session::get('route_url') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@if(count($user->paidOrderList) == 0)
		<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_05.png')}}">
			</div>
			<span>{{ __('messages.Order') }}</span>
		</a></div>
		@else
		<div class="ui-block-c order-active">
	    	<a  class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec" data-ajax="false">
		        <div class="img-container">
		        	<img src="{{asset('images/icons/select-store_05-active.png')}}">
		        </div>
	        	<span>{{ __('messages.Order') }}<span class="order-number">{{count($user->paidOrderList)}}</span></span>
	        </a>
	        <div id="order-popup" data-theme="a">
		      <ul data-role="listview">
		      	{{-- @foreach($user->paidOrderList as $order)
					<li>
						<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->customer_order_id}}</a>
					</li>
				@endforeach --}}
		      </ul>
		    </div>
	    </div>
		@endif
		<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div> -->

</div>
@endsection

@section('footer-script')
	<script type="text/javascript">
		/*$(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });*/
	</script>
@endsection