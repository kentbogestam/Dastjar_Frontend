@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid full">
		<div class="row">
			<div class="col-md-12 text-center"><h2>T2 Restaurant</h2></div>
		</div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Order</th>
					<th>Restaurant</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Accept</th>
					<th>Paid</th>
					<th>Wanted Time</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
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
							html += "<tr>"+
								"<td>"+response.orderDelivery[i]['order_id']+"</td>"+
								"<td>"+response.orderDelivery[i]['store_name']+"</td>"+
								"<td><a href='javascript:void(0) target='_blank' class='link'>"+response.orderDelivery[i]['street']+"<br>"+response.orderDelivery[i]['city']+"</a></td>"+
								"<td><a href='tel:"+response.orderDelivery[i]['phone']+"'><i class='fas fa-phone-square-alt fa-2x'></i></a></td>"+
								"<td><a href='javascript:void(0)''><i class='fas fa-minus-circle fa-2x'></i></a></td>"+
								"<td></td>"+
								"<td>00:00</td>"+
							+"</tr>";
						}

						$('.table').find('tbody').html(html);
					}
				}
			});
		}
	</script>
@endsection