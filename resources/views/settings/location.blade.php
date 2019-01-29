@extends('layouts.master')
@section('head-scripts')
<style>
    #back_arw{
        width: 20px;
    }
</style>
@endsection

@section('content')
<div class="" data-role="page" data-theme="c">
    <div data-role="header" class="header" data-position="fixed">
        <div class="nav_fixed">
            <div data-role="navbar"> 
                <ul> 
                    <li>
                        @if(isset($_GET['k']))
                            <a href="{{url('')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>
                        @else
                            <a href="{{url('user-setting')}}" data-ajax="false" id="back_arw" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>
                        @endif
                    </li>
                    <li><a data-ajax="false" class="ui-btn-active">{{ __('messages.Location') }}</a></li>
                    <li class="done-btn" id="dataSave" onclick="dataSave();">
                        <input type="button" value="{{ __('messages.Done') }}" />
                    </li>
                </ul>

                <a href="javascript:void(0)" class="location_icon" id="locationSave" onclick=locationSave("{{url('saveCurrentlat-long/')}}")><img src="{{asset('images/icons/location.png')}}"><p>{{ __('messages.Current Position') }}</p></a>
            </div><!-- /navbar -->
        </div>
    </div>

    <form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-location') }}">
    {{ csrf_field() }}
        <div role="main" data-role="main-content" class="content map-container">
            <div class="map-input">
                @if(Auth::check())
                    <input type="text" name="street_address" id="pac-input" class="" placeholder="{{ __('messages.Enter a Location') }}*" value="{{ Session::get('with_login_address')}}" required placeholder="Address*" onKeyPress="checkFormsubmit(event)"/>
                @else
                    <input type="text" name="street_address" id="pac-input" class="" placeholder="{{ __('messages.Enter a Location') }}*" value="{{ Session::get('address')}}" required placeholder="Address*" onKeyPress="checkFormsubmit(event)"/>
                @endif

                @if(isset($_GET['k']))
                    <input type="hidden" name="redirect_to_home" value="1"/>
                @else               
                    <input type="hidden" name="redirect_to_home" value="0"/>
                @endif
            </div>
            <div id="map" style="height: 665px;"></div>
        </div>
    </form>
</div>
@endsection

@section('footer-script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&libraries=places&callback=initMap" async defer></script>

    <script type="text/javascript">
        /*$(function(){       
            // Check for Geolocation API permissions  
            navigator.geolocation.getCurrentPosition(function(position) { 
                console.log("latitude=" + position.coords.latitude);
                console.log("longitude=" + position.coords.longitude);
                document.cookie="latitude=" + position.coords.latitude;
                document.cookie="longitude=" + position.coords.longitude;
                
            },function(error){
               $('.login-inner-section a').attr('href','javascript:void(0)');
               $('#login-popup').show();
                
            });

        });*/

        var map = null;
        var marker = null;

        function initMap() {
            @if(Auth::check())
                @if(Session::get('with_login_lat')!=null && Session::get('with_login_lng')!=null)
                    var location  = {lat: {{Session::get('with_login_lat')}} , lng: {{ Session::get('with_login_lng')}} };
                @else    
                    var location  = {lat: 60.1282 , lng: 18.6435};
                @endif    
            @else
                @if(Session::get('with_out_login_lat')!=null && Session::get('with_out_login_lng')!=null)
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
            marker = new google.maps.Marker({
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

            //changeMarkerPosition( map, marker );
        }

        // Update map and market on move
        function changeMarkerPosition(lat, lng)
        {
            var newLatLng = new google.maps.LatLng(lat, lng);

            marker.setPosition(newLatLng);
            // map.panTo(newLatLng);
        }

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
                        changeMarkerPosition(lat2, lon2);
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