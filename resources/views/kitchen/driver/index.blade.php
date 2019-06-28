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
                <a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.driver') }}</a>
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
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.email') }}</th>
                    <th>{{ __('messages.phone') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( !$driver->isEmpty() )
                    @foreach($driver as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>
                                @if($row->status == 1)
                                    {{ __('messages.active') }}
                                @else
                                    {{ __('messages.inactive') }}
                                @endif
                            </td>
                            <td>
                                <snap class="btn-link" onclick="getDriver('{{ $row->id }}')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></snap>
                                <a href="{{ url('kitchen/driver/'.$row->id.'/delete') }}" onclick="return confirmDelete()" data-ajax="false">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">{{ __('messages.noRecordFound') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal: Add -->
    <div class="modal fade" id="add-form-model" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form name="add-form" method="POST" action="{{ url('kitchen/driver/store') }}" id="add-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('messages.name') }} :</label>
                            <input type="text" name="name" class="form-control" id="name" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('messages.email') }} :</label>
                            <input type="text" name="email" class="form-control" id="email" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-rule-email="true" data-msg-email="{{ __('messages.fieldEmail') }}">
                        </div>
                        <div class="form-group">
                            <label for="phone">{{ __('messages.phone') }} :</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="phone_prefix" class="form-control" id="phone_prefix" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                                        <option value="">Select</option>
                                        <option value="+91">+91</option>
                                        <option value="+46">+46</option>
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="phone" class="form-control" id="phone" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-rule-number="true" data-msg-number="{{ __('messages.fieldNumber') }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('messages.save') }}</button>
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
                    <form name="update-form" method="POST" action="{{ url('kitchen/driver/update') }}" id="update-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="name_upd">{{ __('messages.name') }} :</label>
                            <input type="text" name="name_upd" class="form-control" id="name_upd" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <div class="form-group">
                            <label for="email_upd">{{ __('messages.email') }} :</label>
                            <input type="text" name="email_upd" class="form-control" id="email_upd" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-rule-email="true" data-msg-email="{{ __('messages.fieldEmail') }}">
                        </div>
                        <div class="form-group">
                            <label for="phone">{{ __('messages.phone') }} :</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="phone_prefix_upd" class="form-control" id="phone_prefix_upd" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                                        <option value="">Select</option>
                                        <option value="+91">+91</option>
                                        <option value="+46">+46</option>
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="phone_upd" class="form-control" id="phone_upd" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-rule-number="true" data-msg-number="{{ __('messages.fieldNumber') }}">
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
</div>
@endsection

@section('footer-script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Form validation
        $("#add-form").validate();
        $("#update-form").validate();
    });

    // 
    function getDriver(id)
    {
        $.ajax({
            url: '{{ url('kitchen/driver/get-driver') }}/'+id,
            dataType: 'json',
            success: function(response) {
                $('#update-form-model').find('#id').val(response.driver.id);
                $('#update-form-model').find('#name_upd').val(response.driver.name);
                $('#update-form-model').find('#email_upd').val(response.driver.email);
                $('#update-form-model').find('#phone_prefix_upd').val(response.driver.phone_prefix);
                $('#update-form-model').find('#phone_upd').val(response.driver.phone);
                $('#update-form-model').modal();
            }
        });
    }
</script>
@endsection