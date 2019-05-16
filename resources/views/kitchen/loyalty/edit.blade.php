@extends('kitchen.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
    <style>
    label.error {
        color: red !important;
    }
    </style>
@stop

@section('content-jMobile')
<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
    @include('includes.kitchen-header-sticky-bar')
    <div class="order_background setting_head_container">
        <div class="ui-grid-b center">
            <div class="ui-block-a">
                <a href="{{ url('kitchen/loyalty/list') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false"><img src="{{asset('kitchenImages/backarrow.png')}}" width="11px"></a>
            </div>
            <div class="ui-block-b middle_section">
                <a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.listLoyalty') }}</a>
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
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <form name="update-form" method="POST" action="{{ url('kitchen/loyalty/update') }}" id="update-form" data-ajax="false">
                @csrf
                @include('kitchen.loyalty.fields')
                <input type="hidden" name="loyalty_id" id="loyalty_id" value="" data-rule-required="true">
                <input type="hidden" name="e_store_id" id="e_store_id" value="" data-rule-required="true">
                <button type="submit" class="btn btn-success">{{ __('messages.update') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-material-datetimepicker.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        getLoyaltyById("{{ $id }}");

        // Validate if value is less than
        $.validator.addMethod('lessThan', function(value, element, param) {
            var i = parseInt(value);
            var j = parseInt($(param).val());
            return i < j;
        });

        // Form validation
        $("#update-form").validate({
            rules: {
                quantity_get: {
                    lessThan: '#quantity_to_buy'
                }
            },
            messages: {
                quantity_get: {
                    lessThan: '{{ __('messages.quantityGetLessThan') }}'
                }
            }
        });

        // Initialize start/end datetimepicker
        /*$('#start_date').bootstrapMaterialDatePicker
        ({
            weekStart: 0,
            format: 'DD/MM/YYYY HH:mm',
            clearButton: true
        }).on('change', function(e, date) {
            $('#start_date_utc').val(moment.utc(date).format('YYYY/MM/DD HH:mm'));
            $('#end_date').bootstrapMaterialDatePicker('setMinDate', date);
        });

        $('#end_date').bootstrapMaterialDatePicker
        ({
            weekStart: 0,
            format: 'DD/MM/YYYY HH:mm',
            clearButton: true
        }).on('change', function(e, date) {
            $('#end_date_utc').val(moment.utc(date).format('YYYY/MM/DD HH:mm'));
        });*/

        // Initialize material
        $.material.init();
    });

    function getLoyaltyById(id)
    {
        $.ajax({
            url: '{{ url('kitchen/loyalty/get-loyalty-by-id') }}/'+id,
            dataType: 'json',
            success: function(response) {
                if(response.status)
                {
                    // 
                    start_date = moment.utc(response.loyalty.start_date).local().format('DD/MM/YYYY HH:mm');
                    end_date = moment.utc(response.loyalty.end_date).local().format('DD/MM/YYYY HH:mm');

                    // 
                    $('#loyalty_id').val(response.loyalty.id);
                    $('#store_id, #e_store_id').val(response.loyalty.store_id);
                    $('#dish_type_id').val(response.loyalty.dish_type_ids);
                    $('#quantity_to_buy').val(response.loyalty.quantity_to_buy);
                    $('#quantity_get').val(response.loyalty.quantity_get);
                    $('#validity').val(response.loyalty.validity);
                    $('#start_date').val(start_date);
                    $('#start_date_utc').val(moment.utc(response.loyalty.start_date).format('YYYY/MM/DD HH:mm'));
                    $('#end_date').val(end_date);
                    $('#end_date_utc').val(moment.utc(response.loyalty.end_date).format('YYYY/MM/DD HH:mm'));

                    if(response.loyalty.isLoyaltyUsed)
                    {
                        $('#store_id').attr('disabled', true);
                        $('#quantity_to_buy').attr('readonly', true);
                        $('#quantity_get').attr('readonly', true);
                        $('#start_date').attr('readonly', true);
                        $('#end_date').bootstrapMaterialDatePicker({
                            weekStart : 0,
                            format: 'DD/MM/YYYY HH:mm',
                            minDate: end_date,
                            clearButton: true
                        }).on('change', function(e, date) {
                            $('#end_date_utc').val(moment.utc(date).format('YYYY/MM/DD HH:mm'));
                        });
                    }
                    else
                    {
                        $('#start_date').bootstrapMaterialDatePicker({
                            weekStart: 0,
                            format: 'DD/MM/YYYY HH:mm',
                            clearButton: true
                        }).on('change', function(e, date) {
                            $('#start_date_utc').val(moment.utc(date).format('YYYY/MM/DD HH:mm'));
                            $('#end_date').bootstrapMaterialDatePicker('setMinDate', date);
                        });

                        $('#end_date').bootstrapMaterialDatePicker({
                            weekStart : 0,
                            format: 'DD/MM/YYYY HH:mm',
                            clearButton: true
                        }).on('change', function(e, date) {
                            $('#end_date_utc').val(moment.utc(date).format('YYYY/MM/DD HH:mm'));
                        });
                    }
                }
            }
        });
    }
</script>
@endsection