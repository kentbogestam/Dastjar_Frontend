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
    <!-- <div class="row">
        <div class="col-md-12">
            <h1>{{ __('messages.listDishType') }}</h1>
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
            <a href="{{ url('kitchen/menu') }}" class="btn btn-link" data-ajax="false">{{ __('messages.back') }}</a>
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
                        <div class="form-group form-group-sm">
                            <label for="sub_category">{{ __('messages.subCategory') }} ({{ __('messages.optional') }}):</label>
                            <div class="input-group mb-3 input-group-sm">
                                <input type="text" name="sub_category[]" placeholder="Enter sub-category" class="form-control">
                            </div>
                            <p class="text-right">
                                <button type="button" class="btn btn-link add-subcat">Add more</button>
                            </p>
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
                        <div class="form-group form-group-sm">
                            <label for="sub_category">{{ __('messages.subCategory') }} ({{ __('messages.optional') }}):</label>
                            <p class="text-right">
                                <button type="button" class="btn btn-link add-subcat">Add more</button>
                            </p>
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
        // Add sub-category
        $('.add-subcat').on('click', function() {
            $(this).closest('.form-group').find('.input-group').last().after('<div class="input-group mb-3 input-group-sm">'+
                '<input type="text" name="sub_category[]" placeholder="Enter sub-category" class="form-control">'+
                '<div class="input-group-append">'+
                    '<button type="button" class="btn remove-subcat"><i class="fa fa-minus" aria-hidden="true"></i></button>'+
                '</div>'+
            '</div>');
        });

        // Remove sub-category
        $(document).on('click', '.remove-subcat', function() {
            $(this).closest('.input-group').remove();
        });

        // Form validation
        // $("#add-form").validate();
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
                $('#update-form-model').find('.input-group').remove();

                let strSubcategory = '';
                if(response.dishType.subcategory.length)
                {
                    for(let i = 0; i < response.dishType.subcategory.length; i++)
                    {
                        strSubcategory += '<div class="input-group mb-3 input-group-sm input-group-'+response.dishType.subcategory[i].dish_id+'">'+
                            '<input type="text" name="sub_category['+response.dishType.subcategory[i].dish_id+']" value="'+response.dishType.subcategory[i].dish_name+'" placeholder="Enter sub-category" class="form-control">'+
                            '<div class="input-group-append">'+
                                '<button type="button" class="btn" onclick="removeSubcategory('+id+', '+response.dishType.subcategory[i].dish_id+')"><i class="fa fa-minus" aria-hidden="true"></i></button>'+
                            '</div>'+
                        '</div>';
                    }
                }
                else
                {
                    strSubcategory += '<div class="input-group mb-3 input-group-sm">'+
                        '<input type="text" name="sub_category[]" placeholder="Enter sub-category" class="form-control">'+
                    '</div>';
                }

                $('#update-form-model').find('.form-group label').last().after(strSubcategory);
                $('#update-form-model').modal();
            }
        });
    }

    // Remove sub-category
    function removeSubcategory(parentId, dishId)
    {
        $.ajax({
            url: '{{ url('kitchen/dishtype/remove-subcategory') }}/'+parentId+'/'+dishId,
            dataType: 'json',
            success: function() {
                $('#update-form-model').find('.input-group-'+dishId).remove();

                if(!$('#update-form-model').find('.input-group').length)
                {
                    $('#update-form-model').find('.form-group label').last().after('<div class="input-group mb-3 input-group-sm">'+
                        '<input type="text" name="sub_category[]" placeholder="Enter sub-category" class="form-control">'+
                    '</div>');
                }
            }
        });
    }
</script>
@endsection