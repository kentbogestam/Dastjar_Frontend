@extends('v1.user.layouts.master')

@section('content')
	<div class="location-map">
		<form id="form" class="form-horizontal" method="post" action="{{ url('save-location') }}">
			{{ csrf_field() }}
			<div class="map-input">
				@if(Auth::check())
					<input type="text" name="street_address" id="pac-input" class="" placeholder="{{ __('messages.Enter a Location') }}" value="{{ Session::get('with_login_address')}}" required onKeyPress="checkFormsubmit(event)"/>
				@else
					<input type="text" name="street_address" id="pac-input" class="" placeholder="{{ __('messages.Enter a Location') }}" value="{{ Session::get('address')}}" required onKeyPress="checkFormsubmit(event)"/>
				@endif

				@if(isset($_GET['k']))
					<input type="hidden" name="redirect_to_home" value="1"/>
				@else               
					<input type="hidden" name="redirect_to_home" value="0"/>
				@endif
			</div>
			<div id="map" style="height: 665px;"></div>
		</form>
	</div>

	<!-- If allow location is off, show login popup -->
	<div id="login-popup" class="modal fade login-popup" role="dialog">
		<div class='modal-dialog'>
			<div class="modal-content">
				<div class="modal-body text-center">
					<p>{{ __('messages.Please activate Location Services in your mobile') }}</p>
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.OK') }}</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('footer')
	
@endsection

@section('footer-script')
	<script type="text/javascript">
		$(function(){
            // Check if its IOS native
            @if( ((strpos(\Request::server('HTTP_USER_AGENT'), 'Mobile/') !== false) && (strpos(\Request::server('HTTP_USER_AGENT'), 'Safari/') == false)) )
                // requestGeoAddressToIosNative();
            @else
                // Check for Geolocation API permissions
                navigator.geolocation.getCurrentPosition(function(position) {
                    console.log("latitude=" + position.coords.latitude);
                    console.log("longitude=" + position.coords.longitude);
                },function(error){
                    if (typeof loc_lat === "undefined" || loc_lat == "") {
                        if (!getCookie("latitude")){
                            $('.login-inner-section a').attr('href','javascript:void(0)');
                            $('#login-popup').modal('show');
                        }
                    }
                });
            @endif
        });

        var watchPosition;
        var map = null;
        var marker = null;
        var myMarker = null;

        function initMap() {
            @if(Auth::check())
                @if( (Session::has('with_login_lat') && Session::get('with_login_lat')!='null') && (Session::has('with_login_lng') && Session::get('with_login_lng')!='null') )
                    var location  = {lat: {{Session::get('with_login_lat')}} , lng: {{ Session::get('with_login_lng')}} };
                @else    
                    var location  = {lat: 60.1282 , lng: 18.6435};
                @endif    
            @else
                @if( (Session::has('with_out_login_lat') && Session::get('with_out_login_lat')!='null' ) && (Session::has('with_out_login_lng') && Session::get('with_out_login_lng')!='null') )
                    var location  = {lat: {{Session::get('with_out_login_lat')}} , lng: {{Session::get('with_out_login_lng')}} };
                @else    
                    var location  = {lat: 60.1282 , lng: 18.6435};
                @endif    
            @endif
            
            map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 5,
                // mapTypeId: 'roadmap',
                mapTypeControl: false,
                scrollwheel: false
            });
            var input = /** @type {!HTMLInputElement} */(document.getElementById('pac-input'));

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var opt = {maxZoom: 17};
            map.setOptions(opt);

            var infowindow = new google.maps.InfoWindow();
            myMarker = marker = new google.maps.Marker({
                position:location,
                map: map,
                icon: {
                    url:"{{ asset('images/pointer.png') }}",
                    size: new google.maps.Size(71, 95),
                    scaledSize: new google.maps.Size(35, 42),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 36)
                },
                anchorPoint: new google.maps.Point(0, -29)
            });

            map.addListener('click',function(event) {
                this.setOptions({scrollwheel:true});
                marker.setPosition(event.latLng);
                
                var geocoder = new google.maps.Geocoder;
                var latlng = {lat: event.latLng.lat(), lng: event.latLng.lng()};

                geocoder.geocode({'location': latlng}, function(results, status) {
                    if (status === 'OK') {
                        if (results[1]) {
                            document.getElementById('pac-input').value = results[1].formatted_address;
                            infowindow.setContent(results[1].formatted_address);
                            infowindow.open(map, marker);
                        } else {
                            window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
            });

            map.addListener('mouseout', function(event) {
                this.setOptions({scrollwheel:false});
            });


            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                    this.setZoom(17);
                    google.maps.event.removeListener(boundsListener);
                });


            autocomplete.addListener('place_changed', function() {
            
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    setLocationCookieTime(); // code add by saurabh to set the location set start time
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    document.getElementById("dataSave").disabled = false;
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setIcon(/** @type {google.maps.Icon} */({
                    url:"{{ asset('images/pointer.png') }}",
                    size: new google.maps.Size(71, 95),
                    scaledSize: new google.maps.Size(35, 42),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 36)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                document.getElementById("dataSave").disabled = false;    
                
                }
                 
 
                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);
            });
        }

        // Add watch on position
        function addWatchOnPosition()
        {
            // Add watch on location change if location is set to current location
            var flag = checkTimeAfterLocationSet();

            if(!flag)
            {
                // If for 'ios native', else for others (web, pwa etc)
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
                }
            }
        }

        $(window).on('load', function() {
            addWatchOnPosition();
        });
	</script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&libraries=places&callback=initMap" async defer></script>
    <script type="text/javascript" src="{{ asset('js/init.js') }}"></script>
@endsection