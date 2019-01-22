@extends('layouts.master')
@section('content')

@include('includes.headertemplate')
	
	<div role="main" data-role="main-content" class="content">
		<div id="map" class="map_container"></div>
	</div>

{{-- @include('includes.fixedfooter') --}}

@endsection

@section('footer-script')
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script> -->
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630"></script> -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&callback=initMap">
    </script>

	<script type="text/javascript">
		var headerHeight = $( '.header' ).height();
		var footerHeight = $( '.footer' ).height();
		var maincontent =$(window).height();/*
		var footerTop = $( '#footer' ).offset().top;*/
		var height = maincontent - (headerHeight + footerHeight);
		/*alert( height);*/
		$( '.content' ).height( height );

		function initMap() {
			var directionsService = new google.maps.DirectionsService;
			var directionsDisplay = new google.maps.DirectionsRenderer;
			var markers = {!! $latLngList !!};
			for( i = 0; i < markers.length; i++ ) {
				var userLat = markers[0][0];
				var userLong = markers[0][1];
			}
			var map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 18,
			  center: {lat: userLat, lng: userLong}
			});
			directionsDisplay.setMap(map);


			  calculateAndDisplayRoute(directionsService, directionsDisplay);

			}

			function calculateAndDisplayRoute(directionsService, directionsDisplay) {
			var markers = {!! $latLngList !!};
			for( i = 0; i < markers.length; i++ ) {
				var userLat = markers[0][0];
				var userLong = markers[0][1];
				var resLat = markers[1][0];
				var resLongt = markers[1][1];
			}
			directionsService.route({
			  origin: {lat: userLat, lng: userLong},
			  destination: {lat: resLat, lng: resLongt},
			  travelMode: 'DRIVING'
			}, function(response, status) {
			  if (status === 'OK') {
			    directionsDisplay.setDirections(response);
			  } else {
			    window.alert('Directions request failed due to ' + status);
			  }
			});
		}

		/*$(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });*/
</script>
@endsection