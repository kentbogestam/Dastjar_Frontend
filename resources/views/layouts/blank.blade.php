<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.kitchenHead')
    @yield('style')
</head>
<body>
	<div data-role="page">
    	@yield('content')
    </div>
    @yield('footer-script')
</body>
</html>