
var lat;
var lng;

$(document).ready(function($) {
var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

setInterval(function(){getLocation()},10000);

//get location
function getLocation() {
	
    if (navigator.geolocation) 
	{
        navigator.geolocation.getCurrentPosition(showPosition, showError,options);
        var flag=checkTimeAfterLocationSet();

        if(flag==false){
         //alert("Setting location as per distance paramater");
        // setCurrentCoordinates();
         setDistanceParmeter();

         
       }else{

       	console.log("location set nothing to to do with distance calculation");
       }
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
	//checkDistance(position.coords.latitude,position.coords.longitude);
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

function setDistanceParmeter(){

/* Testing Data

		var lat1="28.580830";
		var lon1="77.077720";

		var lat2="28.585560";
		var lon2="77.074809";

		end of testing data*/

	  var lat1 = getCookie("latitude");
	  var lon1 = getCookie("longitude");

	  var lat2 =  getCookie("everyMinutelatitude");
	  var lon2 =  getCookie("everyMinutelongitude");

      var distance = distanceLatLon(lat1, lon1, lat2, lon2, "K");

      distance = distance*1000;

	  if(distance >300){

	  	 document.cookie="latitude="  + '';
	     document.cookie="longitude=" + '';
	  	
         document.cookie="latitude="  + lat2;
         document.cookie="longitude=" + lon2;

		   checkDistance(lat2,lon2);

      }

}

function distanceLatLon(lat1, lon1, lat2, lon2, unit) {
	if ((lat1 == lat2) && (lon1 == lon2)) {
		return 0;
	}
	else {
		var radlat1 = Math.PI * lat1/180;
		var radlat2 = Math.PI * lat2/180;
		var theta = lon1-lon2;
		var radtheta = Math.PI * theta/180;
		var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
		if (dist > 1) {
			dist = 1;
		}
		dist = Math.acos(dist);
		dist = dist * 180/Math.PI;
		dist = dist * 60 * 1.1515;
		if (unit=="K") { dist = dist * 1.609344 }
		if (unit=="N") { dist = dist * 0.8684 }
		return dist;
	}
}

//set geo location data
function checkDistance(latt,lngg)
{
	
	$.ajax({
			type: "GET",
			url: "checkDistance",
			data: {lat: latt, lng : lngg},
			success: function( returnedData ) {
				//alert("in success of alert Distance Executed Distance check parameter");
				reloadRestaurantList();
				
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

