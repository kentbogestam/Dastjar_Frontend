
var lat;
var lng;

$(document).ready(function($) {
	var options = {
		enableHighAccuracy: true,
		timeout: 5000,
		maximumAge: 0
	};

//
// setInterval(function(){getLocation()},10000);
setTimeout(getLocation, 0);

// Geolocation API is used to locate a user's position.
function getLocation() {
	if(ios && (!standalone && !safari))
	{
		requestGeoAddressToIosNative('getLocation');
	}
	else
	{
		if (navigator.geolocation) 
		{
			navigator.geolocation.watchPosition(showPosition, showError, options);
	    } 
		else 
		{
		   	setMyCookie('showError','Geolocation is not supported by this browser.', 7);
		   	console.log('NOT SUPPORT');
	    }
	}
}

//get postion
function showPosition(position) {

	setMyCookie('everyMinutelatitude', position.coords.latitude, 7);
	setMyCookie('everyMinutelongitude', position.coords.longitude, 7);
	setMyCookie('showError','', 0);
   // geo set location 
	//checkDistance(position.coords.latitude,position.coords.longitude);
	console.log('ACCEPTTED');

	var flag=checkTimeAfterLocationSet();

	if(flag==false){
		setDistanceParmeter();
	}else{
		console.log("location set nothing to to do with distance calculation");
		//alert(flag);
	}
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

	
var showErrorThorw =  getCookie("showError");
var lat            =  getCookie("everyMinutelatitude");
var lng            =  getCookie("everyMinutelongitude");

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

		
	}
 }


});
