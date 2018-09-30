
var lat;
var lng;
$(document).ready(function($) {
var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};
//get location
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError,options);
    } else { 
	document.cookie="showError=Geolocation is not supported by this browser.";
    }
}
//get postion
function showPosition(position) {
	document.cookie="everyMinutelatitude=" + position.coords.latitude;
	document.cookie="everyMinutelongitude=" + position.coords.longitude;
	document.cookie="showError=''";
}
//error throw position
function showError(error) {
	var error = '';
    switch(error.code) {
        case error.PERMISSION_DENIED:
           error = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
           error = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            error = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            error = "An unknown error occurred."
            break;
    }
	
	document.cookie="showError=" + error;
}
//get cookie
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
//set geo location data
function setLocation(latt,lngg)
{
	$.ajax({
			type: "GET",
			url: "checkDistance",
			data: {lat: latt, lng : lngg},
			success: function( returnedData ) {
				//tidy up
			}
		});
	}
//check empty
function isEmpty(e) {
  switch (e) {
    case "":
    case 0:
    case "0":
    case null:
    case false:
    case typeof this == "undefined":
      return true;
    default:
      return false;
  }
}

	
var showErrorThorw = getCookie("showError");
var lat       =  getCookie("everyMinutelatitude");
var lng       = getCookie("everyMinutelongitude");

console.log('error=> '+showErrorThorw +' lat=>  '+lat+ ' lng=> '+lng)
//if no error
if(isEmpty(showErrorThorw))
{
	if(isEmpty(lat) || isEmpty(lng))
	{
		console.log('get the geo location')
		// geo get loaction
		getLocation();
		// geo set location 
		setLocation(lat,lng);
	}
}
});
