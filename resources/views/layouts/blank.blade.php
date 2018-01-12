<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.kitchenHead')
</head>
<body>
	<div data-role="page">
    	@yield('content')
    </div>
    @yield('footer-script')
</body>
</html>