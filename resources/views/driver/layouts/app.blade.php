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
	<!-- <link href="{{ url('assets/css/custom.css') }}" rel="stylesheet"> -->

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		.container-fluid.full {
			margin: 0 auto;
  			width: 100%;
		}
		.navbar-brand {
			transform: translateX(-50%);
			left: 50%;
			position: absolute;
		}
		.navbar-brand {
			padding: 0px;
		}
		.navbar-brand>img {
			height: 100%;
			width: auto;
			padding: 7px 14px;
		}
		footer {
			position: fixed;
			bottom: 0;
			width: 100%;
		}
		.navbar-collapse.collapse {
			display: block!important;
		}
		.navbar-nav>li, .navbar-nav {
			float: left !important;
		}
		.navbar-nav.navbar-right:last-child {
			margin-right: -15px !important;
		}
		.navbar-right {
			float: right!important;
		}

		.navbar-header-custom {
			margin-bottom: 0;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-default navbar-header-custom">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="javascript:void(0)">
					<img src="{{ asset('images/logo.png') }}" alt="">
				</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="javascript:void(0)">{{ __('messages.Logout') }}</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<footer id="footer">
		<!-- <nav class="navbar navbar-default navbar-footer-custom" style="margin: 0">
			<div class="container-fluid">
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-left">
						<li class="active"><a href="#">Orders</a></li>
						<li><a href="#">Pickups</a></li>
					</ul>
				</div>
			</div>
		</nav> -->
	</footer>

	<!-- Scripts -->
	<script src="{{ url('assets/js/jquery.min.js') }}"></script>
	<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>
	<!-- <script src="{{ url('assets/js/init.js') }}"></script> -->

	@yield('scripts')
</body>
</html>
