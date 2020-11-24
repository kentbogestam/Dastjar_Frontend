@extends('layouts.blank')

@section('style')
	<link href="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet"
	href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css"/>
	<link rel="stylesheet"
	href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/ripples.min.css"/>
	<link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />
	<link href='//fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<style>
		.slaveDiv select,.slaveDiv input{
			width:100%;
		}
		.menu_txt{
			color: rgba(199,7,17,1);
			text-align: center;
			font-weight: 600;
		}

		.menu_icons a{
			text-decoration: none;
			color: inherit;
		}

		.menu_icons a:hover{
			text-decoration: none;
		}

		.partial-circle-outer{
			text-align: center;
		}

		.partial-circle {
			display: block;
			position: absolute;
			height: 50px;
			width: 180px;
			overflow: hidden;
			margin-top: 8px;
			color: #fff;
			text-decoration: none;
			left: 50%;
			transform: translate(-50%,-50%);
		}

		.partial-circle:hover {
			text-decoration: none;
		}

		.partial-circle p{
			text-align: center;
			line-height: 40px;
			color: #fff;
		    font-weight: 400;
		}

		.partial-circle:before {
			content: 'world';
			position: absolute;
			height: 180px;
			width: 180px;
			border-radius: 50%;
			bottom: 0;
			background: rgba(199,7,17,1);
			z-index: -1;
			/* color: #fff; */
		}

		.create-menu-form input{
			border: 1px solid #aaa !important;
			border-radius: 2px;
		}

		.upload_menu{
			display: block;
			border: 1px solid #aaa !important;			
			text-align: center;
			color: #aaa;
			padding: 5px;
			cursor: pointer;
		}

		.upload_menu:hover{
			text-decoration: none;
		}

		.menu_save_btn{
			background: rgba(199,7,17,1) !important;
			color: #fff !important;
			border-radius: 0px !important;
		}

		.dish_name_col{
						padding-bottom: 50px;
		}

		.dish_name_col .ui-input-text{
			position: absolute;
		    bottom: 0;
    		width: calc(100% - 10px);
		}

		.menu_image_col{
			padding-bottom: 3px;
		}

		.menu_back_btn{
			color: rgba(199,7,17,1) !important;
		}

		.menu_back_btn:hover{
			color: rgb(167, 12, 20) !important;
		}

		#blah{
			display: none;
			max-width: 100%;
			max-height: 50px;
		}

		.cal_icon{
			position: absolute;
			top: 20px;
			right: 25px;
		}

		#fileupload{
			position: absolute; 
			top: -10px;
			opacity:0; 
			z-index=-1; 
			width: 100%; 
			height: 100%;
			cursor: pointer;
		}

		.dtp > .dtp-content > .dtp-date-view > header.dtp-header{
		    background: #821015;
		}

		.dtp div.dtp-date, .dtp div.dtp-time {
    		background: #a72626;
		}

		.warning {
			font-size: 11px;
			color: #8a6d3b;
		}

		@media only screen and (max-width: 768px) {
			.upload_img_txt{
				display: none;
			}
			#blah{
				max-height: 75px;
			}
		}
	</style>
@stop

@section('content')

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<h3 class="ui-bar ui-bar-a order_background"><span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<div class="ready_notification">
			@if ($message = Session::get('success'))
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
				 @if(is_array($message))
		            @foreach ($message as $m)
		                {{ $languageStrings[$m] ?? $m }}
		            @endforeach
		        @else
		            {{ $languageStrings[$message] ?? $message }}
		        @endif
		    </div>
			@endif

			@if ($message = Session::get('error'))
				<div class="table-content sucess_msg">
					<img src="{{asset('images/icons/error-256.png')}}" style="width: 24px; height: 24px">
					 @if(is_array($message))
			            @foreach ($message as $m)
			                {{ $languageStrings[$m] ?? $m }}
			            @endforeach
			        @else
			            {{ $languageStrings[$message] ?? $message }}
			        @endif
			    </div>
			@endif
		</div>
	</div>

	<div class="container">
		<br />

