@extends('layouts.kitchenSetting')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

<style type="text/css">
	.sucess_msg{
		display: none;
		position: absolute;
		top: 100px;
 	   width: 100%;
    }

	.btn_blk{
		background-color: #fff;
		border-radius: 0.5em;
	}

	.btn_blk input{
/*		background-color: #fff;
*/		text-align: left;
		padding-left: 2em;
	}

	#delete_prep_time{
	}

		.add_menu_btn{
/*			background: #fff;
*/			color: #551a8b !important;
			float: right;
			margin-top: -50px;
			margin-right: -50px;
			padding: 0px;
			border-radius: 50%;
			z-index: 999;		
			text-decoration: none;
		}

		.add_menu_btn:hover{
			text-decoration: none;
			color: #48107b !important;
		}

		#prep_time{
			margin-top: 100px;
			max-width: 200px;	
			margin-left: auto;
			margin-right: auto;		
		}

		#overlay {
    		position: fixed;
    		display: none;
    		width: 100vw;
    		height: 100vh;
		    top: 0;
		    left: 0;
		    right: 0;
    		bottom: 0;
	    	background-color: rgba(0,0,0,0.5);
	    	z-index: 999;
		}

		#loading-img{
			display: none;
			position: absolute;
			top: 50%;
			left: 50%;
			-moz-transform: translate(-50%);
			-webkit-transform: translate(-50%);
			-o-transform: translate(-50%);
			-ms-transform: translate(-50%);
			transform: translate(-50%);
			z-index: 99999;
		}
</style>

@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
		@include('includes.kitchen-header-sticky-bar')
		<div class="order_background setting_head_container">
			<div class="ui-grid-b center">
				<div class="ui-block-a">
					<a href="{{ URL::to('kitchen/store') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false">
					<img src="{{asset('kitchenImages/backarrow.png')}}" width="11px">
				</a>
				</div>
				<div class="ui-block-b middle_section">
					<a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					{{ __('messages.Extra Preparation Time') }}
				</a>
				</div>
				
			</div>
		</div>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/save-kitchenSetting') }}">
		{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content">
				<div class="table-content sucess_msg">
					<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
					Extra Preparation Time Added Successfully 
			    </div>

			<div>
				
			</div>

			<div>
			
			</div>

				<div id="prep_time" class="btn_blk">	
				<form id="prep_time_form" action="#">	
					@if($prep_time == null)
						<input type="number" name="" placeholder="Preparation time in minutes" required>
						<a href="javascript:void(0)" id="add_prep_time" class="fa fa-plus-circle fa-3x add_menu_btn" style="display: block;" data-ajax="false"></a>
						<a href="javascript:void(0)" id="delete_prep_time" class="fa fa-close fa-3x add_menu_btn" style="display: none;" data-ajax="false"></a>	
					@else
						<input type="number" name="" value="{{$prep_time}}" placeholder="Preparation time in minutes" required disabled>
						<a href="javascript:void(0)" id="add_prep_time" class="fa fa-plus-circle fa-3x add_menu_btn" style="display: none;" data-ajax="false"></a>
						<a href="javascript:void(0)" id="delete_prep_time" class="fa fa-close fa-3x add_menu_btn" style="display: block;" data-ajax="false"></a>	
					@endif
				</form>
				</div>


		</div>
	</form>
</div>

	<img src="{{ asset('images/loading.gif') }}" id="loading-img" />

	  <div id="overlay" onclick="off()">
	  </div>

@endsection

@section('footer-script')
	<script type="text/javascript">
		$("#dataSave").click(function(e){
			var flag = true;

			if(flag){
				$("#form").submit();
			} else{
				alert("Please fill some value");	
				e.preventDefault();
			}
		});

		$('#about_us').click(function(){
			window.open("https://dastjar.com/");
		});

		$('#admin').click(function(){
			window.open("https://admin-dev.dastjar.com/");
		});

		// $('#add_prep_time').click(function(){
		// 	$('#prep_time_form')[0].checkValidity();

		// 	// var validator = $("#prep_time_form").validate();
		// 	// alert(validator);

		// 	// $("#prep_time_form").submit(); 
		// });

		$('.add_menu_btn').click(function(){
			if($('#prep_time').find('input').val().trim() == ""){
				alert("Preperation time is required");
				return false;
			}

			if(this.id == "delete_prep_time"){
				extra_prep_time = "";
			}else{
				extra_prep_time = $('#prep_time').find('input').val();
			}

			// alert("123");

			$('#prep_time').find('input').prop("disabled",true);
			$('#prep_time').find('input').css({"opacity": 0.3, "pointer-events": "none"});

			$('#overlay').css("display", "block");
			$('#loading-img').css("display", "block");

			$.ajax({
				url: "{{ url('kitchen/add-extra-time') }}", 
				type: "POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"extra_prep_time": extra_prep_time
				},
				success: function(data, status){
					if(status == "success"){
						if(extra_prep_time != ""){
							$('#add_prep_time').hide();
							$('#delete_prep_time').show();
							$('#loading-img').css("display", "none");						
							$('#overlay').css("display", "none");							
							$('.sucess_msg').fadeIn(1000).fadeOut(3000);							
						}else{
							$('#delete_prep_time').hide();
							$('#add_prep_time').show();
							$('#loading-img').css("display", "none");						
							$('#overlay').css("display", "none");	
							// alert($('#prep_time').find('input').attr("disabled"));						
							$('#prep_time').find('input').prop("disabled",false);
							$('#prep_time').find('input').css({"opacity": 1, "pointer-events": "auto"});							
							$('.ui-state-disabled').css({"opacity": 1, "pointer-events": "auto"});
						}
					}else{
						console.log(result);
					}
				}
			});	
		});

		$('#delete_prep_time').click(function(){
			$(this).hide();
			$('#prep_time').find('input').val("");
			$('#prep_time').find('input').prop("disabled",false);
			$('#add_prep_time').show();
		});
	</script>

@endsection