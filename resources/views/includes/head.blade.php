<head>
    <title>Dastjar</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="{{asset('manifest.json')}}">
 	<link rel = "stylesheet" href ="{{asset('css/jquery.mobile.min.css')}}">
 	<link rel="stylesheet" href="{{asset('css/jquery.mobile.icons.min.css')}}">
 	<link rel="stylesheet" href="{{asset('css/main-style.css')}}" >
 	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Aclonica" rel="stylesheet">
	@yield('styles')
 	<script src = "{{asset('js/device.detect.js')}}"></script>
 	<script src = "{{asset('js/jquery.min.js')}}"></script>
	<script src = "{{asset('js/jquery.mobile.min.js')}}"></script>
	<script src = "{{asset('js/main.js')}}"></script>
	<link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.min.css')}}" >

	@yield('head-scripts')
</head> 