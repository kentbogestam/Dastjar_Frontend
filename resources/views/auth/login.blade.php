@extends('layouts.app')
@section('head-scripts')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>


    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
     <script>
          $(document).ready(function () {
              App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
              App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

              App42.initialize("cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2","297b31b7c66e206b39598260e6bab88e701ed4fa891f8995be87f786053e9946");
              App42.enableEventService(true);
              var userName;
              new Fingerprint2().get(function(result, components){
                  userName = result;
                  console.log("Username : " + userName); //a hash, representing your device fingerprint
                  App42.setLoggedInUser(userName);
                  getDeviceToken();
              });
          });
      </script>
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="login-page" data-role="page" data-theme="c">
    <div role="main" data-role="main-content" class="content">
        <div class="inner-page-container">
            <div class="login-inner-section">
                <div class="logo-img-sec">
                    <img src="images/l-logo.png">
                </div>
                <div class="social-sec">
                    <div class="ui-grid-solo">
                        <div class="ui-block-a"><a href="{{ url('login/facebook')}}" class="ui-btn ui-shadow ui-corner-all" data-ajax="false"><img src="images/fb-icon.png"></a></div>
                    </div>
                    <div class="ui-grid-solo">
                        <div class="ui-block-a"><a href="{{ url('login/google')}}" class="ui-btn ui-shadow ui-corner-all" data-ajax="false"><img src="images/gplus.png"></a></div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
<div id="login-popup" style="display: none;" class="login-popup" data-theme="a">
  <div class="inner-popup">
        <div id = "cancel-popup" class="cross"><img src="images/icons/cross.png"></div>
        <div class="pop-body">
           <p>please allow browser location</p>
        </div>
  </div>
</div>
@endsection
@section('footer-script')


    <script type="text/javascript">

     $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
         });

        $(function(){     

// Check for Geolocation API permissions  
navigator.geolocation.getCurrentPosition(function(position) { 

    document.cookie="latitude=" + position.coords.latitude;
    document.cookie="longitude=" + position.coords.longitude;
    
},function(error){
   $('.login-inner-section a').attr('href','javascript:void(0)');
   $('#login-popup').show();
    
});

        });

        $("#cancel-popup").click(function () {
          $('#login-popup').hide();
        }); 
    
</script>
@endsection
