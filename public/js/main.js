/*!
* THEIA.JS
*
* Main scripts file. Contains theme functionals.
*
* Version 1.0
*/

/* 
-----------------------------------------------------------------------------------------------------------*/


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
    value++;
    $("#item"+id).css("background-color", "#fafadc");
    document.getElementById(id).value = value;

    // Update value in basket
    cntCartItems++;
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

      var userLang = navigator.language || navigator.userLanguage; 
        if (userLang=='sv'){
            $("#contentEnglish").hide();
            $("#contentSwedish").show();
       
        }else{

            $("#contentEnglish").show();
            $("#contentSwedish").hide();
        }

   navigator.geolocation.getCurrentPosition(function(position) {

      document.cookie="latitude=" + position.coords.latitude;
      document.cookie="longitude=" + position.coords.longitude;
      console.log("in getCurrentCoordinates and updating current location ");
      //console.log(position.coords.latitude+"-------"+position.coords.longitude);
       $.ajax({
          url: urllatlng,
           type: "GET",
           data: {lat : position.coords.latitude, long : position.coords.longitude},
           dataType: "json"
       });

      // reloadRestaurantList();
  
  },function(error){
    if (typeof lat === "undefined") {
       // $('.login-inner-section a').attr('href','javascript:void(0)');
       // $('#login-popup').show();           
    } else {
        // document.cookie="latitude=" + lat;
        // document.cookie="longitude=" + lng;      
    }           
  });

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
        $('#delete-cart-item-alert').find('span.delete').attr('onclick', 'onDeleteLastItemFromCart('+id+')')
        $('#delete-cart-item-alert').show();
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
function updateCart(qty,productId,totalProductPrice,grandtotal){
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
                            $('.row-delivery-charge #delivery-charge').html(orderInvoice.homeDelivery.delivery_charge);
                            $('.row-delivery-charge').removeClass('hidden');
                        }
                        else
                        {
                            $('.row-delivery-charge').addClass('hidden');
                        }
                    }

                    if($('input[name=delivery_type]:checked').val() == '3')
                    {
                        getHomeDeliveryPartContent(orderid);
                    }
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
    $.mobile.loading('show', {
        text: text,
        textVisible: true,
        theme: 'a',
    });
}

// Hide spinner
function hideLoading()
{
    $.mobile.loading('hide');
}


kWindow = window;
