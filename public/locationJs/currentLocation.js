
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

    if (navigator.geolocation) 
	{
        navigator.geolocation.getCurrentPosition(showPosition, showError,options);
    } 
	else 
	{ 
	   setMyCookie('showError','Geolocation is not supported by this browser.', 7);
	   console.log('NOT SUPPORT');
    }
}
//get postion
function showPosition(position) {
	setMyCookie('everyMinutelatitude', position.coords.latitude, 7);
	setMyCookie('everyMinutelongitude', position.coords.longitude, 7);
	setMyCookie('showError','', 0);
   // geo set location 
	checkDistance(position.coords.latitude,position.coords.longitude);
	console.log('ACCEPTTED');
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
	console.log('DENIED');
	setMyCookie('showError',error, 7);
}
//get cookie
function getMyCookie(cname) {
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
//set the cooke
function setMyCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
//set geo location data
function checkDistance(latt,lngg)
{
	
	$.ajax({
			type: "GET",
			url: "checkDistance",
			data: {lat: latt, lng : lngg},
			success: function( returnedData ) {
				alert("in success of alert Distance Executed Distance check parameter");
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

	
var showErrorThorw = getMyCookie("showError");
var lat            =  getMyCookie("everyMinutelatitude");
var lng            = getMyCookie("everyMinutelongitude");

console.log('error=> '+showErrorThorw +' lat=>  '+lat+ ' lng=> '+lng)
//if no error
if(isEmpty(showErrorThorw))
{
	if(isEmpty(lat) || isEmpty(lng))
	{
		console.log('get the geo location')
		// geo get loaction
		getLocation();
	}
	if(!isEmpty(lat) && !isEmpty(lng))
	{
		console.log('checkDistance')
		// geo set location 
		checkDistance(lat,lng);
	}
}
});
