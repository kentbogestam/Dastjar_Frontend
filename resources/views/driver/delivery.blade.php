@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid full">
		<div class="row">
			<div class="col-md-12 text-center"><h2>Delivery</h2></div>
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
			<tbody>
				@if(!$orderDelivery->isEmpty())
					@foreach($orderDelivery as $row)
						<tr>
							<td><a href="javascript:getOrderDetail({{ $row->order_id }})" class="link">{{ $row->customer_order_id }}</a></td>
							<td>{{ $row->full_name }}</td>
							<td><a href="javascript:void(0)" target="_blank" class="link">{{ $row->street.', '.$row->city }}</a></td>
							<td><a href="tel:{{ $row->mobile }}"><i class="fas fa-phone-square-alt fa-2x"></i></a></td>
							<td>
								<a href="{{ url('driver/order-deliver/'.$row->customer_order_id) }}"><i class="fas fa-minus-circle fa-2x"></i></a>
							</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="6" class="text-center">No more order to deliver.</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>

	<!-- Modal: order detail -->
	<div id="modal-order-detail" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Order Detail</h4>
				</div>
				<div class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th>Order ID</th>
								<th>Product</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		// 
		function getOrderDetail(orderId)
		{
			// $('#modal-order-detail').modal('show');
			$.ajax({
				url: '{{ url('driver/get-order-detail') }}/'+orderId,
				dataType: 'json',
				success: function(response) {
					if(response.html)
					{
						$('#modal-order-detail').find('.table tbody').html(response.html);
					}

					$('#modal-order-detail').modal('show');
				}
			});
		}
	</script>
@endsection