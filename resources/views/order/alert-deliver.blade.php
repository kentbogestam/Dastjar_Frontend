@extends('layouts.master')
@section('content')
<div data-role="page" data-theme="c">
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			<div class="inner-logo">
				<!-- <img src="{{asset('images/logo.png')}}"> -->
				<span class="rest-title">{{$companydetails->store_name}}</span>
				<span>{{ $user->name}}</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"  data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			<div class="alt-msg successmsg">
				<div class="text-msg">
					<p>{{ __('messages.Thanks for your visit.') }} <span>{{ __('messages.Hope to seen you soon again') }} !</span></p>
				</div>
				<img src="{{asset('images/ready-chef.png')}}">
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
		<div class="ui-block-d pull-right"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		@if(count($user->paidOrderList) == 0)
		<div class="ui-block-c pull-right"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
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