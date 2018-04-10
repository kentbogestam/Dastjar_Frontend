
// 'use strict';
// function getDeviceToken() {
//     var deviceToken;
//     if ('serviceWorker' in navigator) {
//         console.log('Service Worker is supported');
 
//         navigator.serviceWorker.register('sw.js').then(function() {
	  
//             return navigator.serviceWorker.ready;
//         }).then(function(reg) {
//             console.log('Service Worker is ready :^)', reg);
//             reg.pushManager.subscribe({
//                 userVisibleOnly: true
//             }).then(function(sub) {
//                 console.log('Endpoint:', sub.endpoint);
	 
//                 deviceToken = sub.endpoint
//                 var idD = deviceToken.substring(deviceToken.indexOf("d/")+1);
//                 deviceToken =  idD.substring(idD.indexOf("/")+1);
//                 console.log("DEVICE TOKEN : "+deviceToken)
//                 storeDeviceToken(deviceToken);
//             // localStorage.setItem("_App42_DeviceId",regID)
//             });
//         }).catch(function(error) {
//             console.log('Service Worker error :^(', error);
//         });
//     }
// }

// function storeDeviceToken(deviceToken) {
//     console.log("storeDeviceToken");
//     var pushNotificationService  = new App42Push();  
//     var userName = App42.getLoggedInUser();
//     var deviceType = 'CHROME';
//     // deviceToken = "Device Token"; 
//     pushNotificationService.storeDeviceToken(userName, deviceToken, deviceType,{    
//         success: function (object) {
//             var pushObj = JSON.parse(object)
//             console.log("Success Res :: ");
//             console.log(pushObj);
//         },
//         error: function (error) {
//             console.log("error Obj :: " + error);
//         }
//     });
// }

function registerSwjs(){
    if ('serviceWorker' in navigator) {
        console.log('Service Worker is supported');
 
        navigator.serviceWorker.register('sw.js').then(function() {
      
            return navigator.serviceWorker.ready;
        }).then(function(reg) {
            console.log('Service Worker is ready :^)', reg);
        }).catch(function(error) {
            console.log('Service Worker error :^(', error);
        });
    }
}

function swJs() {

 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
     (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
     m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
     })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
     
     ga('create', 'UA-59325548-2', 'auto');
     ga('send', 'pageview');


};
swJs();
