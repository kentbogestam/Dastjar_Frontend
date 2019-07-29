<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<script>
		(function(document,navigator,standalone) {
			// prevents links from apps from oppening in mobile safari
			// this javascript must be the first script in your <head>
			if ((standalone in navigator) && navigator[standalone]) {
				var curnode, location=document.location, stop=/^(a|html)$/i;
				document.addEventListener('click', function(e) {
					curnode=e.target;
					while (!(stop).test(curnode.nodeName)) {
						curnode=curnode.parentNode;
					}
					// Condidions to do this only on links to your own app
					// if you want all links, use if('href' in curnode) instead.

					if('href' in curnode && (curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host)) && (curnode.href.indexOf('#')==-1)) {
						e.preventDefault();
						location.href = curnode.href;
					}
				},false);
			}
		})(document,window.navigator,'standalone');
	</script>
	<title>Anar - Delivery App</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="manifest" href="{{asset('assets/driver/manifest.json')}}">

	<link href="{{ url('assets/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/fontawesome.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/brands.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/solid.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/custom.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<link rel="apple-touch-icon-precomposed" href="{{asset('addToHomeIphoneImage/icon-152x152.png')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('addToHomeIphoneCss/addtohomescreen.css')}}">

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

	<script src="{{ url('notifactionJs/serviceWorker.js') }}"></script>
	<script src="{{asset('addToHomeIphoneJs/addtohomescreen.js')}}"></script>
	<script>
		registerSwjs();
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
</body>
</html>
