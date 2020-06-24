@if($userAddresses)
    <div class="col-md-12 added-address-sec">
        <form id="frm-user-address" method="post">
            @foreach($userAddresses as $address)
                @php
                $strAddress = Helper::convertAddressToStr($address);
                @endphp
                <div class='col-sm-6'>
                    <div class='added-address list-group-item'>
                        <label><input type='radio' name='user_address_id' value='{{ $address->id }}' {{ ($address->is_default == '1') ? 'checked' : '' }}>{{ $strAddress }}</label>
                        <!-- <a href="javascript:void(0)" onclick="editUserAddressModal({{$address->id}});"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="javascript:void(0)" onclick="deleteUserAddress({{$address->id}});"><i class="fa fa-trash-o" aria-hidden="true"></i></a> -->
                        <div style="margin-left: 22px;">
                            <a href="javascript:void(0)" onclick="editUserAddressModal({{$address->id}});">{{ __('messages.edit') }}</i></a> | 
                            <a href="javascript:void(0)" onclick="deleteUserAddress({{$address->id}});">{{ __('messages.Remove') }}</a>
                            @if($address->is_default == '0')
                                 | <a href="javascript:void(0)" onclick="setUserAddressDefault({{$address->id}});">{{ __('messages.addressSetAsDefault') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div>
@endif

<div class="modal fade" id="update-user-address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.updateAddress') }}</h4>
            </div>
            <div class="modal-body">
                <form id="update-address" class="contact-info" style="padding-top: 0;">
                    <input type="hidden" name="address_id" id="address_id">
                    <div class="form-group">
                        <input type="text" name="full_name" id="e_full_name" placeholder="{{ __('messages.fullName') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-3">
                                <select name="phone_prefix" class="form-control valid" id="e_phone_prefix" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" aria-invalid="false">
                                    <option value="">{{ __('messages.select') }}</option>
                                    <option value="91">+91</option>
                                    <option value="46">+46</option>
                                </select>
                            </div>
                            <div class="col-xs-9">
                                <input type="text" name="mobile" id="e_mobile" placeholder="{{ __('messages.mobileNumber') }}*" class="form-control" data-rule-required="true" data-rule-number="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-msg-number="{{ __('messages.fieldNumber') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="entry_code" id="e_entry_code" placeholder="{{ __('messages.entryCode') }}" class="form-control">
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="apt_no" id="e_apt_no" placeholder="{{ __('messages.aptNo') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="company_name" id="e_company_name" placeholder="{{ __('messages.companyName') }}" class="form-control">
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="other_info" id="e_other_info" placeholder="{{ __('messages.otherInfo') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="street" id="e_street" placeholder="{{ __('messages.address2') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="zipcode" id="e_zipcode" placeholder="{{ __('messages.zipcode') }}" class="form-control" data-rule-number="true" data-msg-number="{{ __('messages.fieldNumber') }}">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="city" id="e_city" placeholder="{{ __('messages.city') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="country" id="e_country" placeholder="{{ __('messages.country') }}*" class="form-control" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-success">{{ __('messages.update') }}</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.Cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>