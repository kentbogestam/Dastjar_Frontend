<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>Anar - Delivery App</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <meta name="mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-capable" content="yes" />  -->
	<link rel="manifest" href="{{asset('assets/driver/manifest.json')}}">

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
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="javascript:void(0)">
					<img src="{{ asset('kitchenImages/logo.png') }}" alt="">
				</a>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="{{ url('assets/js/jquery.min.js') }}"></script>
	<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>

	@yield('scripts')

	<script src="{{ url('notifactionJs/serviceWorker.js') }}"></script>
	<script>
		registerSwjs();
	</script>
</body>
</html>
