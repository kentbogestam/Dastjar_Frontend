 var loc_lat;
 var loc_lng;
 var loc_flag=0;
 var resExist=0;
 var temp = new Array();
 var list = Array();
 var totalCount=null;

function add(urlEatLater,urlMenulist,noImageUrl,sessionTime){
	var d = new Date();
	$("#browserCurrentTime").val(d);
	d = encodeURIComponent(d);

	if(resExist==0){
		resExist=1;

		$.ajax({
			url: urlEatLater+'?lat='+getCookie("latitude")+'&lng='+getCookie("longitude")+'&currentdateTime='+d+'&browserVersion='+getCookie("browserVersion"),
			async: false,
			success: function(returnedData) {
				loc_flag=4;
				$('#login-popup').modal("hide");
				$("#loading-img").hide();
	    		$("#overlay").hide();

		    	var count = 10;
		    	var url = urlMenulist;
				var temp = returnedData["data"];
				var customerDiscount = (returnedData['customerDiscount']) ? returnedData['customerDiscount'] : {};
				var discountIndex;
				var isFindDiscount;
				let storeClass;
				let storeImage;
				list = temp;
				var liItem = "";
				var liItem1 = "";
				let isStoreOpen;
				list = temp;
				var liItem = htmlStoreRow = ancClose = ancOpen = "";
				if(temp.length != 0)
				{
					if(temp.length < count)
					{
						count = temp.length;
					}
					for (var i=0;i<count;i++)
					{
						storeClass = storeImage = subStr= ancClose=ancOpen = ""; 
						isStoreOpen = true;
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
						if(checkTime(temp[i]["store_open_close_day_time_catering"],sessionTime)){
							ancClose = '<a href="'+url+'/'+temp[i]['store_id']+'">';
							ancOpen = '</a>';
							// Code added to display tagline of restaurant
							subStr = '';
							if(temp[i]["tagline"]){
								subStr = '<p class="info-hotel">'+temp[i]["tagline"]+'</p>';
							}

							// End of code added to dispaly tagline of restaurant
						}
						else
						{
							isStoreOpen = false;
							storeClass = ' store-closed';

							// Code added to display open close time of restaurant
							time=temp[i]["store_open_close_day_time_catering"];
							open_close_time=time.split(' :: ')[1].split(' to ');
							open_time=open_close_time[0].split(':');
							close_time=open_close_time[1].split(':');	
							subStr = '';
							subStr = '<p class="info-hotel">'+returnedData['StoreOpenCloseTimeText']+ ' '+open_time[0]+':'+open_time[1]+'-'+close_time[0]+':'+close_time[1]+'</p>';
							// Code added to display open close time of restaurant
						}
						
						// If found discount
						if(isFindDiscount)
						{
							storeClass = ' li-has-discount';

							if( customerDiscount[discountIndex] )
							{
								customerDiscountParsed = JSON.parse(customerDiscount[discountIndex]);
								subStr += '<p class="text-success"><span>'+customerDiscountParsed.discount_value+'% OFF</span></p>';
							}
						}

						// Check if image URL is not null and valid
						if( temp[i]['store_large_image'] && (temp[i]['store_large_image'].indexOf('.jpg') != -1 || temp[i]['store_large_image'].indexOf('.jpeg') != -1 || temp[i]['store_large_image'].indexOf('.png') != -1) )
						{
							storeImage = '<img src="'+temp[i]['store_large_image']+'" alt="" width="120">';
						}

						htmlStoreRow = '<div class="row-hotel'+storeClass+'">'+
							ancClose+
								'<div class="col-sm-8 col-xs-8">'+
									'<div class="hotel-icon">'+
										'<div class="hotel-icon-none">'+storeImage+'</div>'+
										'<div class="title-with-des">'+
											'<p>'+temp[i]["store_name"]+'</p>'+subStr+
										'</div>'+
									'</div>'+
								'</div>'+
								'<div class="col-sm-4 col-xs-4">'+
									'<div class="hotel-distance">'+
										temp[i]["distance"].toFixed(1)+' KM. <i class="fa fa-angle-right"></i>'+
									'</div>'+
								'</div>'+
							ancOpen+
							'<div class="clearfix"></div>'+
						'</div>';
						if(isStoreOpen)
						{
							liItem += htmlStoreRow;
						}
						else
						{
							liItem1 += htmlStoreRow;
						}
						totalCount= i;
					}

					liItem=liItem + liItem1;
				}else{
					liItem += "<div class='col-sm-8 col-xs-8'><p>"+returnedData['restaurantStatusMsg']+"</p></div>";
				}

				if(!liItem.length)
				{
					liItem += "<div class='col-sm-8 col-xs-8'><p>"+returnedData['restaurantStatusMsg']+"</p></div>";
				}

				$("#companyDetailContianer").append(liItem);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				window.location.reload();
			}
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

			if(checkTime(list[i]["store_open_close_day_time_catering"],sessionTime)){
				
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

		if(openTime > closeTime)
		{
			// 
			if(d.getHours() >= parseInt(openTime) && d.getHours() <= 23)
			{
				var todayDate = new Date(sessionTime);
				todayDate = (todayDate.getMonth() + 1)+'/'+todayDate.getDate()+'/'+todayDate.getFullYear();
				var tomorrowDate = new Date(sessionTime);
				tomorrowDate.setDate(tomorrowDate.getDate() + 1);
				tomorrowDate = (tomorrowDate.getMonth() + 1)+'/'+tomorrowDate.getDate()+'/'+tomorrowDate.getFullYear();
			}
			else
			{
				var todayDate = new Date(sessionTime);
				todayDate = (todayDate.getMonth() + 1)+'/'+(todayDate.getDate()-1)+'/'+todayDate.getFullYear();
				var tomorrowDate = new Date(sessionTime);
				tomorrowDate.setDate(tomorrowDate.getDate());
				tomorrowDate = (tomorrowDate.getMonth() + 1)+'/'+tomorrowDate.getDate()+'/'+tomorrowDate.getFullYear();
			}

			var openDateTime = todayDate + ' ' + openTime;
			var closeDateTime = tomorrowDate + ' ' + closeTime;
			var openDateTime = new Date(openDateTime);
			var closeDateTime = new Date(closeDateTime);

			if(d.getTime() >= openDateTime.getTime() && d.getTime() < closeDateTime.getTime())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if(openTime <= currentTime && closeTime >= currentTime){
				return true;
			}else{
				return false;
			}
		}
	}else{
		if(day.length == 2){
			if(day[0] == todayDay){
				var timeSplit = day[1].split(' to ');
				var openTime = timeSplit[0];
				var closeTime = timeSplit[1];

				if(openTime > closeTime)
				{
					// 
					if(d.getHours() >= parseInt(openTime) && d.getHours() <= 23)
					{
						var todayDate = new Date(sessionTime);
						todayDate = (todayDate.getMonth() + 1)+'/'+todayDate.getDate()+'/'+todayDate.getFullYear();
						var tomorrowDate = new Date(sessionTime);
						tomorrowDate.setDate(tomorrowDate.getDate() + 1);
						tomorrowDate = (tomorrowDate.getMonth() + 1)+'/'+tomorrowDate.getDate()+'/'+tomorrowDate.getFullYear();
					}
					else
					{
						var todayDate = new Date(sessionTime);
						todayDate = (todayDate.getMonth() + 1)+'/'+(todayDate.getDate()-1)+'/'+todayDate.getFullYear();
						var tomorrowDate = new Date(sessionTime);
						tomorrowDate.setDate(tomorrowDate.getDate());
						tomorrowDate = (tomorrowDate.getMonth() + 1)+'/'+tomorrowDate.getDate()+'/'+tomorrowDate.getFullYear();
					}
					
					var openDateTime = todayDate + ' ' + openTime;
					var closeDateTime = tomorrowDate + ' ' + closeTime;
					var openDateTime = new Date(openDateTime);
					var closeDateTime = new Date(closeDateTime);

					if(d.getTime() >= openDateTime.getTime() && d.getTime() < closeDateTime.getTime())
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					if(openTime <= currentTime && closeTime >= currentTime){
						return true;
					}else{
						return false;
					}
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

	function onScroll(urlRestroMenuList,noImageUrl,sessionOrderDate){
        var tempCount = list.length
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
       // console.log(list);
        $.mobile.loading("show", {
        text: "loading more..",
        textVisible: true,
        theme: "b"
    	});
    	setTimeout(function () {
          addMore(tempCount,urlRestroMenuList,noImageUrl,sessionOrderDate);
         tempCount += 10;
         $.mobile.loading("hide");
     },500);
    	}


	}