/*!
* THEIA.JS
*
* Main scripts file. Contains theme functionals.
*
* Version 1.0
*/

/* 
-----------------------------------------------------------------------------------------------------------*/

standalone = true;
if ( ("standalone" in window.navigator) && !window.navigator.standalone ){
    standalone = window.navigator.standalone;
}
userAgent = window.navigator.userAgent.toLowerCase();
safari = /safari/.test( userAgent );
ios = /iphone|ipod|ipad/.test( userAgent );
userAction = 'msg';
watchPositionAction = '';
responseGeoAddressCnt = 0;

$(document).ready(function($) {

    "use strict";

    var aSearchClicked = false;
    
    /*jQuery(".sub-menu").hide();
    jQuery(".container").hide();*/
        
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
function changeValueQuantity(chengeValue) {
    var cartValue = $(".cart-badge").html();
    var productsInputQuantity = document.getElementsByClassName('product_input_quantity');
    productsInputQuantityTotal = 0;
    for (let index = 0; index < productsInputQuantity.length; ++index) {
        productsInputQuantityTotal = productsInputQuantityTotal+parseInt(productsInputQuantity[index].value);
    }
    cntCartItems=productsInputQuantityTotal;
    $('.cart-badge').html(cntCartItems);
    $('.cart-badge').removeClass('hidden');  
}

function incrementValue(id,people_serve)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    incrimented_value_more = 0;
    if(value == 1)
    {
        if(people_serve)
        {
            people_serve = parseInt(people_serve);
            value = (value -1) + people_serve;
            incrimented_value_more = value;
        }       
    } 
    $("#item"+id).css("background-color", "#fafadc");
    document.getElementById(id).value = value;
    // Update value in basket
    if(incrimented_value_more>1)
    {
        cntCartItems =cntCartItems +   incrimented_value_more;
    }
    else
    {
        cntCartItems++;
    }
    $('.cart-badge').html(cntCartItems);
    $('.cart-badge').removeClass('hidden');
}


function decrementValue(id)
{
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    if(value>0){
        value--;
        document.getElementById(id).value = value;

        // Update value in basket
        cntCartItems--;
        $('.cart-badge').html(cntCartItems);

        if(!cntCartItems)
        {
            $('.cart-badge').addClass('hidden');
        }
    }

    if(value==0){
       $("#item"+id).css("background-color", "white");
    }
}

// Call from 'popupSelection' on load and update user location (if type_selection is null)
function setCurrentLatLong(urllatlng){
    /*var userLang = navigator.language || navigator.userLanguage; 
    
    if (userLang=='sv'){
        $("#contentEnglish").hide();
        $("#contentSwedish").show();
   
    }else{
        $("#contentEnglish").show();
        $("#contentSwedish").hide();
    }*/

    if(ios && (!standalone && !safari))
    {
        requestGeoAddressToIosNative('setCurrentLatLong');
    }
    else
    {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.cookie="latitude=" + position.coords.latitude;
            document.cookie="longitude=" + position.coords.longitude;

            $.ajax({
                url: urllatlng,
                type: "GET",
                data: {lat : position.coords.latitude, long : position.coords.longitude},
                dataType: "json"
            });

            reloadRestaurantList();
        },function(error){
            if(!getCookie("latitude") && !getCookie("longitude"))
            {
                $("#loading-img").hide();
                $("#overlay").hide();
                $('#login-popup').modal("show");
            }
        });
    }
}

// Increase quantity of cart item and update price
function incrementCartValue(id)
{
    // Update item quantity
    var itemQty = parseInt($('#qty'+id).val(), 10);
    itemQty = isNaN(itemQty) ? 0 : itemQty;
    itemQty++;
    $('#qty'+id).val(itemQty);

    // Update static cart value
    updateCartDetail(id);

    // Update cart
    updateCart(itemQty, $('#prod'+id).val(), 0, 0);

    // Update value in cart badge and show
    cntCartItems++;
    $('.cart-badge').html(cntCartItems);
    $('.cart-badge').removeClass('hidden');
}

