<!DOCTYPE html>
<html lang="en">
    @include('includes.head')
<body>
    <div class="top-container">
        <div class="main-content">
            @yield('content')
        </div>
        @yield('footer-script')
    </div>
</body>
</html>