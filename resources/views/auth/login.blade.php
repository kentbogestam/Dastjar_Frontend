@extends('layouts.app')
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
