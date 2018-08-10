



let version = '0.6.2';

self.addEventListener('install', e => {
  let timeStamp = Date.now();
  e.waitUntil(
    caches.open('anar').then(cache => {
    const displayMode = new URL(location).searchParams.get('displayMode');
    console.log("displayMode " + displayMode);
    var paths = [];

      if (displayMode == "standalone") {
        paths = [                  // `/`
                // `/cloneAddtohomescreen/index.html?timestamp=${timeStamp}`,
                // `/cloneAddtohomescreen/styles/main.css?timestamp=${timeStamp}`,
                // `/cloneAddtohomescreen/scripts/main.min.js?timestamp=${timeStamp}`,
                // `/cloneAddtohomescreen/scripts/comlink.global.js?timestamp=${timeStamp}`,
                // `/cloneAddtohomescreen/scripts/messagechanneladapter.global.js?timestamp=${timeStamp}`,
                // `/cloneAddtohomescreen/sounds/airhorn.mp3?timestamp=${timeStamp}`];
                ];
      }

        return cache.addAll(paths)
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

function fetchFromNetworkAndCache(e) {
  if (e.request.cache === 'only-if-cached' && e.request.mode !== 'same-origin'){
   return;
  }
}