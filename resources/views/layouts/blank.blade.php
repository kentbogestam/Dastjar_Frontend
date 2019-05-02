<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.kitchenHead')
    @yield('style')

    <script type="text/javascript">
    	var RESTAURANT_BASE_URL = "{{ url('kitchen') }}";
    	var BASE_URL_API = '{{ url('api') }}';
    	var CURRENT_PATH = '{{ Request::path() }}';
    </script>
</head>
<body>
	<div data-role="page">
    	@yield('content')
    </div>
    @yield('footer-script')
    <script src="{{asset('kitchenJs/speekJs.js')}}"></script>
    <script src="{{asset('kitchenJs/init.js')}}"></script>
    <script type="text/javascript">
    	@if(Session::has('storeId'))
    		// Server-Sent Events allow a web page to get updates from a server in x second.
    		var serverSE = function() {
    			$.get("{{ url('kitchen/check-store-subscription-plan') }}", function(returnedData) {
    				// console.log('message');
    				var data = returnedData['data'];
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
    			});
    		}

    		setInterval(serverSE, 30000);

			/*if(typeof(EventSource) !== "undefined") {
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
			}*/
    	@endif
	</script>
</body>
</html>