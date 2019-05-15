<div class="form-group">
    <label for="store_id">{{ __('messages.selectStore') }} <span class='mandatory'>*</span>:</label>
    <select class="form-control" id="store_id" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
        <option value="">{{ __('messages.select') }}</option>
        @if($store)
            @foreach($store as $row)
                <option value='{{ $row->store_id }}'>{{ $row->store_name }}</option>
            @endforeach
        @endif
    </select>
</div>
<div class="form-group">
    <label for="dish_type_id">{{ __('messages.dishType') }} <span class='mandatory'>*</span>:</label>
    <select multiple name="dish_type_id[]" class="form-control" id="dish_type_id" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
        <option value="">{{ __('messages.select') }}</option>
        @if($dishType)
            @foreach($dishType as $row)
                <option value='{{ $row->dish_id }}'>{{ $row->dish_name }}</option>
            @endforeach
        @endif
    </select>
</div>
<div class="form-group">
    <label for="quantity_to_buy">{{ __('messages.quantityToBuy') }} <span class='mandatory'>*</span>:</label>
    <input type="number" name="quantity_to_buy" placeholder="{{ __('messages.quantityToBuyPlaceholder') }}" class="form-control" id="quantity_to_buy" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
</div>
<div class="form-group">
    <label for="quantity_get">{{ __('messages.quantityGet') }} <span class='mandatory'>*</span>:</label>
    <input type="number" name="quantity_get" placeholder="{{ __('messages.quantityGetPlaceholder') }}" class="form-control" id="quantity_get" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
</div>
<div class="form-group">
    <label for="validity">{{ __('messages.validity') }} <span class='mandatory'>*</span>:</label>
    <select name="validity" class="form-control" id="validity" data-rule-required="true">
        <option value="1">{{ __('messages.once') }}</option>
        <option value="0">{{ __('messages.repeatedly') }}</option>
    </select>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="start_date">{{ __('messages.startDate') }} <span class='mandatory'>*</span>:</label>
            <input type="text" name="start_date" placeholder="{{ __('messages.discountStartDatePlaceholder') }}" class="form-control" id="start_date" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
            <input type="hidden" name="start_date_utc" id="start_date_utc">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="end_date">{{ __('messages.endDate') }} <span class='mandatory'>*</span>:</label>
            <input type="text" name="end_date" placeholder="{{ __('messages.discountEndDatePlaceholder') }}" class="form-control" id="end_date" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
            <input type="hidden" name="end_date_utc" id="end_date_utc">
        </div>
    </div>
</div>