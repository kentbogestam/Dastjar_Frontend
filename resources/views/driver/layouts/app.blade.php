<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body>
	<nav class="navbar navbar-default navbar-header-custom">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="javascript:void(0)">
					<img src="{{ asset('images/logo.png') }}" alt="">
				</a>
			</div>
			@if(Auth::guard('driver')->check())
				<div class="collapse navbar-collapse">
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

	<footer id="footer" class="footer-fixed-bottom">
		@if(Auth::guard('driver')->check())
			<nav class="navbar navbar-default navbar-footer-custom" style="margin: 0">
				<div class="container-fluid">
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-left">
							<li class="{{ request()->is('driver/orders') ? 'active' : '' }}"><a href="{{ url('driver/deliver') }}"">Orders</a></li>
							<li class="{{ request()->is('driver/pickup') ? 'active' : '' }}"><a href="{{ url('driver/pickup') }}">Pickups</a></li>
						</ul>
					</div>
				</div>
			</nav>
		@endif
	</footer>

	<!-- Scripts -->
	<script src="{{ url('assets/js/jquery.min.js') }}"></script>
	<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>
	<!-- <script src="{{ url('assets/js/init.js') }}"></script> -->

	@yield('scripts')
</body>
</html>
