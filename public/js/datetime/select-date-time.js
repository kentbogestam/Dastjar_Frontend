

jQuery(window).load(function () {
    var dateVar = $('#date16').handleDtpicker('getDate');  
	 		$('#date-value1-2').html(dateVar);

});

function checkDate(){

	var dateVar = $('#date16').handleDtpicker('getDate');
         	$('#date-value1-23').val(dateVar.toString());

				var curDate = new Date().getTime();
				var selDate = new Date($('#date-value1-23').val()).getTime();

			    var cur=  new Date();
			    var sel = new Date($('#date-value1-23').val());

				hdate = moment(selDate).toDate();
				utcdate = moment.utc(hdate);

               if(cur.getDate()==sel.getDate()){
					
                  $('.error_time3').show();
					
				
				}else{

					$('#date-value1-23').val(hdate);					
					$('.error_time').hide();
					$("#form").submit();
				}
}

function setDateTime(){

	 var dateVar = $('#date16').handleDtpicker('getDate');       
         $('#date-value1-23').val(dateVar);
         $('#date-value1-2').html(dateVar);
}