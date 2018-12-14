@extends('layouts.master')
@section('styles')
	<style>
		.accept-btn{
			font-family: "ProximaNova-Regular";
			display: inline-block;
			float: right;
			width: auto !important;		
			background-color: #7ebe12;
			color: #fff !important;	
		}

		.pop_up {
			position: fixed;
			display: none;
			font-family: "ProximaNova-Regular";
			top: 0;
			left: 0;
			max-height: 95vw;
			width: 80vw;
			top: 50%;
			left: 50%;
			-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			-webkit-animation: fadeIn 500ms linear;
			animation: fadeIn 500ms linear;
			z-index: 9999;
		}

		.pop_up_inner {
			max-height: 95vw;
			color: #333;
			background-color: #FFFFFF;
			border-radius: 5px;
			-webkit-box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.5);
					box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.5);
			overflow-y: auto;
		}

		.pop_up h2 {
			font-family: "ProximaNova-Regular";
			text-align: center;
			color: #7ebe12;
		}

		.pop_up p {
			font-family: "ProximaNova-Regular";
			text-align: justify;
		}

		.popup-close1 {
			width: 30px;
			height: 26px;
			padding-top: 4px;
			display: inline-block;
			position: absolute;
			top: 0px;
			right: 0px;
			-webkit-transition: ease 0.25s all;
			transition: ease 0.25s all;
			-webkit-transform: translate(50%, -50%);
			transform: translate(50%, -50%);
			border-radius: 100% !important;
			background: #7ebe12;
			font-family: Arial, Sans-Serif;
			font-size: 20px;
			text-align: center;
			line-height: 1;
			color: #fff;
			cursor: pointer;
			padding-left: 0px;
			z-index: 999;
		}

		.popup-close1:hover {
			text-decoration: none;
		}

		@media only screen and (min-width: 768px) {
		.pop_up_inner {
			padding: 30px;
		}
		}

		@media only screen and (max-width: 768px) {
			.pop_up_inner {
				padding: 20px;
				max-height: 145vw;
			}

			.pop_up {
			/*	top: 33%; */
			}

			.pop_up h2 {
				font-size: 25px;
			}
		}

		#overlay {
    		position: fixed;
    		display: none;
    		width: 100%;
    		height: 100%;
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
@stop

@section('content')
	<div data-role="header" class="header"  data-position="fixed" data-tap-toggle="false">
		<div class="logo">
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="{{url('search-store-map')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	
	<div class="table-content">
		<p>{{ __('messages.Menu is not available.') }}</p>
	</div>
	
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-order') }}">
		{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content">
			
				<!-- popup section -->

			<div data-role="popup" id="transitionExample" class="ui-content comment-popup" data-theme="a">
				<div class="pop-header">
				<a href="#" data-rel="back"  class="cancel-btn ui-btn ui-btn-left ui-corner-all ui-shadow ui-btn-a">{{ __('messages.Cancel') }}</a>
				<label>{{ __('messages.Add Comments') }}</label>
				
				</div>
				<div class="pop-body">
					
						<textarea name="textarea-1" id="textarea-1" placeholder="Add a comment"></textarea>
						<a id="submitId" href="" data-ajax="false" class="submit-btn ui-btn ui-btn-right ui-corner-all ui-shadow ui-btn-a">{{ __('messages.Submit') }}</a>

					
				</div>
			</div>
		</div>

      @include('includes.fixedfooter')
	</form>
	
	<div class="pop_up">   
		<div class="pop_up_inner">   
		  <article class="pop_up_content">
			<div>
				<p>
					This is a location-based service! It will save your time and serve you best based on your location! To manage to do so, we need your permission to store your login data, and make sure that your order is not mixed with any others and delivers correctly to you!
				</p>
			</div>
			<div class="pop_up-footer">
				<button type="button" class="accept-btn">Accept</button>
			</div>
			</article>        
			  <a class="popup-close1" onclick="off()">x</a>
		</div>
	  </div>

	<img src="{{ asset('images/loading.gif') }}" id="loading-img" />

	  <div id="overlay" onclick="off()">
	  </div>
	

@endsection



@section('footer-script')
	<script type="text/javascript">
		var id;

		$(".extra-btn a").click(function(){
			id=$(this).attr('id');
		});
		
	$('#submitId').click(function(){ 		
		var text = $('textarea#textarea-1').val();
		$('#orderDetail'+id).val(text);
		$('#transitionExample').popup( "close" );
		document.getElementById("textarea-1").value = "";
	});

	$("#menudataSave").click(function(e){
		var flag = false;
		var x = $('form input[type="text"]').each(function(){
        // Do your magic here
        	var checkVal = parseInt($(this).val());
        	console.log(checkVal);
        	if(checkVal > 0){
        		flag = true;
        		return flag;
        	}
		});

		if(flag){			
			send_btn();
		} else{
			// alert("Please fill some value");	
			e.preventDefault();
		}
	});

	function send_btn(){
		$('#overlay').show();
		$('#loading-img').show();
		
		$.ajax({
			url: "{{ url('gdpr') }}", 
			type: "POST",
			data: {
				"_token": "{{ csrf_token() }}"
			},
			success: function(result){
				console.log(result);
				if(result == 0){
					$("#loading-img").hide();
					$('#overlay').show();
					$(".pop_up").show();
				}else{
					$("#form").submit();
				}
			}
		});		
	}

	$("body").on('click',".accept-btn", function(){
		$.ajax({
			url: "{{ url('accept-gdpr') }}", 
			type: "POST",
			data: {
				"_token": "{{ csrf_token() }}"
			},
			success: function(result){
				console.log(result);
    			if(result == 0){
					off();
				}else{
					off();
					$("#form").submit();
				}
			}
		});		
	});
	
	function off(){
		$("#loading-img").hide();
		$(".pop_up").hide();
		$('#overlay').hide();
	}	

	function makeRedirection(link){
		window.location.href = link;
	}

	$(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });
	
</script>
@endsection