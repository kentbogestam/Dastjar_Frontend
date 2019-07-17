@extends('driver.layouts.app')

@section('content')
	<div id="map" style="width: 100%;"></div>
@endsection

@section('scripts')
	<script>
		var headerHeight = $( '.navbar' ).height();
		var footerHeight = $( '#footer' ).height();
		var maincontent =$(window).height();
		var height = maincontent - (headerHeight + footerHeight);
		$( '#map' ).height( height );

		function initMap()
		{
			markerArray = [];
			markerArray = {!! $markerArray !!};
			// console.log(markerArray);

			if(markerArray.length == 2)
			{
				// Create a map and center it on ...
				map = new google.maps.Map(document.getElementById('map'), {
					zoom: 13,
					center: {lat: parseFloat(markerArray[1]['lat']), lng: parseFloat(markerArray[1]['lng'])}
				});

				// Instantiate a directions service.
				directionsService = new google.maps.DirectionsService;

				// Create a renderer for directions and bind it to the map.
				directionsDisplay = new google.maps.DirectionsRenderer({map: map});

	    		// Display the route between the initial start and end selections.
				calculateAndDisplayRoute();
			}
			else
			{
				map = new google.maps.Map(document.getElementById('map'), {
					zoom: 7,
					center: {lat: 41.85, lng: -87.65}
				});
			}
		}

		function calculateAndDisplayRoute() {
			directionsService.route({
				// origin: 'chicago, il',
				// destination: 'st louis, mo',
				origin: {lat: parseFloat(markerArray[0]['lat']), lng: parseFloat(markerArray[0]['lng'])},
				destination: {lat: parseFloat(markerArray[1]['lat']), lng: parseFloat(markerArray[1]['lng'])},
				travelMode: 'DRIVING'
			}, function(response, status) {
				if (status === 'OK') {
					directionsDisplay.setDirections(response);
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
		}

		// Watch position callback on change location, update lat/lng on moved x meter
	    function showLocation(position)
	    {
	        var lat1 = getCookie("driver-latitude");
	        var lon1 = getCookie("driver-longitude");
	        var lat2 = position.coords.latitude;
	        var lon2 = position.coords.longitude;

	        var distance = (distanceLatLon(lat1, lon1, lat2, lon2, "K") * 1000);

	        if(distance > 20)
	        {
	            markerArray[0]['lat'] = lat2;
				markerArray[0]['lng'] = lon2;

	            calculateAndDisplayRoute();
	        }
	    }

	    // Error through position
	    function errorHandler(err) {
	        if(err.code == 1) {
	            alert("Error: Access is denied!");
	        } else if( err.code == 2) {
	            alert("Error: Position is unavailable!");
	        }
	    }

		$(window).on('load', function() {
	        if(navigator.geolocation)
	        {
	            var options = {timeout:60000};
	            watchPosition = navigator.geolocation.watchPosition(showLocation, errorHandler, options);
	        }
	    });
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&callback=initMap"></script>
@endsection