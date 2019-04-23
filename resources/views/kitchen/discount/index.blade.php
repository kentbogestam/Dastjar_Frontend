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
            <h1>{{ __('messages.listDiscount') }}</h1>
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
            <button class="btn btn-info" data-toggle="modal" data-target="#add-discount-modal">{{ __('messages.addNew') }}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>{{ __('messages.store') }}</th>
                    <th>{{ __('messages.discountCode') }}</th>
                    <th>{{ __('messages.discountValue') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.startDate') }}</th>
                    <th>{{ __('messages.endDate') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( !$discount->isEmpty() )
                    @foreach($discount as $row)
                        <tr>
                            <td>{{ $row->store_name }}</td>
                            <td>{{ $row->code }}</td>
                            <td>{{ $row->discount_value }}</td>
                            <td>{{ $row->description }}</td>
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
    <div class="modal fade" id="add-discount-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <form name="frm-discount" method="POST" action="{{ url('kitchen/discount/store') }}" id="frm-discount" data-ajax="false">
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
                            <label for="code">{{ __('messages.discountCode') }} <span class='mandatory'>*</span>:</label>
                            <div class="input-group">
                                <input type="text" name="code" value="" readonly class="form-control" id="code">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" onClick="getNewDiscountCode()">{{ __('messages.refresh') }}</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="discount_value">{{ __('messages.discountValue') }} <span class='mandatory'>*</span>:</label>
                            <input type="number" name="discount_value" placeholder="{{ __('messages.discountValuePlaceholder') }}" class="form-control" id="discount_value" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('messages.description') }} :</label>
                            <textarea name="description" id="description" class="form-control" rows="1"></textarea>
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
    getNewDiscountCode();

    $(document).ready(function() {
        // Form validation
        $("#frm-discount").validate({
            rules: {
                code: {
                    required: true,
                    minlength: 5,
                    maxlength: 5,
                    remote: {
                        url: '{{ url('kitchen/discount/remote-validate-discount') }}',
                        type: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            code: function() {
                                return $("#code").val();
                            }
                        }
                    }
                }
            },
            messages: {
                code: {
                    remote: '{{ __('messages.discountCodeFieldRemote') }}'
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

    // Refresh discount code
    function getNewDiscountCode()
    {
        $.ajax({
            url: '{{ url('kitchen/discount/get-discount-code') }}',
            success: function(response) {
                $('#code').val(response.code);
            }
        });
    }
</script>
@endsection