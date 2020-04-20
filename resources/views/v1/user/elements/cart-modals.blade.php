<!-- Modal delete cart -->
<div id="delete-cart-alert" class="modal fade" role="dialog">
	<div class='modal-dialog'>
		<div class="modal-content">
			<div class="modal-body text-center">
				<p>{{ __("messages.Delete Cart Order") }}</p><br>
           		<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
				<button type="button" class="btn btn-primary submit-btn" onclick="deleteFullCart('{{ url("emptyCart/") }}','1','{{ __("messages.Delete Cart Order") }}')">{{ __('messages.Delete') }}</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal delete cart item -->
<div id="delete-cart-item-alert" class="modal fade" role="dialog">
	<div class='modal-dialog'>
		<div class="modal-content">
			<div class="modal-body text-center">
				<p>{{ __("messages.Delete Product") }}</p><br>
           		<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
				<button type="button" class="btn btn-primary delete">{{ __('messages.Delete') }}</button>
			</div>
		</div>
	</div>
</div>

<!-- pop modal when rs offline -->
<div class="modal fade" id="store-not-live-alert" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-body text-center">
				{{ __('messages.storeNotLive') }}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
			</div>
		</div>
	</div>
</div>