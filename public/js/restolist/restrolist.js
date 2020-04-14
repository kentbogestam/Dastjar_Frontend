	
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
	    			/*$.get("{{url('userLogin')}}", { usetId : localStorage.getItem("userId")}, 
	    				function(returnedData){
	    					// console.log(returnedData["data"]);
	    					// location.reload();
	    				}
	    			);*/
	    		}else{
	    			// console.log('logout');
	    		}
	    	}
		}
	);
}

function getPos(urlLatlng,urlMenulist,noImageUrl){
	if (typeof loc_lat === "undefined" || loc_lat == "") {
		if(ios && (!standalone && !safari))
		{
			requestGeoAddressToIosNative('getPos');
		}
		else
		{
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
		 			    $('#login-popup').modal("show");
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
		}
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
	var d = new Date();
	$("#browserCurrentTime").val(d);
	d = encodeURIComponent(d);
	
	if(resExist==0){
		resExist=1;

		$.ajax({
			url: urlLatlng+'?lat='+getCookie("latitude")+'&lng='+getCookie("longitude")+'&currentdateTime='+d+'&browserVersion='+getCookie("browserVersion"),
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
				let isStoreOpen;
				list = temp;
				var liItem = htmlStoreRow = "";
				var liItem1 = liItem2 = "";
				if(temp.length != 0){
					if(temp.length < count){
						count = temp.length;
					}

					for (var i=0;i<count;i++)
					{
						storeClass = storeImage = subStr = ancClose = ancOpen = ''; 
						isStoreOpen = true;

						// 
						if( temp[i].heartbeat && temp[i].heartbeat >= 2 && checkTime(temp[i]["store_open_close_day_time"]) == true )
						{
							isStoreOpen = "nolive";
							storeClass = ' store-closed not-live';

							// Code added to display tagline of restaurant	
							if(temp[i]["tagline"]){
								subStr = '<p class="info-hotel">'+temp[i]["tagline"]+'</p>';
							}
							// End of code added to dispaly tagline of restaurant

							if(!subStr.length)
							{
								subStr += '<br>'
							}

							subStr += '<span class="label label-default">'+returnedData['storeNotLive']+'</span>';
						}
						else if( checkTime(temp[i]["store_open_close_day_time"]) )
						{
							//open and online restaurant
							ancClose = '<a href="'+url+'/'+temp[i]['store_id']+'">';
							ancOpen = '</a>';

							// Code added to display tagline of restaurant	
							if(temp[i]["tagline"]){
								subStr = '<p class="info-hotel">'+temp[i]["tagline"]+'</p>';
							}
							// End of code added to dispaly tagline of restaurant
						}
						else
						{
							//closed restaunt
							isStoreOpen = false;
							storeClass = ' store-closed';

							// Code added to display tagline of restaurant	
							if(temp[i]["tagline"]){
								subStr = '<p class="info-hotel">'+temp[i]["tagline"]+'</p>';
							}

							if(!subStr.length)
							{
								subStr += '<br>'
							}

							// Code added to display open close time of restaurant
							time=temp[i]["store_open_close_day_time"];
							open_close_time=time.split(' :: ')[1].split(' to ');
							open_time=open_close_time[0].split(':');
							close_time=open_close_time[1].split(':');	
							subStr += '<span class="label label-default">'+returnedData['StoreOpenCloseTimeText']+ ' '+open_time[0]+':'+open_time[1]+'-'+close_time[0]+':'+close_time[1]+'</span>';
						}

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
						
						// If found discount
						if(isFindDiscount)
						{
							storeClass = storeClass+' li-has-discount';

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

						htmlStoreRow = '<div class="row-hotel'+storeClass+'">'+ ancClose +
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

						// 
						if(isStoreOpen == true)
						{
							liItem += htmlStoreRow;
						}
						else if(isStoreOpen=="nolive")
						{
							liItem2 += htmlStoreRow;
						}
						else
						{
							liItem1 += htmlStoreRow;
						}

						totalCount= i;
					}

					liItem = liItem + liItem2 + liItem1;
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
				liItem += "<img src="+list[i]['store_large_image']+" onerror=this.src='"+noImageUrl+"'>";
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
      $('#login-popup').modal("hide");
      var extraclass = document.body;
	  extraclass.classList.add("disableClass");
	  window.location.replace(url);
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