@if(!isset($product))
		<?php $product = new stdClass(); ?>
		<form class="create-menu-form" method="post" action="{{ url('kitchen/create-menu-save') }}" enctype="multipart/form-data" data-ajax="false">
@else
		<form class="create-menu-form" method="post" action="{{ url('kitchen/create-menu-update') }}" enctype="multipart/form-data" data-ajax="false">
@endif

@if(!isset($product_price_list))
	<?php $product_price_list = new stdClass(); ?>
@endif

		<div class="row">
			<div class="col-10 dish_name_col">
				<a href="{{ url('kitchen/menu') }}" class="menu_back_btn" data-ajax="false"><span class="fa fa-chevron-left"></span>Back</a>
			</div>
			<div class="col-2 menu_image_col">
				<label class="upload_menu" for="fileupload" title="{{ __('messages.iDishImage') }}">
					<img src="{{ $product->small_image ?? "" }}" id="blah"/>
					<span class="fa fa-camera camera_icon"></span>
					<p class="upload_img_txt">Upload Menu Image</p>
					<input type="file" name="prodImage" id="fileupload" onerror="alert('Image missing')" onchange="readURL(this);"/>
					@if(isset($product->small_image) && $product->small_image)
						<input type="hidden" name="smallImage" value="{{ $product->small_image }}">
					@endif
				</label>
				<div id='warning-image-upload' class="warning"></div>
			</div>
		</div>

		<input type="hidden" name="timezoneOffset" class="timezoneOffset">

		<button class="btn btn-success addMore" style="background-color:green;color:white" type="button">Add More Languages</button>
		<div class="masterDiv">
			@if(!empty($names))
				@for(@$i=(count($names)-1);$i>=0 ; $i--)
					<div class="row slaveDiv" id="slaveDiv1">
						<div class="col-3">
							<select name="dishLang[]" class="dishLang" required title="{{ __('messages.iDishLanguage') }}">
								<option value="" selected disabled>Dish Language</option>
								<option value="SWE" @if(@$langs[$i] == "SWE") selected @endif>SWE</option>
								<option value="ENG" @if(@$langs[$i] == "ENG") selected @endif>ENG</option>
							</select>
						</div>
						<div class="col-4">
							<input type="text" name="prodName[]" placeholder="Dish Name" class="dish_name" value="{{@$names[$i]}}" maxlength="24" title="{{ __('messages.iDishName') }}" required/>
						</div>
						<div class="col-4">
							<input type="text" name="prodDesc[]" placeholder="Description" maxlength="50" title="{{ __('messages.iDishDescription') }}" value="{{@$descs[$i]}}" required/>
						</div>
						<div class="col-1">
							<button class="btn btn-danger btn-sm removeMore" style="background-color:maroon;color:white" rel="1" type="button">X</button>
						</div>
					</div>
				@endfor
			@else
				<div class="row slaveDiv" id="slaveDiv1">
					<div class="col-3">
						<select name="dishLang[]" class="dishLang" required title="{{ __('messages.iDishLanguage') }}">
							<option value="" selected disabled>Dish Language</option>
							<option value="SWE">SWE</option>
							<option value="ENG">ENG</option>
						</select>
					</div>
					<div class="col-4">
						<input type="text" name="prodName[]" placeholder="Dish Name" class="dish_name" value="" maxlength="24" title="{{ __('messages.iDishName') }}" required/>
					</div>
					<div class="col-4">
						<input type="text" name="prodDesc[]" placeholder="Description" value="" maxlength="50" title="{{ __('messages.iDishDescription') }}" required/>
					</div>
					<div class="col-1">
						<button class="btn btn-danger btn-sm removeMore" style="background-color:maroon;color:white" rel="1" type="button">X</button>
					</div>
				</div>
			@endif
		</div>
		<input type="hidden" name="countParam" id="countParam" value="1">
		<div class="row">
			<div class="col-12">
				<select id="dishType" name="dishType" required title="{{ __('messages.iDishType') }}">
					<option value="" selected disabled>Dish Type</option>
					@foreach($listDishes as $row)
						@if( isset($product->dish_type) && ($row['dish_id'] == $product->dish_type) )
							<option value="{{ $row['dish_id'] }}" class="level-{{ $row['level'] }}" selected>{!! Helper::strReplaceBy($row['dish_name'], $row['level']*2) !!}</option>
						@else
							<option value="{{ $row['dish_id'] }}" class="level-{{ $row['level'] }}">{!! Helper::strReplaceBy($row['dish_name'], $row['level']*2) !!}</option>
						@endif
					@endforeach
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<input type="number" id="prep-time" name="prepTime" placeholder="Prep. Time (mins)" value="{{ empty(@$product->preparation_Time) ? '15' : @$product->preparation_Time }}" required title="{{ __('messages.iDishPrepTime') }}" />
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<input type="number" name="prodPrice" placeholder="Price ({{$currency}})" value="{{ $product_price_list->price ?? "" }}" required title="{{ __('messages.iDishPrice') }}" />
			</div>
		</div>

		<input type="hidden" name="currency" value="{{$currency}}"/>

		<div class="row">
			<div class="col-12">
				<input type="text" id="date-start" name="" placeholder="Publishing Start Date" value="" required title="{{ __('messages.iDishStartPublishDate') }}" />
				<input type="hidden" id="date-start-utc" name="publish_start_date">
				<span class="fa fa-calendar cal_icon"></span>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<input type="text" id="date-end" name="" placeholder="Publishing End Date" value="" required title="{{ __('messages.iDishEndPublishDate') }}" />
				<input type="hidden" id="date-end-utc" name="publish_end_date">				
				<span class="fa fa-calendar cal_icon"></span>
			</div>
		</div>

		@if(isset($product->product_id))
		<input type="hidden" name="product_id" value="{{ $product->product_id ?? "" }}"/>
		@endif

		@if(isset($store_id))
		<input type="hidden" name="store_id" value="{{ $store_id ?? "" }}"/>
		@endif

		@if(isset($product_price_list->id))
			<input type="hidden" name="price_id" value="{{ $product_price_list->id }}">
		@endif

		{{ csrf_field() }}

		<div class="row">
				<div class="col-12">
					<button class="btn menu_save_btn">SAVE</button>
				</div>
		</div>
		</form>
	</div>
	
	@include('includes.kitchen-footer-menu')

	<div data-role="popup" id="popupCloseRight" class="ui-content" style="max-width:100%;border: none;">
	    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" style="background-color:#000;border-color: #000;">Close</a>
		<table data-role="table" id="table-custom-2" class="ui-body-d ui-shadow table-stripe ui-responsive table_size" >
			<thead>
				<tr class="ui-bar-d">
					<th data-priority="2">{{ __('messages.Orders') }}</th>
			   		<th>{{ __('messages.Amount') }}</th> 
			   		<th data-priority="3">{{ __('messages.Product') }}</th>
			    	<th data-priority="1">{{ __('messages.Comments') }}</th> 
			    </tr>
			</thead>
			<tbody id="specificOrderDetailContianer">
				<tr>
				
				</tr> 
			</tbody>
		</table>
	</div>
