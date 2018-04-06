@extends('layouts.master')
@section('head-scripts')
<script src="{{asset('locationJs/currentLocation.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>
<script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
<script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
<script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
 <script>

	  $(document).ready(function () {
	      App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
	      App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

	      App42.initialize("cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2","297b31b7c66e206b39598260e6bab88e701ed4fa891f8995be87f786053e9946");
	      App42.enableEventService(true);
	      var userName;
	      new Fingerprint2().get(function(result, components){
	          userName = "{{ Auth::user()->email}}";
	          console.log("Username : " + userName); //a hash, representing your device fingerprint
	          App42.setLoggedInUser(userName);
	          getDeviceToken();
	      });
	  });
</script>
@endsection      
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
			@if ($message = Session::get('success'))
				<div class="table-content sucess_msg">
					<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
					 @if(is_array($message))
			            @foreach ($message as $m)
			                {{ $languageStrings[$m] or $m }}
			            @endforeach
			        @else
			            {{  __("messages.$message") }}
			        @endif
			    </div>
			@endif
			<div class="wait-bg-img">
				<div class="text-content">
					<p>{{ __('messages.Thanks for your order') }} </p>
					<p>{{ __('messages.Order Number') }} </p>
					<p class="large-text">{{$order->customer_order_id}}</p>
					<p>({{$order->store_name}})</p>
					<p>{{ __('messages.Your order will be ready on') }}
						@if($order->order_type == 'eat_later')
						{{$order->deliver_date}}
						{{date_format(date_create($order->deliver_time), 'G:i')}} 
						@else
						{{date_format(date_create($order->order_delivery_time), 'i')}} mins
						@endif
					</p>
				</div>
			</div>
			<div class="table-content">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
					@foreach($orderDetails as $orderDetail)
						<tr>
							<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
						</tr>	
					@endforeach
				<tr class="last-row">	<td> </td><td>         </td><td>  TOTAL:-    {{$order->currencies}} {{$order->order_total}}</td></tr>
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
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@include('orderQuantity')
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
