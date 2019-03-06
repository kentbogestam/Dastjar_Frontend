@extends('layouts.app')
@section('content')
<div class="login-page" data-role="page" data-theme="c">
    <div role="main" data-role="main-content" class="content">
         @if ($message = Session::get('success'))
            <div class="table-content sucess_msg">
              <img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
               @if(is_array($message))
                      @foreach ($message as $m)
                          {{ $languageStrings[$m] ?? $m }}
                      @endforeach
                  @else
                      {{ $languageStrings[$message] ?? $message }}
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

                    <div>
                      OTP sent on <strong>{{Session::get('userPhoneNumber')}}</strong>                
                      <small><a href="#" onclick="goToLogin()">Wrong mobile number?</a>
                      </small>
                      <br/><br/>
                    </div>

                    <div style="text-align: center;">
                      <span id="timer" style="color: #375722">60 secs</span>
                    </div><br/>

                    <div class="social-sec">
                        <div class="ui-grid-solo">
                         <input type="hidden" name="userPhoneNumber" value="{{Session::get('userPhoneNumber')}}">
                         <input id="otp" type="tel" class="form-control" name="otp" value="{{ old('otp') }}" required autofocus placeholder="Enter the code you received on SMS*" autocomplete="off">
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
<script type="text/javascript">

var count=60;
var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

function timer()
{
  count=count-1;

  if (count <= 0)
  {
     clearInterval(counter);
     //counter ended, do something here
     window.location.href = "{{url('mobileLogin')}}@if(Session::get('userPhoneNumber')!=null)?m={{Session::get('userPhoneNumber')}}@endif";
     return;
  }

  //Do code for showing the number of seconds here
  // alert(count);
  $("#timer").html(count + " secs"); // watch for spelling  
}

function goToLogin(){
  
       window.location.href = "{{url('mobileLogin')}}@if(Session::get('userPhoneNumber')!=null)?m={{Session::get('userPhoneNumber')}}@endif";
}

</script>

@endsection
