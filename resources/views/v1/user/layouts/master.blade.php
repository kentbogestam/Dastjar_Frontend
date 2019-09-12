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
    <title>Anar</title>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" /> 
	
	<!-- <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900,900i&display=swap" rel="stylesheet"> -->
	<link href="//fonts.googleapis.com/css?family=Aclonica" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('v1/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('v1/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('v1/css/responsive.css') }}">
	<link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.min.css')}}">
	<link rel="icon" href="{{asset('images/l-logo.png')}}">
	<link rel="manifest" href="{{asset('manifest.json')}}">
	<link rel="apple-touch-icon-precomposed" href="{{asset('addToHomeIphoneImage/icon-152x152.png')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('addToHomeIphoneCss/addtohomescreen.css')}}">
	@yield('styles')

	<!-- Scripts -->
	<script type="text/javascript">
        var BASE_URL = '{{url('/')}}';
    </script>
    <script src = "{{asset('js/device.detect.js')}}"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('v1/js/bootstrap.min.js') }}"></script>
    <script src = "{{ asset('js/main.js') }}"></script>
    <script src = "{{ asset('js/common.js') }}"></script>
    <script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
	@yield('head-scripts')
</head>
<body>
	<div id="main-wrapper">
		@section('header')
            @includeIf('v1.user.elements.header')
        @show

		<div class="mid-section">
			@yield('content')
		</div>
		
		@section('footer')
			@includeIf('v1.user.elements.footer')
		@show
	</div>

	@yield('footer-script')

	<script type="text/javascript">
    	function getCookie(cname) {
		    var name = cname + "=";
		    var decodedCookie = decodeURIComponent(document.cookie);
		    var ca = decodedCookie.split(';');
		    for(var i = 0; i <ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) == ' ') {
		            c = c.substring(1);
		        }
		        if (c.indexOf(name) == 0) {
		            return c.substring(name.length, c.length);
		        }
		    }
		    return "";
		}

    	$(document).ready(function(){
    		if(getCookie("browser") == 'Safari' && getCookie("osVersion") >= 10){
	    		$('div[data-role="footer"]').css({'padding-top':'10px', 'padding-bottom':'10px'});
			}
    	});

    	// Check if session 'recentOrderList' exist
    	@if( Session::has('recentOrderList') && !empty(Session::get('recentOrderList')) )
    		var intervalCheckIfOrderReady = null;

    		// Check in each x second and if order is ready, redirect on 'order ready' page automatically
			var checkIfOrderReady = function() {
				$.get('{{ url('check-if-order-ready') }}', function(result) {
					if(result.status)
					{
						console.log(result.order['order_id']);
						window.location = "{{ url('ready-notification') }}/"+result.order['customer_order_id'];
						// clearInterval(intervalCheckIfOrderReady);
					}
				});
			}

			intervalCheckIfOrderReady = setInterval(checkIfOrderReady, 10000);
    	@endif
    </script>
</body>
</html>