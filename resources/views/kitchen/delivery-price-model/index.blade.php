@extends('kitchen.layouts.app')

@section('style')
    <style>
    ul.pagination {
        float: right;
    }

    label.error {
        color: red !important;
    }
    .btn-link {
        cursor: pointer;
    }
    </style>
@stop

@section('content-jMobile')
<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
    @include('includes.kitchen-header-sticky-bar')
    <div class="order_background setting_head_container">
        <div class="ui-grid-b center">
            <div class="ui-block-a">
                <a href="{{ url('kitchen/kitchen-setting') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false"><img src="{{asset('kitchenImages/backarrow.png')}}" width="11px"></a>
            </div>
            <div class="ui-block-b middle_section">
                <a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.delivery_price_model') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-bootstrap')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <div class="col-md-12">
            <hr>
            @include('common.errors')
            @include('common.flash')
        </div>
    </div>
    @if($deliveryPriceModel->isEmpty())
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12 text-right">
                <button class="btn btn-info" data-toggle="modal" data-target="#add-form-model">{{ __('messages.addNew') }}</button>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>{{ __('messages.delivery_rule_id') }}</th>
                    <th>{{ __('messages.delivery_charge') }}</th>
                    <th>{{ __('messages.threshold') }}</th>
                    <th>{{ __('messages.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( !$deliveryPriceModel->isEmpty() )
                    @foreach($deliveryPriceModel as $row)
                        <tr>
                            <td>{{ $row->deliveryRule->summary }}</td>
                            <td>{{ $row->delivery_charge }}</td>
                            <td>{{ $row->threshold }}</td>
                            <td>
                                <snap class="btn-link" onclick="getDeliveryPrice('{{ $row->id }}')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></snap>
                                <a href="{{ url('kitchen/delivery-price-model/'.$row->id.'/delete') }}" onclick="return confirmDelete()" data-ajax="false">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">{{ __('messages.noRecordFound') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal: Add new dish -->
    <div class="modal fade" id="add-form-model" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <form name="add-form" method="POST" action="{{ url('kitchen/delivery-price-model/store') }}" id="add-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="delivery_rule_id">{{ __('messages.delivery_rule_id') }} <span class='mandatory'>*</span>:</label>
                            <select name="delivery_rule_id" class="form-control" id="delivery_rule_id" data-rule-required="true">
                                @foreach($deliveryRule as $row)
                                    <option value="{{ $row->id }}">{{ $row->summary }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="delivery_charge">{{ __('messages.delivery_charge') }} :</label>
                            <input type="text" name="delivery_charge" class="form-control" id="delivery_charge" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <div class="form-group">
                            <label for="threshold">{{ __('messages.threshold') }} :</label>
                            <input type="text" name="threshold" class="form-control" id="threshold" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('messages.submit') }}</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: Edit dish -->
    <div class="modal fade" id="update-form-model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form name="update-form" method="POST" action="{{ url('kitchen/delivery-price-model/update') }}" id="update-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="delivery_rule_id_upd">{{ __('messages.delivery_rule_id') }} <span class='mandatory'>*</span>:</label>
                            <select name="delivery_rule_id_upd" class="form-control" id="delivery_rule_id_upd" data-rule-required="true">
                                @foreach($deliveryRule as $row)
                                    <option value="{{ $row->id }}">{{ $row->summary }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="delivery_charge_upd">{{ __('messages.delivery_charge') }} :</label>
                            <input type="text" name="delivery_charge_upd" class="form-control" id="delivery_charge_upd" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <div class="form-group">
                            <label for="threshold_upd">{{ __('messages.threshold') }} :</label>
                            <input type="text" name="threshold_upd" class="form-control" id="threshold_upd" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <input type="hidden" name="id" id="id" data-rule-required="true">
                        <button type="submit" class="btn btn-success">{{ __('messages.update') }}</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Form validation
        $("#add-form").validate({
            rules: {
                delivery_charge: {
                    required: function(element) {
                        return ($('#delivery_rule_id').val() == '1' || $('#delivery_rule_id').val() == '2');
                    }
                },
                threshold: {
                    required: function(element) {
                        return ($('#delivery_rule_id').val() == '2' || $('#delivery_rule_id').val() == '3');
                    }
                }
            }
        });

        $("#update-form").validate({
            rules: {
                delivery_charge_upd: {
                    required: function(element) {
                        return ($('#delivery_rule_id_upd').val() == '1' || $('#delivery_rule_id_upd').val() == '2');
                    }
                },
                threshold_upd: {
                    required: function(element) {
                        return ($('#delivery_rule_id_upd').val() == '2' || $('#delivery_rule_id_upd').val() == '3');
                    }
                }
            }
        });
    });

    // 
    function getDeliveryPrice(id)
    {
        $.ajax({
            url: '{{ url('kitchen/delivery-price-model/get-delivery-price') }}/'+id,
            dataType: 'json',
            success: function(response) {
                $('#update-form-model').find('#id').val(response.deliveryPriceModel.id);
                $('#update-form-model').find('#delivery_rule_id_upd').val(response.deliveryPriceModel.delivery_rule_id);
                $('#update-form-model').find('#delivery_charge_upd').val(response.deliveryPriceModel.delivery_charge);
                $('#update-form-model').find('#threshold_upd').val(response.deliveryPriceModel.threshold);
                $('#update-form-model').modal();
            }
        });
    }
</script>
@endsection