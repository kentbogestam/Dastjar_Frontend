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
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" /> 
    <script type="text/javascript">
        var BASE_URL = '{{url('/')}}';
    </script>
    <link rel="icon" href="{{asset('images/l-logo.png')}}">
	<link rel="manifest" href="{{asset('manifest.json')}}">	
 	<link rel = "stylesheet" href ="{{asset('css/jquery.mobile.min.css')}}">
 	<link rel="stylesheet" href="{{asset('css/jquery.mobile.icons.min.css')}}">
 	<link rel="stylesheet" href="{{ asset('css/main-style.css').'?v='.$RAND_APP_VERSION }}">
 	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link href="//fonts.googleapis.com/css?family=Aclonica" rel="stylesheet">
	<link rel="apple-touch-icon-precomposed" href="{{asset('addToHomeIphoneImage/icon-152x152.png')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('addToHomeIphoneCss/addtohomescreen.css')}}">
	@yield('styles')
 	<script src = "{{asset('js/device.detect.js')}}"></script>
 	<script src = "{{asset('js/jquery.min.js')}}"></script> 
	<script src = "{{asset('js/jquery.mobile.min.js')}}"></script>
	<script src = "{{ asset('js/main.js').'?v='.$RAND_APP_VERSION }}"></script>
    <script src = "{{ asset('js/common.js').'?v='.$RAND_APP_VERSION }}"></script>
	<link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.min.css')}}">
	@yield('head-scripts')
</head>