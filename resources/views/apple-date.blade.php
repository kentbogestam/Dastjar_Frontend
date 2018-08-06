<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- saved from url=(0028)http://cubiq.org/dropbox/sw/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<link rel="stylesheet" href="{{asset('css/spinningwheel.css')}}" type="text/css" media="all">
	<script type="text/javascript" src="{{asset('js/spinningwheel-min.js')}}"></script>

	<title>Spinning Wheel for iPhone/iPod touch</title>

<script type="text/javascript">
function openWeight() {
	
	var numbers = { 0: 0, 1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6, 7: 7, 8: 8, 9: 9 };
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot({ separator: '.' }, 'readonly shrink');
	SpinningWheel.addSlot(numbers, 'right');
	SpinningWheel.addSlot({ Kg: 'Kg', Lb: 'Lb', St: 'St' }, 'shrink');
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function openBirthDate() {
	var now = new Date();
	var days = { };
	var years = { };
	var months = { 1: 'Gen', 2: 'Feb', 3: 'Mar', 4: 'Apr', 5: 'May', 6: 'Jun', 7: 'Jul', 8: 'Aug', 9: 'Sep', 10: 'Oct', 11: 'Nov', 12: 'Dec' };
	
	for( var i = 1; i < 32; i += 1 ) {
		days[i] = i;
	}

	for( i = now.getFullYear()-100; i < now.getFullYear()+1; i += 1 ) {
		years[i] = i;
	}

	SpinningWheel.addSlot(years, 'right', 1999);
	SpinningWheel.addSlot(months, '', 4);
	SpinningWheel.addSlot(days, 'right', 12);
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function openOneSlot() {
	SpinningWheel.addSlot({1: 'Ichi', 2: 'Ni', 3: 'San', 4: 'Shi', 5: 'Go'});
	
	SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	
	SpinningWheel.open();
}

function done() {
	var results = SpinningWheel.getSelectedValues();
	document.getElementById('result').innerHTML = 'values: ' + results.values.join(' ') + '<br />keys: ' + results.keys.join(', ');
}

function cancel() {
	document.getElementById('result').innerHTML = 'cancelled!';
}

//		$(document).ready(function () {
	        openBirthDate();
	//    });
	  
window.addEventListener('load', function(){ setTimeout(function(){ window.scrollTo(0,0); }, 100); }, true);

</script>


<style type="text/css">
body { text-align:center; font-family:helvetica; }
button { font-size:16px; }
#result { margin:10px; background:#aaa; -webkit-border-radius:8px; padding:8px; font-size:18px; }
</style>

</head>

<body>
<p>Spinning Wheel slot machine alike widget for iPhone and iPod touch. 
This demo works only on the simulator and the real devices, it does not work on any other browser.</p>
<button onclick="openBirthDate()">birth date</button>
<button onclick="openWeight()">weight</button>
<button onclick="openOneSlot()">1 slot</button>
<p id="result">results</p>
<p><a href="http://cubiq.org/spinning-wheel-on-webkit-for-iphone-ipod-touch/11">Read the related article on cubiq.org</a></p>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>

this text is here for the sole purpose of making the page longer


</body></html>