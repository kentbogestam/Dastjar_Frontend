@extends('v1.user.layouts.master')

@section('content')
	<div class="location-map">
		<div id="map" class="map_container"></div>
	</div>
@endsection

@section('footer')
	
@endsection

@section('footer-script')
	<script>
		var headerHeight = $( '.head-section' ).height();
	    var maincontent =$(window).height();
	    var height = maincontent - (headerHeight);
	    $( '.map_container' ).height( height );

		// 
		var markers = [];

		// 
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

				// Start/Finish icons
				icons = {
					start: new google.maps.MarkerImage(
						"{{ url('images/cabs.png') }}",
					),
					end: new google.maps.MarkerImage(
						"{{ url('images/delivery-destination.png') }}",
					)
				};

				// Instantiate a directions service.
				directionsService = new google.maps.DirectionsService;

				// Create a renderer for directions and bind it to the map.
				directionsDisplay = new google.maps.DirectionsRenderer({map: map, suppressMarkers: true});

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

					var leg = response.routes[0].legs[0];
					clearMarkers();
					makeMarker(leg.start_location, icons.start, "Driver");
					makeMarker(leg.end_location, icons.end, 'Destination');
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
		}

		// Draw custom icon
		function makeMarker(position, icon, title) {
			var marker = new google.maps.Marker({
				position: position,
				map: map,
				icon: icon,
				title: title
			});

			if(title == 'Driver')
			{
				markers.push(marker);
			}
		}

		// Sets the map on all markers in the array.
		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}

		// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
			setMapOnAll(null);
		}

		// Get driver updated position and update map marker
		var getDriverPosition = function() {
			$.ajax({
				url: '{{ url('get-driver-position/'.$order->order_id) }}',
				dataType: 'json',
				success: function(result) {
					if(result.status)
					{
						markerArray[0]['lat'] = result.driver['latitude'];
						markerArray[0]['lng'] = result.driver['longitude'];

						calculateAndDisplayRoute();
					}
				}
			});
		}

		intervalGetDriverPosition = setInterval(getDriverPosition, 10000);
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&callback=initMap"></script>
@endsection