@extends('layouts.master')
@section('content')
<div class="" data-role="page" data-theme="c">
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('user-setting')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">Setting</a></li>
			  <li class="done-btn">  <input  type="button" value="Done" id="dataSave"/></li> </ul> </div><!-- /navbar -->
		</div>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-location') }}">
	{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content map-container">
			
			<div class="map-input">
				<input type="text" name="street_address" id="pac-input" class="" placeholder="Enter a Location*" value="" required placeholder="Address*">
			</div>
            <div id="map" style="height: 665px;">
            </div>
		</div>
	</form>
</div>
@endsection

@section('footer-script')
	
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630&libraries=places&callback=initMap" async defer></script>
	<script type="text/javascript">

		$(function(){
			
			document.getElementById("dataSave").disabled = true;    
		});


		function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 0, lng: 0},
                zoom: 2,
                mapTypeId: 'roadmap',
                mapTypeControl: false,
                scrollwheel: false
            });
            var input = /** @type {!HTMLInputElement} */(document.getElementById('pac-input'));

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var opt = { minZoom: 2, maxZoom: 12};
            map.setOptions(opt);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
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
@endsection