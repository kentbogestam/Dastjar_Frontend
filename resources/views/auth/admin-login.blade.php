@extends('layouts.blank')

@section('content')
        <div data-role="header" data-theme="c">
             <div class="logo_header">
                <img src="{{asset('kitchenImages/logo-img.png')}}">
            </div>
        </div><!-- /header -->
        <form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ route('admin-login') }}">
        {{ csrf_field() }}
            <div role="main" class="ui-content">
                <h3>Kitchen Login</h3>
                @if ($message = Session::get('error'))
                    <div class="notifications">
                        <div class="alert alert-danger alert-block">
                            <h4>Error</h4>
                            @if(is_array($message))
                                @foreach ($message as $m)
                                    {{ $languageStrings[$m] or $m }}
                                @endforeach
                            @else
                                {{ $languageStrings[$message] or $message }}
                            @endif
                        </div>
                    </div>
                @endif
                <label for="txt-email">Email Address</label>
                <input type="text" name="email" id="email" value="">
                <label for="txt-password">Password</label>
                <input type="password" name="password" id="password" value="">
               <!--  <fieldset data-role="controlgroup">
                    <input type="checkbox" name="chck-rememberme" id="chck-rememberme" checked="">
                    <label for="chck-rememberme">Remember me</label>
                </fieldset> -->
                <a href="" class="ui-btn ui-btn-b ui-corner-all mc-top-margin-1-5"><input type="button" value="Done" id="dataSave"/></a>
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
@endsection

@section('footer-script')
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
                alert("Please fill some value");    
                e.preventDefault();
            }
        })

        function makeRedirection(link){
            window.location.href = link;
        }
    </script>
@endsection