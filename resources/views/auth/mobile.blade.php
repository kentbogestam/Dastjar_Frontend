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
                         <input id="mobileNo" type="text" class="form-control" name="mobileNo" min="0" max="100" value="" required autofocus placeholder="Enter Mobile No.*">
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
    <script type="text/javascript">
        $(document).ready(function(){
          
          $("#mobileNo").on("blur", function(){
                var mobNum = $(this).val();
                var filter = /^\d*(?:\.\d{1,2})?$/;

                  if (filter.test(mobNum)) {
                    if(mobNum.length==10){
                          return true;
                     } else {
                        alert('Please put 10  digit mobile number');
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
