$(document).ready(function () {
	navigator.sayswho= (function(){
		var ua= navigator.userAgent, tem, 
		M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
		if(/trident/i.test(M[1])){
			tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
			return 'IE '+(tem[1] || '');
		}
		if(M[1]=== 'Chrome'){
			tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
			if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
		}
		M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
		if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);

		//console.log("browserVersion=" + M.join(' '));
		var browserVersion = M.join(' ');
		var getBrowser = browserVersion.split(" ");
		var browser = getBrowser[0];
		document.cookie="iphonePopupcount=" + 1;
		document.cookie="browser=" + browser;
		document.cookie="browserVersion=" + M.join(' ');
		var string = M.join(' ');
		string = string.split(" ");
		if(string[0] == 'Safari'){
			$('#facebook-hide').hide();
			$('#google-hide').hide();
		}
	})();
});

// Set restaurant 'type_selection' and redirect user on 'Eat Now/Eat Later'
function setResttype(url,type){
	var d = new Date();

	$.get(url, { restType: type, currentdateTime : d}, 
		function(returnedData){
		// window.location.href = returnedData["data"];
	});
}