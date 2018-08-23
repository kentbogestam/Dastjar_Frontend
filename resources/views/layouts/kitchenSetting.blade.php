<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.kitchenHead')
</head>
<body>
	<div class="main-content">
            @yield('content')
        </div>
    @yield('footer-script')
</body>
</html>