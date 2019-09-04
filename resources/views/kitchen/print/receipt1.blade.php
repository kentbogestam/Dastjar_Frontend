<!DOCTYPE html>
<!--
//
// Star webPRNT Sample(API Receipt)
//
// Version 1.2.1
//
// Copyright (C) 2012-2016 STAR MICRONICS CO., LTD. All Rights Reserved.
//
-->
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Expires" content="86400">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<title>Star webPRNT Sample(API Receipt)</title>

<link href="{{ url('plugins/printer/css/import.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ url('plugins/printer/style.css') }}" media="screen">

<link href="{{ url('plugins/printer/css/style_apireceipt.css') }}" rel="stylesheet" type="text/css">
<script type='text/javascript' src='{{ url('plugins/printer/js/StarWebPrintBuilder.js') }}'></script>
<script type='text/javascript' src='{{ url('plugins/printer/js/StarWebPrintTrader.js') }}'></script>
<script type='text/javascript'>
//<!--
var request = '';

function sendMessage(request) {
    showNowPrinting();
    var url              = document.getElementById('url').value;
    var papertype        = document.getElementById('papertype').value;

    var trader = new StarWebPrintTrader({url:url, papertype:papertype});

    trader.onReceive = function (response) {
        hideNowPrinting();

        var msg = '- onReceive -\n\n';

        msg += 'TraderSuccess : [ ' + response.traderSuccess + ' ]\n';

//      msg += 'TraderCode : [ ' + response.traderCode + ' ]\n';

        msg += 'TraderStatus : [ ' + response.traderStatus + ',\n';

        if (trader.isCoverOpen            ({traderStatus:response.traderStatus})) {msg += '\tCoverOpen,\n';}
        if (trader.isOffLine              ({traderStatus:response.traderStatus})) {msg += '\tOffLine,\n';}
        if (trader.isCompulsionSwitchClose({traderStatus:response.traderStatus})) {msg += '\tCompulsionSwitchClose,\n';}
        if (trader.isEtbCommandExecute    ({traderStatus:response.traderStatus})) {msg += '\tEtbCommandExecute,\n';}
        if (trader.isHighTemperatureStop  ({traderStatus:response.traderStatus})) {msg += '\tHighTemperatureStop,\n';}
        if (trader.isNonRecoverableError  ({traderStatus:response.traderStatus})) {msg += '\tNonRecoverableError,\n';}
        if (trader.isAutoCutterError      ({traderStatus:response.traderStatus})) {msg += '\tAutoCutterError,\n';}
        if (trader.isBlackMarkError       ({traderStatus:response.traderStatus})) {msg += '\tBlackMarkError,\n';}
        if (trader.isPaperEnd             ({traderStatus:response.traderStatus})) {msg += '\tPaperEnd,\n';}
        if (trader.isPaperNearEnd         ({traderStatus:response.traderStatus})) {msg += '\tPaperNearEnd,\n';}

        msg += '\tEtbCounter = ' + trader.extractionEtbCounter({traderStatus:response.traderStatus}).toString() + ' ]\n';

//      msg += 'Status : [ ' + response.status + ' ]\n';
//
//      msg += 'ResponseText : [ ' + response.responseText + ' ]\n';

        alert(msg);
    }

    trader.onError = function (response) {
        var msg = '- onError -\n\n';

        msg += '\tStatus:' + response.status + '\n';

        msg += '\tResponseText:' + response.responseText + '\n\n';

        msg += 'Do you want to retry?\n';

        var answer = confirm(msg);

        if (answer) {
            sendMessage(request);
        }
        else {
            hideNowPrinting();
        }
    }

    trader.sendMessage({request:request});
}

