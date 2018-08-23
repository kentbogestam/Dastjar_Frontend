@extends('layouts.blank')
@section('head-scripts')
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.5.1/fingerprint2.min.js"></script>


    <script src="{{asset('notifactionJs/App42-all-3.1.min.js')}}"></script>
    <script src="{{asset('notifactionJs/SiteTwo.js')}}"></script>
    <script src="{{asset('notifactionJs/serviceWorker.js')}}"></script>
    
     <script>
          $(document).ready(function () {
              App42.setEventBaseUrl("https://analytics.shephertz.com/cloud/1.0/");
              App42.setBaseUrl("https://api.shephertz.com/cloud/1.0/");

              App42.initialize("{{env('APP42_API_KEY')}}","{{env('APP42_API_SECRET')}}");
              App42.enableEventService(true);
              var userName;
              new Fingerprint2().get(function(result, components){
                  userName = "{{Auth::guard('admin')->user()->email}}";
                  console.log("Username : " + userName); //a hash, representing your device fingerprint
                  App42.setLoggedInUser(userName);
                  getDeviceToken();
              });
          });
      </script>
@endsection
@section('content')
	<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
			<a href = "{{ url('kitchen/logout') }}"  class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
			</a>
		</div>
			<h3 class="ui-bar ui-bar-a order_background">
				<a href="{{ URL::previous() }}" data-ajax="false" class="text-left ui-link ui-btn back_btn"><img src="{{asset('kitchenImages/backarrow.png')}}" width="11px"></a>
				{{ __('messages.Main Admin') }}
				
			</h3>
		<div class="top_two-menu">
			<div class="ui-grid-a center">
				<div class="ui-block-a"><a href="#order-popup" data-rel="popup" data-transition="turn" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<img src="{{asset('kitchenImages/order-img.png')}}">
					</div>
					<span>{{ __('messages.Order') }}<span class="order_number">{{count(Auth::guard('admin')->user()->kitchenPaidOrderList)}}</span></span>
				</a></div>
				<!-- <div class="ui-block-b"><a onClick="makeRedirection('{{url('kitchen/selectOrder-dateKitchen')}}')" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/eat-icon.png')}}">
					</div>
					<span>Eat Later</span>
				</a></div>
				<div class="ui-block-c"><a onClick="makeRedirection('{{url('kitchen/kitchen-order-onsite')}}')" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('kitchenImages/eat-icon.png')}}">
					</div>
					<span>Eat Now</span>
				</a></div> -->
			</div>
		</div>
	</div>
	<div data-role="popup" id="order-popup" data-theme="b">
        <ul data-role="listview" data-inset="true" style="min-width:210px;">
            <!-- <li data-role="list-divider">Choose an action</li> -->
            @foreach(Auth::guard('admin')->user()->kitchenPaidOrderList as $order)
            	<li><a href="{{ url('kitchen/kitchen-order-view/'.$order->order_id) }}">{{ __('messages.Order') }} - {{$order->customer_order_id}}</a></li>
            @endforeach
        </ul>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/kitchen-order-save') }}">
		{{ csrf_field() }}
	
		<div role="main" data-role="main-content" class="content">
		
			<div class="cat-list-sec single-restro-list-sec">
				<div class="ui-grid-a">
					<div class="ui-block-a left_part_kit">
						<p>{{ __('messages.You are the main admin of company.Please log in from any store of that company and then the menu of that store will appear') }}</p>
					</div>
					<div class="ui-block-b second-part">
						<div class="seprate_qr">
							<div class="mid_para">
								<h2>{{ __('messages.Gain Time by Ordering on the go') }}</h2>
								<h4><img src="{{asset('kitchenImages/600px-Black_check.svg.png')}}">{{ __('messages.Your food is ready by the time of arrival') }}</h4>
								<h3>{{ __('messages.Get notification, when your order is ready') }}</h3>
							</div>
							<h3 class="no-margin"><strong>{{ __('messages.DOWNLOAD') }}</strong> {{ __('messages.the App') }}  <span>{{ __('messages."anar"') }}</span> {{ __('messages.and get the benefites') }} </h3>
							<a class="blue_link" href="">dastjar.com/download</a>
							<h3>{{ __('messages.OR') }}</h3>
							<h3 class="blue_link">{{ __('messages.Enter your mobile number here, to get the app') }}</h3>
							<!-- <h3 class="grey_text">070-12345678</h3> -->
							<form>
							<div class="ui-field-contain search_container"> <input type="number" data-clear-btn="false" name="number-1" id="number-1" value=""> <button type="submit" id="submit-6" class="ui-shadow ui-btn ui-corner-all ui-mini">ok</button> </div>
							</form>
							<h3>{{ __('messages.OR') }}</h3>
							<h3 class="blue_link">{{ __('messages.use the QR-code below') }}</h3>
						</div>
							<div class="scan_code">
								<img src="http://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=https://dastjar.com/dbuzzu/public/">
							</div>
					</div>
				</div>
			</div>
		</div>
				<!-- popup section -->

		<div data-role="popup" id="transitionExample" class="ui-content comment-popup" data-theme="a">
			<div class="pop-header">
			<a href="#" data-rel="back"  class="cancel-btn ui-btn ui-btn-left ui-corner-all ui-shadow ui-btn-a">{{ __('messages.Cancel') }}</a>
			<label>{{ __('messages.Add Comments') }}</label>
			
			</div>
			<div class="pop-body">
				
				<textarea name="textarea-1" id="textarea-1" placeholder="{{ __('messages.Add Comments') }}"></textarea>
				<a id="submitId" href="" data-ajax="false" class="submit-btn ui-btn ui-btn-right ui-corner-all ui-shadow ui-btn-a">{{ __('messages.Submit') }}</a>
			</div>
		</div>

	</form>
	



	<div data-role="footer" class="footer_container" data-position="fixed" data-tap-toggle="false">
		<div class="ui-grid-a inner-footer center">
		<div class="ui-block-a"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container" id = "menudataSave">
				<img src="{{asset('kitchenImages/send_icon.png')}}">
			</div>
			<span>{{ __('messages.Submit') }}</span>
		</a></div>
		</div>
	</div>
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

		function incrementValue(id)
		{
		    var value = parseInt(document.getElementById(id).value, 10);
		    value = isNaN(value) ? 0 : value;
		    if(value<10){
		        value++;
		            document.getElementById(id).value = value;
		    }
		}
		function decrementValue(id)
		{
		    var value = parseInt(document.getElementById(id).value, 10);
		    value = isNaN(value) ? 0 : value;
		    if(value>1){
		        value--;
		            document.getElementById(id).value = value;
		    }

		}

		function makeRedirection(link){
			window.location.href = link;
		}
	</script>

@endsection