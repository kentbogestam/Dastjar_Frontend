
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
					if('href' in curnode && ( curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host) ) ) {
						e.preventDefault();
						location.href = curnode.href;
					}
				},false);
			}
		})(document,window.navigator,'standalone');
	</script>

	<title>Anar</title>
	<link rel="icon" href="{{asset('images/l-logo.png')}}">
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-capable" content="yes" /> 

	<link rel="manifest" href="{{asset('kitchen/manifest.json')}}">

	<link rel="stylesheet" href="{{asset('kitchenCss/jquery.mobile-1.4.5.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('kitchenCss/style.css')}}">
	<script src="{{asset('kitchenJs/jquery-1.11.1.min.js')}}"></script>
	<script src="{{asset('kitchenJs/jquery.mobile-1.4.5.min.js')}}"></script>
	<link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.min.css')}}" >
	<link rel="stylesheet" href="{{asset('kitchenCss/customstyle.css')}}" >

	@yield('head-scripts') 
