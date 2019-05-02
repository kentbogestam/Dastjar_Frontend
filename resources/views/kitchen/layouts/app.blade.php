<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.kitchenHead')
    <link href="{{asset('css/kitchen/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    @yield('style')

    <script type="text/javascript">
        var RESTAURANT_BASE_URL = "{{ url('kitchen') }}";
        var BASE_URL_API = '{{url('api')}}';
        var CURRENT_PATH = '{{ Request::path() }}';
    </script>
</head>
<body>
    <div data-role="page">
        <div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
            @include('includes.kitchen-header-sticky-bar')
        </div>
        @yield('content-jMobile')
    </div>
    @yield('content-bootstrap')
    @yield('footer-script')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="{{asset('kitchenJs/speekJs.js')}}"></script>
    <script src="{{asset('kitchenJs/init.js')}}"></script>
</body>
</html>