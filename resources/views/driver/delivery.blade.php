@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid full">
		<div class="row">
			<div class="col-md-12 text-center"><h2>{{ $company->company_name }}</h2></div>
		</div>
		<table class="table table-bordered table-listing">
			<thead>
				<tr>
					<th><i class="fas fa-list-alt" data-toggle="tooltip" title="{{ __('messages.Orders') }}"></i></th>
					<th><i class="fas fa-user" data-toggle="tooltip" title="{{ __('messages.name') }}"></i></th>
					<th><i class="fas fa-map-marked-alt" data-toggle="tooltip" title="{{ __('messages.address') }}"></i></th>
					<th><i class="fas fa-phone" data-toggle="tooltip" title="{{ __('messages.phone') }}"></i></th>
					<th><i class="fas fa-check-circle" data-toggle="tooltip" title="{{ __('messages.Delivered') }}"></i></th>
					<th><i class="fas fa-credit-card" data-toggle="tooltip" title="{{ __('messages.Paid') }}"></i></th>
					<th><i class="fas fa-user-clock" data-toggle="tooltip" title="{{ __('messages.wanted_time') }}"></i></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
	<script type="text/javascript">
		// 
		async function getDeliverOrderList()
		{
			$.ajax({
				url: '{{ url('driver/get-deliver-order-list') }}',
				dataType: 'json',
				success: function(response) {
					if(response.orderDelivery.length)
					{
						// console.log('start');
						let html = '';
						for(let i = 0; i < response.orderDelivery.length; i++)
						{
							let address2 = new Array();
							let customer_order_id = response.orderDelivery[i]['customer_order_id'];
							
							// 
							let address = (response.orderDelivery[i]['address']) ? response.orderDelivery[i]['address']+', ' : '';
							address += response.orderDelivery[i]['street']+'<br>'+response.orderDelivery[i]['city'];

							// 
							if(response.orderDelivery[i]['address'])
							{
								address2.push(response.orderDelivery[i]['address']);
							}
							else
							{
								if(response.orderDelivery[i]['entry_code'])
								{
									address2.push("{{ __('messages.entryCode') }}: "+response.orderDelivery[i]['entry_code']);
								}

								if(response.orderDelivery[i]['apt_no'])
								{
									address2.push("{{ __('messages.aptNo') }}: "+response.orderDelivery[i]['apt_no']);
								}

								if(response.orderDelivery[i]['company_name'])
								{
									address2.push("{{ __('messages.companyName') }}: "+response.orderDelivery[i]['company_name']);
								}

								if(response.orderDelivery[i]['other_info'])
								{
									address2.push("{{ __('messages.otherInfo') }}: "+response.orderDelivery[i]['other_info']);
								}
							}
							
							if(address2.length)
							{
								address2 = address2.join(', ');
								address2 = "<br>"+address2;
							}

							let delivered = '';
							if(response.orderDelivery[i]['paid'])
							{
								delivered += '<span class="label label-success">{{ __('messages.yes') }}</span>&nbsp;'+
								'<a href="{{ url('driver/order-deliver') }}/'+response.orderDelivery[i]['customer_order_id']+'"><i class="fas fa-undo" title="Undo"></i></a>';
							}
							else
							{
								delivered += '<a href="{{ url('driver/order-deliver') }}/'+response.orderDelivery[i]['customer_order_id']+'"><i class="fas fa-minus-circle fa-2x"></i></a>';
							}

							let paid = '';
							if(response.orderDelivery[i]['online_paid'] == 1 || response.orderDelivery[i]['online_paid'] == 3)
							{
								paid = '<span class="label label-success">{{ __('messages.yes') }}</span>';
							}
							else if(response.orderDelivery[i]['online_paid'] == 0)
							{
								paid = '<button type="button" class="btn btn-warning btn-xs" onclick="orderPayManually('+response.orderDelivery[i]['order_id']+', this)">{{ __('messages.pay_manual') }}</button>';
							}

							let timeObj;

							// let time = addTimes(response.orderDelivery[i]['order_delivery_time'], response.orderDelivery[i]['deliver_time'], response.orderDelivery[i]['extra_prep_time']);
							if(response.orderDelivery[i]['order_response'])
							{
								timeObj = new Array(response.orderDelivery[i]['order_delivery_time'], response.orderDelivery[i]['deliver_time'], response.orderDelivery[i]['extra_prep_time']);
							}
							else
							{
								timeObj = new Array(response.orderDelivery[i]['deliver_time'], response.orderDelivery[i]['o_extra_prep_time']);
							}
							let time = addTimeByMoment(timeObj);
							
							getDistanceMatrix(response.orderDelivery[i]['store_address'], response.orderDelivery[i]['customer_address'])
								.then(duration => {
									// Add travelling time (driving)
									if(duration == null)
									{
										time = '';
									}
									else
									{
										time = moment(time, 'HH:mm:ss').add(duration, 'seconds').format('HH:mm');
									}

									// Draw HTML
									html = '<tr>'+
										'<td><a href="javascript:getOrderDetail(\''+customer_order_id+'\')" class="link">'+customer_order_id+'</a></td>'+
										'<td>'+response.orderDelivery[i]['full_name']+'</td>'+
										// "<td><a href='https://www.google.com/maps/place/"+response.orderDelivery[i]['customer_address']+"' target='_blank' class='link'>"+address+" <i class='fas fa-directions'></i></a></td>"+
										"<td><a href='{{ url('driver/delivery-direction') }}/"+response.orderDelivery[i]['order_id']+"' class='link'>"+address+" <i class='fas fa-directions'></i></a>"+address2+"</td>"+
										'<td class="text-center"><a href="tel:'+response.orderDelivery[i]['mobile']+'"><i class="fas fa-phone-volume"></i></a></td>'+
										'<td class="text-center">'+delivered+'</td>'+
										'<td class="text-center">'+paid+'</td>'+
										'<td>'+time+'</td>'
									'</tr>';

									$('.table').find('tbody').append(html);
								});
						}
						// console.log('finished');
					}
					else
					{
						$('.table').find('tbody').html('<tr><td colspan="7" class="text-center">{{ __('messages.noRecordFound') }}</td></tr>');
					}
				}
			});
		}

		getDeliverOrderList();
		// setInterval(getDeliverOrderList, 30000);
		
		// Add times
		function addTimeByMoment(timeObj)
		{
			var time = moment('00:00:00', 'HH:mm:ss');

			for(let i = 0; i < timeObj.length; i++)
			{
				if(timeObj[i] != null)
				{
					arrTime = timeObj[i].split(':');
					time = time.add(arrTime[0], 'hours').add(arrTime[1], 'minutes');
				}
			}

			return time.format('HH:mm');
		}

		// 
		function orderPayManually(orderId, This)
		{
			This = $(This);

			$.get("{{url('driver/order-pay-manually')}}/"+orderId,
			function(returnedData){
				console.log(returnedData);
				if(returnedData["status"])
				{
					This.replaceWith('<span class="label label-success">{{ __('messages.yes') }}</span>');
				}
				else
				{
					alert('{{ __('messages.somethingWentWrong') }}');
				}
			});
		}

		// 
		async function getDistanceMatrix(origin, destination)
		{
		   	return new Promise((resolve, reject) => {
		   		setTimeout(() => {
		   			// return resolve(27)
		   			service = new google.maps.DistanceMatrixService;

					service.getDistanceMatrix({
						origins: [origin],
						destinations: [destination],
						travelMode: 'DRIVING',
						unitSystem: google.maps.UnitSystem.METRIC,
						avoidHighways: false,
						avoidTolls: false
					}, function(response, status) {
						// console.log(response);
						if(status !== 'OK')
						{
							alert('Error was: ' + status);
						}
						else
						{
							if(response.rows[0].elements[0].status !== 'undefined' && response.rows[0].elements[0].status == 'OK')
							{
								return resolve(response.rows[0].elements[0].duration.value)
							}
							else
							{
								return resolve(null);
							}
						}
					});
		   		}, 10)
		   	})
		}
	</script>
	<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2sOhO6FvI99miUsi_ukqvn3u3XVO4JLg">
    </script>
	<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script> -->
@endsection