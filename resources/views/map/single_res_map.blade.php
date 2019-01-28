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

	var map = null;
    var markers = null;

	function initMap() {
		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer;
		markers = {!! $latLngList !!};
		for( i = 0; i < markers.length; i++ ) {
			var userLat = markers[0][0];
			var userLong = markers[0][1];
		}
		
		map = new google.maps.Map(document.getElementById('map'), {
		  zoom: 18,
		  center: {lat: userLat, lng: userLong}
		});
		
		directionsDisplay.setMap(map);

		calculateAndDisplayRoute(directionsService, directionsDisplay);
	}

	function calculateAndDisplayRoute(directionsService, directionsDisplay) {
		markers = {!! $latLngList !!};
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

        if(distance > 20)
        {
            // alert('lat1/lon1: '+lat1+'/'+lon1+', lat2/lon2: '+lat2+'/'+lon2+', distance:'+distance);
            document.cookie="latitude="  + lat2;
            document.cookie="longitude=" + lon2;

            $.ajax({
                type: "GET",
                url: "checkDistance",
                data: {lat: lat2, lng : lon2},
                success: function( returnedData ) {
                    window.location.reload();
                }
            });
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
        // Add watch on location change
        if(navigator.geolocation)
        {
            var options = {timeout:60000};
            watchPosition = navigator.geolocation.watchPosition(showLocation, errorHandler, options);
        }
    });
</script>
@endsection