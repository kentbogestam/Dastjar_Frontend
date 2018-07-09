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
                         <input id="mobileNo" type="number" class="form-control" name="mobileNo" min="0" max="100" value="" required autofocus placeholder="Enter Mobile No.*">
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
    <script type="text/javascript">
        $(document).ready(function(){
          
          $(".mobile_otp_btn").on("click", function(){
                var mobNum = $('#mobileNo').val();
                var filter = /^\d*(?:\.\d{1,2})?$/;

                  if (filter.test(mobNum)) {
                    if(mobNum.length==9 || mobNum.length==10){
                          document.getElementById('form11').submit();
                          return true;
                     } else {
                        alert('Please put 9 or 10 digit mobile number');
                       $("#folio-invalid").removeClass("hidden");
                       $("#mobile-valid").addClass("hidden");
                        return false;
                      }
                    }
                    else {
                      alert('Not a valid number');
                      $("#folio-invalid").removeClass("hidden");
                      $("#mobile-valid").addClass("hidden");
                      return false;
                   }            
          });
          
        });
    </script>
@endsection
