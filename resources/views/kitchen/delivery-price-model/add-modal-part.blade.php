<!-- Modal: Add new dish -->
<div class="modal fade" id="add-form-model" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form name="add-form" method="POST" action="{{ url('kitchen/delivery-price-model/store') }}" id="add-form" data-ajax="false">
                    @csrf
                    <div class="form-group">
                        <label for="delivery_rule_id">{{ __('messages.deliveryType') }} <span class='mandatory'>*</span>:</label>
                        <div class="input-group">
                            <select name="delivery_rule_id" class="form-control" id="delivery_rule_id" data-rule-required="true">
                                @foreach($deliveryRule as $row)
                                    <option value="{{ $row['id'] }}">{{ $row['summary'] }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" title="{{ __('messages.iDeliveryType') }}">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="delivery_charge">{{ __('messages.delivery_charge') }} :</label>
                        <input type="text" name="delivery_charge" class="form-control" id="delivery_charge" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                    <div class="form-group">
                        <label for="threshold">{{ __('messages.threshold') }} :</label>
                        <input type="text" name="threshold" class="form-control" id="threshold" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                    <div class="type2 d-none">
                        <div class="row">
                            <div class="col-sm-5">
                                <label>{{ __('messages.delivery_distance') }} :</label>
                            </div>
                            <div class="col-sm-5">
                                <label>{{ __('messages.delivery_charge') }} :</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <input type="text" name="dp_distance[]" class="form-control">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="distance_delivery_charge[]" class="form-control">
                            </div>
                        </div>
                        <button type="button" onclick="addMore(this)" class="btn btn-link pull-right">
                            {{ __('messages.addMore') }}
                        </button>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>