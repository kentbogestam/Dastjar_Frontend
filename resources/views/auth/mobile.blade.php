@extends('layouts.app')
@section('content')
<div class="login-page" data-role="page" data-theme="c">
    <div role="main" data-role="main-content" class="content">
        <form id="form11" name="form11" class="form-horizontal" data-ajax="false" method="post" action="{{ url('sentOtp') }}">
        {{ csrf_field() }}
            <div class="inner-page-container">
                <div class="login-inner-section">
                    <div class="social-sec_login">
                    <div class="logo-img-sec">
                        <img src="images/l-logo.png">
                    </div>
                    <div class="social-sec">
                        <div class="ui-grid-solo">
                         <input id="mobileNo" type="text" class="form-control" name="mobileNo" value="" required autofocus placeholder="Enter Mobile No.*">
                        </div>
                    </div>
                    <div class="ui-grid-solo">
                        <button type="submit" class="mobile_otp_btn" placeholder="Login">
                            Login
                        </button>
                    </div>
                </div> 
                </div> 
            </div>
        </form>
    </div>
</div>
@endsection
@section('footer-script')

@endsection
