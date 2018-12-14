@extends('layouts.master')
@section('content')

@include('includes.headertemplate')
	<!--<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			{{$storedetails->store_id}}
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>-->
	<div role="main" data-role="main-content" class="content">
		<div id="map" class="map_container"></div>
	</div>

@include('includes.fixedfooter')	

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


	// 	function initialize() {
	//     var map;
	//     var bounds = new google.maps.LatLngBounds();
	//     var mapOptions = {
	//         mapTypeId: 'roadmap'
	//     };
	                    
	//     // Display a map on the page
	//     map = new google.maps.Map(document.getElementById("map"), mapOptions);
	//     map.setTilt(45);
	
 //    	var markers = {!! $latLngList !!};
	//     for( i = 0; i < markers.length; i++ ) {

	//     console.log('userLat'+markers[0][0]); 
	//     console.log('userLong'+markers[0][1]); 
	//     console.log('resLat'+markers[1][0]); 
	//     console.log('resLongt'+markers[1][1]); 
	//         if( i == 0){
	// 	        var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
	// 	        bounds.extend(position);
	// 	        marker = new google.maps.Marker({
	// 	            position: position,
	// 	            map: map,
	// 	            icon: {
 //                    url:"{{ asset('images/blue-pin.png') }}",
 //                    size: new google.maps.Size(71, 95),
 //                    scaledSize: new google.maps.Size(35, 42),
 //                    origin: new google.maps.Point(0, 0),
 //                    anchor: new google.maps.Point(17, 36)
 //                },
 //                anchorPoint: new google.maps.Point(0, -29)
	// 	        });
	//     	}else{
	// 	        var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
	// 	        bounds.extend(position);
	// 	        marker = new google.maps.Marker({
	// 	            position: position,
	// 	            map: map
	// 	        });
	//     	}
	        
	//         // Allow each marker to have an info window    
	//         /*google.maps.event.addListener(marker, 'click', (function(marker, i) {
	//             return function() {
	//                 infoWindow.setContent(infoWindowContent[i][0]);
	//                 infoWindow.open(map, marker);
	//             }
	//         })(marker, i));*/

	//         // Automatically center the map fitting all markers on the screen
	//         map.fitBounds(bounds);
	//     }

	//     // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
	//     var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
	//         this.setZoom(17);
	//         google.maps.event.removeListener(boundsListener);
	//     });
	    
	// }
		// google.maps.event.addDomListener(window, 'load', initialize);


		$(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });
</script>
@endsection