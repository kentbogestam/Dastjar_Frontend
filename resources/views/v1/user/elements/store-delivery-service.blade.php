@if( (Request::is('iframe/restro-menu-list/*') || Request::is('restro-menu-list/*') || Request::is('view-cart/*') || Request::is('cart')) && (isset($storedetails) && $storedetails->deliveryTypes->count() > 0 && $storedetails->deliveryTypes->count() < 3) )
	<div class="tag-line-service">
		@php
		$deliveryType = array();

		foreach($storedetails->deliveryTypes as $row)
		{
			if($row->delivery_type == 1)
			{
				array_push($deliveryType, __('messages.deliveryOptionDineIn'));
			}
			elseif($row->delivery_type == 2)
			{
				array_push($deliveryType, __('messages.deliveryOptionTakeAway'));
			}
			elseif($row->delivery_type == 3 && Helper::isPackageSubscribed(12))
			{
				array_push($deliveryType, __('messages.deliveryOptionHomeDelivery'));
			}
		}

		$deliveryType = implode(', ', $deliveryType)
		@endphp

		<p>{{ __('messages.storeDeliveryType', ['deliveryType' => $deliveryType]) }}</p>
	</div>
@endif