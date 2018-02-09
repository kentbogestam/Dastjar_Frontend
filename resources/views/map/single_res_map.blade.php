@extends('layouts.master')
@section('content')

	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				<span>{{ Auth::user()->name}}</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div id="map" class="map_container"></div>
	</div>

	<div data-role="footer" class="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href = "{{ url('eat-later-map') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>
		@if(count(Auth::user()->paidOrderList) == 0)
			<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_05.png')}}">
				</div>
				<span>{{ __('messages.Order') }}</span>
			</a></div>
		@else
			<div class="ui-block-c order-active">
		    	<a  class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec">
			        <div class="img-container">
			       		<!-- <img src="images/icons/select-store_05.png"> -->
			        	<img src="images/icons/select-store_05-active.png">
			        </div>
		        	<span>{{ __('messages.Order') }}<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
		        </a>
		        <div id="order-popup" data-theme="a">
			      <ul data-role="listview">
			      	@foreach(Auth::user()->paidOrderList as $order)
						<li>
							<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->customer_order_id}}</a>
						</li>
					@endforeach
			      </ul>
			    </div>
		    </div>
		@endif
		<div class="ui-block-d"><a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
		</a></div>
		</div>
	</div>
	<!-- pop-up -->
	<div data-role="popup" id="order-popup" class="ui-content" data-theme="a">
		<ul data-role="listview">
			@foreach(Auth::user()->paidOrderList as $order)
				<li>
					<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->order_id}}</a>
				</li>
			@endforeach
		</ul>
	</div>

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
	    
	    // Loop through our array of markers & place each one on the map  
	    for( i = 0; i < markers.length; i++ ) {
	        var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
	        bounds.extend(position);
	        marker = new google.maps.Marker({
	            position: position,
	            map: map
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
	        this.setZoom(17);
	        google.maps.event.removeListener(boundsListener);
	    });
	    
	}
		google.maps.event.addDomListener(window, 'load', initialize);


		$(".ordersec").click(function(){
		    $("#order-popup").toggleClass("hide-popup");
		 });
</script>
@endsection