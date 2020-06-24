@if($storedetails->deliveryTypes->count() >= 1)
	<div class="contact-form-section row-order-delivery-type">
		<ul>
			@foreach($storedetails->deliveryTypes as $row)
				@if($row->delivery_type == 1 || $row->delivery_type == 2 || ($row->delivery_type == 3 && Helper::isPackageSubscribed(12)))
					<li>
						<input type="radio" name="delivery_type" value="{{ $row->delivery_type }}" id="delivery_type{{ $row->delivery_type }}">
						<label for="delivery_type{{ $row->delivery_type }}" class="delivery_type_3" rel="{{ $row->delivery_type }}">
							@if($row->delivery_type == 1)
								<img src="{{ asset('v1/images/dine.png') }}" alt="">
								{{ __('messages.deliveryOptionDineIn') }}
							@elseif($row->delivery_type == 2)
								<img src="{{ asset('v1/images/dinner-1.png') }}" alt="">
								{{ __('messages.deliveryOptionTakeAway') }}
							@elseif($row->delivery_type == 3 && Helper::isPackageSubscribed(12))
								<img src="{{ asset('v1/images/car.png') }}" alt="">
								{{ __('messages.deliveryOptionHomeDelivery') }}
							@endif
						</label>
					</li>
				@endif
			@endforeach
		</ul>
		{{-- delivery at door checkbox added--}}
        <div class="checkbox delivery_at_door" style="display: none">
            <label><input type="checkbox" id="delivery_at_door" value="0"> <b>{{ __('messages.deliveryAtDoor') }}</b></label>
        </div>
	</div>
@else
	@foreach($storedetails->deliveryTypes as $row)
		@if($storedetails->deliveryTypes[0]['delivery_type'] != 3 || Helper::isPackageSubscribed(12))
			<input type="radio" name="delivery_type" value="{{ $row->delivery_type }}" checked="" class="hidden">
		@endif
	@endforeach
@endif