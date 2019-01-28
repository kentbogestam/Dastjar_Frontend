// Watch position callback on change location, update lat/lng on moved x meter
function updateMapOnLocationChange(position)
{
    var lat1 = getCookie("latitude");
    var lon1 = getCookie("longitude");
    var lat2 = position.coords.latitude;
    var lon2 = position.coords.longitude;
    // var lat2 = 28.477330;
    // var lon2 = 77.068140;

    var distance = (distanceLatLon(lat1, lon1, lat2, lon2, "K") * 1000);

    if(distance > 20)
    {
        alert('lat1/lon1: '+lat1+'/'+lon1+', lat2/lon2: '+lat2+'/'+lon2+', distance:'+distance);
        document.cookie="latitude="  + lat2;
        document.cookie="longitude=" + lon2;

        $.ajax({
            type: "GET",
            url: "checkDistance",
            data: {lat: lat2, lng : lon2},
            success: function( returnedData ) {
                window.location.reload();
            }
        });
    }
}

// Error through position
function updateMapErrorHandler(err) {
    if(err.code == 1) {
        alert("Error: Access is denied!");
    } else if( err.code == 2) {
        alert("Error: Position is unavailable!");
    }
}