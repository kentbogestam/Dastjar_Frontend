var clickURL = "";
self.addEventListener('install', function(event) {
  self.skipWaiting();
  console.log('Installed', event);
});

self.addEventListener('activate', function(event) {
  console.log('Activated', event);
});

self.addEventListener('push', function(event) {
 console.log('Push message', event);
  var title = 'Push message';
  var FETCH_ENDPOINT = " https://api.shephertz.com/cloud/1.0/storage/getAllNotications/deviceId/";  
 event.waitUntil(self.registration.pushManager.getSubscription().then(function(subscription) {
        var regID = null;
        if ('subscriptionId' in subscription) {
            regID = subscription.subscriptionId;
        } else {
            //in Chrome 44+ and other SW browsers, reg ID is part of endpoint, send the whole thing and let the server figure it out.
            regID = subscription.endpoint;
        }
        var idD = regID.substring(regID.indexOf("d/")+1);
          regID =  idD.substring(idD.indexOf("/")+1);
       
 var URL = FETCH_ENDPOINT +btoa(regID)+ "/CHROME"+"?apiKey=cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2";
 console.log(URL);
        return fetch(URL).then(function(response) {
 
            return response.json().then(function(json) {
 var jsonOBJECT = json.app42.response.notification.messages
 var messagePayload
 var id
 console.log(messagePayload)
 console.log(jsonOBJECT.length)
 for(var i=0;i<jsonOBJECT.length;i++){
 var jsonPayload = jsonOBJECT[i];
 console.log(jsonPayload)
 //promise.push(jsonPayload)
 messagePayload = jsonPayload.message
 id= jsonPayload.id
 clickURL = messagePayload.Url;
          title = messagePayload.alert;
          console.log(clickURL);
  self.registration.showNotification(title, { 

   //icon: " http://localhost/dast-jar-frontend/public/images/dastjar.png"

   icon: "https://dastjar.com/anar/public/images/dastjar.png"

 })
 
 }
            });
        });
    }));
 //return Promise.all(promise)
});

self.addEventListener('message', function(event) {
  event.ports[0].postMessage({'test': 'This is my response.'});
});

function endpointCorrection(pushSubscription) {
  if (pushSubscription.endpoint.indexOf(' https://android.googleapis.com/gcm/send') !== 0) {
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
  var url = event.target.clickURL;
  event.notification.close(); // Android needs explicit close.
  event.waitUntil(
      clients.matchAll({type: 'window'}).then( windowClients => {
          // Check if there is already a window/tab open with the target URL
          for (var i = 0; i < windowClients.length; i++) {
              var client = windowClients[i];
              // If so, just focus it.
              if (client.url === url && 'focus' in client) {
                  return client.focus();
              }
          }
          // If not, then open the target URL in a new window/tab.
          if (clients.openWindow) {
              return clients.openWindow(url);
          }
      })
  );
});

// self.addEventListener('notificationclick', function(event) {
//   // Android doesn’t close the notification when you click on it
//   // See: http://crbug.com/463146
//   console.log("Through click "+ event)
//     if (Notification.prototype.hasOwnProperty('data')) {
//     console.log('Using Data');
//     var url = event.notification.body;
//   console.log("Through click "+ url)
//   }
  
//   event.notification.close();
//   // This looks to see if the current is already open and
//   // focuses if it is
//   event.waitUntil(clients.matchAll({
//     type: "window"
//   }).then(function(clientList) {
//     for (var i = 0; i < clientList.length; i++) {
//       var client = clientList[i];
//    }
//     if (clients.openWindow){
//   return clients.openWindow("");
//   }
//   }));

// }); 



let version = '0.6.2';

self.addEventListener('install', e => {
  let timeStamp = Date.now();
  e.waitUntil(
    caches.open('anar').then(cache => {
      return cache.addAll([
        `/`,
        // `/cloneAddtohomescreen/index.html?timestamp=${timeStamp}`,
        // `/cloneAddtohomescreen/styles/main.css?timestamp=${timeStamp}`,
        // `/cloneAddtohomescreen/scripts/main.min.js?timestamp=${timeStamp}`,
        // `/cloneAddtohomescreen/scripts/comlink.global.js?timestamp=${timeStamp}`,
        // `/cloneAddtohomescreen/scripts/messagechanneladapter.global.js?timestamp=${timeStamp}`,
        // `/cloneAddtohomescreen/sounds/airhorn.mp3?timestamp=${timeStamp}`
      ])
      .then(() => self.skipWaiting());
    })
  )
});

self.addEventListener('activate',  event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request, {ignoreSearch:true}).then(response => {
      return response || fetch(event.request);
    })
  );
});

