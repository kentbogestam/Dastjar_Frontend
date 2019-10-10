@extends('v1.user.layouts.master')

@section('head-scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>
    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
	<script>
	  $(document).ready(function () {
	      App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
	      App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

	      App42.initialize("{{env('APP42_API_KEY')}}","{{env('APP42_API_SECRET')}}");
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
	@if(isset($message))
		<p style="text-align: center">{{ $message }}</p>
	@endif
@endsection