 var loc_lat;
 var loc_lng;
 $(document).ready(function () {


    $("#overlay").show();
	  $("#loading-img").show();

});

function iconEatInactive(){

	eatActive = $(".icon-eat-active");
    eatInactive = $(".icon-eat-inactive");

	eatActive.removeClass('icon-eat-active');
	eatActive.addClass('icon-eat-inactive');

	eatInactive.removeClass('icon-eat-inactive');
	eatInactive.addClass('icon-eat-active');
}


function orderPopup(){
	
 	$("#order-popup").toggleClass("hide-popup");
 }

  function setLngLat(lat,lng){

   loc_lat = lat;
   loc_lng = lng;

  }

  // Browser setting function used for browsershortcut fro apple
  function browserPhoneSetting(){

    var count = getCookie("iphonePopupcount") + getCookie("iphonePopupcountIncrease");
	  var IphoneVersion;
    var deviceDetection = function () { 
    var osVersion, 
    device, 
    deviceType, 
    userAgent, 
    isSmartphoneOrTablet; 

    device = (navigator.userAgent).match(/Android|iPhone|iPad|iPod/i); 

    if ( /Android/i.test(device) ) { 
        if ( !/mobile/i.test(navigator.userAgent) ) { 
            deviceType = 'tablet'; 
        } else { 
            deviceType = 'phone'; 
        } 

        osVersion = (navigator.userAgent).match(/Android\s+([\d\.]+)/i); 
        osVersion = osVersion[0]; 
        osVersion = osVersion.replace('Android ', ''); 

    } else if ( /iPhone/i.test(device) ) { 
        deviceType = 'phone'; 
        osVersion = (navigator.userAgent).match(/OS\s+([\d\_]+)/i); 
        osVersion = osVersion[0]; 
        osVersion = osVersion.replace(/_/g, '.'); 
        osVersion = osVersion.replace('OS ', ''); 

		document.cookie="osVersion=" + osVersion;		

	    if(osVersion >= 10){
	    	$('.footer').css({'padding-top':'10px', 'padding-bottom':'10px'});
	    }			

    } else if ( /iPad/i.test(device) ) { 
        deviceType = 'tablet'; 
        osVersion = (navigator.userAgent).match(/OS\s+([\d\_]+)/i); 
        osVersion = osVersion[0]; 
        osVersion = osVersion.replace(/_/g, '.'); 
        osVersion = osVersion.replace('OS ', ''); 
    } 
    isSmartphoneOrTablet = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent); 
    userAgent = navigator.userAgent; 
    IphoneVersion = osVersion;
    return { 'isSmartphoneOrTablet': isSmartphoneOrTablet, 
             'device': device, 
             'osVersion': osVersion, 
             'userAgent': userAgent, 
             'deviceType': deviceType 
            }; 
    }();
    //console.log('IphoneVersion='+IphoneVersion);
alert(count);
	if(getCookie("browser") == 'Safari' && count == 1 ){
		document.cookie="iphonePopupcountIncrease=" + 2;
		var ath = addToHomescreen({
		    debug: 'ios',           // activate debug mode in ios emulation
		    skipFirstVisit: false,	// show at first access
		    startDelay: 0,          // display the message right away
		    lifespan: 0,            // do not automatically kill the call out
		    displayPace: 0,         // do not obey the display pace
		    privateModeOverride: true,	// show the message in private mode
		    maxDisplayCount: 0      // do not obey the max display count
		});
	}
  }
