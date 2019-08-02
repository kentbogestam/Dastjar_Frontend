

jQuery(window).load(function () {
    var dateVar = $('#date16').handleDtpicker('getDate');  
	 $('#date-value1-2').html(dateVar);
});

function checkDate(){
	var dateVar = $('#date16').handleDtpicker('getDate');
	$('#date-value1-23').val(dateVar.toString());

	// var curDate = new Date().getTime();
	var selDate = new Date($('#date-value1-23').val()).getTime();
	hdate = moment(selDate).toDate();
	// utcdate = moment.utc(hdate);
	var cur=  new Date();
	var sel = new Date($('#date-value1-23').val());

	// 
	currentDateTime = moment();
	orderDateTime = moment(sel.getTime());
	hoursDiff = orderDateTime.diff(currentDateTime, 'hours');

	// if(cur.getDate()==sel.getDate()){
	if(hoursDiff < 2){
		$('.error_time3').show();
	}
	else{
		$('#date-value1-23').val(hdate);					
		$('.error_time').hide();
		$("#form").submit();
	}
}

function setDateTime(){
	// alert('setDateTime');
	var dateVar = $('#date16').handleDtpicker('getDate');       
	$('#date-value1-23').val(dateVar);
	$('#date-value1-2').html(dateVar);
}