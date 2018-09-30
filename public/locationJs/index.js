var tz = moment.tz.guess();
	$.get(setTimezone,{'tz':tz});

	// $.get("{{url('writeLogs')}}",{'log':'index page'});

	  $("#cancel-popup").click(function () {
      $('#login-popup').hide();
      var extraclass = document.body;
	  extraclass.classList.add("disableClass");
	  window.location.replace(replace_url);
    });

	$(".ordersec").click(function(){
		$("#order-popup").toggleClass("hide-popup");
	});

	var list = Array();
	var totalCount = 0;

	var curDate = new Date();
	curTimezoneOffset = curDate.getTimezoneOffset();

	function makeRedirection(link){
		window.location.href = link;
	}


	$(document).on("scrollstop", function (e) {
		var tempCount = 10;
    	var activePage = $.mobile.pageContainer.pagecontainer("getActivePage"),
        screenHeight = $.mobile.getScreenHeight(),
        contentHeight = $(".ui-content", activePage).outerHeight(),
        header = $(".ui-header", activePage).outerHeight() - 1,
        scrolled = $(window).scrollTop(),
        footer = $(".ui-footer", activePage).outerHeight() - 1,
        scrollEnd = contentHeight - screenHeight + header + footer;

    	$(".ui-btn-left", activePage).text("Scrolled: " + scrolled);
    	//$(".ui-btn-right", activePage).text("ScrollEnd: " + scrollEnd);
    	
    	//if in future this page will get it, then add this condition in and in below if activePage[0].id == "home" 
    	if (scrolled >= scrollEnd) {
	        //console.log(list);
	        $.mobile.loading("show", {
	        text: "loading more..",
	        textVisible: true,
	        theme: "b"
	    	});
	    	setTimeout(function () {
		        addMore(tempCount);
		        tempCount += 10;
		        $.mobile.loading("hide");
		    },500);
    	}
	});
    //set geo location data
	function checkDistance(latt,lngg)
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
	function  addMore(len){
		var liItem = "";
    	var url = restrolinkUrl;
    	var limit = 0;
    	var countCheck = 1;
		if(totalCount > 10){
			limit = 10;
			totalCount -= 10;
		} else if(totalCount<=0){
			return;
		} else{
			limit = totalCount;
			totalCount -= totalCount;
		}


		for (var i=len;i<len + 10;i++){

			if(countCheck>limit){
				break;
			}
			if(checkTime(temp[i]["store_open_close_day_time"])){
				
				liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
				liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+list[i]['store_id']+" data-ajax='false'>";
				liItem += "<img src="+"'"+temp[i]["store_image"]+ "' onerror='this.src=\""+"{{url('images/placeholder-image.png')}}\""+"'" +">";
				liItem += "<h2>"+list[i]["store_name"]+"</h2>";
				liItem += "<p>";
				
				for (var j=0;j<list[i]["products"].length;j++){
					if(j <= 1){
						liItem += list[i]["products"][j]["product_name"];
					}   
					if(list[i]["products"].length > 1 && j <= 1){
						liItem += ",&nbsp;";
					}
				}

				if(list[i]["products"].length > 1){
					liItem += "&nbsp;&more";
				} 
			liItem += "</p>";
				liItem += "<div class='ui-li-count ui-body-inherit'>";
				liItem += "<span>"+list[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

				liItem += "</div></a></li>";

			}
			countCheck++;
		}
		$("#companyDetailContianer").append(liItem);	
	}

	function add(){
		var d = new Date();
		//console.log(d);
		$("#browserCurrentTime").val(d);
		if(resExist==0){
			resExist=1;
			$.get(latLongUrl, { lat: getCookie("latitude"), lng : getCookie("longitude"), currentdateTime : d, browserVersion : getCookie("browserVersion")}, 
		    	function(returnedData){
		    		loc_flag=4;
		    		$('#login-popup').hide();
	    			$("#loading-img").hide();
		    		$("#overlay").hide();

			    	var count = 10;
			    	//console.log(returnedData["data"]);
			    	var url = "{{url('restro-menu-list/')}}";
					var temp = returnedData["data"];
					list = temp;
					var liItem = "";
					if(temp.length != 0){
						totalCount = temp.length;
						if(temp.length < count){
							count = temp.length
						}
						totalCount -= 10;

						for (var i=0;i<count;i++){
							if(checkTime(temp[i]["store_open_close_day_time"])){

								console.log(temp[i]);

								liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
								liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+temp[i]['store_id']+" data-ajax='false'>";
								liItem += "<img src="+"'"+temp[i]["store_image"]+ "' onerror='this.src=\""+"{{url('images/placeholder-image.png')}}\""+"'" +">";
								liItem += "<h2>"+temp[i]["store_name"]+"</h2>";
								liItem += "<p>";
								
								for (var j=0;j<temp[i]["products"].length;j++){
									if(j <= 1){
										liItem += temp[i]["products"][j]["product_name"];
									}   
									if(temp[i]["products"].length > 1 && j <= 1){
										liItem += ",&nbsp;";
									}
								}

								if(temp[i]["products"].length > 1){
									liItem += "&nbsp;&more";
								} 
								liItem += "</p>";
								liItem += "<div class='ui-li-count ui-body-inherit'>";
								liItem += "<span>"+temp[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

								liItem += "</div></a></li>";
							}
						}
					}else{
						liItem += "<div class='table-content'>";
						liItem += "<p>";
						liItem += '';
						liItem += "</p>";
						liItem += "</div>";
					}
			  		$("#companyDetailContianer").append(liItem);
				});
		}
	}



	$(function(){
    	$("#overlay").show();
    	$("#loading-img").show();

    	$(".icon-eat-inactive").click(function(){
    		eatActive = $(".icon-eat-active");
    		eatInactive = $(".icon-eat-inactive");

    		eatActive.removeClass('icon-eat-active');
    		eatActive.addClass('icon-eat-inactive');

    		eatInactive.removeClass('icon-eat-inactive');
    		eatInactive.addClass('icon-eat-active');
    	});

		var extraclass = document.body;

	setTimeout(getPosAgain,3000);

	function getPosAgain(){
		if(loc_flag==0){
			getPos();
		}
	}

	getPos();

	var d = new Date();

	$("#browserCurrentTime").val(d);

	$.get(checkloginUrl, 
	    function(returnedData){
	    	var temp = returnedData["data"];
	    	if(temp){
	    		 document.cookie="userId=" + temp;
   	    		 localStorage.setItem("userId", temp);
	    	}else{
	    		if(localStorage.getItem("userId")){
	    			console.log('logoutloginId='+localStorage.getItem("userId"));
	    			$.get(userLoginUrl, { usetId : localStorage.getItem("userId")}, 
	    				function(returnedData){
	    					// console.log(returnedData["data"]);
	    					// location.reload();
	    				});
	    		}else{
	    			// console.log('logout');
	    		}
	    	}
	    });

	});


	function checkTime($time){
		var d = new Date();
		var dd = (d.toString()).split(' ');
		var currentTime = dd[4];
		var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
		var todayDay = days[d.getDay()];
		var time = $time;
		var day = time.split(' :: ')
		var checkday = time.split(',')
		if(day[0] == 'All'){
			var timeSplit = day[1].split(' to ');
			var openTime = timeSplit[0];
			var closeTime = timeSplit[1];
			if(openTime < currentTime && closeTime > currentTime){
				return true;
			}else{
				return false;
			}
		}else{
			if(day.length == 2){
				if(day[0] == todayDay){
					var timeSplit = day[1].split(' to ');
					var openTime = timeSplit[0];
					var closeTime = timeSplit[1];
					if(openTime < currentTime && closeTime > currentTime){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				for(i=0;i<checkday.length;i++){
					var getDay = checkday[i].split(' :: ');
					if(getDay[0] == todayDay){
						var timeSplit = getDay[1].split(' to ');
						var openTime = timeSplit[0];
						var closeTime = timeSplit[1];
						if(openTime < currentTime && closeTime > currentTime){
							return true;
						}else{
							return false;
						}
					}
				}
			}
		}
		return false;
	}

	function getPos(){
		if (typeof loc_lat === "undefined" || loc_lat == "") {		
		navigator.geolocation.getCurrentPosition(function(position) { 
			loc_flag=1;
		    document.cookie="latitude=" + position.coords.latitude;
		    document.cookie="longitude=" + position.coords.longitude;

		    loc_lat = position.coords.latitude;
		    loc_lng = position.coords.longitude;
            checkDistance(loc_lat,loc_lng)
		    var extraclass = document.body;
			extraclass.classList.remove('disableClass');
			//location.reload ();
			// $.get("{{url('writeLogs')}}",{'log':'location 1'});
			add();
		},function(error){
				// $.get("{{url('writeLogs')}}",{'log':'cookie ' + getCookie("latitude")});

			if (typeof loc_lat === "undefined" || loc_lat == "") {
				if (!getCookie("latitude")){
		    		$("#loading-img").hide();
		    		$("#overlay").hide();
				    $('.login-inner-section a').attr('href','javascript:void(0)');
	 			    $('#login-popup').show();	
					// $.get("{{url('writeLogs')}}",{'log':'location 2 ' + error + ' ' + loc_lat});
				} else {
					loc_flag=2;
				    document.cookie="latitude=" + getCookie("latitude");
				    document.cookie="longitude=" + getCookie("longitude");		
					// $.get("{{url('writeLogs')}}",{'log':'location 3'});
					add();					
				}
			}else{
				loc_flag=3;
			    document.cookie="latitude=" + loc_lat;
			    document.cookie="longitude=" + loc_lng;		
				// $.get("{{url('writeLogs')}}",{'log':'location 4'});
				add();
			} 
		},{maximumAge:0,timeout:5000});
		}else{
				loc_flag=5;
			    document.cookie="latitude=" + loc_lat;
			    document.cookie="longitude=" + loc_lng;	
				// $.get("{{url('writeLogs')}}",{'log':'location 5'});
				add();			    
		}
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

	if(getCookie("browser") == 'Safari' && count == 1){
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
	
	
	