// Decrease quantity of cart item and update price
function decrementCartValue(id,msg)
{
    // Get item quantity
    var itemQty = parseInt($('#qty'+id).val(), 10);
    itemQty = isNaN(itemQty) ? 0 : itemQty;
    var rowCount = $('#table-custom-2 tr').length;
    var productId = $('#prod'+id).val();

    if(itemQty > 1 || rowCount >= 2)
    {
        // Update item quantity or remove complete row
        itemQty--;
        $('#qty'+id).val(itemQty);
        
        if(itemQty == 0)
        {
            $('#row_'+id).remove();
        }

        // Update static cart value
        updateCartDetail(id);

        // Update cart
        updateCart(itemQty, productId, 0, 0);

        // Update value in cart badge and hide/show
        cntCartItems--;
        $('.cart-badge').html(cntCartItems);

        if(!cntCartItems)
        {
            $('.cart-badge').addClass('hidden');
        }
    }
    else
    {
        $('#delete-cart-item-alert').find('.delete').attr('onclick', 'onDeleteLastItemFromCart('+id+')')
        $('#delete-cart-item-alert').modal('show');
    }
}

// While increment/decrement, update item total, subtotal, discount etc.
function updateCartDetail(id)
{
    var subTotal = finalTotal = 0;
    itemQty = $('#qty'+id).val();

    // Update item price
    itemPrice = parseInt($('#itemprice'+id).val(), 10);
    itemTotal = itemQty * itemPrice;
    $('#itemtotalDisplay'+id).html(itemTotal.toFixed(2));
    $('#itemtotal'+id).val(itemTotal);

    // Calculate finalTotal, subtotal and discounted price if exist
    /*subTotal = finalTotal = calculateGrandtotal();

    if( $('#discount_value').length )
    {
        discountAmount = (subTotal*$('#discount_value').val()/100);
        finalTotal -= discountAmount;

        $('#discount-amount').html(discountAmount.toFixed(2));
    }

    $('#sub-total').html(subTotal.toFixed(2));
    $('#grandTotalDisplay').html(finalTotal.toFixed(2));*/
}

// Delele last item on cart and redirect
function onDeleteLastItemFromCart(id = null)
{
    if(id)
    {
        $('#row_'+id).remove();
        $('#last-row').remove();
        $('#saveorder').remove();
        
        updateCart(0,0,0,0);
        makeRedirection($('#redirectUrl').val());
    }
}

/*function calculateGrandtotal(){
    var grandtotal=0;
    var arrayValues = $('input:hidden.itemtotal').map(function(){
        return $(this).val()
    }).get();
   
    for (var i=0;i<arrayValues.length;i++){
       grandtotal=parseInt(grandtotal)+parseInt(arrayValues[i]);
    }

    return grandtotal;
}*/

// Update cart
function updateCart(qty,productId,totalProductPrice,grandtotal, homeDelPartCntRefresh = true){
    var url= $('#baseUrl').val()+"/updateCart";
    var orderid= $('#orderid').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $("input[name=_token]").val()
        }
    });

    $.ajax({
        url: url,
        data: {
            'qty': qty,
            'productId': productId,
            'totalProductPrice': totalProductPrice,
            'grandtotal': grandtotal,
            'orderid': orderid
        },
        type: 'POST',
        datatype: 'JSON',
        async: false,
        success: function (response) {
            if (response.status) {
                orderInvoice = response.data.orderInvoice;
                console.log(orderInvoice);

                // Update total and sub-total
                $('#sub-total').html(orderInvoice.order_total.toFixed(2));
                $('#grandTotalDisplay').html(orderInvoice.final_order_total.toFixed(2));

                // Update loyalty if exist
                $('.loyalty-discount-text').text('');
                if( typeof orderInvoice.loyalty_quantity_free !== 'undefined' )
                {
                    $('.loyalty-discount-text').html(orderInvoice.loyaltyOfferApplied);
                }

                // Update discount if exist
                if( typeof orderInvoice.discount !== 'undefined' )
                {
                    $('#discount-amount').text(orderInvoice.discount.toFixed(2));
                }

                // Home delivery
                if( typeof orderInvoice.homeDelivery !== 'undefined' )
                {
                    if($('.row-delivery-charge').length)
                    {
                        if( typeof orderInvoice.homeDelivery.delivery_charge !== 'undefined' && orderInvoice.homeDelivery.delivery_charge != null )
                        {
                            $('.row-delivery-charge #delivery-charge').html(orderInvoice.homeDelivery.delivery_charge.toFixed(2));
                            $('.row-delivery-charge').removeClass('hidden');
                        }
                        else
                        {
                            $('.row-delivery-charge').addClass('hidden');
                        }
                    }

                    /*if($('input[name=delivery_type]:checked').val() == '3' && homeDelPartCntRefresh)
                    {
                        getHomeDeliveryPartContent(orderid);
                    }*/
                }
            }
        },
        error: function (response) {
            $('#errormessage').html(response.message);
        }
    });
}

