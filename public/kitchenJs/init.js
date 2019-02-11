$(function() {

});

// Check if order qualified to show popup to add manual extra time
function isManualPrepTimeForOrder(orderId, itemId, This)
{
	$.get(RESTAURANT_BASE_URL+"/is-manual-prep-time-for-order/"+orderId,
	function(returnedData){
		if(!returnedData.count)
		{
			$('#add-manual-prep-time').find('input[name="order_id"]').val(orderId);
			$('#add-manual-prep-time').find('input[name="item_id"]').val(itemId);
			$('#add-manual-prep-time').popup('open');
		}
		else
		{
			// Start order item or order
			if(itemId)
			{
				// Start item individually for order from 'Kitchen Menu', and update speak
				orderReadyStarted(itemId, This);
				updateSpeak(itemId);
			}
			else
			{
				// Start order from 'Order Menu' and stop text to speak
				startOrder(orderId, This);
				clearSpeakTextInterval();
			}
		}
	});
}

// Update the manual extra time for order and then start the order/item
function frmAddManualPrepTime()
{
	$.post(RESTAURANT_BASE_URL+"/add-manual-prep-time",
		{
			'_token': $('meta[name="_token"]').attr('content'),
			'order_id': $('input[name=order_id]').val(),
			'extra_prep_time': $('input[name="extra_prep_time"]:checked').val()
		},
		function(data) {
			// Start order item or order
			orderId = $('input[name=order_id]').val();
			itemId = $('input[name=item_id]').val();

			This = (itemId != 'false') ? 'img#'+itemId : 'img#'+orderId;

			if(itemId != 'false')
			{
				// Start item individually for order from 'Kitchen Menu', and update speak
				orderReadyStarted(itemId, This);
				updateSpeak(itemId);
			}
			else
			{
				// Start order from 'Order Menu' and stop text to speak
				startOrder(orderId, This);
				clearSpeakTextInterval();
			}
			
			// Close the popup
			$( "#add-manual-prep-time" ).popup("close");
		}
	);
}

// Function to speak text once/repeat 
function speakText(message = null, repeat = 0)
{
	clearInterval(intervalSpeakText);
	test(message);

	if(repeat)
	{
		intervalSpeakText = setInterval(function() {
			test(message);
		}, 5000);
	}
}

// Stop speakText interval
function clearSpeakTextInterval()
{
	clearInterval(intervalSpeakText);
}

// Update column is DB to speak it once only
function updateSpeak(id){
	// var url = '{{url('api/v1/kitchen/updateTextspeach')}}'+'/'+id;
	var url = BASE_URL_API+'/v1/kitchen/updateTextspeach/'+id;

	$.ajax({
        url: url, //This is the current doc
        type: "GET",//variables should be pass like this
        success: function(data){
           //console.log('fff');
           clearInterval(intervalSpeakText);
        }
    });
}