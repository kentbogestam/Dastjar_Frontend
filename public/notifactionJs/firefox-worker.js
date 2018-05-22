var apiKey = "cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2"
var CLICK_URL ,title,actionOne,actionTwo;
var iconURL = "images/logo.png"
self.addEventListener('install', function(event) {
  self.skipWaiting();
});

self.addEventListener('activate', function(event) {
});

self.addEventListener('push', function(event) {
var FETCH_ENDPOINT = "https://api.shephertz.com/cloud/1.0/storage/getAllNotications/deviceId/";  
	event.waitUntil(self.registration.pushManager.getSubscription().then(function(subscription) {
        var regID = null;
        if ('subscriptionId' in subscription) {
            regID = subscription.subscriptionId;
        } else {
            regID = subscription.endpoint;
        }
        var idD = regID.substring(regID.indexOf("d/")+1);
       	regID =  idD.substring(idD.indexOf("/")+1);
		var encodedString = btoa(regID);	
		FETCH_ENDPOINT = FETCH_ENDPOINT +encodedString+"/FIREFOX"+"?apiKey="+apiKey+"&";
		return fetch(FETCH_ENDPOINT).then(function(response) {
		    return response.json().then(function(json) {
			var jsonOBJECT = json.app42.response.notification.messages
				for(var i=0;i< jsonOBJECT.length;i++){
					var jsonPayload = jsonOBJECT[i];
					var messagePayload = {};
					messagePayload.body = jsonPayload.message;
					messagePayload.icon = iconURL;
					parseJSON(jsonOBJECT, i, messagePayload, function (dataUrl, a1, a2, returnMsgPayload) {
						var messagePayloadDataUrl = {};
						messagePayloadDataUrl.url = dataUrl;
						messagePayloadDataUrl.actionOneUrl = a1;
						messagePayloadDataUrl.actionTwoUrl = a2;
						returnMsgPayload.data = messagePayloadDataUrl;
						return self.registration.showNotification(title, returnMsgPayload);
					});
				}
            });
        });
    }));
});


self.addEventListener('message', function(event) {
  event.ports[0].postMessage({'test': 'This is my response.'});
});

