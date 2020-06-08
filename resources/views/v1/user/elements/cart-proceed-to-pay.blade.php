<div class="row" style="padding: 10px 0">
    @if($storeDetail->online_payment == 1)
		<div class="col-md-12 text-center">
			<button type="button" class="btn btn-pay btn-success" disabled="">{{ __('messages.proceedToPay') }}</button>
		</div>
		<div class="col-md-12 panel panel-default row-confirm-payment hidden">
			@if(isset($paymentMethod->data))
				@php
				$isCardDefault = false;

				if( count($paymentMethod->data) == 1 )
				{
					$isCardDefault = true;
				}
				@endphp
				<div class="row-saved-cards">
					<form id="list-saved-cards">
						@foreach($paymentMethod->data as $row)
							<div class="radio">
								<label>
									<input type="radio" name="payment_method_id" value="{{ $row->id }}" <?php echo ($isCardDefault) ? 'checked' : ''; ?>>
									<i class="fa fa-cc-visa" aria-hidden="true"></i>
									<i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i><i class="fa fa-circle" aria-hidden="true" style="font-size: 9px;"></i>
									{{ $row->card->last4 }}
								</label>
								<button type="button" class="btn btn-link btn-xs" onclick="deleteSource('{{ $row->id }}', this)">Delete</button>
							</div>
						@endforeach
						<div class="card-errors text-danger"></div>
						<button type="button" id="charging-saved-cards" class="btn btn-success" style="{{ !($isCardDefault) ? 'display: none' : '' }}">{{ __('messages.paySecurely') }}</button>
					</form>
				</div>
			@endif
			<div class="row-new-card">
				<div class="radio">
					<label>
						<input type="radio" name="pay-options" id="pay-options">
						{{ __('messages.payOptions') }}
					</label>
				</div>
				<div class="section-pay-with-card hidden">
					<form id="payment-form">
						<!-- placeholder for Elements -->
						<div id="card-element"></div>
						<div class="card-errors text-danger"></div>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="isSaveCard" id="isSaveCard" checked="">
								{{ __('messages.saveCardInfo') }}
							</label>
						</div>
						<button type="button" id="card-button" class="btn">{{__('messages.Pay with card')}}</button>
						<ul>
							<li><i class="fa fa-cc-stripe" aria-hidden="true"></i></li>
							<li><i class="fa fa-cc-amex" aria-hidden="true"></i></li>
							<li><i class="fa fa-cc-mastercard" aria-hidden="true"></i></li>
							<li><i class="fa fa-cc-visa" aria-hidden="true"></i></li>
						</ul>
					</form>
				</div>
			</div>
		</div>
	@else
		<div class="col-md-12 text-center">
			<button type="button" class="btn btn-primary send-order" disabled="">{{ __('messages.send order and pay in restaurant') }}</button>
		</div>
	@endif
</div>