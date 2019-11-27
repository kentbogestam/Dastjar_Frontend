<!DOCTYPE html>
<html lang="en">
<head>
    <title>Anar Scripts</title>
	<meta charset="UTF-8">
	<link href="//fonts.googleapis.com/css?family=Aclonica" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('v1/css/bootstrap.css') }}">
	@yield('styles')

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('v1/js/bootstrap.min.js') }}"></script>
	@yield('head-scripts')
</head>
<body>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Dastjar RS</a>
			</div>
		</div>
	</nav>
	<div class="container">
		@yield('content')
	</div>

	@yield('footer-script')
</body>
</html>