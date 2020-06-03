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
            let INTERVAL_CHECK_STORE_SUBS = '{{ env('INTERVAL_CHECK_STORE_SUBS') }}';

    		var serverSE = function() {
                $.ajax({
                    url: '{{ url('kitchen/check-store-subscription-plan') }}',
                    success: function(returnedData) {
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
        
                        // if catering order is new so show count.
                        if(returnedData['catering_order_count'] > 0){
                            $('i.catering-badge').html(returnedData['catering_order_count']);
                        }else{
                            $('i.catering-badge').html('');
                        }

                        setTimeout(serverSE, INTERVAL_CHECK_STORE_SUBS);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        setTimeout(serverSE, INTERVAL_CHECK_STORE_SUBS);
                    }
                });
    		}

            if(INTERVAL_CHECK_STORE_SUBS)
            {
                setTimeout(serverSE, INTERVAL_CHECK_STORE_SUBS);
            }
    	@endif
	</script>
</body>
</html>