<!-- Modal: Edit dish -->
<div class="modal fade" id="update-form-model" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form name="update-form" method="POST" action="{{ url('kitchen/delivery-price-model/update') }}" id="update-form" data-ajax="false">
                    @csrf
                    <div class="form-group">
                        <label for="delivery_rule_id_upd">{{ __('messages.delivery_rule_id') }} <span class='mandatory'>*</span>:</label>
                        <div class="input-group">
                            <select name="delivery_rule_id_upd" class="form-control" id="delivery_rule_id_upd" data-rule-required="true">
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
                        <label for="delivery_charge_upd">{{ __('messages.delivery_charge') }} :</label>
                        <input type="text" name="delivery_charge_upd" class="form-control" id="delivery_charge_upd" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                    <div class="form-group">
                        <label for="threshold_upd">{{ __('messages.threshold') }} :</label>
                        <input type="text" name="threshold_upd" class="form-control" id="threshold_upd" data-msg-required="{{ __('messages.fieldRequired') }}">
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
                    </div>
                    <input type="hidden" name="id" id="id" data-rule-required="true">
                    <button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>