// Delete cart function #It only redirects on other page so need add functionality to delete cart server side
function deleteFullCart(url,value,msg){
    /*var deleteConfirm;

    if(value==1){
        deleteConfirm = confirm(msg);
    }else if(value==2){
        deleteConfirm = confirm("Your cart item will be removed if you leave this page");
    }

    if(deleteConfirm==true){
        var orderid= $('#orderid').val();
        url= url+"/?orderid="+orderid;
        makeRedirection(url);
    }*/

    var orderid= $('#orderid').val();
    url= url+"/?orderid="+orderid;
    makeRedirection(url);
}

// Show spinner
function showLoading(text = 'Loading...')
{
    $('.block-loader').show();
}

// Hide spinner
function hideLoading()
{
    $('.block-loader').hide();
}

/* Start from currentLocation.js */
// Update distance while move either to get restaurant list or to update map
function setDistanceParmeter()
{
    // var lat1="28.580830";
    // var lon1="77.077720";
    // var lat2="28.585560";
    // var lon2="77.074809";

    var lat1 = getCookie("latitude");
    var lon1 = getCookie("longitude");

    var lat2 =  getCookie("everyMinutelatitude");
    var lon2 =  getCookie("everyMinutelongitude");

    var distance = distanceLatLon(lat1, lon1, lat2, lon2, "K");

    distance = distance*1000;

    if(distance > 100){
        document.cookie="latitude="  + '';
        document.cookie="longitude=" + '';

        document.cookie="latitude="  + lat2;
        document.cookie="longitude=" + lon2;

        checkDistance(lat2,lon2);
    }
}

//set geo location data
function checkDistance(latt, lngg)
{
    $.ajax({
        type: "GET",
        url: "checkDistance",
        data: {lat: latt, lng : lngg},
        success: function( returnedData ) {
            //alert("in success of alert Distance Executed Distance check parameter");
            reloadRestaurantList();
        }
    });
}
/* End from currentLocation.js */

// Make request to return lat/lng from IOS GPS
function requestGeoAddressToIosNative(action = 'msg')
{
    userAction = action;
    window.webkit.messageHandlers.geoAddress.postMessage(action);
}

