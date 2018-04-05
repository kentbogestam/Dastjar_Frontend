@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="login-page" data-role="page" data-theme="c">
    <div role="main" data-role="main-content" class="content">
      <div class="ready_notification">
        @if ($message = Session::get('success'))
        <div class="table-content sucess_msg">
          <img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
           @if(is_array($message))
                  @foreach ($message as $m)
                      {{ $languageStrings[$m] or $m }}
                  @endforeach
              @else
                  {{ $languageStrings[$message] or $message }}
              @endif
          </div>
      @endif
      </div>
        <div class="inner-page-container">
            <div class="login-inner-section">
                <div class="logo-img-sec">
                    <img src="{{asset('images/l-logo.png')}}">
                </div>
                <div class="social-sec">
                    <div id="facebook-hide" class="ui-grid-solo">
                        <div class="ui-block-a"><a href="{{ url('login/facebook')}}" class="ui-btn ui-shadow ui-corner-all" data-ajax="false"><img src="{{asset('images/fb-icon.png')}}"></a></div>
                    </div>
                    <div id="google-hide" class="ui-grid-solo">
                        <div class="ui-block-a"><a href="{{ url('login/google')}}" class="ui-btn ui-shadow ui-corner-all" data-ajax="false"><img src="{{asset('images/gplus.png')}}"></a></div>
                    </div>
                     <div class="ui-grid-solo login_mobile">
                        <div class="ui-block-a"><a href="{{ url('/mobileLogin') }}" class="ui-btn ui-shadow ui-corner-all orange_box" data-ajax="false"><div class="text_box_login"><img src="{{asset('images/phone.png')}}"><div class="wrap_text_signin"><p>Sign in with Mobile Number</p></div></div></a></div></div>
                    </div>
                </div>
            </div>  
        </div>
       <!--   <div class="ui-grid-solo bottom_login_text">
                    <div class="ui-block-a"><a href="{{ url('/userRegister') }}" class="ui-btn ui-shadow ui-corner-all" data-ajax="false"><span>Not a member ? sign up now</span></a></div>
                </div> -->
    </div>
</div>
<div id="login-popup" style="display: none;" class="login-popup" data-theme="a">
  <div class="inner-popup">
        <div id = "cancel-popup" class="cross"><img src="{{asset('images/icons/cross.png')}}"></div>
        <div class="pop-body">
           <p>Please allow browser location</p>
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
    console.log("latitude=" + position.coords.latitude);
    console.log("longitude=" + position.coords.longitude);
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
<script type="text/javascript">
  navigator.sayswho= (function(){
    var ua= navigator.userAgent, tem, 
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);

    console.log("browserVersion=" + M.join(' '));
    document.cookie="browserVersion=" + M.join(' ');
    var string = M.join(' ');
    string = string.split(" ");
    if(string[0] == 'Safari'){
     $('#facebook-hide').hide();
     $('#google-hide').hide();
    }
})();
</script>
@endsection
