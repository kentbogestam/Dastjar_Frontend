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

@section('content-bootstrap')
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('messages.listDishType') }}</h1>
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
            <a href="{{ url('kitchen/menu') }}" class="btn btn-link" data-ajax="false">{{ __('messages.back') }}</a>
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
                    <th>{{ __('messages.dishType') }}</th>
                    <th>{{ __('messages.language') }}</th>
                    <th>{{ __('messages.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( !$dishType->isEmpty() )
                    @foreach($dishType as $row)
                        <tr>
                            <td>{{ $row->dish_name }}</td>
                            <td>{{ $row->dish_lang }}</td>
                            <td>
                                <snap class="btn-link" onclick="getDishType({{ $row->dish_id }})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></snap>
                                <a href="{{ url('kitchen/dishtype/'.$row->dish_id.'/delete') }}" onclick="return confirmDelete()" data-ajax="false">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center">{{ __('messages.noRecordFound') }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            {{ __('messages.pagination', ['first' => $dishType->firstItem(), 'last' => $dishType->lastItem(), 'total' => $dishType->total()]) }}
        </div>
        <div class="col-md-6">
            {!! $links !!}
        </div>
    </div>
    <!-- Modal: Add new dish -->
    <div class="modal fade" id="add-form-model" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <form name="add-form" method="POST" action="{{ url('kitchen/dishtype/store') }}" id="add-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="dish_lang">{{ __('messages.language') }} <span class='mandatory'>*</span>:</label>
                            <select name="dish_lang" class="form-control" id="dish_lang" data-rule-required="true">
                                <option value="SWE">Swedish</option>
                                <option value="ENG">English</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dish_name">{{ __('messages.dishType') }} <span class='mandatory'>*</span>:</label>
                            <input type="text" name="dish_name" placeholder="Enter title" class="form-control" id="dish_name" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
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
                    <form name="update-form" method="POST" action="{{ url('kitchen/dishtype/update') }}" id="update-form" data-ajax="false">
                        @csrf
                        <div class="form-group">
                            <label for="dish_lang">{{ __('messages.language') }} <span class='mandatory'>*</span>:</label>
                            <select name="dish_lang" class="form-control" id="dish_lang" data-rule-required="true">
                                <option value="SWE">Swedish</option>
                                <option value="ENG">English</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dish_name">{{ __('messages.dishType') }} <span class='mandatory'>*</span>:</label>
                            <input type="text" name="dish_name" placeholder="Enter title" class="form-control" id="dish_name" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
                        </div>
                        <input type="hidden" name="dish_id" id="dish_id" data-rule-required="true">
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
        $("#add-form").validate();
        $("#update-form").validate();
    });

    function getDishType(id)
    {
        $.ajax({
            url: '{{ url('kitchen/dishtype/get-dish-type') }}/'+id,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#update-form-model').find('#dish_id').val(response.dishType.dish_id);
                $('#update-form-model').find('#dish_lang').val(response.dishType.dish_lang);
                $('#update-form-model').find('#dish_name').val(response.dishType.dish_name);
                $('#update-form-model').modal();
            }
        });
    }
</script>
@endsection