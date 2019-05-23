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
    <div class="row">
        <div class="col-md-12">
            <hr>
            @include('common.errors')
            @include('common.flash')
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
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
                    <th>{{ __('messages.action') }}</th>
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
                            <td>
                                <!-- <snap class="btn-link" onclick="getLoyaltyById({{ $row->id }})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></snap> -->
                                <a href="{{ url('kitchen/loyalty/'.$row->id.'/edit') }}" data-ajax="false">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                @if(!$row->isLoyaltyUsed)
                                    <a href="{{ url('kitchen/loyalty/'.$row->id.'/delete') }}" onclick="return confirmDelete()" data-ajax="false">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">{{ __('messages.noRecordFound') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Add -->
    <div class="modal fade" id="add-form-model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form name="add-form" method="POST" action="{{ url('kitchen/loyalty/store') }}" id="add-form" data-ajax="false">
                        @csrf
                        @include('kitchen.loyalty.fields')
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