function parseJSON(jsonOBJECT,i,messagePayload, onDone){
	title = 'Anar';
	CLICK_URL = "https://anar.dastjar.com/";
	actionOne = null;
	actionTwo = null;
	var actions = new Array();
	var jsonPayload = jsonOBJECT[i];
	
	if(jsonPayload.url){
		CLICK_URL = jsonPayload.url
	}	
	if(typeof(jsonPayload.message)!="string"){
		messagePayload.body = jsonPayload.message.alert
		var richPush  = jsonPayload.message._app42RichPush;
		if(richPush !== undefined && richPush!== null){
			if(richPush.type==="image"){
				messagePayload.image = richPush.content
			}else if(richPush.type==="openUrl"){
				CLICK_URL = richPush.content
			}else if(richPush.type==="youtube"){
				CLICK_URL = richPush.content
			}else if(richPush.type==="video"){
				messagePayload.image = iconURL
				CLICK_URL = richPush.content
			}else if(richPush.type==="text"){
				CLICK_URL = richPush.content
			}
			if(richPush.requireInteraction==="1")
				messagePayload.requireInteraction= true
			if(richPush.title){
				title = richPush.title;
			}if(richPush.url){
				CLICK_URL = richPush.url;
			}
			if(richPush.b1URL){
				actionOne = richPush.b1URL
				var action =  {
					"title":richPush.b1Title,
					"action":richPush.b1URL
				}
				actions.push(action)
			}
			if(richPush.b2URL){
				actionTwo = richPush.b2URL
				var actionT =  {
					"title":richPush.b2Title,
					"action":richPush.b2URL
				}
				actions.push(actionT)
			}							
		}if(typeof(jsonPayload.message.alert)!="string"){
			messagePayload.body = jsonPayload.message.alert.alert
			if(jsonPayload.message.alert.url){
				CLICK_URL = jsonPayload.message.alert.url
			}
			var campPayload = jsonPayload.message.alert
			var campPayloadRichPush  = campPayload._app42RichPush;
			if(campPayloadRichPush !== undefined && campPayloadRichPush!== null){
				if(campPayloadRichPush.type==="image"){
					messagePayload.image = campPayloadRichPush.content
				}else if(campPayloadRichPush.type==="openUrl"){
					CLICK_URL = campPayloadRichPush.content
				}else if(campPayloadRichPush.type==="youtube"){
					CLICK_URL = campPayloadRichPush.content
				}else if(campPayloadRichPush.type==="video"){
					messagePayload.image = iconURL
					CLICK_URL = campPayloadRichPush.content
				}else if(campPayloadRichPush.type==="text"){
					CLICK_URL = campPayloadRichPush.content
				}
				if(campPayloadRichPush.requireInteraction==="1")
					messagePayload.requireInteraction= true
				if(campPayloadRichPush.title){
					title = campPayloadRichPush.title;
				}if(campPayloadRichPush.url){
					CLICK_URL = campPayloadRichPush.url;
				}
				if(campPayloadRichPush.b1URL){
					actionOne = campPayloadRichPush.b1URL
					var action =  {
						"title":campPayloadRichPush.b1Title,
						"action":campPayloadRichPush.b1URL
					}
					actions.push(action)
				}
				if(campPayloadRichPush.b2URL){
					actionTwo = campPayloadRichPush.b2URL
					var actionT =  {
						"title":campPayloadRichPush.b2Title,
						"action":campPayloadRichPush.b2URL
					}
					actions.push(actionT)
				}		
			}
		}else{
		messagePayload.body = jsonPayload.message.alert
		if(jsonPayload.message.requireInteraction==="1")
				messagePayload.requireInteraction= true
			if(jsonPayload.message.title){
				title = jsonPayload.message.title;
			}if(jsonPayload.message.url){
				CLICK_URL = jsonPayload.message.url;
			}
			if(jsonPayload.message.b1URL){
				actionOne = jsonPayload.message.b1URL
				var action =  {
					"title":jsonPayload.message.b1Title,
					"action":jsonPayload.message.b1URL
				}
				actions.push(action)
			}
			if(jsonPayload.message.b2URL){
				actionTwo = jsonPayload.message.b2URL
				var actionT =  {
					"title":jsonPayload.message.b2Title,
					"action":jsonPayload.message.b2URL
				}
				actions.push(actionT)
			}
		}
		if(actions.length > 0){
			messagePayload.actions = actions
		}
	}
	onDone(CLICK_URL, actionOne, actionTwo, messagePayload);
}

function endpointCorrection(pushSubscription) {
  if (pushSubscription.endpoint.indexOf('https://android.googleapis.com/gcm/send') !== 0) {
    return pushSubscription.endpoint;
  }
  var mergedEndpoint = pushSubscription.endpoint;
  if (pushSubscription.subscriptionId &&
    pushSubscription.endpoint.indexOf(pushSubscription.subscriptionId) === -1) {
    mergedEndpoint = pushSubscription.endpoint + '/' +
      pushSubscription.subscriptionId;
  }
  return mergedEndpoint;
}
self.addEventListener('notificationclick', function(event) {
   if (Notification.prototype.hasOwnProperty('data')) {
		var url = event.notification.body;
	}
	var clickURL = event.notification.data.url;
	actionOne = event.notification.data.actionOneUrl;
	actionTwo = event.notification.data.actionTwoUrl;
	if (event.action === actionOne) {  
		clickURL = actionOne;
	}if (event.action === actionTwo) {  
		clickURL = actionTwo;
	}  
	
  event.notification.close();
  event.waitUntil(clients.matchAll({
    type: "window"
  }).then(function(clientList) {
    for (var i = 0; i < clientList.length; i++) {
      var client = clientList[i];
   }
    if (clients.openWindow){
		return clients.openWindow(clickURL);
	}
}));
});