function onSendAscii() {
    var builder = new StarWebPrintBuilder();
//basket
var item1 = "1 Pizza Margarita  80.00 kr\n";
var item2 = "2 Stekt ägg       18.00 kr\n";
var item3 = "1 Ryggbiff        150.00 kr\n";
var item4 = "2 Svenska köttbullar\n"; 
var price4 = "100.00 kr \n";
var item5 = "1 Krämig Carbonara \n";
var price5 = "110.00 kr\n";

    request = '';

    request += builder.createInitializationElement();

    switch (document.getElementById('paperWidth').value) {
        case 'inch2' :
            request += builder.createTextElement({characterspace:2});

            request += builder.createTextElement({codepage:'utf8', international:'sweden'});
            request += builder.createAlignmentElement({position:'left'});
            request += builder.createLogoElement({number:1});
            request += builder.createTextElement({data:'T2 Restaurant\n'});
            request += builder.createTextElement({data:'TEL 9999-99-9999\n'});
            request += builder.createAlignmentElement({position:'left'});

            request += builder.createTextElement({data:'\n'});
            request += builder.createAlignmentElement({position:'center'});
            request += builder.createTextElement({emphasis:true});
            request += builder.createTextElement({width:2, data:'ABC123'});
	    request += builder.createTextElement({width:1});
            request += builder.createTextElement({emphasis:false});
            request += builder.createTextElement({data:'\n'});

            request += builder.createAlignmentElement({position:'left'});

            request += builder.createTextElement({data:'\n'});

            request += builder.createTextElement({data:'' + item1 + ''});
            request += builder.createTextElement({data:'' + item2 + ''});
            request += builder.createTextElement({data:'' + item3 + ''});
            request += builder.createTextElement({data:'' + item4 + ''});
            request += builder.createAlignmentElement({position:'right'});
            request += builder.createTextElement({data:'' + price4 + ''});
            request += builder.createAlignmentElement({position:'left'});
            request += builder.createTextElement({data:'' + item5 + ''});
            request += builder.createAlignmentElement({position:'right'});
            request += builder.createTextElement({data:'' + price5 + ''});
            request += builder.createAlignmentElement({position:'left'});
            request += builder.createTextElement({emphasis:true, data:'Subtotal          458.00 kr\n'});
            request += builder.createTextElement({data:'\n'});

            request += builder.createTextElement({underline:true, data:'Tax                60.00 kr\n'});
            request += builder.createTextElement({underline:false});

            request += builder.createTextElement({emphasis:true});
            request += builder.createTextElement({width:1, data:'Total'});
            request += builder.createTextElement({width:1, data:'             '});
            request += builder.createTextElement({width:1, data:'458.00 kr\n'});
            request += builder.createTextElement({width:1});
            request += builder.createTextElement({emphasis:false});

            request += builder.createTextElement({data:'\n'});

            request += builder.createTextElement({data:'Paid            458.00 kr\n'});
            request += builder.createTextElement({data:'\n'});
            request += builder.createAlignmentElement({position:'center'});
            request += builder.createTextElement({data:"Den här restaurangen finns\nockså i Anar Find&Eat appen\n"});
            request += builder.createTextElement({data:"ladda ner den från\nGoogle Play eller App Store\n"});

            request += builder.createTextElement({width:1});
            request += builder.createTextElement({data:'\n'});

            request += builder.createTextElement({characterspace:0});
            break;


    }

    request += builder.createCutPaperElement({feed:true});

    sendMessage(request);
}

function onAppendSound() {
    try {
        var channel = parseInt(document.getElementById('soundChannel').value);
        var repeat  = parseInt(document.getElementById('soundRepeat') .value);

        request += builder.createSoundElement({channel:channel, repeat:repeat});

        sampleCode += 'request += builder.createSoundElement({channel:' + channel + ', repeat:' + repeat + '});\n';

        updateSampleCode();
    }
    catch (e) {
        alert(e.message);
    }
}

function nowLoading(){
	document.getElementById('form').style.display="block";
	document.getElementById('overlay').style.display="none";
	document.getElementById('nowLoadingWrapper').style.display="none";
}
function showNowPrinting(){
    document.getElementById('overlay').style.display="block";
    document.getElementById('nowPrintingWrapper').style.display="table";
}
function hideNowPrinting(){
    document.getElementById('overlay').style.opacity= 0.0;
    document.getElementById('overlay').style.transition= "all 0.3s";
    intimer = setTimeout(function (){
        document.getElementById('overlay').style.display="none";
    document.getElementById('overlay').style.opacity= 1;
        clearTimeout(intimer);
    }, 300);
    document.getElementById('nowPrintingWrapper').style.display="none";
}
window.onload = function() {
	nowLoading();
}
// -->
</script>
<noscript>
    Your browser does not support JavaScript!
</noscript>
</head>
<body>

	<div id="overlay">
		<div id="nowPrintingWrapper">
			<section id="nowPrinting">
				<h1>Now Printing</h1>
			</section>
		</div>
		<div id="nowLoadingWrapper">
			<section id="nowLoading">
				<h1>Now Loading</h1>
				<p><img src="{{ url('plugins/printer/images/icon_loading.gif') }}" /></p>
			</section>
		</div>
	</div>

<header id="global-header">

</header>

<section class="btmMg20">
	<h2 class="h2-tit-01 btmMg20">API : Receipt</h2>
</section>

    <form onsubmit='return false;' id="form">
		<div class="container">
				<dl>
					<dt>Paper Width</dt>
                    <dd>:
 						<select id='paperWidth'>
							<option value='inch2' selected='selected'>2 Inch</option>
						</select>
					</dd>
				</dl>
                <dl>
                    <dt>Encoding</dt>
                    <dd>:
                        <select id='encoding'>
                            <option value='utf8' selected='selected'>UTF-8</option>
                        </select>
                    </dd>
                </dl>
            <hr>
            <footer>
                <dl>
					<dt>URL</dt>
					<dd>:
                    <input id="url" type="text" value="https://192.168.1.228/StarWebPRNT/SendMessage" /></dd>
				</dl>
                <d1>
                    <dt>Paper Type</dt>
                    <dd>:
                        <select id='papertype'>
                            <option value='normal' selected='selected'>Normal</option>
                        </select>
                    </dd>
                </dl>

				<input id="sendBtnAscii" type="button" value="Send (Ascii)" onclick="onSendAscii()" />
			</footer>
		</div>
	</form>

    <div class="to_top">
    </div>
<footer id="global-footer" class="clearfix">
	<a href="https://www.star-m.jp/starwebprnt-oml.html" target="_blank"><img src="{{ url('plugins/printer/images/footer-logo.png') }}" width="123" alt="" id="footer-logo"></a>
    <img src="{{ url('plugins/printer/images/footer-image.png') }}"height="54" alt=""/>
</footer>

</body>
</html>