@endsection

@section('footer-script')
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<!-- <script type="text/javascript" src="//rawgit.com/FezVrasta/bootstrap-material-design/master/dist/js/material.min.js"></script> -->
<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-material-datetimepicker.js') }}"></script>

<script type="text/javascript">

	$('body').on('click', '.addMore', function(){
		// var html = $('#slaveDiv1').html();
		// $('.masterDiv').append('<div class="row slaveDiv" id="slaveDiv1">'+html+'</div>');
		// $('.masterDiv').append('<div class="row slaveDiv" id="slaveDiv1"><div class="col-3"><select name="dishLang[]" class="dishLang" required title="{{ __('messages.iDishLanguage') }}"><option value="" selected disabled>Dish Language</option><option value="SWE">SWE</option><option value="ENG">ENG</option></select></div><div class="col-4"><input type="text" name="prodName" placeholder="Dish Name" class="dish_name" value="" maxlength="24" title="{{ __('messages.iDishName') }}" required/></div><div class="col-4"><input type="text" name="prodDesc" placeholder="Description" value="" maxlength="50" title="{{ __('messages.iDishDescription') }}" required/></div><div class="col-1"><button class="btn btn-danger removeMore" rel="1"  type="button">X</button></div></div>');

		$('.masterDiv').append('<div class="row slaveDiv" id="slaveDiv1"><div class="col-3"><div class="ui-select"><div id="select-10-button" class="ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow"><span>Dish Language</span><select name="dishLang[]" class="dishLang" required="" title="{{ __('messages.iDishLanguage') }}"><option value="" selected="" disabled="">Dish Language</option><option value="SWE">SWE</option><option value="ENG">ENG</option></select></div></div></div><div class="col-4"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input type="text" name="prodName[]" placeholder="Dish Name" class="dish_name" value="" maxlength="24" title="{{ __('messages.iDishName') }}" required=""></div></div><div class="col-4"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input type="text" name="prodDesc[]" placeholder="Description" value="" maxlength="50" title="{{ __('messages.iDishDescription') }}" required=""></div></div><div class="col-1"><button class="btn btn-danger btn-sm removeMore ui-btn ui-shadow ui-corner-all" style="background-color:maroon;color:white" rel="1" type="button">X</button></div></div>');
		var countParam = countSet();
		$('#countParam').val(countParam);
	});

	$('body').on('click', '.removeMore', function(){
		if($('#countParam').val() == "1"){
			alert('At least one Row must be here');
			return false;
		}
		var rel = $(this).attr('rel');
		$('#slaveDiv'+rel).remove();
		var countParam = countSet();
		$('#countParam').val(countParam);
	});

	function countSet(){
		var count = 0;
		$('.masterDiv .slaveDiv').each(function(){
			++count;
			$(this).attr('id', 'slaveDiv'+count);
			$(this).find('.removeMore').attr('rel', count);
		});
		return count;
	}

	$('body').on('change', '.dishLang', function(){
		$(this).parents('.ui-select').find('span').text($(this).val());
	});

	var list = Array();
	var tempCount = 18;
	var fileExt = "";
	var fileSize=0;

	var _URL = window.URL || window.webkitURL;
    function readURL(input) {
    	var file, img;

        if (input.files && input.files[0]) {
        	file = input.files[0];
        	var reader = new FileReader();
            
            reader.onload = function (e) {
            	$('#warning-image-upload').html('');
				$('.camera_icon').hide();
				$('.upload_img_txt').hide();
                $('#blah').attr('src', e.target.result);
				$('#blah').show();					

				// Get image size
                img = new Image();
                img.onload = function () {
                    if(this.width < 1024)
                    {
                        $('#warning-image-upload').html('Image width should be 1024px');
                    }
                };
                img.src = _URL.createObjectURL(file);
            }

            fileSize = input.files[0].size;
            fileExt = input.files[0].name.split('.').pop().toLowerCase();
            reader.readAsDataURL(input.files[0]);	
	    }
    }

    $('.create-menu-form').submit(function(e){     
    	dkS = moment($("#date-start").val(),'DD/MM/YYYY HH:mm').toDate();
    	dkE = moment($("#date-end").val(),'DD/MM/YYYY HH:mm').toDate();

        if(fileSize>6000000){
				alert("Image size should be smaller than 6MB");          	
				return false;
		}else if(fileExt!="" && fileExt!="png" && fileExt!="jpg" && fileExt!="jpeg"){
				alert("Only PNG, JPG and JPEG images are allowed");
				return false;
		}else if(dkS>dkE){
			alert("Publishing start date must be smaller than publishing end date");
			return false;
		}
    });

	$(document).ready(function(){
		var defaultStartDate = "{{date('d/m/Y',strtotime('+1day'))}} 05:00";
		var defaultEndDate = "{{date('d/m/Y',strtotime('+10Year'))}} 05:00";
		@if(isset($product_price_list->publishing_start_date) && $product_price_list->publishing_start_date != "0000-00-00 00:00:00")
			dStart = "{{date('Y-m-d H:i:s', strtotime($product_price_list->publishing_start_date))}}";
			dStart = moment.utc(dStart).toDate();
			$('#date-start').val(moment(dStart).local().format("DD/MM/YYYY HH:mm"));
			$('#date-start-utc').val(moment.utc(dStart).format("DD/MM/YYYY HH:mm"));
			dStart = moment(dStart).local().format("DD/MM/YYYY HH:mm");
		@else
			$('#date-start').val(defaultStartDate);
			dStart = "{{date('Y-m-d',strtotime('+1Day'))}} 05:00";	
			dStart = moment(dStart).toDate();
			dStart = moment.utc(dStart).format("DD/MM/YYYY HH:mm");
			$('#date-start-utc').val(dStart);
		@endif

		@if(isset($product_price_list->publishing_end_date) && $product_price_list->publishing_end_date != "0000-00-00 00:00:00")
			dEnd = "{{date('Y-m-d H:i:s', strtotime($product_price_list->publishing_end_date))}}";
			dEnd = moment.utc(dEnd).toDate();
			$('#date-end').val(moment(dEnd).local().format("DD/MM/YYYY HH:mm"));
			$('#date-end-utc').val(moment.utc(dEnd).format("DD/MM/YYYY HH:mm"));
			dKEnd = moment(dEnd).local().format("YYYY-MM-DD HH:mm");					
			dEnd = moment(dEnd).local().format("DD/MM/YYYY HH:mm");	
		@else
			$('#date-end').val(defaultEndDate);
			dEnd = "{{date('Y-m-d',strtotime('+10Year'))}} 05:00";
			dEnd = moment(dEnd).toDate();
			dKEnd = moment(dEnd).local().format("YYYY-MM-DD HH:mm");											
			dEnd = moment.utc(dEnd).format("DD/MM/YYYY HH:mm");
			$('#date-end-utc').val(dEnd);
		@endif

		@if(isset($product->small_image))
			$('.camera_icon').hide();
			$('.upload_img_txt').hide();
			$('#blah').show();
		@endif

		dKStart = dStart;
		var dateToday = new Date();
		
		$('#date-start').bootstrapMaterialDatePicker
		({
			weekStart: 0, format: 'DD/MM/YYYY HH:mm', minDate: dateToday, maxDate: defaultEndDate, clearButton: true
		}).on('change', function(e, date)
		{
			dKStart = date;
			$('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
			$('#date-start-utc').val(moment.utc(date).format('DD/MM/YYYY HH:mm'));
		});

		$('#date-end').bootstrapMaterialDatePicker
		({
			weekStart: 0, format: 'DD/MM/YYYY HH:mm', minDate: dateToday, maxDate: defaultEndDate, clearButton: true
		}).on('change', function(e2, date2)
		{
			dKEnd = date2;
			$('#date-end-utc').val(moment.utc(date2).format('DD/MM/YYYY HH:mm'));
		});

		$.material.init();
	});

	$(document).on("scrollstop", function (e) {
    	var activePage = $.mobile.pageContainer.pagecontainer("getActivePage"),
        screenHeight = $.mobile.getScreenHeight(),
        contentHeight = $(".ui-content", activePage).outerHeight(),
        header = $(".ui-header", activePage).outerHeight() - 1,
        scrolled = $(window).scrollTop(),
        footer = $(".ui-footer", activePage).outerHeight() - 1,
        scrollEnd = contentHeight - screenHeight + header + footer;

    	$(".ui-btn-left", activePage).text("Scrolled: " + scrolled);
    	//$(".ui-btn-right", activePage).text("ScrollEnd: " + scrollEnd);
    	
    	//if in future this page will get it, then add this condition in and in below if activePage[0].id == "home" 
    	/*if (scrolled >= scrollEnd) {
		        console.log(list);
		        $.mobile.loading("show", {
		        text: "loading more..",
		        textVisible: true,
		        theme: "b"
		    	});
		    	setTimeout(function () {
		         addMore(tempCount);
		         tempCount += 10;
		         $.mobile.loading("hide");
		     },500);
    	}*/
	});

</script>

@endsection