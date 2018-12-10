	
 var loc_lat;
 var loc_lng;
 var loc_flag=0;
 var resExist=0;
 var temp = new Array();
 var list = Array();
 var totalCount=null;

 $(document).ready(function () {

    navigator.sayswho= (function(){
	    var ua= navigator.userAgent, tem, 
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(/trident/i.test(M[1])){
	        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	        return 'IE '+(tem[1] || '');
	    }
	    if(M[1]=== 'Chrome'){
	        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
	        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
	    }
	    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);

	    //console.log("browserVersion=" + M.join(' '));
	    var browserVersion = M.join(' ');
	    var getBrowser = browserVersion.split(" ");
	    var browser = getBrowser[0];
	    document.cookie="iphonePopupcount=" + 1;
	    document.cookie="browser=" + browser;
	    document.cookie="browserVersion=" + M.join(' ');
	    var string = M.join(' ');
	    string = string.split(" ");
	    if(string[0] == 'Safari'){
	     $('#facebook-hide').hide();
	     $('#google-hide').hide();
	    }
	})();
	      
	     registerSwjs();

	     var d = new Date();
	     $("#browserCurrentTime").val(d);
	     var extraclass = document.body;
	     

 });

function checkUserLogin(url){

    $.get(url, 
	    function(returnedData){
	    	var temp = returnedData["data"];
	    	if(temp){
	    		 document.cookie="userId=" + temp;
   	    		 localStorage.setItem("userId", temp);
	    	}else{
	    		if(localStorage.getItem("userId")){
	    			console.log('logoutloginId='+localStorage.getItem("userId"));
	    			$.get("{{url('userLogin')}}", { usetId : localStorage.getItem("userId")}, 
	    				function(returnedData){
	    					// console.log(returnedData["data"]);
	    					// location.reload();
	    				});
	    		}else{
	    			// console.log('logout');
	    		}
	    	}
	    });

  }

  function setLngLat(lat,lng){

   loc_lat = lat;
   loc_lng = lng;

  }

function getPos(urlLatlng,urlMenulist,noImageUrl){
			
		if (typeof loc_lat === "undefined" || loc_lat == "") {	

		   navigator.geolocation.getCurrentPosition(function(position) { 

			loc_flag=1;
		    document.cookie="latitude="  + position.coords.latitude;
		    document.cookie="longitude=" + position.coords.longitude;

		    loc_lat = position.coords.latitude;
		    loc_lng = position.coords.longitude;

		    var extraclass = document.body;
			extraclass.classList.remove('disableClass');
			
			add(urlLatlng,urlMenulist,noImageUrl);

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
					add(urlLatlng,urlMenulist,noImageUrl);					
				}
			}else{
				loc_flag=3;
			    document.cookie="latitude=" + loc_lat;
			    document.cookie="longitude=" + loc_lng;		
				// $.get("{{url('writeLogs')}}",{'log':'location 4'});
				add(urlLatlng,urlMenulist,noImageUrl);
			} 
			},{maximumAge:0,timeout:5000});
			}else{
					loc_flag=5;
				    document.cookie="latitude=" + loc_lat;
				    document.cookie="longitude=" + loc_lng;	
					// $.get("{{url('writeLogs')}}",{'log':'location 5'});
					add(urlLatlng,urlMenulist,noImageUrl);		    
			}
	} 


function add(urlLatlng,urlMenulist,noImageUrl){
	
	var d = new Date();
	//console.log(d);
	$("#browserCurrentTime").val(d);
	if(resExist==0){
		resExist=1;
		$.get(urlLatlng, { lat: getCookie("latitude"), lng : getCookie("longitude"), currentdateTime : d, browserVersion : getCookie("browserVersion")}, 
    	function(returnedData){
    		loc_flag=4;
    		$('#login-popup').hide();
			$("#loading-img").hide();
    		$("#overlay").hide();

	    	var count = 10;
	    	//console.log(returnedData["data"]);
	    	var url = urlMenulist;
			var temp = returnedData["data"];

			list = temp;
			var liItem = "";
			if(temp.length != 0){
				
			if(temp.length < count){
				count = temp.length;
			}
					

		for (var i=0;i<count;i++){
		if(checkTime(temp[i]["store_open_close_day_time"])){


			liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
			liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+temp[i]['store_id']+" data-ajax='false'>";
			liItem += "<img src="+temp[i]['store_image']+" onerror=this.src='"+noImageUrl+"'>";

			liItem += "<h2>"+temp[i]["store_name"]+"</h2>";
			liItem += "<p>";

          	// Code added to display tagline of restaurant	
			if(temp[i]["tagline"]){
             liItem += temp[i]["tagline"];

			}
		// End of code added to dispaly tagline of restaurant

				liItem += "</p>";
				liItem += "<div class='ui-li-count ui-body-inherit'>";
				liItem += "<span>"+temp[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

				liItem += "</div></a></li>";

				totalCount= i;
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

function  addMore(len,url,noImageUrl){

		var liItem = "";
    	var url = url;
    	var limit = 0;
    	var countCheck = 1;

		if(totalCount < list.length){
			totalCount =totalCount+1
		
		for (var i=totalCount;i<=len-1;i++){

			if(checkTime(list[i]["store_open_close_day_time"])){
				
				liItem += "<li class='ui-li-has-count ui-li-has-thumb ui-first-child'>";
				liItem += "<a class = 'ui-btn ui-btn-icon-right ui-icon-carat-r' href="+url+"/"+list[i]['store_id']+" data-ajax='false'>";
				liItem += "<img src="+list[i]['store_image']+" onerror=this.src='"+noImageUrl+"'>";
				liItem += "<h2>"+list[i]["store_name"]+"</h2>";
				liItem += "<p>";
				
				// Code added to display tagline of restaurant	
			      if(list[i]["tagline"]){
                   liItem += list[i]["tagline"];

			       }
			// End of code added to dispaly tagline of restaurant 
			    liItem += "</p>";
				liItem += "<div class='ui-li-count ui-body-inherit'>";
				liItem += "<span>"+list[i]["distance"].toFixed(2)+ "&nbsp;Km" + "</span>";

				liItem += "</div></a></li>";

			}
			//countCheck++;
			totalCount=i;
		}

		$("#companyDetailContianer").append(liItem);	
	  }
}




function getPosAgain(){
		if(loc_flag==0){
			getPos();
		}
	}

function getTimeZone(url){
 	var tz = moment.tz.guess();
	$.get(url,{'tz':tz});
	 
 }

 function closeLocationPopup(url){
      $('#login-popup').hide();
      var extraclass = document.body;
	  extraclass.classList.add("disableClass");
	  window.location.replace(url);
 }

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

function onScroll(url){

	    var tempCount = list.length;
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
		        addMore(tempCount,url,noImageUrl);
		        tempCount += 10;
		        $.mobile.loading("hide");
		    },500);
    	}

}