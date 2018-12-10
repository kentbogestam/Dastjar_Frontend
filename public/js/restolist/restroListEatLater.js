 var loc_lat;
 var loc_lng;
 var loc_flag=0;
 var resExist=0;
 var temp = new Array();
 var list = Array();
 var totalCount=null;

 function add(urlEatLater,urlMenulist,noImageUrl,sessionTime){
	
	var d = new Date();
	//console.log(d);
	$("#browserCurrentTime").val(d);
	if(resExist==0){
		resExist=1;
		$.get(urlEatLater, { lat: getCookie("latitude"), lng : getCookie("longitude"), currentdateTime : d, browserVersion : getCookie("browserVersion")}, 
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
		if(checkTime(temp[i]["store_open_close_day_time"],sessionTime)){


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


function  addMore(len,url,noImageUrl,sessionTime){

		var liItem = "";
    	var url = url;
    	var limit = 0;
    	var countCheck = 1;

		if(totalCount < list.length){
			totalCount =totalCount+1
		
		for (var i=totalCount;i<=len-1;i++){

			if(checkTime(list[i]["store_open_close_day_time"],sessionTime)){
				
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



 function checkTime($time,sessionTime){
	 	if(sessionTime){	 
	 		var d = new Date(sessionTime);
			var dd = (d.toString()).split(' ');
			var currentTime = dd[4];
			var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
			var todayDay = days[d.getDay()];

	 	}else{
			var d = new Date();
			var dd = (d.toString()).split(' ');
			var currentTime = dd[4];
			var days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
			var todayDay = days[d.getDay()];
	 	}


		var time = $time;
		var day = time.split(' :: ')
		var checkday = time.split(',')
		if(day[0] == 'All'){
			var timeSplit = day[1].split(' to ');
			var openTime = timeSplit[0];
			var closeTime = timeSplit[1];

			// console.log('currentTime '+currentTime);
			// console.log('openTime ' + openTime);
			// console.log('closeTime ' + closeTime);
			// console.log('todayDay '+todayDay);
			// console.log('$time '+$time);

			if(openTime <= currentTime && closeTime >= currentTime){
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
					if(openTime <= currentTime && closeTime >= currentTime){
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
						if(openTime <= currentTime && closeTime >= currentTime){
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