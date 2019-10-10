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
				<span style="font-size: 14px;">o o o</span>
			</a>
		</li>
	</ul>
</div>