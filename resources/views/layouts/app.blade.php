<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.head')
</head>
<body>
    <div class="top-container">
        <div class="main-content">
            @yield('content')
        </div>
        @yield('footer-script')
    </div>
</body>
</html>
