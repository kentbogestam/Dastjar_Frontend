 var getUrl = window.location;
 var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
 
setInterval(function(){getCurrentCoordinates()},3000); // Check the position afer 20 min and reset the longitude and latitude

function getCurrentCoordinates(){

   navigator.geolocation.getCurrentPosition(function(position) {

	    document.cookie="latitude=" + position.coords.latitude;
	    document.cookie="longitude=" + position.coords.longitude;
       $.ajax({
           url: baseUrl+"/update-location",
           type: "GET",
           data: {lat : position.coords.latitude, long : position.coords.longitude},
           dataType: "json"
       });
	
	},function(error){
		if (typeof lat === "undefined") {
		   // $('.login-inner-section a').attr('href','javascript:void(0)');
		   // $('#login-popup').show();	    			
		} else {
		    // document.cookie="latitude=" + lat;
		    // document.cookie="longitude=" + lng;			
		} 			    
	});

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