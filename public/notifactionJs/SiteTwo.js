function testTrackEvent() {
    console.log("testTrackEvent");
    var event = new App42Event();      // Initialize "event" As New App42Event.
    var eventName = $("#activityName").val();
    var properties = {
        "Emp_Name":"Gaurav",
        "ID":45221,
        "isTrainee":true
    };
    event.trackEvent(eventName, properties, {      
        success: function (object) {
            var userAuthObj = JSON.parse(object)
            console.log("Success Res :: "+userAuthObj);
        },
        error: function (error) {
            console.log("errorsr Obj :: " + error);
        }
    });
}

function testStartSession() {
    console.log("testStartSession");
    var event = new App42Event();      // Initialize "event" As New App42Event.
    var activityName = $("#activityName").val();
    var properties = {};
    event.startActivity(activityName, properties, {
        success: function (object) {
            var userAuthObj = JSON.parse(object)
            console.log("Success Obj Client :: " + object);
        },
        error: function (error) {
            console.log("errorsr Obj Client :: " + error);
        }
    });
}

function testEndSession() {
    console.log("testStartSession");
    var event = new App42Event();      // Initialize "event" As New App42Event.
    var activityName = $("#activityName").val();
    var properties = {
        "UserName": "Akshay",
        "Source": "JavaScript",
        "State": "ActivityEnded",
        "country": "IN",
        "city" : "Chennai"
    };
    event.endActivity(activityName, properties, {
        success: function (object) {
            var userAuthObj = JSON.parse(object)
            console.log("Success Obj Client :: " + object);
        },
        error: function (error) {
            console.log("errorsr Obj Client :: " + error);
        }
    });
}

function testSetUserProps() {
    console.log("testSetUserProps");
    var event = new App42Event();      // Initialize "event" As New App42Event.
    var activityName = $("#activityName").val();
    var properties = {
        "Content Type":"Live Tv",
        "Channel": "Hindi"
    };
    event.setLoggedInUserProperties(properties, {
        success: function (object) {
            var userAuthObj = JSON.parse(object)
            //      var loggedInUser = userAuthObj.app42.response.users.user.userName; testStartSession
            console.log( object);
        },
        error: function (error) {
            console.log("errorsr Obj Client :: " + error);
        }
    });
}

function testGetUserProps() {
    console.log("testSetUserProps");
    var event = new App42Event();      // Initialize "event" As New App42Event.
    var activityName = $("#activityName").val();
    event.getLoggedInUserProperties({
        success: function (object) {
            var userAuthObj = JSON.parse(object)
            console.log("Success Obj Client :: " + object);
        },
        error: function (error) {
            console.log("errorsr Obj Client :: " + error);
        }
    });
}




