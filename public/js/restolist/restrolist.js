	
 // var loc_lat;
 // var loc_lng;
 var loc_flag=0;
 var resExist=0;
 var temp = new Array();
 var list = Array();
 var totalCount=null;

 $(document).ready(function () {
      
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
		}
	);
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

// Get restaurant list dynamically
function add(urlLatlng,urlMenulist,noImageUrl){
	// alert(loc_lat+', '+loc_lng+', '+getCookie("latitude")+', '+getCookie("longitude"));
	var d = new Date();
	$("#browserCurrentTime").val(d);
	
	if(resExist==0){
		resExist=1;

		$.ajax({
			url: urlLatlng+'?lat='+getCookie("latitude")+'&lng='+getCookie("longitude")+'&currentdateTime='+d+'&browserVersion='+getCookie("browserVersion"),
			async: false,
			success: function(returnedData) {
				loc_flag=4;
	    		$('#login-popup').hide();
				$("#loading-img").hide();
	    		$("#overlay").hide();

		    	var count = 10;
		    	var url = urlMenulist;
				var temp = returnedData["data"];
				var customerDiscount = (returnedData['customerDiscount']) ? returnedData['customerDiscount'] : {};
				var discountIndex;
				var isFindDiscount;

				list = temp;
				var liItem = "";
				if(temp.length != 0){
					if(temp.length < count){
						count = temp.length;
					}

					for (var i=0;i<count;i++){
						if(checkTime(temp[i]["store_open_close_day_time"])){
							// Check if discount is applying on restaurant
							isFindDiscount = false;
							if(Object.keys(customerDiscount).length)
							{
								// Search and get discount index
								discountIndex = searchIndexFromMultiDimArray('store_id', temp[i]['store_id'], customerDiscount);

								if(discountIndex != 'false')
								{
									isFindDiscount = true;
								}
							}

							// Code added to display tagline of restaurant	
							subStr = '';
							if(temp[i]["tagline"]){
								subStr = '<p>'+temp[i]["tagline"]+'</p>';
							}
							// End of code added to dispaly tagline of restaurant
							
							// If found discount
							if(isFindDiscount)
							{
								if( customerDiscount[discountIndex] )
								{
									customerDiscountParsed = JSON.parse(customerDiscount[discountIndex]);
									subStr += '<p><span>'+customerDiscountParsed.discount_value+'% OFF</span></p>';
								}

								liItem += '<li class="ui-li-has-count ui-li-has-thumb li-has-discount ui-first-child">'+
									'<a class="ui-btn ui-btn-icon-right ui-icon-carat-r" href="'+url+'/'+temp[i]['store_id']+'" data-ajax="false">'+
										'<img src="'+temp[i]['store_image']+'" onerror="this.src=\''+noImageUrl+'\'">'+
										'<h2>'+temp[i]["store_name"]+'</h2>'+subStr
										'<div class="ui-li-count ui-body-inherit">'+
											'<span>'+temp[i]["distance"].toFixed(1)+'&nbsp;Km</span>'+
										'</div>'+
									'</a>'+
								'</li>';
							}
							else
							{
								liItem += '<li class="ui-li-has-count ui-li-has-thumb ui-first-child">'+
									'<a class="ui-btn ui-btn-icon-right ui-icon-carat-r" href="'+url+'/'+temp[i]['store_id']+'" data-ajax="false">'+
										'<img src="'+temp[i]['store_image']+'" onerror="this.src=\''+noImageUrl+'\'">'+
										'<h2>'+temp[i]["store_name"]+'</h2>'+subStr
										'<div class="ui-li-count ui-body-inherit">'+
											'<span>'+temp[i]["distance"].toFixed(1)+'&nbsp;Km</span>'+
										'</div>'+
									'</a>'+
								'</li>';
							}

							totalCount= i;
						}
					}
				}else{
					liItem += "<div class='table-content'><p>"+returnedData['restaurantStatusMsg']+"</p></div>";
				}

				$("#companyDetailContianer").append(liItem);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log('Something went wrong!');
			}
		});
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