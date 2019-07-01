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
					<th>Restaurant</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Accept</th>
					<th>Pick-up Time</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(function() {
			$(document).on('click', '.order-pickup-accept', function() {
				var orderDeliveryId = $(this).data('id');
				orderPickupAccept(orderDeliveryId, $(this));
			});
		});

		getPickupOrderList();

		// 
		function getPickupOrderList()
		{
			$.ajax({
				url: '{{ url('driver/get-pickup-order-list') }}',
				dataType: 'json',
				success: function(response) {
					if(response.orderDelivery.length)
					{
						var html = '';
						for(var i = 0; i < response.orderDelivery.length; i++)
						{
							customer_order_id = response.orderDelivery[i]['customer_order_id'];
							
							html += "<tr>"+
								"<td><a href='javascript:getOrderDetail(\""+customer_order_id+"\")' class='link'>"+customer_order_id+"</a></td>"+
								"<td>"+response.orderDelivery[i]['store_name']+"</td>"+
								"<td><a href='javascript:void(0)' target='_blank' class='link'>"+response.orderDelivery[i]['street']+"<br>"+response.orderDelivery[i]['city']+"</a></td>"+
								"<td><a href='tel:"+response.orderDelivery[i]['phone']+"'><i class='fas fa-phone-square-alt fa-2x'></i></a></td>"+
								"<td><a href='javascript:void(0)' class='order-pickup-accept' data-id='"+response.orderDelivery[i]['id']+"'><i class='fas fa-minus-circle fa-2x'></i></a></td>"+
								"<td>00:00</td>"+
							+"</tr>";
						}

						$('.table').find('tbody').html(html);
					}
					else
					{
						$('.table').find('tbody').html('<tr><td colspan="6" class="text-center">{{ __('messages.noRecordFound') }}</td></tr>');
					}
				}
			});
		}

		setInterval(getPickupOrderList, 30000);

		// Accept order pickup
		function orderPickupAccept(orderDeliveryId, This)
		{
			$This = $(This);

			$.ajax({
				url: '{{ url('driver/order-pickup-accept') }}/'+orderDeliveryId,
				success: function(response) {
					if(response.status)
					{
						$This.closest('tr').remove();
					}
				}
			});
		}
	</script>
@endsection