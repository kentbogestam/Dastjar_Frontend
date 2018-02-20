@extends('layouts.app')
@section('content')
<div class="login-page" data-role="page" data-theme="c">
    <div role="main" data-role="main-content" class="content">
        <form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('userLogin') }}">
        {{ csrf_field() }}
            <div class="inner-page-container">
                <div class="login-inner-section">
                    <div class="social-sec_login">
                    <div class="logo-img-sec">
                        <img src="images/l-logo.png">
                    </div>
                    <div class="social-sec">
                        <div class="ui-grid-solo">
                         <input id="otp" type="text" class="form-control" name="otp" value="{{ old('otp') }}" required autofocus placeholder="Enter Otp*">
                        </div>
                    </div>
                    <div class="ui-grid-solo">
                        <button type="submit" class="mobile_otp_btn" placeholder="Submit">
                            Submit
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
