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
    	@if(Session::has('storeId'))
    		// Server-Sent Events allow a web page to get updates from a server.
			if(typeof(EventSource) !== "undefined") {
				console.log('SSE supported!');
				var es = new EventSource("{{ url('kitchen/check-store-subscription-plan') }}");

				function closeConnection()
				{
					es.close();
					console.log('close');
				}

				es.addEventListener('message', function(e) {
					var data = JSON.parse(e.data);
					console.log('message');
					if(data.length)
					{
						for(var i = 0; i < data.length; i++)
						{
							if( $('#menu-'+data[i]).hasClass('ui-state-disabled') )
							{
								$('#menu-'+data[i]).removeClass('ui-state-disabled');
							}
						}
					}
				}, false);

				es.addEventListener('error', function(e) {
					console.log('error');
				}, false);
			}
			else
			{
				console.log('SSE not supported by browser!');
			}
    	@endif
	</script>
</body>
</html>