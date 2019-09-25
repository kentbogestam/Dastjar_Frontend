@php
$requestPath = Request::path();
@endphp

<div class="footer-section">
	<ul>
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
				<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
			</a>
		</li>
	</ul>
</div>