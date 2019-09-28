@php
$requestPath = Request::path();
@endphp

<div class="footer-section">
	<ul>
		@if(Request::is('/') || Request::is('home') || Request::is('eat-now') || Request::is('eat-later'))
			<li class="active"><a href="javascript:void(0)"><i class="fa fa-cutlery"></i></a></li>
		@else
			@if(Request::is('cart') || Request::is('save-order'))
				<li><a href="javascript:void(0)" id="leave-cart" data-content="{{ __("messages.Leave Cart Page") }}"><i class="fa fa-cutlery"></i></a></li>
			@else
				<li><a href="{{ Session::get('route_url') }}"><i class="fa fa-cutlery"></i></a></li>
			@endif
		@endif

		@if(Request::is('restro-menu-list/*'))
			<li class="active">
				<a href="javascript:void(0)" id="menudataSave">
					<i class="fa fa-shopping-cart"></i> <sup><span class="badge cart-badge">0</span></sup>
				</a>
			</li>
		@else
			<li {{ (Request::is('cart') || Request::is('save-order')) ? 'class=active' : '' }}>
				<a href="javascript:void(0)">
					<i class="fa fa-shopping-cart"></i> <sup><span class="badge cart-badge">0</span></sup>
				</a>
			</li>
		@endif

		@include('v1.user.elements.orderQuantity')

		<li>
			<a href="{{ url('user-setting') }}">
				<!-- <img src="{{asset('images/icons/select-store_07.png')}}" width="36"> -->
				<i class="fa fa-bars" aria-hidden="true"></i>
			</a>
		</li>
	</ul>
</div>

<!-- Popup 'orderQuantity' -->
@if(Auth::check())
	@if(count(Auth::user()->paidOrderList))
		<div id="order-popup" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
							@foreach(Auth::user()->paidOrderList as $order)
								<div class="col-md-12">
									<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">
										{{ __('messages.Order id') }} - {{$order->customer_order_id}}
									</a>
								</div>
							@endforeach
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	@endif
@endif