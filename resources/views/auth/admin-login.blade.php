@extends('layouts.blank')

@section('head-scripts')
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" />
@stop

@section('content')
        <div data-role="header" data-theme="c">
             <div class="logo_header icon_logo">
                <img src="{{asset('kitchenImages/logo.png')}}">
            </div>
        </div><!-- /header -->
    <div class="container">
        <form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ route('admin-login') }}">
        {{ csrf_field() }}
            <div role="main" class="ui-content">
                <h3>Kitchen Login</h3>
                @if ($message = Session::get('error'))
                    <div class="notifications">
                        <div class="alert alert-danger alert-block error_msg_login">
                            <h4>Error:</h4>
                            @if(is_array($message))
                                @foreach ($message as $m)
                                    <p>{{ $languageStrings[$m] or $m }}</p>
                                @endforeach
                            @else
                                <p>{{ $languageStrings[$message] or $message }}</p>
                            @endif
                        </div>
                    </div>
                @endif
                <input type="hidden" name="browser" id="browser" value="">
                <label for="txt-email">Email Address</label>
                <input type="text" name="email" id="email" value="">
                <label for="txt-password">Password</label>
                <input type="password" name="password" id="password" value="">
               <!--  <fieldset data-role="controlgroup">
                    <input type="checkbox" name="chck-rememberme" id="chck-rememberme" checked="">
                    <label for="chck-rememberme">Remember me</label>
                </fieldset> -->
                <a href="" class="ui-btn ui-btn-b ui-corner-all mc-top-margin-1-5" id="dataSave">Done</a>
               <!--  <a href="#dlg-invalid-credentials" data-rel="popup" data-transition="pop" data-position-to="window" id="btn-submit" class="ui-btn ui-btn-b ui-corner-all mc-top-margin-1-5"><input type="button" value="Done" id="dataSave"/>Submit</a> -->
                <!-- <p class="mc-top-margin-1-5"><a href="begin-password-reset.html">Can't access your account?</a></p> -->
                <div data-role="popup" id="dlg-invalid-credentials" data-dismissible="false" style="max-width:400px;">
                    <div role="main" class="ui-content">
                        <h3 class="mc-text-danger">Login Failed</h3>
                        <p>Did you enter the right credentials?</p>
                        <div class="mc-text-center"><a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-b mc-top-margin-1-5">OK</a></div>
                    </div>
                </div>
            </div><!-- /content -->
        </form>    
    </div>    
@endsection

@section('footer-script')
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $("#dataSave").click(function(e){
            var flag = false;
            if($("#email").val() != "" && $("#password").val() != ""){
                flag = true;
                console.log(flag);
            }
            if(flag){
                $("#form").submit();
            } else{
                alert("Please Enter vailed Email and Password.");    
                e.preventDefault();
            }
        });

        function makeRedirection(link){
            window.location.href = link;
        }
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

        document.getElementById('browser').value = M.join(' ');
    })();
    </script>
@endsection