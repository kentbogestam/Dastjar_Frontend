@extends('layouts.app')
@section('content')
<div class="login-page" data-role="page" data-theme="c">
    <div role="main" data-role="main-content" class="content">
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
        <form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('userLogin') }}">
        {{ csrf_field() }}
            <div class="inner-page-container">
                <div class="login-inner-section">
                    <div class="social-sec_login">
                    <div class="logo-img-sec">
                        <img src="{{asset('images/l-logo.png')}}">
                    </div>
                    <div class="social-sec">
                        <div class="ui-grid-solo">
                         <input id="otp" type="tel" class="form-control" name="otp" value="{{ old('otp') }}" required autofocus placeholder="Enter the code you received on SMS*">
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
