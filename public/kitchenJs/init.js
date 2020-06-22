$(function() {
	// Bootstrap tooltip
	if( $('[data-toggle="tooltip"]').length )
	{
		$('[data-toggle="tooltip"]').tooltip();
	}
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
	var orderId = $('#add-manual-prep-time').find('input[name=order_id]').val();
	var itemId = $('#add-manual-prep-time').find('input[name=item_id]').val();

	$.post(RESTAURANT_BASE_URL+"/add-manual-prep-time",
		{
			'_token': $('meta[name="_token"]').attr('content'),
			'order_id': orderId,
			'extra_prep_time': $('input[name="extra_prep_time"]:checked').val()
		},
		function(data) {
			// Start order item or order
			This = (itemId != 'false') ? 'img#'+itemId : 'img#'+orderId;

			if(itemId != 'false')
			{
				// Start item individually for order from 'Kitchen Menu', and update speak
				orderReadyStarted(itemId, This);
				updateSpeak(itemId);
				timechange(This,data.time);
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

// Get list of avalable drivers to assign them to order
function popupOrderAssignDriver(orderId, itemId, isReady = true)
{
	$.ajax({
		url: RESTAURANT_BASE_URL+'/get-available-driver-to-assign/'+orderId,
		dataType: 'json',
		success: function(response) {
			if(!response.orderDelivery || response.orderDelivery.status == 0)
			{
				if(response.driver.length)
				{
					$('#popup-order-assign-driver').find('input[name="order_id"]').val(orderId);
					$('#popup-order-assign-driver').find('input[name="item_id"]').val(itemId);
					$('#popup-order-assign-driver').find('input[name="is_ready"]').val(isReady);
					$('#popup-order-assign-driver').find('table#list-driver tbody').html(response.html);
					$('#popup-order-assign-driver').popup('open');
				}
				else
				{
                    $('.ready_notifications span').html('No Driver Found.');
                    $('.ready_notifications').show();
                    setTimeout(function(){
                    	$('.ready_notifications').hide();
                    },1000);
				}
			}
			else if(isReady)
			{
				if(itemId)
				{
					onReady(itemId);
				}
				else
				{
					window.location.href = urlReadyOrder+'/'+orderId;
				}
			}
		}
	});
}

// Assign driver to order
function orderAssignDriver()
{
	var orderId = $('#popup-order-assign-driver').find('input[name=order_id]').val();
	var itemId = $('#popup-order-assign-driver').find('input[name=item_id]').val();
	var isReady = $('#popup-order-assign-driver').find('input[name=is_ready]').val();
	var driverId = $('#popup-order-assign-driver').find('input[name=driver_id]:checked').val();

	if(driverId != '' && orderId != '')
	{
		$.ajax({
			url: RESTAURANT_BASE_URL+'/order-assign-driver',
			type: 'POST',
			data: {
				'_token': $('meta[name="_token"]').attr('content'),
				'order_id': orderId,
				'driver_id': driverId,
			},
			dataType: 'json',
			success: function(response) {
				$('#popup-order-assign-driver').popup('close');

				if(isReady && isReady != 'false')
				{
					if(itemId != 'false')
					{
						onReady(itemId);
					}
					else
					{
						window.location.href = urlReadyOrder+'/'+orderId;
					}
				}
			}
		});
	}
	else
	{
		alert('Select driver.');
	}
}

// 
function getOrderDeliveryAddress(id)
{
	$.ajax({
		url: RESTAURANT_BASE_URL+'/get-order-delivery-address/'+id,
		dataType: 'json',
		success: function(response) {
			$('#popup-order-delivery-address').find('.addr').html(response.strAddress).text();
			$('#popup-order-delivery-address').popup('open');
		}
	});
}

intervalSpeakText = 0;

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
	var url = BASE_URL_API+'/v1/kitchen/updateTextspeach/'+id;

	$.ajax({
        url: url, //This is the current doc
        type: "GET",//variables should be pass like this
        success: function(data){
           clearInterval(intervalSpeakText);
        }
    });
}

// 
function confirmDelete(){return confirm("Are you sure you want to delete?")}

// Check if new orders comes and user's is not in Kitchen/Orders
if(CURRENT_PATH.indexOf('kitchen/store') == -1 && CURRENT_PATH.indexOf('kitchen/kitchen-detail') == -1)
{
	setInterval(getNewOrderDetailToSpeak, 10000);
}

arrSpeakTextQueue = [];

// Get new orders
function getNewOrderDetailToSpeak()
{
	$.ajax({
		url: RESTAURANT_BASE_URL+'/get-new-orders-detail-to-speak',
		success: function(data) {
			if(data['orderDetail'].length)
			{
				orderDetail = data['orderDetail'];
				textSpeech = data['text_speech'];

				// If textSpeech is ON else speak default text
				if(textSpeech == 1)
				{
					var isNew = 0;

					for(var j = 0; j < orderDetail.length; j++)
					{
						id = orderDetail[j]['id'];

						var result = $.grep(arrSpeakTextQueue, function(e){ 
							return e.id == id;
						});

						if(!result.length)
						{
							if(orderDetail[j]['is_speak'] == 0)
							{
								isNew = 1;

								if(orderDetail[j]["product_description"] != null){
			          				var message = orderDetail[j]["product_quality"]+orderDetail[j]["product_name"]+orderDetail[j]["product_description"];
			          			}else{
			          				var message = orderDetail[j]["product_quality"]+orderDetail[j]["product_name"];
			          			}

								arrSpeakTextQueue.push({id: orderDetail[j]['id'], text: message});
							}
						}
					}

					if(isNew)
					{
						speakTextQueue();
					}
				}
				else
				{
					speakText(data['kitchenTextToSpeechDefault'], 1);
				}
			}
		}
	});
}

// Loop array and speak text 
function speakTextQueue()
{
	if(arrSpeakTextQueue.length)
	{
		var k = 0;
		var j = arrSpeakTextQueue.length;

		speakTextInterval = setInterval(function() {
			speakText(arrSpeakTextQueue[k].text);
			k++;

			if(k >= j)
			{
				clearInterval(speakTextInterval);
			}
		}, 4000);
	}
}