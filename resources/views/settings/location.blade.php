@extends('layouts.master')
@section('content')
<div class="" data-role="page" data-theme="c">
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('user-setting')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">{{ __('messages.Location') }}</a></li>
			  <li class="done-btn" id="dataSave">  <input type="button" value="{{ __('messages.Done') }}" /></li>  </ul>
              
                <a href="#" class="location_icon" id="locationSave">
                   <img src="{{asset('images/icons/location.png')}}">
                   <p>{{ __('messages.Current Position') }}</p> 
                      </a>
            </div><!-- /navbar -->
		</div>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-location') }}">
	{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content map-container">
			
			<div class="map-input">
				<input type="text" name="street_address" id="pac-input" class="" placeholder="Enter a Location*" value="{{ Auth::user()->address}}" required placeholder="Address*">
			</div>
            <div id="map" style="height: 665px;">
            </div>
		</div>
	</form>
</div>
@endsection

@section('footer-script')
	
        
	<script type="text/javascript">

		$(function(){
			
			//document.getElementById("dataSave").disabled = true;    
		});

        $(function(){     

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

        });


		function initMap() {
            var location  = {lat: {{Auth::user()->customer_latitude}} , lng: {{ Auth::user()->customer_longitude}} };
            
            var map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 5,
                mapTypeId: 'roadmap',
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
            var marker = new google.maps.Marker({
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
    



		$("#dataSave").click(function(e){

			var flag = true;
			// var x = $('form input[type="radio"]').each(function(){
	  //       // Do your magic here
	  //       	var checkVal = parseInt($(this).val());
	  //       	console.log(checkVal);
	  //       	if(checkVal > 0){
	  //       		flag = true;
	  //       		return flag;
	  //       	}
			// });

			if(flag){
				$("#form").submit();
			} else{
				alert("Please fill some value");	
				e.preventDefault();
			}
		})
	</script>

    <script type="text/javascript">
        $("#locationSave").click(function(e){
            console.log('gggg');
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
            var latitude = getCookie("latitude");
            var longitude = getCookie("longitude");
            $.get("{{url('saveCurrentlat-long')}}", { lat: latitude, lng : longitude}, function(returnedData){
                console.log(returnedData["data"]);
                location.reload();
            });
            console.log(latitude);
            console.log(longitude);
        });

        function makeRedirection(link){
            window.location.href = link;
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&libraries=places&callback=initMap" async defer></script>
@endsection