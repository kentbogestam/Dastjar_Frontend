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
                <a href="{{ url('kitchen/kitchen-setting') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false"><img src="{{asset('kitchenImages/backarrow.png')}}" width="11px"></a>
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
    <!-- <div class="row">
        <div class="col-md-12">
            <h1>{{ __('messages.listLoyalty') }}</h1>
        </div>
    </div> -->
    <div class="row">
        <div class="col-md-12">
            <hr>
            @include('common.errors')
            @include('common.flash')
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
        <!-- <div class="col-md-6 text-left">
            <a href="{{ url('kitchen/kitchen-setting') }}" class="btn btn-link" data-ajax="false">{{ __('messages.back') }}</a>
        </div> -->
        <div class="col-md-12 text-right">
            <button class="btn btn-info" data-toggle="modal" data-target="#add-form-model">{{ __('messages.addNew') }}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>{{ __('messages.store') }}</th>
                    <th>{{ __('messages.dishType') }}</th>
                    <th>{{ __('messages.quantityToBuy') }}</th>
                    <th>{{ __('messages.quantityGet') }}</th>
                    <th>{{ __('messages.startDate') }}</th>
                    <th>{{ __('messages.endDate') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( !$loyalty->isEmpty() )
                    @foreach($loyalty as $row)
                        <tr>
                            <td>{{ $row->store_name }}</td>
                            <td>{{ $row->dish_name }}</td>
                            <td>{{ $row->quantity_to_buy }}</td>
                            <td>{{ $row->quantity_get }}</td>
                            <td>
                                {!! "<script type='text/javascript'>document.write(moment.utc('{$row->start_date}').local().format('YYYY/MM/DD HH:mm'))</script>" !!}
                            </td>
                            <td>
                                {!! "<script type='text/javascript'>document.write(moment.utc('{$row->end_date}').local().format('YYYY/MM/DD HH:mm'))</script>" !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">{{ __('messages.noRecordFound') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="add-form-model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form name="add-form" method="POST" action="{{ url('kitchen/loyalty/store') }}" id="add-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="store_id">{{ __('messages.selectStore') }} <span class='mandatory'>*</span>:</label>
                            <select name="store_id" class="form-control" id="store_id" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
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
                        <button type="submit" class="btn btn-success">{{ __('messages.submit') }}</button>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-material-datetimepicker.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Validate if value is less than
        $.validator.addMethod('lessThan', function(value, element, param) {
            var i = parseInt(value);
            var j = parseInt($(param).val());
            return i < j;
        });

        // Form validation
        $("#add-form").validate({
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
        $('#start_date').bootstrapMaterialDatePicker
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
        });

        // Initialize material
        $.material.init();
    });
</script>
@endsection