<!DOCTYPE html>
<html lang="en">
    @include('includes.head')
<body>
    <div class="top-container">
        <div class="main-content ">
            @yield('content')
        </div>
        @yield('footer-script')
    </div>

    <script type="text/javascript">
    	function getCookie(cname) {
		    var name = cname + "=";
		    var decodedCookie = decodeURIComponent(document.cookie);
		    var ca = decodedCookie.split(';');
		    for(var i = 0; i <ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) == ' ') {
		            c = c.substring(1);
		        }
		        if (c.indexOf(name) == 0) {
		            return c.substring(name.length, c.length);
		        }
		    }
		    return "";
		}

    	$(document).ready(function(){
    		if(getCookie("browser") == 'Safari' && getCookie("osVersion") >= 10){
	    		$('div[data-role="footer"]').css({'padding-top':'10px', 'padding-bottom':'10px'});
			}
    	});

    	// Check if session 'recentOrderList' exist
    	@if( Session::has('recentOrderList') && !empty(Session::get('recentOrderList')) )
    		var intervalCheckIfOrderReady = null;

    		// Check in each x second and if order is ready, redirect on 'order ready' page automatically
			var checkIfOrderReady = function() {
				$.get('{{ url('check-if-order-ready') }}', function(result) {
					if(result.status)
					{
						console.log(result.order['order_id']);
						window.location = "{{ url('ready-notification') }}/"+result.order['customer_order_id'];
						// clearInterval(intervalCheckIfOrderReady);
					}
				});
			}

			intervalCheckIfOrderReady = setInterval(checkIfOrderReady, 10000);
    	@endif
    </script>
</body>
</html>