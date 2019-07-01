@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid full">
		<div class="row">
			<div class="col-md-12 text-center"><h2>{{ $company->company_name }}</h2></div>
		</div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Order</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Delivered</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		getDeliverOrderList();

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

							html += '<tr>'+
								'<td><a href="javascript:getOrderDetail(\''+customer_order_id+'\')" class="link">'+customer_order_id+'</a></td>'+
								'<td>'+response.orderDelivery[i]['full_name']+'</td>'+
								'<td><a href="javascript:void(0)" target="_blank" class="link">'+response.orderDelivery[i]['street']+'<br>'+response.orderDelivery[i]['city']+'</a></td>'+
								'<td><a href="tel:'+response.orderDelivery[i]['mobile']+'"><i class="fas fa-phone-square-alt fa-2x"></i></a></td>'+
								'<td><a href="{{ url('driver/order-deliver') }}/'+response.orderDelivery[i]['customer_order_id']+'"><i class="fas fa-minus-circle fa-2x"></i></a></td>'
							'</tr>';
						}

						$('.table').find('tbody').html(html);
					}
					else
					{
						$('.table').find('tbody').html('<tr><td colspan="5" class="text-center">{{ __('messages.noRecordFound') }}</td></tr>');
					}
				}
			});
		}

		setInterval(getDeliverOrderList, 30000);
	</script>
@endsection