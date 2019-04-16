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

@section('content-bootstrap')
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('messages.listLoyalty') }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            @include('common.errors')
            @include('common.flash')
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-6 text-left">
            <a href="{{ url('kitchen/kitchen-setting') }}" class="btn btn-link" data-ajax="false">{{ __('messages.back') }}</a>
        </div>
        <div class="col-md-6 text-right">
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
                            <select name="store_id" class="form-control" id="store_id" data-rule-required="true">
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
                            <select multiple name="dish_type_id[]" class="form-control" id="dish_type_id" data-rule-required="true">
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
                            <input type="number" name="quantity_to_buy" placeholder="Enter quantity to buy" class="form-control" id="quantity_to_buy" data-rule-required="true">
                        </div>
                        <div class="form-group">
                            <label for="quantity_get">{{ __('messages.quantityGet') }} <span class='mandatory'>*</span>:</label>
                            <input type="number" name="quantity_get" placeholder="Enter quantity to get" class="form-control" id="quantity_get" data-rule-required="true">
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
                                    <input type="text" name="start_date" placeholder="Enter coupon start date" class="form-control" id="start_date" data-rule-required="true">
                                    <input type="hidden" name="start_date_utc" id="start_date_utc">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="end_date">{{ __('messages.endDate') }} <span class='mandatory'>*</span>:</label>
                                    <input type="text" name="end_date" placeholder="Enter coupon end date" class="form-control" id="end_date" data-rule-required="true">
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
        // Form validation
        $("#add-form").validate();

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