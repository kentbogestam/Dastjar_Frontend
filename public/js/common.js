 var getUrl = window.location;
 var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]; //for local testing
 //var baseUrl =getUrl .protocol + "//" + getUrl.host ; // for live testing
 
setInterval(function() {
    getCurrentCoordinates();
}, 1200000); // Check the position afer 20 min and reset the longitude and latitude

$(document).ready(function() {
    checkTimeAfterLocationSet();

    // Remove alert
    $('.alert-dismissible .close').on('click', function() {
        $(this).closest('.ui-bar-a').remove();
    })
});

function reloadRestaurantList(){
    var url = window.location.href;
    //var pathname = window.location.pathname 

    var pieces = url.split("/");

    $value=pieces[pieces.length-1];

    if($value=='eat-now' || $value=='eat-later' || $value=='' ){
        location.reload();
    }
}

// Reset 'co-ordinate' with currect after 20 minutes
function getCurrentCoordinates(){
    var flag=checkTimeAfterLocationSet(); // problem area function called the poisiton itself

    if(flag==false){
        if(ios && (!standalone && !safari))
        {
            requestGeoAddressToIosNative('getCurrentCoordinates');
        }
        else
        {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.cookie="latitude=" + position.coords.latitude;
                document.cookie="longitude=" + position.coords.longitude;

                $.ajax({
                    url: BASE_URL+"/update-location",
                    type: "GET",
                    data: {lat : position.coords.latitude, long : position.coords.longitude},
                    dataType: "json"
                });

                reloadRestaurantList();
            },function(error){
                if (typeof lat === "undefined") {
                    // $('.login-inner-section a').attr('href','javascript:void(0)');
                    // $('#login-popup').modal("show");
                } else {
                    // document.cookie="latitude=" + lat;
                    // document.cookie="longitude=" + lng;          
                }
            });
        }
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
  
        if (minutes > 10){
            //setCurrentCoordinates();
            unsetLocationCookieTime();
            return false;
        }
        else{
            return true;
        }
    }
    else{
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
     
// Remove cookie 'setLocationTime' that used to reset location after x minute
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

// Reset location to current, cookie and session with current coordinates from 'location'
function locationSave(url){
    // Clear watch first so it can get the current position
    navigator.geolocation.clearWatch(watchPosition);

    // Check for Geolocation API permissions  
    navigator.geolocation.getCurrentPosition(function(position) {
        // Update Cookie
        document.cookie="latitude=" + position.coords.latitude;
        document.cookie="longitude=" + position.coords.longitude;
        
        // Update Session
        var latitude  = getCookie("latitude");
        var longitude = getCookie("longitude");

        $.get(url, { lat: latitude, lng : longitude}, function(returnedData){
            console.log(returnedData["data"]);
            unsetLocationCookieTime();
            location.reload();
        });
    },function(error){
       $('.login-inner-section a').attr('href','javascript:void(0)');
       $('#login-popup').modal("show");
    });
}

function makeRedirection(link){
    
     window.location.href = link;
}

// End of Moved function click to file resource/views/location.blade.php

function checkFormsubmit(e){

 var code = (e.keyCode ? e.keyCode : e.which);
  if(code == 13) { //Enter keycode
      setLocationCookieTime();
  }
}

function orderPopup(){
  $("#order-popup").toggleClass("hide-popup");
 }

// Return distance between two coordinates using 'haversine formula'
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

// Return the object index by search column name in array
function searchIndexFromMultiDimArray(columnName, columnValue, arr) {
    for (var key in arr) {
        row = JSON.parse(arr[key]);
        if(row[columnName] === columnValue) {
            return key;
            break;
        }
    }

    return 'false';
}