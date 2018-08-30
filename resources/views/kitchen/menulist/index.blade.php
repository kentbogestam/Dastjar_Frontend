@extends('layouts.blank')

@section('style')
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet"
	href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css"/>
	<link rel="stylesheet"
	href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/ripples.min.css"/>
	<link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />
	<link href='//fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="//momentjs.com/downloads/moment-with-locales.min.js"></script>

	<style>
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

		.add_menu_btn{
			background: #fff;
			color: rgba(199,7,17,1) !important;
			float: right;
			margin-top: -30px;
			padding: 0px;
			border-radius: 50%;
			z-index: 999;
		}

		.add_menu_btn:hover{
			text-decoration: none;
			color: rgb(167, 12, 20) !important;
		}

		.prod_img{
			height: 47px;
			width: 55px;
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

		.collapse_block{
			margin-bottom: 50px;
		}

		.add-price-btn{
			float: right; 
			margin-left: 15px;
			color: rgba(199,7,17,1) !important;
			border: 1px solid rgba(199,7,17,1);
			padding: 8px;
			font-size: 16px;
			letter-spacing: 1px;
		}

		.add-price-btn:hover{
			color: #fff !important;
			background-color: rgba(199,7,17,1) !important;
		}

		.close{
			width: auto !important;
			color: rgba(199,7,17,1) !important;
		}

		.close:hover{
			color: rgb(167, 12, 20) !important;
		}

		#myModal input{
			border: 1 !important;
		}

		.modal-title{
			color: rgba(199,7,17,1);
		}

		.modal-body div{
			box-shadow: inset 0 1px 3px rgba(0,0,0,.2) !important;
		    border: 1px solid #ddd !important;
		}

		#close-price-btn{
			margin-bottom: 16px;
			margin-right: 32px;
			color: rgba(199,7,17,1);
			border: 1px solid rgba(199,7,17,1);
		}

		#save-price-btn{
			color: #fff;
			background-color: rgba(199,7,17,1) !important;
		}

		.modal-footer{
			padding-left: 24px !important;
    		padding-right: 24px !important;
		}

		.dtp > .dtp-content > .dtp-date-view > header.dtp-header{
		    background: #821015;
		}

		.dtp div.dtp-date, .dtp div.dtp-time {
    		background: #a72626;
		}

		.dish_type{
			padding-left: 25px;
			padding-right: 25px;
		}

		/*** accordian ***/
		.group { zoom: 1 }
		#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
		#sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; }
		html>body #sortable li { height: 1.5em; line-height: 1.2em; }
		.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
	</style>
@stop

@section('content')
<?php
//dd(session()->all());
?>

