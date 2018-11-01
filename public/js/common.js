 var getUrl = window.location;
 var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]; //for local testing
 //var baseUrl =getUrl .protocol + "//" + getUrl.host ; // for live testing
 
setInterval(function(){getCurrentCoordinates()},40000); // Check the position afer 20 min and reset the longitude and latitude

function getCurrentCoordinates(){

   navigator.geolocation.getCurrentPosition(function(position) {

	    document.cookie="latitude=" + position.coords.latitude;
	    document.cookie="longitude=" + position.coords.longitude;
       $.ajax({
          url: baseUrl+"/public/update-location", // for local host testing
          //url: baseUrl+"/update-location", // for live testing
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


// function used for select-datetime.blade.php
 
	   $("#bdaytime").on('change', function(){
				dateVal = moment($("#bdaytime").val()).local();
				dateVal = new Date(dateVal);

				curr_date = dateVal.getDate();
				curr_month = dateVal.getMonth()+1;
				curr_year = dateVal.getFullYear();
				hours = dateVal.getHours(); //returns 0-23
				minutes = dateVal.getMinutes(); //returns 0-59
				seconds = dateVal.getSeconds(); //returns 0-59
                dateVal=$.format.date(curr_year+"-"+curr_month+"-"+curr_date+" "+hours+":"+minutes+":"+seconds, "E MMM dd yyyy HH:mm:ss");

                $('#date-value1-2').text(dateVal+" GMT+05:30 (Indian Standard Time)");

				if(dateVal<new Date()){
				    $('.error_apple_time').show();
				}else{
	 			    $('.error_apple_time').hide();
				}
	   });

	  
	   

// End function used for select-datetime.blade.php