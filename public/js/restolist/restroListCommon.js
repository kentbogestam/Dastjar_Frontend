 
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