<div data-role="header" data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
		<img src="{{asset('kitchenImages/logo-img.png')}}">
		<a href = "{{ url('kitchen/logout') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
			</a>
		</div>
		<h3 class="ui-bar ui-bar-a order_background">{{ __('messages.Menu') }} <span>{{$storeName}}</span></h3>
	</div>
	<div role="main" class="ui-content">
		<div class="ready_notification">
			@if ($message = Session::get('success'))
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
				 @if(is_array($message))
		            @foreach ($message as $m)
		                {{ $languageStrings[$m] or $m }}
		            @endforeach
		        @else
		            {{ $languageStrings[$message] or $message }}
		        @endif
		    </div>
			@endif

			@if ($message = Session::get('error'))
			<div class="table-content sucess_msg">
				<img src="{{asset('images/icons/error-256.png')}}" style="width: 24px; height: 24px">
				 @if(is_array($message))
		            @foreach ($message as $m)
		                {{ $languageStrings[$m] or $m }}
		            @endforeach
		        @else
		            {{ $languageStrings[$message] or $message }}
		        @endif
		    </div>
			@endif
		</div>
	</div>

	<div id="accordion2" class="container">
		<p class="menu_txt">MENU</p>
		<a href="{{ url('kitchen/create-menu') }}" class="fa fa-plus-circle fa-4x add_menu_btn" data-ajax="false"></a>

		@foreach($allData as $key => $row)		
		<hr />

		<a href="#demo_{{$key}}" class="partial-circle" data-toggle="collapse">
				<p class="dish_type">
					<?php
						if(strlen($menuTypes[$key]) > 15){
							$menuTypes[$key] = substr_replace($menuTypes[$key],"",12) . "...";
						}
					?>
					{{ $menuTypes[$key] }}
				</p>
		</a>	

			<br/><br/>

		<div id="demo_{{$key}}" data-id="{{$key}}" class="collapse collapse_block sortable">	

		@foreach($row as $key2 => $row2)	
		<div class="card" style="padding: 20px" data-id="{{$row2['product_id']}}">			
		<div class="row">
			<div class="col-sm-2">
			<img src="{{$row2['small_image']}}" class="prod_img"/>
			</div>
			<div class="col-sm-10">
				<div>
					<h3 style="display: inline">{{$row2['product_name']}}</h3>
					<a href="javascript:void(0)" onClick="add_dish_price('{{$row2['product_id'].'\',\''.Session::get('storeId')}}')" class="btn waves-effect add-price-btn" data-ajax="false">Add Future Price</a>
				</div>
				<div>
					<p>{{$row2['product_description']}}</p>
				</div>

				@foreach($row2['prices'] as $key3 => $row3)	
				<div class="menu_icons">
					<span style="margin-right: 10px; color: rgba(199,7,17,1)">SEK {{$row3['price']}}</span>		
					<?php if($row3['publishing_start_date'] != "0000-00-00 00:00:00" && $row3['publishing_start_date'] != null  && $row3['publishing_start_date'] != ""){ ?>
						<span class="fa fa-calendar"></span><span>

					<script type="text/javascript">
						dStart = "{{date('Y-m-d H:i:s', strtotime($row3['publishing_start_date']))}}";
						dStart = moment.utc(dStart).toDate();
						dStart = moment(dStart).local().format('MMMM DD, Y HH:mm');

						dEnd = "{{date('Y-m-d H:i:s', strtotime($row3['publishing_end_date']))}}";
						dEnd = moment.utc(dEnd).toDate();				
						dEnd = moment(dEnd).local().format('MMMM DD, Y HH:mm');

						document.write(" " + dStart + " - " + dEnd);
					</script>

						</span>
					<?php }else{ ?>
						<span class=""></span><span></span>
					<?php } ?>
					<a href="javascript:void(0)" onClick="delete_dish('{{url('kitchen/delete-menu-dish?product_id='.$row2['product_id'].'&price_id='.$row3['price_id'])}}')" data-ajax="false"><span class="fa fa-trash" style="float: right; margin-left: 15px"></span></a>
					<a href="{{url('kitchen/edit-menu-dish?product_id='.$row2['product_id'].'&store_id='.Session::get('storeId').'&price_id='.$row3['price_id'])}}" data-ajax="false"><span class="fa fa-edit" style="float: right"></span></a>
				</div>	
				@endforeach

			</div>
		</div>
		</div>
		@endforeach

		</div>
		<br/>

		@endforeach

	<!-- The Modal -->
	<div class="modal fade" id="myModal">
		<div class="modal-dialog modal-dialog-centered">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title">Add Future Price</h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form id="" method="post" action="{{ url('kitchen/add-dish-price') }}" data-ajax="false">
			<!-- Modal body -->
			<div class="modal-body">
				  	<input type="hidden" id="selected_prod_product_id" name="product_id"/>
				  	<input type="hidden" id="selected_prod_store_id" name="store_id"/>	  
					<input type="number" name="price" placeholder="Price ({{$currency}})"/>
					<input type="text" id="date-start" name="" placeholder="Publishing Start Date"/>
					<input type="hidden" id="date-start-utc" name="publishing_start_date"/>
					<input type="text" id="date-end" name="" placeholder="Publishing End Date"/>
					<input type="hidden" id="date-end-utc" name="publishing_end_date"/>						  
					{{ csrf_field() }}
			</div>
			
			<!-- Modal footer -->
			<div class="modal-footer">
			  <button type="button" id="close-price-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  <button type="submit" id="save-price-btn" class="btn btn-primary">Save</button>
			</div>
		</form>
			
		  </div>
		</div>
	  </div>

	</div>


	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer_container">
		<div class="ui-grid-a center">
			<div class="ui-block-a left-side_menu">
				<div class="ui-block-a block_div">
					<a href="{{ url('kitchen/store') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-1.png')}}">
					</div>
					<span>{{ __('messages.Orders') }}</span>
					</a>
				</div>
				<div class="ui-block-b" title="{{ __('messages.Kitchen') }}">
					<a href = "{{ url('kitchen/kitchen-detail') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-2.png')}}">
					</div>
					<span>{{ __('messages.Kitchen') }}</span>
					</a>
				</div>
				<div class="ui-block-b" title="{{ __('messages.Catering') }}">
					<a href = "{{ url('kitchen/catering') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-3.png')}}">
					</div>
					<span>{{ __('messages.Catering') }}</span>
					</a>
				</div>
			</div>

			<div class="ui-block-b right-side_menu" title="Kitchen Setting">							
				<div class="ui-block-a drop_down"><a href = "{{ url('kitchen/kitchen-setting') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-6.png')}}">
					</div>
				</a></div>

				<div class="ui-block-b block_div active" title="{{ __('messages.Menu') }}"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-7.png')}}">
					</div>
					<span>{{ __('messages.Menu') }}</span>
				</a></div>

				<div class="ui-block-c" title="{{ __('messages.Order Onsite') }}"><a href = "{{ url('kitchen/kitchen-order-onsite') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/icon-4.png')}}">
					</div>
					<span>{{ __('messages.Order Onsite') }}</span>
				</a></div>
			</div>
		</div>
	</div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
	var list = Array();
	var tempCount = 18;

		$(document).ready(function(){
			$('close').removeClass('ui-btn').removeClass('ui-shadow').removeClass('ui-corner-all');

			var dateToday = new Date();
			dKEnd = null;

			$('#date-start').bootstrapMaterialDatePicker
			({
				weekStart: 0, format: 'DD/MM/YYYY - HH:mm', minDate: dateToday, clearButton: true
			}).on('change', function(e, date)
			{
				if(dKEnd!=null){
					if(new Date(date)>new Date(dKEnd)){
						alert("Publishing start date must be smaller than publishing end date");
					}					
				}

				$('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
				$('#date-start-utc').val(moment.utc(date).format('DD/MM/YYYY HH:mm'));								
			});

			$('#date-end').bootstrapMaterialDatePicker
			({
				weekStart: 0, format: 'DD/MM/YYYY - HH:mm', minDate: dateToday, clearButton: true
			}).on('change', function(e2, date2)
			{
				dKEnd = date2;
				$('#date-start').bootstrapMaterialDatePicker('setMaxDate', date2);
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
    	if (scrolled >= scrollEnd) {
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
    	}
	});

	function delete_dish(url){
		if(confirm('Are you sure you want to delete this product?')) {
    	    window.location = url;
	    }
	}

	function add_dish_price(product_id, store_id){
			$('#selected_prod_product_id').val(product_id);
			$('#selected_prod_store_id').val(store_id);
    	    // show Modal
        	$('#myModal').modal('show');
	}

	var lastDishId;

	$(document).ready(function(){
	
		 $(".sortable").sortable({
			stop: function(event, ui) {
				index =  ui.item.index();
				product_id = ui.item.attr("data-id");
				dish_type = event.target.id.replace("demo_","");				

				$.post("{{ url('api/v1/kitchen/update-product-rank') }}", 
					{dish_type: dish_type,
					product_id: product_id,
					index: index+1},
					function(data, status){
		        	console.log("Data: " + data['data'] + "\nStatus: " + status);
			    });
			}
		 });
	});
	</script>

@endsection