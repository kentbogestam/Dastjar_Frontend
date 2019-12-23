$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

// Update driver status
function updateStatus(This)
{
    var This = $(This);

    var status = This.is(':checked') ? 1 : 0;

    $.ajax({
        url: BASE_URL_DRIVER+'/update-status/'+status,
        success: function() {

        }
    });
}

// Goelocation add watch
function getLocationUpdate()
{
    if(navigator.geolocation)
    {
        // navigator.geolocation.getCurrentPosition(showPosition);
        var options = {timeout:60000};
        geoLoc = navigator.geolocation;
        watchID = geoLoc.watchPosition(showLocationUpdate, errorHandlerLocationUpdate, options);
    }
    else
    {
        console.log('Geolocation is not supported by this browser.');
    }
}

// Current position
function showLocationUpdate(position)
{
    var lat2 = position.coords.latitude;
    var lon2 = position.coords.longitude;

    var lat1 = getCookie("driver-latitude");
    var lon1 = getCookie("driver-longitude");

    var distance = getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2, 'K');

    distance = distance*1000;

    if( !lat1 || (distance > 100) )
    {
        updateDriverPosition(lat2, lon2);
    }
}

function errorHandlerLocationUpdate()
{
    $('#location-denied').modal('show');
}

// Update driver current position
function updateDriverPosition(lat2, lon2)
{
    $.ajax({
        url: BASE_URL_DRIVER+'/update-driver-position',
        type: 'POST',
        data: {
            '_token': $('meta[name=_token]').attr('content'),
            'latitude': lat2,
            'longitude': lon2
        },
        success: function(response) {
            if(response.status)
            {
                document.cookie="driver-latitude="+lat2;
                document.cookie="driver-longitude="+lon2;
            }
        }
    });
}

// Add watch to track driver location
getLocationUpdate();

// Reset driver position
function resetPosition()
{
    document.cookie = "driver-latitude= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
    document.cookie = "driver-longitude= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";

    // Clear watch first so it can get the current position
    if(watchID)
    {
        navigator.geolocation.clearWatch(watchID);
    }
    
    getLocationUpdate();
}

// Get order detail
function getOrderDetail(customerOrderId)
{
    // $('#modal-order-detail').modal('show');
    $.ajax({
        url: BASE_URL_DRIVER+'/get-order-detail/'+customerOrderId,
        dataType: 'json',
        success: function(response) {
            if(response.html)
            {
                $('#modal-order-detail').find('.list-table-modal tbody').html(response.html);
            }

            $('#modal-order-detail').modal('show');
        }
    });
}

// Return distance between two coordinates using 'haversine formula'
function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2, unit) {
    if ((lat1 == lat2) && (lon1 == lon2)) {
        return 0;
    }
    else {
        var radlat1 = Math.PI * lat1/180;
        var radlat2 = Math.PI * lat2/180;
        var theta = lon1-lon2;
        var radtheta = Math.PI * theta/180;
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
            dist = 1;
        }
        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        if (unit=="K") { dist = dist * 1.609344 }
        if (unit=="N") { dist = dist * 0.8684 }
        return dist;
    }
}

// Function get cookie
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

// Add multiple time in format 'h:i:s'
function addTimes (startTime, endTime, extra_prep_time) {
    var times = [ 0, 0, 0 ];
    var max = times.length;

    var a = (startTime || '').split(':')
    var b = (endTime || '').split(':')
    var c = (extra_prep_time || '').split(':')

    // normalize time values
    for (var i = 0; i < max; i++) {
        a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i])
        b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i])
        c[i] = isNaN(parseInt(c[i])) ? 0 : parseInt(c[i])
    }

    // store time values
    for (var i = 0; i < max; i++) {
        times[i] = a[i] + b[i] + c[i]
    }

    var hours = times[0]
    var minutes = times[1]
    var seconds = times[2]

    if (seconds > 59) {
        var m = (seconds / 60) << 0
        minutes += m
        seconds -= 60 * m
    }

    if (minutes > 59) {
        var h = (minutes / 60) << 0
        hours += h
        minutes -= 60 * h
    }

    return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2)
}