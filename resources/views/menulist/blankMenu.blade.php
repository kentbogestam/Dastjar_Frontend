@extends('layouts.master')
@section('content')

	<div data-role="header" class="header"  data-position="fixed" data-tap-toggle="false">
		<div class="logo">
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				<span>{{ Auth::user()->name}}</span>
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" onClick="makeRedirection('{{url('search-store-map')}}')" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
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



		<div data-role="footer" class="footer" data-tap-toggle="false" data-position="fixed">
			<div class="ui-grid-c inner-footer center">
			<div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_01.png')}}">
				</div>
				<span>{{ __('messages.Restaurant') }}</span>
			</a></div>
			<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container" id = "menudataSave">
					<img src="{{asset('images/icons/select-store_03.png')}}">
				</div>
				<input type="button" value="{{ __('messages.Send') }}" id="dataSave"/>
			</a></div>
			@if(count(Auth::user()->paidOrderList) == 0)
				<div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_05.png')}}">
					</div>
					<span>{{ __('messages.Order') }}</span>
				</a></div>
			@else
				<div class="ui-block-c order-active">
			    	<a  class="ui-shadow ui-corner-all icon-img ui-btn-inline ordersec" data-ajax="false">
				        <div class="img-container">
				       		<!-- <img src="images/icons/select-store_05.png"> -->
				        	<img src="{{asset('images/icons/select-store_05-active.png')}}">
				        </div>
			        	<span>{{ __('messages.Order') }}<span class="order-number">{{count(Auth::user()->paidOrderList)}}</span></span>
			        </a>
			        <div id="order-popup" data-theme="a">
				      <ul data-role="listview">
				      	@foreach(Auth::user()->paidOrderList as $order)
							<li>
								<a href="{{ url('order-view/'.$order->order_id) }}" data-ajax="false">{{ __('messages.Order id') }} - {{$order->customer_order_id}}</a>
							</li>
						@endforeach
				      </ul>
				    </div>
	    </div>
			@endif
			<div class="ui-block-d"><a href="{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container"><img src="{{asset('images/icons/select-store_07.png')}}"></div>
			</a></div>
			</div>
		</div>
	</form>
	

@endsection



@section('footer-script')
	<script type="text/javascript">
		var id ;
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
			$("#form").submit();
		} else{
			alert("Please fill some value");	
			e.preventDefault();
		}
	});

	function makeRedirection(link){
		window.location.href = link;
	}

	$(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });
	
</script>
@endsection