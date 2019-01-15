@extends('layouts.master')
@section('content')

	@include('includes.headertemplate')
	<div role="main" data-role="main-content" class="content">
		<div id="map" class="map_container"></div>
	</div>

	
 <!--@include('includes.fixedfooter')-->
@endsection

@section('footer-script')
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script> -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630"></script>

	<script type="text/javascript">
		
		var headerHeight = $( '.header' ).height();
		var footerHeight = $( '.footer' ).height();
		var maincontent =$(window).height();/*
		var footerTop = $( '#footer' ).offset().top;*/
		var height = maincontent - (headerHeight + footerHeight);
		/*alert( height);*/
		$( '.content' ).height( height );

		function initialize() {
	    var map;
	    var bounds = new google.maps.LatLngBounds();
	    var mapOptions = {
	        mapTypeId: 'roadmap'
	    };
	                    
	    // Display a map on the page
	    map = new google.maps.Map(document.getElementById("map"), mapOptions);
	    map.setTilt(45);
	
    	var markers = {!! $latLngList !!};

    	var nearbyRestaurantDetail = {!! json_encode($nearbyRestaurantDetail) !!};
	                        
	    // Info Window Content
	    /*var infoWindowContent = [
	        ['<div class="info_content">' +
	        '<h3>London Eye</h3>' +
	        '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames. The entire structure is 135 metres (443 ft) tall and the wheel has a diameter of 120 metres (394 ft).</p>' +        '</div>'],
	        ['<div class="info_content">' +
	        '<h3>Palace of Westminster</h3>' +
	        '<p>The Palace of Westminster is the meeting place of the House of Commons and the House of Lords, the two houses of the Parliament of the United Kingdom. Commonly known as the Houses of Parliament after its tenants.</p>' +
	        '</div>']
	    ];*/

	        
	    // Display multiple markers on a map
	    /*var infoWindow = new google.maps.InfoWindow(), marker, i;*/

	    var infowindow = new google.maps.InfoWindow();
	    
	    // Loop through our array of markers & place each one on the map  
	    for( i = 0; i < markers.length; i++ ) {
	    	if( i == 0){
		        var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
		        bounds.extend(position);
		        marker = new google.maps.Marker({
		            position: position,
		            map: map,
		            icon: {
	                    url:"{{ asset('images/blue-pin.png') }}",
	                    size: new google.maps.Size(71, 95),
	                    scaledSize: new google.maps.Size(35, 42),
	                    origin: new google.maps.Point(0, 0),
	                    anchor: new google.maps.Point(17, 36)
	                },
                	anchorPoint: new google.maps.Point(0, -29)
		        });
	    	}else{
		        var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
		        bounds.extend(position);
		        marker = new google.maps.Marker({
		            position: position,
		            map: map,
		            info: nearbyRestaurantDetail[(i-1)]['store_name']
		        });
	    	}

	    	// Allow each marker to have an info window
			google.maps.event.addListener(marker, 'click', function () {
				infowindow.setContent(this.info);
				infowindow.open(map, this);
			});
	        
	        // Allow each marker to have an info window    
	        /*google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
	                infoWindow.setContent(infoWindowContent[i][0]);
	                infoWindow.open(map, marker);
	            }
	        })(marker, i));*/

	        // Automatically center the map fitting all markers on the screen
	        map.fitBounds(bounds);
	    }
	    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
	    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
	        this.setZoom(13);
	        google.maps.event.removeListener(boundsListener);
	    });
	    
	}
		google.maps.event.addDomListener(window, 'load', initialize);

		$(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });
</script>
@endsection
