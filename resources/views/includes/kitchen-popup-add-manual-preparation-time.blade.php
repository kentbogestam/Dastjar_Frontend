<!-- Popup to add manual preparation time -->
<div data-role="popup" data-dismissible="false" id="add-manual-prep-time" style="max-width:450px;">
	<div data-role="header"><h2>Preparation Time</h2></div>
	<div class="ui-content" style="padding: 15px;">
		<form name="frm-add-manual-prep-time">
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>{{ __('messages.textAddManualPrepTime') }}</legend>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-0" value="0" checked="checked">
				<label for="manual-prep-time-0">0</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-5" value="5">
				<label for="manual-prep-time-5">5</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-10" value="10">
				<label for="manual-prep-time-10">10</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-15" value="15">
				<label for="manual-prep-time-15">15</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-20" value="20">
				<label for="manual-prep-time-20">20</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-30" value="30">
				<label for="manual-prep-time-30">30</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-40" value="40">
				<label for="manual-prep-time-40">40</label>
				<input type="radio" name="extra_prep_time" id="manual-prep-time-50" value="50">
				<label for="manual-prep-time-50">50</label>
			</fieldset>
			<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-b" data-rel="back">Cancel</a>
			<input type="hidden" name="order_id">
			<button type="button" onclick="frmAddManualPrepTime()" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left">Update</button>
		</form>
	</div>
</div>