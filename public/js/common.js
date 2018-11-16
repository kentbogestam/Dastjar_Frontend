 var getUrl = window.location;
 var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]; //for local testing
 //var baseUrl =getUrl .protocol + "//" + getUrl.host ; // for live testing
 
setInterval(function(){getCurrentCoordinates()},120000); // Check the position afer 20 min and reset the longitude and latitude

$(document).ready(function() {

    checkTimeAfterLocationSet();
    
});

function reloadRestaurantList(){

  var url      = window.location.href;
  //var pathname = window.location.pathname 

  var pieces = url.split("/");

  $value=pieces[pieces.length-1];

    if($value=='eat-now' || $value=='eat-later' || $value=='' ){

      location.reload();
    }
  

}

function getCurrentCoordinates(){

   var flag=checkTimeAfterLocationSet();

   if(flag==false){
   navigator.geolocation.getCurrentPosition(function(position) {

      alert("current postion after 20 min executed");

	    document.cookie="latitude=" + position.coords.latitude;
	    document.cookie="longitude=" + position.coords.longitude;
      console.log("in getCurrentCoordinates and updating current location ");
      //console.log(position.coords.latitude+"-------"+position.coords.longitude);
       $.ajax({
          url: baseUrl+"/public/update-location", // for local host testing
          //url: baseUrl+"/update-location", // for live testing
           type: "GET",
           data: {lat : position.coords.latitude, long : position.coords.longitude},
           dataType: "json"
       });

       reloadRestaurantList();
	
	},function(error){
		if (typeof lat === "undefined") {
		   // $('.login-inner-section a').attr('href','javascript:void(0)');
		   // $('#login-popup').show();	    			
		} else {
		    // document.cookie="latitude=" + lat;
		    // document.cookie="longitude=" + lng;			
		} 			    
	});

 }else{

  console.log("Current location not required to update because user set location time is less than 20 min");
 }

}

function checkTimeAfterLocationSet(){

   var setLocationTime = getCookie("setLocationTime");

    if (setLocationTime!=''){

      var date1 = getCookie("setLocationTime");
      var date2=getDateTimeStamp("D"); 

      var minutes =getDiffTimeStamp(date1,date2);
    
       if (minutes > 1){

             getCurrentCoordinates();
             unsetLocationCookieTime();
             return true;
      
      }else{

            return true;
       }

      }else{

         return false;
      console.log("setLocationTime set to null ")
     }



}
// Function get cookie
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

//set the cookie
function setMyCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
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


/* The function return timestamp or date on basis of parameter pass

   If pass d then retun date and if pass t as parameter return time stamp

*/

function getDateTimeStamp(val){

   
    if (val=='D'){

     var dt = new Date();
     return dt;

   }else if (val=='T'){

   	 var t= $.now() ;
   	 return t;
   }
   
   //alert(dt.getDate() + '/' + (dt.getMonth()+1) + '/' + dt.getFullYear());
   // var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
   // return v;
}

function getDiffTimeStamp(date1,date2){

         date1 = new Date(date1);
       //  document.write(""+date1);

         date2 = new Date( date2 );
       //  document.write("<br>"+date2);

         var res = Math.abs(date1 - date2) / 1000;
         
         // get total days between two dates
         // var days = Math.floor(res / 86400);
         // alert("<br>Difference (Days): "+days);                        
         
         // get hours        
         // var hours = Math.floor(res / 3600) % 24;        
         // alert("<br>Difference (Hours): "+hours);  
         
         // get minutes
         var minutes = Math.floor(res / 60) % 60;
        // alert ("<br>Difference (Minutes): "+minutes);  
     
          return minutes
         // get seconds
         //var seconds = res % 60;
         //alert("<br>Difference (Seconds): "+seconds);  
}

function setLocationCookieTime(){


	var date1=getDateTimeStamp("D"); 
     document.cookie="setLocationTime=" + date1;
     var setLocationTime = getCookie("setLocationTime");
}
     

function unsetLocationCookieTime(){

      document.cookie="setLocationTime=" + '';
     // var setLocationTime = getCookie("setLocationTime");
	 
}


// Moved function click to file resource/views/location.blade.php
 function dataSave(){

            var flag = true;

            if(flag){

                if($("#pac-input").val()){

                   setLocationCookieTime(); // code add by saurabh to set the location set start time

                 }else{

                  console.log("Map text box value not exist");
                 }
                $("#form").submit();
            } else{
                alert("Please fill some value");    
                e.preventDefault();
            }
        }


 function locationSave(url){

             unsetLocationCookieTime();
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

            $.get(url, { lat: latitude, lng : longitude}, function(returnedData){
                console.log(returnedData["data"]);
                location.reload();
            });
            console.log(latitude);
            console.log(longitude);
        };

    function makeRedirection(link){
            window.location.href = link;
     }
// End of Moved function click to file resource/views/location.blade.php