@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid full">
		<div class="row">
			<div class="col-md-12 text-center"><h2>{{ $company->company_name }}</h2></div>
		</div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>{{ __('messages.Orders') }}</th>
					<th>{{ __('messages.name') }}</th>
					<th>{{ __('messages.address') }}</th>
					<th>{{ __('messages.phone') }}</th>
					<th>{{ __('messages.Delivered') }}</th>
					<th>{{ __('messages.Paid') }}</th>
					<th>{{ __('messages.wanted_time') }}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		// 
		function getDeliverOrderList()
		{
			$.ajax({
				url: '{{ url('driver/get-deliver-order-list') }}',
				dataType: 'json',
				success: function(response) {
					if(response.orderDelivery.length)
					{
						var html = '';
						for(var i = 0; i < response.orderDelivery.length; i++)
						{
							customer_order_id = response.orderDelivery[i]['customer_order_id'];
							address = (response.orderDelivery[i]['address']) ? response.orderDelivery[i]['address']+', ' : '';
							address += response.orderDelivery[i]['street']+'<br>'+response.orderDelivery[i]['city'];

							paid = '';
							if(response.orderDelivery[i]['online_paid'] == 1 || response.orderDelivery[i]['online_paid'] == 3)
							{
								paid = '<span class="label label-success">Yes</span>';
							}
							else if(response.orderDelivery[i]['online_paid'] == 0)
							{
								paid = '<button type="button" class="btn btn-warning" onclick="orderPayManually('+response.orderDelivery[i]['order_id']+', this)">{{ __('messages.pay_manual') }}</button>';
							}

							html += '<tr>'+
								'<td><a href="javascript:getOrderDetail(\''+customer_order_id+'\')" class="link">'+customer_order_id+'</a></td>'+
								'<td>'+response.orderDelivery[i]['full_name']+'</td>'+
								"<td><a href='https://www.google.com/maps/place/"+response.orderDelivery[i]['full_address']+"' target='_blank' class='link'>"+address+" <i class='fas fa-directions'></i></a></td>"+
								'<td><a href="tel:'+response.orderDelivery[i]['mobile']+'"><i class="fas fa-phone-alt fa-2x"></i></a></td>'+
								'<td><a href="{{ url('driver/order-deliver') }}/'+response.orderDelivery[i]['customer_order_id']+'"><i class="fas fa-minus-circle fa-2x"></i></a></td>'+
								'<td>'+paid+'</td>'+
								'<td></td>'
							'</tr>';
						}

						$('.table').find('tbody').html(html);
					}
					else
					{
						$('.table').find('tbody').html('<tr><td colspan="7" class="text-center">{{ __('messages.noRecordFound') }}</td></tr>');
					}
				}
			});
		}

		getDeliverOrderList();
		setInterval(getDeliverOrderList, 30000);

		function orderPayManually(orderId, This)
		{
			This = $(This);

			$.get("{{url('kitchen/order-pay-manually')}}/"+orderId,
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
	</script>
@endsection