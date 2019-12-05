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
	<meta name="_token" content="{{ csrf_token() }}">
	<link rel="manifest" href="{{asset('assets/driver/manifest.json')}}">

	<link href="{{ url('assets/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/fontawesome.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/brands.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/fontawesome/solid.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/custom.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<link rel="apple-touch-icon-precomposed" href="{{asset('assets/driver/touch/dastjar-ds-logo-152.png')}}">
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
	<nav class="navbar navbar-default navbar-header-custom">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="javascript:void(0)" title="">
					<img src="{{ asset('assets/driver/touch/dastjar-ds-logo-128.png') }}" alt="" class="img-responsive">
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
						<li>
							<button type="button" class="btn btn-link" onclick="resetPosition();" style="margin-top: 5px;">
								<i class="fas fa-location-arrow" data-toggle="tooltip" title="{{ __('messages.reset_position') }}" data-placement="bottom"></i>
							</button>
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
