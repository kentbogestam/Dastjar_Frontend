<div class="col-md-12 clear" id="add-new-address">
    <div class="text-center">
        <button type="button" class="btn btn-address" data-toggle="collapse" data-target=".add-address-form">{{ __('messages.addAddress') }}</button>
    </div>
    <div class="collapse add-address-form contact-info">
        <form id="save-address">
            <div class="form-group">
                <input type="text" name="full_name" id="full_name" placeholder="{{ __('messages.fullName') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        @php
                        $phone_prefix = 46;
                        @endphp

                        @if( !is_null($user->phone_number_prifix) )
                            @php
                            $phone_prefix = $user->phone_number_prifix;
                            @endphp
                        @endif
                        <select name="phone_prefix" class="form-control valid" id="phone_prefix" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" aria-invalid="false">
                            <option value="">{{ __('messages.select') }}</option>
                            <option value="91" {{ ($phone_prefix == 91) ? "selected" : '' }}>+91</option>
                            <option value="46" {{ ($phone_prefix == 46) ? "selected" : '' }}>+46</option>
                        </select>
                    </div>
                    <div class="col-xs-9">
                        <input type="text" name="mobile" id="mobile" value="{{ !is_null($user->phone_number) ? $user->phone_number : '' }}" placeholder="{{ __('messages.mobileNumber') }}*" class="form-control" data-rule-required="true" data-rule-number="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-msg-number="{{ __('messages.fieldNumber') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="entry_code" id="entry_code" placeholder="{{ __('messages.entryCode') }}" class="form-control">
                    </div>
                    <div class="col-xs-6">
                        <input type="text" name="apt_no" id="apt_no" placeholder="{{ __('messages.aptNo') }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="company_name" id="company_name" placeholder="{{ __('messages.companyName') }}" class="form-control">
                    </div>
                    <div class="col-xs-6">
                        <input type="text" name="other_info" id="other_info" placeholder="{{ __('messages.otherInfo') }}" class="form-control">
                    </div>
                </div>
            </div>
            <!-- <div class="form-group">
                <input type="text" name="address" id="address" placeholder="{{ __('messages.address1') }}" class="form-control">
            </div> -->
            <div class="form-group">
                <input type="text" name="street" id="street" placeholder="{{ __('messages.address2') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
            </div>
            <div class="form-group">
                <input type="text" name="zipcode" id="zipcode" placeholder="{{ __('messages.zipcode') }}" class="form-control" data-rule-number="true" data-msg-number="{{ __('messages.fieldNumber') }}">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="city" id="city" placeholder="{{ __('messages.city') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                    <div class="col-xs-6">
                        <input type="text" name="country" id="country" placeholder="{{ __('messages.country') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="is_permanent" value="1" checked="" id="is_permanent"> {{ __('messages.saveAddress') }}</label>
                    </div>
                </div>
            </div>
            <input type="submit" value="{{ __('messages.save') }}" class="btn btn-success">
            <input type="reset" value="{{ __('messages.Cancel') }}" class="btn btn-default" onclick="$('.add-address-form').collapse('hide');">
        </form>
    </div>
</div>

<!-- Confirm address before save -->
<div id="confirm-address-alert" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>{{ __('messages.confirmAddressAlert') }}</h4><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveUserAddress()">{{ __('messages.yes') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.no') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm address before save -->
<div id="warning-address-alert" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>{{ __('messages.addAddressWarning') }}</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm address before save -->
<div id="address-verification-alert" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" tabindex='-1'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="alert alert-success verification-code-sent-at">
                    {{ __('messages.addrVerificationCodeSentAt') }}
                </div>
                <form id="frm-address-verification">
                    <div class="form-group">
                        <input type="text" name="verification_code" id="verification_code" placeholder="{{ __('messages.enterVerificationCode') }}" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                        <label id="verification-error" class="error" for="verification_code" style="display: none;"></label>
                    </div>
                    <input type="hidden" name="address_id" id="address_id">
                    <input type="submit" value="{{ __('messages.OK') }}" class="btn btn-success"><br>
                    <button type="button" class="btn btn-link" onclick="resendVerificationCode()" style="font-size: 17px; color: #999;">{{ __('messages.resendVerificationCode') }}</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>