// Get updated lat/lng (response) from IOS native
function responseGeoAddressFromIosNative(data)
{
    locationPermission = (typeof data['locationPermission'] !== 'undefined') ? data['locationPermission'] : '0';
    action = (typeof data['action'] !== 'undefined') ? data['action'] : userAction;
    responseGeoAddressCnt++;

    // If added watch here on position (home page, location, map etc)
    if(action == 'getLocation')
    {
        //
        if(locationPermission == '1')
        {
            if( (data['lat'] == '0.00' || data['long'] == '0.00') && responseGeoAddressCnt < 20 )
            {
                requestGeoAddressToIosNative('getLocation');
                return false;
            }
        }

        // Page 'location.blade.php', 'map/index.blade.php'
        if(watchPositionAction == 'updateLocationOnMap')
        {
            //
            if($('input[name="street_address"]').length && $('input[name="street_address"]').val() != '')
            {
                return false;
            }

            var lat1 = getCookie("latitude"); var lon1 = getCookie("longitude");
            var lat2 = data['lat']; var lon2 = data['long'];

            if(typeof changeMarkerPosition == 'function')
            {
                changeMarkerPosition(lat2, lon2);
            }
            else
            {
                alert('function \'changeMarkerPosition\' does not exist!');
            }
        }
        // Page 'map/single_res_map.blade.php'
        else if(watchPositionAction == 'showLocation')
        {
            var lat1 = getCookie("latitude"); var lon1 = getCookie("longitude");
            var lat2 = data['lat']; var lon2 = data['long'];
            markers[0][0] = lat2; markers[0][1] = lon2;

            if(typeof calculateAndDisplayRoute == 'function')
            {
                calculateAndDisplayRoute();
            }
            else
            {
                alert('function \'calculateAndDisplayRoute\' does not exist!');
            }
        }
        else
        {
            // Check if function exist
            if(typeof setDistanceParmeter == 'function')
            {
                if( (data['lat'] != '0.00' && data['long'] != '0.00') || responseGeoAddressCnt >= 20 )
                {
                    if( getCookie('latitude') == '' || getCookie('longitude') == '' )
                    {
                        document.cookie = "latitude=" + data['lat'];
                        document.cookie = "longitude=" + data['long'];
                    }

                    setMyCookie('everyMinutelatitude', data['lat'], 7);
                    setMyCookie('everyMinutelongitude', data['long'], 7);
                    setMyCookie('showError','', 0);

                    var flag=checkTimeAfterLocationSet();

                    if(flag==false){
                        setDistanceParmeter();
                    }
                }
            }
            else
            {
                alert('function \'setDistanceParmeter\' does not exist!');
            }
        }
    }
    
    // On load home page if type_selection is null 'popupSelection'
    if(action == 'setCurrentLatLong')
    {
        if( (data['lat'] != '0.00' && data['long'] != '0.00') || responseGeoAddressCnt >= 20 )
        {
            // Update Cookie
            document.cookie="latitude=" + data['lat'];
            document.cookie="longitude=" + data['long'];
            
            // Update Session
            var latitude  = getCookie("latitude");
            var longitude = getCookie("longitude");

            $.ajax({
                url: BASE_URL+"/update-location",
                type: "GET",
                data: {lat : latitude, long : longitude},
                dataType: "json"
            });
        }
    }
    
    // type_selection is not null, get current position and show restaurant listing on home page
    if(action == 'getPos')
    {
        if(locationPermission == '1')
        {
            // 
            if( (data['lat'] == '0.00' || data['long'] == '0.00') && responseGeoAddressCnt < 20 )
            {
                requestGeoAddressToIosNative('getPos');
                return false;
            }

            // loc_flag=1;
            // Update Cookie
            loc_lat = data['lat'];
            loc_lng = data['long'];

            document.cookie="latitude=" + loc_lat;
            document.cookie="longitude=" + loc_lng;

            // update user location
            $.ajax({
                url: BASE_URL+"/update-location",
                type: "GET",
                data: {lat : loc_lat, long : loc_lng},
                dataType: "json"
            });

            var extraclass = document.body;
            extraclass.classList.remove('disableClass');

            if(typeof add == 'function')
            {
                add(constUrlLatLng,constUrlRestaurantMenu,noImageUrl);
            }
            else
            {
                alert('function \'add\' does not exist!');
            }
        }
        else
        {
            if(!getCookie("latitude") && !getCookie("longitude"))
            {
                $("#loading-img").hide();
                $("#overlay").hide();
                $('.login-inner-section a').attr('href','javascript:void(0)');
                $('#login-popup').modal("show");
            }
            else
            {
                // loc_flag=3;
                document.cookie="latitude=" + loc_lat;
                document.cookie="longitude=" + loc_lng;
                add(constUrlLatLng,constUrlRestaurantMenu,noImageUrl);
            }
        }
    }
    
    // Check the position afer 20 min and reset the longitude and latitude
    if(action == 'getCurrentCoordinates')
    {
        if( (data['lat'] != '0.00' && data['long'] != '0.00') || responseGeoAddressCnt >= 20 )
        {
            // Update Cookie
            document.cookie="latitude=" + data['lat'];
            document.cookie="longitude=" + data['long'];
            
            // Update Session
            var latitude  = getCookie("latitude");
            var longitude = getCookie("longitude");

            $.ajax({
                url: BASE_URL+"/update-location",
                type: "GET",
                data: {lat : latitude, long : longitude},
                dataType: "json"
            });

            if(typeof reloadRestaurantList == 'function')
            {
                reloadRestaurantList();
            }
            else
            {
                alert('function \'reloadRestaurantList\' does not exist!');
            }
        }
    }
    
    // Reset current position from 'Settings'
    if(action == 'locationSave')
    {
        if(locationPermission == '1')
        {
            if( (data['lat'] != '0.00' && data['long'] != '0.00') || responseGeoAddressCnt >= 20 )
            {
                // Update Cookie
                document.cookie="latitude=" + data['lat'];
                document.cookie="longitude=" + data['long'];
                
                // Update Session
                var latitude  = getCookie("latitude");
                var longitude = getCookie("longitude");

                $.get(BASE_URL+'/saveCurrentlat-long', { lat: latitude, lng : longitude}, function(returnedData){
                    console.log(returnedData["data"]);
                    unsetLocationCookieTime();
                    window.location.reload();
                });
            }
        }
        else
        {
            $("#loading-img").hide();
            $("#overlay").hide();
            $('.login-inner-section a').attr('href','javascript:void(0)');
            $('#login-popup').modal("show");
        }
    }
}

kWindow = window;