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
        <form id="form11" name="form11" class="form-horizontal" data-ajax="false" method="post" action="{{ url('sentOtp') }}">
        {{ csrf_field() }}
            <div class="inner-page-container">
                <div class="login-inner-section">
                    <div class="social-sec_login">
                    <div class="logo-img-sec">
                        <img src="{{asset('images/l-logo.png')}}">
                    </div>
                    <div class="social-sec">
                        <div class="ui-grid-solo">                          
                         <input id="mobileNo" type="text" class="form-control" name="mobileNo" minlength="6" maxlength="15" value="<?php
                          if(isset($_GET['m'])){
                            echo $_GET['m'];
                          }
                          ?>" required autofocus placeholder="Enter Mobile No.*" onkeypress="return isNumberKey(event)">
                        </div>
                    </div>
                    <div class="ui-grid-solo">
                        <button type="button" class="mobile_otp_btn" placeholder="Login">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
       
          function isNumberKey(evt)
          {
              var charCode = (evt.which) ? evt.which : evt.keyCode;
              if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
                if(charCode == 32 || charCode == 43 || charCode ==45){
                  return true;
                }else{
                  return false;
                }
              }
              return true;
          }

          $(".mobile_otp_btn").on("click", function(){
            var mobNum = $('#mobileNo').val();
            document.getElementById('form11').submit();            
          });
    </script>
@endsection
