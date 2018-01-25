@extends('layouts.master')

@section('head-scripts')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>


    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
	<script>
	  $(document).ready(function () {
	      App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
	      App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

	      App42.initialize("cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2","297b31b7c66e206b39598260e6bab88e701ed4fa891f8995be87f786053e9946");
	      App42.enableEventService(true);
	      var userName;
	      new Fingerprint2().get(function(result, components){
	          userName = "{{ Auth::user()->email}}";
	          console.log("Username : " + userName); //a hash, representing your device fingerprint
	          App42.setLoggedInUser(userName);
	          getDeviceToken();
	      });
	  });
	</script>
@endsection

@section('content')

@endsection


@section('footer-script')

<script>
	$(document).ready(function () {
	  //  window.location.href = "{{url('/')}}";
	});
</script>

@endsection
