@extends('layouts.master')
@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed"><!--  -->
		<div class="nav_fixed">
			<div class="logo">
				<div class="inner-logo">
					<img src="{{asset('images/logo.png')}}">
					<span>{{ Auth::user()->name}}</span>
				</div>
			</div>
			<a class="ui-btn-right map-btn user-link" onClick="makeRedirection('{{url('search-map-eatlater')}}')"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
		</div>
		<div class="cat-btn">
			<div class="ui-grid-a top-btn">
				<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive" class="active"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">Eat Now</a></div>
				<div class="ui-block-b"><a href="#" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">Eat Later</a></div>
			</div>
		</div>
	</div>
	<div role="main" data-role="main-content" id="content">
		<div class="cat-list-sec">
			<ul data-role="listview" data-inset="true">

				@foreach($companydetails as $companydetail)
					<li>
						<a href="{{ url('restro-menu-list/'.$companydetail->company_id) }}">
							<img src="images/img-store-3.png">
							<h2>{{$companydetail->company_name}}</h2>
							<p>@foreach($companydetail->products as  $key => $product)
									@if(++$key <= 2)
										{{$product->product_name}}
									@endif
									@if(count($companydetail->products) >1 && ++$key <= 2)
									,
									@endif 
								@endforeach 
							@if(count($companydetail->products) >1)
							& more
							@endif</p>
						</a>
						<div class="ui-li-count">
							<span>{{ round($companydetail->distance, 2) }} km</span>
						</div>
					</li>
				@endforeach
			</ul>
		</div>


	</div>	
	<div data-role="footer" id="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>Restaurant</span>
		</a></div>
		<div class="ui-block-b"><a href = "#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>send</span>
		</a></div>
		@if(count(Auth::user()->paidOrderList) == 0)
			<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_05.png')}}">
				</div>
				<span>Order</span>
			</a></div>
		@else
			<div class="ui-block-c order-active">
				<a href="#order-popup" data-transition="slideup" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline"  data-rel="popup">
					<div class="img-container">
						<!-- <img src="images/icons/select-store_05.png"> -->
						<img src="{{asset('images/icons/select-store_05-active.png')}}">
					</div>
					<span >Order<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
				</a>
			</div>
		@endif
		<div class="ui-block-d"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>
	<!-- pop-up -->
	<div data-role="popup" id="order-popup" class="ui-content" data-theme="a">
		<ul data-role="listview">
			@foreach(Auth::user()->paidOrderList as $order)
				<li>
					<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">Order id - {{$order->order_id}}</a>
				</li>
			@endforeach
		</ul>
	</div>

@endsection

@section('footer-script')

<script type="text/javascript">
	
	function makeRedirection(link){
		window.location.href = link;
	}

</script>

@endsection