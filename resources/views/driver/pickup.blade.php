@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center"><h2>{{ $company->company_name }}</h2></div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-listing">
				<thead>
					<tr>
						<th><i class="fas fa-list-alt" data-toggle="tooltip" title="{{ __('messages.Orders') }}"></i></th>
						<th><i class="fas fa-utensils" data-toggle="tooltip" title="{{ __('messages.Restaurant') }}"></i></th>
						<th><i class="fas fa-map-marked-alt" data-toggle="tooltip" title="{{ __('messages.address') }}"></i></th>
						<th><i class="fas fa-phone" data-toggle="tooltip" title="{{ __('messages.phone') }}"></i></th>
						<th><i class="fas fa-check-circle" data-toggle="tooltip" title="{{ __('messages.accept') }}"></i></th>
						<th><i class="fas fa-stopwatch" data-toggle="tooltip" title="{{ __('messages.pickup') }}"></i></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(function() {
			// On order accept
			$(document).on('click', '.order-pickup-accept', function() {
				var orderDeliveryId = $(this).data('id');
				orderPickupAccept(orderDeliveryId, $(this));
			});

			// On order picked-up
			$(document).on('click', '.order-pickup-pickedup', function() {
				if( !$(this).hasClass('disabled') )
				{
					var orderDeliveryId = $(this).data('id');
					orderPickupPickedup(orderDeliveryId, $(this));
				}
			});
		});

		// Get pickup order detail
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

							if(response.orderDelivery[i]['order_response'])
							{
								pickupTime = addTimes(response.orderDelivery[i]['deliver_time'], response.orderDelivery[i]['order_delivery_time'], response.orderDelivery[i]['extra_prep_time']);
							}
							else
							{
								pickupTime = addTimes(response.orderDelivery[i]['deliver_time'], response.orderDelivery[i]['o_extra_prep_time']);
							}
							
							html += "<tr>"+
								'<td><a href="javascript:getOrderDetail(\''+customer_order_id+'\')" class="link">'+customer_order_id+'</a></td>'+
								"<td>"+response.orderDelivery[i]['store_name']+"</td>"+
								"<td><a href='{{ url('driver/pickup-direction') }}/"+response.orderDelivery[i]['order_id']+"' class='link'>"+response.orderDelivery[i]['street']+"<br>"+response.orderDelivery[i]['city']+" <i class='fas fa-directions'></i></a></td>"+
								"<td class='text-center'><a href='tel:"+response.orderDelivery[i]['phone']+"'><i class='fas fa-phone-volume'></i></a></td>";

							if(response.orderDelivery[i]['status'] == '0')
							{
								html += 
									"<td class='text-center'><a href='javascript:void(0)' class='order-pickup-accept' data-id='"+response.orderDelivery[i]['id']+"'><i class='fas fa-minus-circle'></i></a></td>"+
									"<td class='text-center'><a href='javascript:void(0)' class='order-pickup-pickedup disabled' data-id='"+response.orderDelivery[i]['id']+"'><i class='fas fa-minus-circle'></i></a><br>"+pickupTime+"</td>";
							}
							else
							{
								html +=
									"<td class='text-center'><i class='fas fa-check-circle'></i></td>"+
									"<td class='text-center'><a href='javascript:void(0)' class='order-pickup-pickedup' data-id='"+response.orderDelivery[i]['id']+"'><i class='fas fa-minus-circle'></i></a><br>"+pickupTime+"</td>";
							}
							
							html += "</tr>";
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

		// 
		getPickupOrderList();
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
						$This.closest('tr').find('.order-pickup-pickedup').removeClass('disabled');
						$This.replaceWith("<i class='fas fa-check-circle'></i>");
					}
				}
			});
		}

		// Order picked-up from store
		function orderPickupPickedup(orderDeliveryId, This)
		{
			$This = $(This);

			$.ajax({
				url: '{{ url('driver/order-pickup-pickedup') }}/'+orderDeliveryId,
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