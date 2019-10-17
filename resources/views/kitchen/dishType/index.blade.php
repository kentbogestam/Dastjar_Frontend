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
                <a href="{{ url('kitchen/menu') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false"><img src="{{asset('kitchenImages/backarrow.png')}}" width="11px"></a>
            </div>
            <div class="ui-block-b middle_section">
                <a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.listDishType') }}</a>
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
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ __('messages.dishType') }}</th>
                    <th>{{ __('messages.language') }}</th>
                    <th>{{ __('messages.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @if( !empty($dishType) )
                    @foreach($dishType as $row)
                        <tr class="level-{{ $row['level'] }}">
                            <td>{!! Helper::strReplaceBy($row['dish_name'], $row['level'], '— ') !!}</td>
                            <td>{{ $row['dish_lang'] }}</td>
                            <td>
                                <snap class="btn-link" onclick="getDishType({{ $row['dish_id'] }})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></snap>
                                <a href="{{ url('kitchen/dishtype/'.$row['dish_id'].'/delete') }}" onclick="return confirmDelete()" data-ajax="false">
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
    </div>
    <!-- Modal: Add new dish -->
    <div class="modal fade" id="add-form-model" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <form name="add-form" id="add-form" method="POST" action="{{ url('kitchen/dishtype/store') }}" enctype="multipart/form-data" data-ajax="false">
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
                        <div class="form-group">
                            <label for="parent_id">{{ __('messages.parentCategory') }}</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">{{ __('messages.none') }}</option>
                                @if( !empty($dishType) )
                                    @foreach($dishType as $row)
                                        @if($row['level'] <= 1)
                                            <option value="{{ $row['dish_id'] }}" class="level-{{ $row['level'] }}">
                                                {!! Helper::strReplaceBy($row['dish_name'], $row['level']*2) !!}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dish_image">{{ __('messages.dishImage') }}:</label>
                            <input type="file" name="dish_image" class="form-control" />
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
                    <form name="update-form" id="update-form" method="POST" action="{{ url('kitchen/dishtype/update') }}" enctype="multipart/form-data" data-ajax="false">
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
                        <div class="form-group">
                            <label for="parent_id">{{ __('messages.parentCategory') }}</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">{{ __('messages.none') }}</option>
                                @if( !empty($dishType) )
                                    @foreach($dishType as $row)
                                        @if($row['level'] <= 1)
                                            <option value="{{ $row['dish_id'] }}" class="level-{{ $row['level'] }}">
                                                {!! Helper::strReplaceBy($row['dish_name'], $row['level']*2) !!}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dish_image">{{ __('messages.dishImage') }}:</label>
                            <input type="file" name="dish_image" class="form-control" />
                        </div>
                        <div class="img-wrap"></div>
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
        // $("#add-form").validate();
        $("#update-form").validate();
    });

    function getDishType(id)
    {
        $('.img-wrap').html('');

        $.ajax({
            url: '{{ url('kitchen/dishtype/get-dish-type') }}/'+id,
            dataType: 'json',
            success: function(response) {
                $('#update-form-model').find('#dish_id').val(response.dishType.dish_id);
                $('#update-form-model').find('#dish_lang').val(response.dishType.dish_lang);
                $('#update-form-model').find('#dish_name').val(response.dishType.dish_name);
                $('#update-form-model').find('#parent_id').val(response.dishType.parent_id);

                if(response.dishType.dish_image)
                {
                    let str = '<img src="https://s3.eu-west-1.amazonaws.com/dastjar-coupons/'+response.dishType.dish_image+'" class="img-thumbnail" alt="" style="max-width: 200px;"><button type="button" class="btn btn-link" onclick="removeCategoryImage('+response.dishType.dish_id+')"><i class="fa fa-remove" aria-hidden="true"></i></button>';
                    $('#update-form-model').find('.img-wrap').html(str);
                }

                $('#update-form-model').modal();
            }
        });
    }

    // Remove cat image
    function removeCategoryImage(id)
    {
        $.ajax({
            url: '{{ url('kitchen/dishtype/remove-category-image') }}/'+id,
            dataType: 'json',
            success: function(response) {
                if(response.status)
                {
                    $('.img-wrap').html('');
                }
            }
        });
    }
</script>
@endsection