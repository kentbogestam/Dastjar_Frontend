@extends('layouts.app')

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
@endsection
