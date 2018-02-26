importScripts('http://localhost/dast-jar-frontend/public/speakJs/speakGenerator.js');

onmessage = function(event) {
  postMessage(generateSpeech(event.data.text, event.data.args));
};

