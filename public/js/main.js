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
    if(value<10){
        value++;
            document.getElementById(id).value = value;
    }
}
function decrementValue(id)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    if(value>1){
        value--;
            document.getElementById(id).value = value;
    }

}



/* ==================== content height ============================ */
 
$(document).ready(function($) {
    var headerHeight = $( '.header' ).height();
    var footerHeight = $( '.footer' ).height();
    var maincontent =$(window).height();
    var height = maincontent - (headerHeight + footerHeight);
    console.log(maincontent);
    console.log(headerHeight);
    console.log(footerHeight);


    $( '.content' ).height( height );
    var mql = window.matchMedia("(orientation: portrait)");




    mql.addListener(function(m) {
        if(m.matches) {
        var headerHeight = $( '.header' ).height();
        var footerHeight = $( '.footer' ).height();
        var maincontent =$(window).height();
        var height = maincontent - (headerHeight + footerHeight);
        $( '.content' ).height( height );
        }
        else {
        var headerHeight = $( '.header' ).height();
        var footerHeight = $( '.footer' ).height();
        var maincontent =$(window).height();
        var height = maincontent - (headerHeight + footerHeight);
        $( '.content' ).height( height );
        }
    })
 });


