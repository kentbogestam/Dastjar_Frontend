@extends('v1.user.layouts.master')

@section('content')
	<div class="location-map">
		<div id="map" class="map_container"></div>
	</div>
@endsection

@section('footer')
	
@endsection

@section('footer-script')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630"></script>
<script type="text/javascript" src="{{ asset('js/init.js') }}"></script>

<script type="text/javascript">
    var headerHeight = $( '.head-section' ).height();
    var maincontent =$(window).height();
    var height = maincontent - (headerHeight);
    $( '.map_container' ).height( height );

    var map = null;
    var markers = null;
    var myMarker = null;

    function initialize() {
        markers = {!! $latLngList !!};

        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap'
        };
                        
        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        map.setTilt(45);

        var infowindow = new google.maps.InfoWindow();
        
        // Display multiple markers on a map
        // Loop through our array of markers & place each one on the map  
        for( i = 0; i < markers.length; i++ ) {
            if(markers[i]['latitude'] && markers[i]['longitude'])
            {
                if( i == 0){
                    var position = new google.maps.LatLng(markers[i]['latitude'], markers[i]['longitude']);
                    bounds.extend(position);
                    myMarker = marker = new google.maps.Marker({
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
                    var position = new google.maps.LatLng(markers[i]['latitude'], markers[i]['longitude']);
                    bounds.extend(position);
                    marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        info: markers[i]['nearbyRestaurantDetail']['store_name']
                    });
                }

                // Allow each marker to have an info window
                if(i){
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.setContent(this.info);
                        infowindow.open(map, this);
                    });
                }

                // Automatically center the map fitting all markers on the screen
                map.fitBounds(bounds);
            }
        }

        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(13);
            google.maps.event.removeListener(boundsListener);
        });
        
    }
    google.maps.event.addDomListener(window, 'load', initialize);

    $(window).on('load', function() {
        // Add watch on location change if location is set to current location
        var flag = checkTimeAfterLocationSet();

        if(!flag)
        {
            if(ios && (!standalone && !safari))
            {
                watchPositionAction = 'updateLocationOnMap';
                requestGeoAddressToIosNative('getLocation');
            }
            else
            {
                if(navigator.geolocation)
                {
                    var options = {timeout:60000};
                    watchPosition = navigator.geolocation.watchPosition(updateLocationOnMap, errorHandlerOnMap, options);
                }
                else
                {
                    alert('Location not supported');
                }
            }
        }

        // setTimeout(updateLocationOnMap, 5000);
    });
</script>
@endsection