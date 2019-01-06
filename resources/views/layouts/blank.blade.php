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

    <script type="text/javascript">
		if( document.getElementsByClassName('link-logout').length && typeof(EventSource) !== "undefined" )
		{
			function closeConnection()
			{
				es.close();
				console.log('close');
			}
		}
	</script>
</body>
</html>