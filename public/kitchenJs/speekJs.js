var synth = window.speechSynthesis;

//var inputForm = document.querySelector('form');
//var inputTxt = document.querySelector('.txt');
var voiceSelect = document.querySelector('select');

//var pitch = document.querySelector('#pitch');
//var pitchValue = document.querySelector('.pitch-value');
//var rate = document.querySelector('#rate');
//var rateValue = document.querySelector('.rate-value');

var voices = [];

// function populateVoiceList() {
//   voices = synth.getVoices();
//   var selectedIndex = voiceSelect.selectedIndex < 0 ? 0 : voiceSelect.selectedIndex;
//   voiceSelect.innerHTML = '';
//   for(i = 0; i < voices.length ; i++) {
//     var option = document.createElement('option');
//     option.textContent = voices[i].name + ' (' + voices[i].lang + ')';
    
//     if(voices[i].default) {
//       option.textContent += ' -- DEFAULT';
//     }

//     option.setAttribute('data-lang', voices[i].lang);
//     option.setAttribute('data-name', voices[i].name);
//     voiceSelect.appendChild(option);
//   }
//   voiceSelect.selectedIndex = selectedIndex;
// }

// populateVoiceList();
// if (speechSynthesis.onvoiceschanged !== undefined) {
//   speechSynthesis.onvoiceschanged = populateVoiceList;
// }

function speak(arg){
    if (synth.speaking) {
        console.error('speechSynthesis.speaking');
        return;
    }
    if (arg !== '') {
    var utterThis = new SpeechSynthesisUtterance(arg);
    utterThis.onend = function (event) {
        console.log('SpeechSynthesisUtterance.onend');
    }
    utterThis.onerror = function (event) {
        console.error('SpeechSynthesisUtterance.onerror');
    }
    var selectedOption = 'icelandic';
    console.log('selectedOption='+selectedOption);
    for(i = 0; i < voices.length ; i++) {
      if(voices[i].name === selectedOption) {
        utterThis.voice = voices[i];
      }
    }
    utterThis.pitch = 1;
    utterThis.rate = 1;
    synth.speak(utterThis);
  }
}

// inputForm.onsubmit = function(event) {
//   event.preventDefault();

//   speak();

//   inputTxt.blur();
// }

function test(test){
  console.log(test);
  speak(test);

}

// pitch.onchange = function() {
//   pitchValue.textContent = 1;
// }

// rate.onchange = function() {
//   rateValue.textContent = 1;
// }

/*voiceSelect.onchange = function(){
  speak();
}*/