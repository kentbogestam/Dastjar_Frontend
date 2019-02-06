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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&callback=initMap"></script>
<script type="text/javascript">
	var headerHeight = $( '.header' ).height();
	var footerHeight = $( '.footer' ).height();
	var maincontent =$(window).height();/*
	var footerTop = $( '#footer' ).offset().top;*/
	var height = maincontent - (headerHeight + footerHeight);
	/*alert( height);*/
	$( '.content' ).height( height );

	function initMap() {
		// Instantiate a directions service.
		directionsService = new google.maps.DirectionsService;
		
		markers = {!! $latLngList !!};
		console.log(markers);
		
		for( i = 0; i < markers.length; i++ ) {
			var userLat = markers[0][0];
			var userLong = markers[0][1];
		}
		
		// Create a map and center it on Users Location
		map = new google.maps.Map(document.getElementById('map'), {
		  zoom: 18,
		  center: {lat: userLat, lng: userLong}
		});
		
		// Create a renderer for directions and bind it to the map.
		directionsDisplay = new google.maps.DirectionsRenderer;
		directionsDisplay.setMap(map);

		// Display the route between the initial start and end selections.
		calculateAndDisplayRoute();
	}

	function calculateAndDisplayRoute() {
		for( i = 0; i < markers.length; i++ ) {
			var userLat = markers[0][0];
			var userLong = markers[0][1];
			var resLat = markers[1][0];
			var resLongt = markers[1][1];
		}

		directionsService.route({
			origin: {lat: userLat, lng: userLong},
			destination: {lat: resLat, lng: resLongt},
			travelMode: 'WALKING'
		}, function(response, status) {
			if (status === 'OK') {
				directionsDisplay.setDirections(response);
			} else {
				window.alert('Directions request failed due to ' + status);
			}
		});
	}

	// setTimeout(showLocation, 5000);

	// Watch position callback on change location, update lat/lng on moved x meter
    function showLocation(position)
    {
        var lat1 = getCookie("latitude");
        var lon1 = getCookie("longitude");
        var lat2 = position.coords.latitude;
        var lon2 = position.coords.longitude;
        // var lat2 = 28.477330;
        // var lon2 = 77.068140;

        var distance = (distanceLatLon(lat1, lon1, lat2, lon2, "K") * 1000);
        // alert('lat1/lon1: '+lat1+'/'+lon1+', lat2/lon2: '+lat2+'/'+lon2+', distance:'+distance);

        if(distance > 20)
        {
            markers[0][0] = lat2;
            markers[0][1] = lon2;

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
        // Add watch on location change if location is set to current location
        var flag = checkTimeAfterLocationSet();

        if(!flag)
        {
        	if(navigator.geolocation)
	        {
	            var options = {timeout:60000};
	            watchPosition = navigator.geolocation.watchPosition(showLocation, errorHandler, options);
	        }
        }
    });
</script>
@endsection