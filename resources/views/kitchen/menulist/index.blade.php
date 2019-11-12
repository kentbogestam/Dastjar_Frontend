@extends('layouts.blank')

@section('style')
	<link href="{{asset('css/kitchen/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/kitchen/bootstrap-material-design.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/kitchen/ripples.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/bootstrap-material-datetimepicker.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/kitchen/jquery-ui.css') }}" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link href='//fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- <script type="text/javascript" href="{{ asset('kitchenJs/moment-with-locales.min.js') }}"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>

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
			/*float: right; */
			margin-left: 15px;
			color: rgba(199,7,17,1) !important;
			border: 1px solid rgba(199,7,17,1);
			padding: 4px 8px;
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

		.future-prices { display: none; }

		.circle-top {
			height: 60px;
		    width: 150px;
		    border-radius: 90px 90px 0 0 !important;
		    background: rgba(199,7,17,1) !important;
		    color: #fff !important;
		    font-weight: 400 !important;
		}
		.circle-top span {
			line-height: 40px;
		}
	</style>
@stop

@section('content')
<?php
//dd(session()->all());
?>

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

	<div id="accordion2" class="container">
		<p class="menu_txt">
			<a href="{{ url('kitchen/dishtype/list') }}" class="circle-top" data-role="button" data-inline="true" data-ajax="false"><span>Dish Type</span></a>
		</p>
		<a href="{{ url('kitchen/create-menu') }}" class="fa fa-plus-circle fa-4x add_menu_btn" data-ajax="false"></a>
		<hr>

		@if( !empty($menuTypes) )
			<div class="menu-sortable">
				@foreach($menuTypes as $menuType)
					<div id="{{ $menuType->dish_id }}" class="menu-sortable-item">
						<a href="#demo_{{ $menuType->dish_id }}" class="partial-circle menu-type" data-id="{{ $menuType->dish_id }}" data-toggle="collapse">
							<p class="dish_type">{{ $menuType->dish_name }}</p>
						</a>
						<br/><br/>
						<div id="demo_{{ $menuType->dish_id }}" data-id="{{ $menuType->dish_id }}" class="collapse collapse_block sortable">Loading...</div>
						<br>
						@if(!$loop->last)
							<hr>
						@endif
					</div>
				@endforeach
			</div>
		@endif

		<!-- The Modal -->
		<div class="modal fade" id="myModal">
			<div class="modal-dialog modal-dialog-centered">
			  <div class="modal-content">
			  
				<!-- Modal Header -->
				<div class="modal-header">
				  <h4 class="modal-title">Add New Price</h4>
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<form id="add-dish-price-frm" method="post" action="{{ url('kitchen/add-dish-price') }}" data-ajax="false">
				<!-- Modal body -->
				<div class="modal-body">
					  	<input type="hidden" id="selected_prod_product_id" name="product_id"/>
					  	<input type="hidden" id="selected_prod_store_id" name="store_id"/>	  
						<input type="number" name="price" placeholder="Price ({{$currency}})" autocomplete="off" required />
						<input type="text" id="date-start" name="dateStart" placeholder="Publishing Start Date" required />
						<input type="hidden" id="date-start-utc" name="publishing_start_date"/>
						<input type="text" id="date-end" name="dateEnd" placeholder="Publishing End Date" required />
						<input type="hidden" id="date-end-utc" name="publishing_end_date"/>						  
						{{ csrf_field() }}
				</div>
				
				<!-- Modal footer -->
				<div class="modal-footer">
				  <button type="button" id="close-price-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
				  <button type="button" id="save-price-btn" class="btn btn-primary">Save</button>
				</div>
				</form>
			  </div>
			</div>
		</div>
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

	<!-- Warning Model: if time doesn't cover the working hours of the restaurant -->
	<div class="modal fade" id="warning-add-product-future-price">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Warning!</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">{{ __('messages.warningAddProductFuturePrice') }}</div>
			</div>
		</div>
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
		// Validation before add the future date
	    $('#save-price-btn').on('click', function() {
	    	dkS = moment($("#date-start").val(),'DD/MM/YYYY HH:mm').toDate();
	    	dkE = moment($("#date-end").val(),'DD/MM/YYYY HH:mm').toDate();

			if(dkS>dkE){
				alert("Publishing start date must be smaller than publishing end date");
				return false;
			}
			else
			{
				$.post("{{url('kitchen/is-future-date-available')}}", 
					{
						"_token": "{{ csrf_token() }}", 
						'product_id': $('#selected_prod_product_id').val(),
						'store_id': $('#selected_prod_store_id').val(),
						'publishing_start_date': $("#date-start-utc").val(), 
						'publishing_end_date': $("#date-end-utc").val()
					}, 
					function(data) {
						// console.log(data);

						if(!data.status)
						{
							alert('Invalid date.');
							return false;
						}
						else
						{
							$('#add-dish-price-frm').submit();
						}
					});
			}
	    });

		$('close').removeClass('ui-btn').removeClass('ui-shadow').removeClass('ui-corner-all');

		var dateToday = new Date();
		dKEnd = null;

		$('#date-start').bootstrapMaterialDatePicker
		({
			weekStart: 0, format: 'DD/MM/YYYY - HH:mm', minDate: dateToday, clearButton: true
		}).on('change', function(e, date)
		{
			$('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
			$('#date-start-utc').val(moment.utc(date).format('DD/MM/YYYY HH:mm'));								
		});

		$('#date-end').bootstrapMaterialDatePicker
		({
			weekStart: 0, format: 'DD/MM/YYYY - HH:mm', minDate: dateToday, clearButton: true
		}).on('change', function(e2, date2)
		{
			dKEnd = date2;
			$('#date-end-utc').val(moment.utc(date2).format('DD/MM/YYYY HH:mm'));
		});

		$.material.init();

		// Get dishes belongs to menu
		$('.menu-type').on('click', function() {
			var id = $(this).attr('data-id');
			$this = $(this);

			if( !$this.nextAll('#demo_'+id).hasClass('show') )
			{
				$.ajax({
					type: "POST",
					url: "{{ url('kitchen/ajax-get-product-by-dish-type') }}",
					data: {"_token": "{{ csrf_token() }}", "dish_id": id},
					//dataType: 'json',
					success: function(returnedData) {
						// console.log(returnedData);
						var html = '';

				        $.each(returnedData.products, function(i, item) {
				        	//console.log(item.current_price);
				        	
				        	var currentPrice = '';
				        	if(item.current_price != null)
				        	{
				        		//
								dStart = item.current_price.publishing_start_date;
								dStart = moment.utc(dStart).toDate();
								dStart = moment(dStart).local().format('MMM DD, Y HH:mm');
								dEnd = item.current_price.publishing_end_date;
								dEnd = moment.utc(dEnd).toDate();				
								dEnd = moment(dEnd).local().format('MMM DD, Y HH:mm');
								formattedFromToDate = " " + dStart + " - " + dEnd;

								//
				        		currentPrice += '<div class="menu_icons row">'+
				        			'<div class="col-sm-12">'+
				        				'<span style="margin-right: 10px; color: rgba(199,7,17,1)">SEK '+item.current_price.price+'</span><span class="fa fa-calendar"></span><span>'+formattedFromToDate+'</span>'+
					        			'<a href="javascript:void(0)" title="{{ __('messages.iDishRemovePrice') }}" onClick="deleteDishPrice(\'{{url('kitchen/delete-dish-price?price_id=')}}'+item.current_price.id+'\')" data-ajax="false">'+
					        				'<span class="fa fa-trash" style="margin-left: 15px"></span>'+
					        			'</a>'+
					        			'<a href="{{url('kitchen/edit-menu-dish?product_id=')}}'+item.product_id+'&store_id={{ Session::get('kitchenStoreId') }}&price_id='+item.current_price.id+'" title="{{ __('messages.iDishUpdatePrice') }}" data-ajax="false"><span class="fa fa-edit" style="margin-left: 5px"></span>'+
					        			'</a>'+
				        			'</div>'+
				        		'</div>';
				        	}

				        	// HTML part
				            html += '<div class="card" style="padding: 20px" data-id="'+item.product_id+'">'+
				            	'<div class="row">'+
				            		'<div class="col-sm-2">'+
				            			'<img src="'+item.small_image+'" class="prod_img"/>'+
				            		'</div>'+
				            		'<div class="col-sm-6">'+
				            			'<h3>'+item.product_name+'</h3>'+
										'<p>'+item.product_description+'</p>'+
										'<div class="current-price">'+currentPrice+'</div>'+
										'<button type="button" class="ui-btn ui-mini ui-btn-inline btn-show-future-prices" onclick="getFuturePriceByProduct(\''+item.product_id+'\', this);">Show future prices</button>'+
				            			'<div class="future-prices"></div>'+
				            		'</div>'+
				            		'<div class="col-sm-4 text-right">'+
				            			'<span><a href="javascript:void(0)" onClick="copyDish(\'{{url('kitchen/copy-dish')}}/'+item.product_id+'\')" data-ajax="false">'+
					        				'<i class="fa fa-clone" aria-hidden="true"></i>'+
					        			'</a></span>'+
				            			'<span style="margin-left: 10px"><a href="javascript:void(0)" onClick="delete_dish(\'{{url('kitchen/delete-menu-dish?product_id=')}}'+item.product_id+'\')" data-ajax="false">'+
					        				'<span class="fa fa-trash"></span>'+
					        			'</a></span>'+
				            			'<a href="javascript:void(0)" title="{{ __('messages.iDishAddNewPrice') }}" onClick="add_dish_price(\''+item.product_id+'\', \'{{Session::get('kitchenStoreId')}}\')" class="btn waves-effect add-price-btn" data-ajax="false">Add New Price</a>'+
				            		'</div>'+
				            	'</div>'+
				            '</div>';
				        });

				        $this.nextAll('#demo_'+id).html(html);
					}
				});
			}
		});
	});

	// Show warning add future price model
	@if(Session::has('warningAddFuturePrice'))
		$("#warning-add-product-future-price").modal();
	@endif

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
		        // console.log(list);
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

	// Copy dish
	function copyDish(url)
	{
		if(confirm('Are you sure you want to clone this product?')) {
    	    window.location = url;
	    }
	}

	// Delete Dish
	function delete_dish(url){
		if(confirm('Are you sure you want to delete this product?')) {
    	    window.location = url;
	    }
	}

	// Delete dish price
	function deleteDishPrice(url)
	{
		if(confirm('Are you sure you want to delete this price?')) {
    	    window.location = url;
	    }
	}

	function add_dish_price(product_id, store_id){
			$('#selected_prod_product_id').val(product_id);
			$('#selected_prod_store_id').val(store_id);
    	    // show Modal
        	$('#myModal').modal('show');
	}

	//
	function getFuturePriceByProduct(product_id, This)
	{
		var $this = $(This);

		if( $this.next('.future-prices').text().length )
		{
			$this.next('.future-prices').slideUp('100').text('');
			$this.text('Show future prices');
		}
		else
		{
			$.post("{{url('kitchen/ajax-get-future-price-by-product')}}",
				{"_token": "{{ csrf_token() }}", "product_id": product_id},
				function(returnedData){
					// console.log(returnedData);
					// console.log(returnedData.futureProductPrices.length);
					var futurePricesHtml = '';

					if(returnedData.futureProductPrices != null && returnedData.futureProductPrices.length)
					{
						$.each(returnedData.futureProductPrices, function(i, item) {
							//
							dStart = item.publishing_start_date;
							dStart = moment.utc(dStart).toDate();
							dStart = moment(dStart).local().format('MMM DD, Y HH:mm');
							dEnd = item.publishing_end_date;
							dEnd = moment.utc(dEnd).toDate();				
							dEnd = moment(dEnd).local().format('MMM DD, Y HH:mm');
							formattedFromToDate = " " + dStart + " - " + dEnd;

							//
				        	futurePricesHtml += '<div class="menu_icons row">'+
				        		'<div class="col-sm-12">'+
				        			'<span style="margin-right: 10px; color: rgba(199,7,17,1)">SEK '+item.price+'</span><span class="fa fa-calendar"></span><span>'+formattedFromToDate+'</span>'+
				        			'<a href="javascript:void(0)" title="{{ __('messages.iDishRemovePrice') }}" onClick="deleteDishPrice(\'{{url('kitchen/delete-dish-price?price_id=')}}'+item.id+'&price_id='+item.id+'\')" data-ajax="false">'+
				        				'<span class="fa fa-trash" style="margin-left: 15px"></span>'+
				        			'</a>'+
				        			'<a href="{{url('kitchen/edit-menu-dish?product_id=')}}'+item.product_id+'&store_id={{ Session::get('kitchenStoreId') }}&price_id='+item.id+'" title="{{ __('messages.iDishUpdatePrice') }}" data-ajax="false"><span class="fa fa-edit" style="margin-left: 5px"></span>'+
				        			'</a>'+
				        		'</div>'+
			        		'</div>';
				        });
					}
					else
					{
						futurePricesHtml += 'No future price found for this product.';
					}

					$this.next('.future-prices').html(futurePricesHtml).slideDown(200);
					$this.text('Hide future prices');
				}
			);
		}
	}

	var lastDishId;

	$(document).ready(function(){
		$(".sortable").sortable({
			update: function(event, ui) {
				dish_type = event.target.id.replace("demo_","");

				// Get products updated order
				products = [];

				$(this).find('.card').each(function() {
					products.push($(this).data('id'));
				});

				products = JSON.stringify(products);

				// Update products order
				$.post("{{ url('api/v1/kitchen/update-product-rank') }}", 
				{
					dish_type: dish_type,
					products: products
				},
				function(data, status){
					// console.log("Data: " + data + "\nStatus: " + status);
				});
			}
		});

		//
		if( $('.menu-sortable').length )
		{
			$(".menu-sortable").sortable({
				axis: 'y',
				update: function(event, ui) {
					items = JSON.stringify($(this).sortable('toArray'));

					$.post("{{ url('api/v1/kitchen/update-menu-rank') }}", 
						{
							u_id: "<?php echo Auth::user()->u_id; ?>",
							items: items
						}, function(data, status){
							// console.log("Data: " + data + "\nStatus: " + status);
						}
					);
				}
			});
		}
	});
	</script>

@endsection