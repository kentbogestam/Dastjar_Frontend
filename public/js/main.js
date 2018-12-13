/*!
* THEIA.JS
*
* Main scripts file. Contains theme functionals.
*
* Version 1.0
*/

/* 
-----------------------------------------------------------------------------------------------------------*/


/* ======================  start sticky header js =================== */     
/*    $(document).ready(function(){
        $(window).scroll(function(){
            var window_top = $(window).scrollTop() + 0; // the "12" should equal the margin-top value for nav.stick
            var div_top = $('#nav-anchor').offset().top;
                if (window_top > div_top) {
                    $('.nav_fixed').addClass('stick');
                } else {
                    $('.nav_fixed').removeClass('stick');
                }
        });
        
    });*/


/* ======================  end sticky header js =================== */


$(document).ready(function($) {

    "use strict";

    var aSearchClicked = false;
    
    jQuery(".sub-menu").hide();
    jQuery(".container").hide();
        
    if("ontouchstart" in document.documentElement){    
        jQuery(".menu-item-has-children").bind('touchstart touchon', function(event){
            event.preventDefault();
            jQuery(this).children(".sub-menu").toggleClass("active").toggle(350);
            return false;
        }).children(".sub-menu").children("li").bind('touchstart touchon', function(event) {
            window.location.href = jQuery(this).children("a").attr("href");
        });        
    }else{    
        jQuery(".menu-item-has-children").bind('click', function(event){
            event.preventDefault();
            jQuery(this).children(".sub-menu").toggleClass("active").toggle(350);
            return false;
        }).children(".sub-menu").children("li").bind('click', function(event) {
            window.location.href = jQuery(this).children("a").attr("href");
        });
    
    }
});



/* ============== qty box script =============== */
function incrementValue(id)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    document.getElementById(id).value = value;
}
function decrementValue(id)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    if(value>0){
        value--;
            document.getElementById(id).value = value;
    }

}

function setCurrentLatLong(){
   
   navigator.geolocation.getCurrentPosition(function(position) {

      document.cookie="latitude=" + position.coords.latitude;
      document.cookie="longitude=" + position.coords.longitude;
      console.log("in getCurrentCoordinates and updating current location ");
      //console.log(position.coords.latitude+"-------"+position.coords.longitude);
       $.ajax({
          url: baseUrl+"/public/update-location", // for local host testing
         // url: baseUrl+"/update-location", // for live testing
           type: "GET",
           data: {lat : position.coords.latitude, long : position.coords.longitude},
           dataType: "json"
       });

      // reloadRestaurantList();
  
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

kWindow = window;
