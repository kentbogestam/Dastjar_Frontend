<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.kitchenHead')

    <script type="text/javascript">
        var RESTAURANT_BASE_URL = "{{ url('kitchen') }}";
        var BASE_URL_API = '{{url('api')}}';
        var CURRENT_PATH = '{{ Request::path() }}';
    </script>
</head>
<body>
	<div class="main-content">
            @yield('content')
        </div>
    @yield('footer-script')
    <script src="{{asset('kitchenJs/speekJs.js')}}"></script>
    <script src="{{asset('kitchenJs/init.js')}}"></script>
</body>
</html>