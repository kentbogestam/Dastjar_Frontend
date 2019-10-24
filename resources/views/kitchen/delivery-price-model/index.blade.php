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

    .tooltip-inner {
        max-width: 600px !important;
        width: 600px !important;
        text-align: left;
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
    @include('kitchen.delivery-price-model.list-part')
    @include('kitchen.delivery-price-model.add-modal-part')
    @include('kitchen.delivery-price-model.edit-modal-part')
</div>
@endsection

@section('footer-script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // 'trigger' on open add modal
        $('#add-form-model').on('shown.bs.modal', function (e) {
            modalConditionalFields();
        });

        // 'trigger' on open edit modal
        $('#update-form-model').on('shown.bs.modal', function (e) {
            modalConditionalFields('_upd');
        });

        $('#delivery_rule_id').on('change', function() {
            modalConditionalFields();
        });

        $('#delivery_rule_id_upd').on('change', function() {
            modalConditionalFields('_upd');
        });

        // Form validation
        $("#add-form").validate({
            rules: {
                delivery_charge: {
                    required: function(element) {
                        return ($('#delivery_rule_id').val() == '1' || $('#delivery_rule_id').val() == '2' || $('#delivery_rule_id').val() == '4');
                    }
                },
                threshold: {
                    required: function(element) {
                        return ($('#delivery_rule_id').val() == '2' || $('#delivery_rule_id').val() == '3' || $('#delivery_rule_id').val() == '4' || $('#delivery_rule_id').val() == '5');
                    }
                },
                'dp_distance[]': {
                    digits: true,
                    required: function(element) {
                        return ($('#delivery_rule_id').val() == '5');
                    }
                },
                'distance_delivery_charge[]': {
                    digits: true,
                    required: function(element) {
                        return ($('#delivery_rule_id').val() == '5');
                    }
                }
            }
        });

        $("#update-form").validate({
            rules: {
                delivery_charge_upd: {
                    required: function(element) {
                        return ($('#delivery_rule_id_upd').val() == '1' || $('#delivery_rule_id_upd').val() == '2' || $('#delivery_rule_id_upd').val() == '4');
                    }
                },
                threshold_upd: {
                    required: function(element) {
                        return ($('#delivery_rule_id_upd').val() == '2' || $('#delivery_rule_id_upd').val() == '3' || $('#delivery_rule_id_upd').val() == '4' || $('#delivery_rule_id_upd').val() == '5');
                    }
                },
                'dp_distance_upd[]': {
                    digits: true,
                    required: function(element) {
                        return ($('#delivery_rule_id_upd').val() == '5');
                    }
                },
                'distance_delivery_charge_upd[]': {
                    digits: true,
                    required: function(element) {
                        return ($('#delivery_rule_id_upd').val() == '5');
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

                let html = '';
                if(response.deliveryPriceModel.delivery_rule_id == 5 && response.deliveryPriceModel.delivery_price_distance.length)
                {
                    for(let i = 0; i < response.deliveryPriceModel.delivery_price_distance.length; i++)
                    {
                        html += '<div class="form-group row"><div class="col-sm-5"><input type="text" name="dp_distance_upd[]" value="'+response.deliveryPriceModel.delivery_price_distance[i]['distance']+'" class="form-control"></div><div class="col-sm-5"><input type="text" name="distance_delivery_charge_upd[]" value="'+response.deliveryPriceModel.delivery_price_distance[i]['delivery_charge']+'" class="form-control"></div>';

                        if(i != 0)
                        {
                            html += '<div class="col-sm-2"><button onclick="removeMore(this)" class="btn btn-link"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></div>';
                        }

                        html += '</div>';
                    }
                }
                else
                {
                    html += '<div class="form-group row"><div class="col-sm-5"><input type="text" name="dp_distance_upd[]" class="form-control"></div><div class="col-sm-5"><input type="text" name="distance_delivery_charge_upd[]" class="form-control"></div></div>';
                }

                html += '<button type="button" onclick="addMore(this, \'_upd\')" class="btn btn-link pull-right">{{ __('messages.addMore') }}</button>';    
                $('#update-form-model').find('.type2').append(html);

                $('#update-form-model').modal();
            }
        });
    }

    // Modal show/hide fields
    function modalConditionalFields(flag = '')
    {
        var delivery_rule_id = $('#delivery_rule_id'+flag).val();

        $('.type2').addClass('d-none');

        if(delivery_rule_id == '1')
        {
            $('#delivery_charge'+flag).closest('.form-group').show();
            $('#threshold'+flag).val('');
            $('#threshold'+flag).closest('.form-group').hide();
        }
        else if(delivery_rule_id == '2' || delivery_rule_id == '4')
        {
            $('#threshold'+flag).closest('.form-group').show();
            $('#delivery_charge'+flag).closest('.form-group').show();
        }
        else if(delivery_rule_id == '3')
        {
            $('#threshold'+flag).closest('.form-group').show();
            $('#delivery_charge'+flag).val('');
            $('#delivery_charge'+flag).closest('.form-group').hide();
        }
        else if(delivery_rule_id == '5')
        {
            $('#threshold'+flag).closest('.form-group').show();
            $('#delivery_charge'+flag).closest('.form-group').hide();
            $('.type2').removeClass('d-none');
        }
    }

    // 
    function addMore(This, suffix = '')
    {
        This = $(This);

        let html = '<div class="form-group row"><div class="col-sm-5"><input type="text" name="dp_distance'+suffix+'[]" class="form-control"></div><div class="col-sm-5"><input type="text" name="distance_delivery_charge'+suffix+'[]" class="form-control"></div><div class="col-sm-2"><button onclick="removeMore(this)" class="btn btn-link"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></div></div>';

        This.before(html);
    }

    // 
    function removeMore(This)
    {
        This = $(This);
        This.closest('.row').remove();
    }
</script>
@endsection