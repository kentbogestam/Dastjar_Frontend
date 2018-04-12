
var lat;
var lng;
$(document).ready(function($) {

	navigator.geolocation.getCurrentPosition(function(position) { 
	    document.cookie="everyMinutelatitude=" + position.coords.latitude;
	    document.cookie="everyMinutelongitude=" + position.coords.longitude;
	},function(error){
	   $('.login-inner-section a').attr('href','javascript:void(0)');
	   $('#login-popup').show();
	    
	});

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
	lat = getCookie("everyMinutelatitude");
	lng = getCookie("everyMinutelongitude");
	$.ajax({
        type: "GET",
        url: "checkDistance",
        data: {lat: getCookie("everyMinutelatitude"), lng : getCookie("everyMinutelongitude")},
        success: function( returnedData ) {
           console.log(returnedData);
        }
    });

});
