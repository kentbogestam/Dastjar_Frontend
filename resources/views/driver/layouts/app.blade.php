<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="_token" content="{{ csrf_token() }}">
	<title>Anar - Delivery App</title>

	<link href="{{ url('assets/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/fontawesome.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/brands.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/solid.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/custom.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script type="text/javascript">
		var BASE_URL = "{{ url('/') }}";
		var BASE_URL_DRIVER = "{{ url('driver') }}";
	</script>
</head>
<body>
	<nav class="navbar navbar-default navbar-header-custom">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="javascript:void(0)" title="">
					<img src="{{ asset('images/logo.png') }}" alt="" class="img-responsive">
				</a>
			</div>
			@if(Auth::guard('driver')->check())
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li>
							@if( strpos($_SERVER['REQUEST_URI'], 'pickup-direction') !== false || strpos($_SERVER['REQUEST_URI'], 'delivery-direction') !== false )
								<a href="{{ url('driver/pickup') }}" class="text-left">
									<img src="{{asset('images/icons/backarrow.png')}}" width="11px">
								</a>
							@else
								<div class="checkbox">
									<label><input type="checkbox" name="status" onchange="updateStatus(this)" {{ Auth::user()->status ? 'checked' : '' }}>Active</label>
								</div>
							@endif
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="{{ url('driver/logout') }}">{{ __('messages.Logout') }}</a>
						</li>
					</ul>
				</div>
			@endif
		</div>
	</nav>

	@yield('content')

	<!-- Modal: order detail -->
	<div id="modal-order-detail" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Order Detail</h4>
				</div>
				<div class="modal-body">
					<table class="table list-table-modal">
						<thead>
							<tr>
								<th>Order ID</th>
								<th>Product</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<footer id="footer" class="footer-fixed-bottom">
		@if(Auth::guard('driver')->check())
			<nav class="navbar navbar-default navbar-footer-custom" style="margin: 0">
				<div class="container-fluid">
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-left">
							<li class="{{ request()->is('driver/delivery') ? 'active' : '' }}"><a href="{{ url('driver/delivery') }}"">Orders</a></li>
							<li class="{{ request()->is('driver/pickup') ? 'active' : '' }}"><a href="{{ url('driver/pickup') }}">Pickups</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="{{ url('driver/setting') }}"><img src="{{ url('kitchenImages/icon-6.png') }}" width="20" alt="Setting"></a></li>
						</ul>
					</div>
				</div>
			</nav>
		@endif
	</footer>

	<!-- Scripts -->
	<script src="{{ url('assets/js/jquery.min.js') }}"></script>
	<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ url('assets/js/init.js') }}"></script>

	<script type="text/javascript">
		$(function() {
			
		});
	</script>

	@yield('scripts')
</body>
</html>
