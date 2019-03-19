@extends('layouts.master')
@section('head-scripts')


<style>
	.msg-lbl{
		color: #000; 
		padding-left: 0px !important;
	}

	.msg-txt{
		margin-bottom: 10px !important; 
		border: 1px solid #777 !important;
	}

	#contact-setting-list .ui-controlgroup{
		display: block !important;
	}

	.terms{
	    display: block;
    	color: #fff;
	    border-radius: 10px;
		width: 100%;		
	    height: 39px; 
	    line-height: 39px;
	    padding-left: .83em;
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
	    z-index: 9;
	}	

	#back_arw{
		width: 20px;
	}

	#language fieldset{
		display: block;
	}
	.ui-btn-active.Settings{width:40px;margin:0 auto !important}
	.done-btn.dataSave{width:40px;margin:0 auto !important; float: right;}
	p.error { color: #FF0000; }
	.link { cursor: pointer; }
</style>

<!-- Start validation JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<!-- End -->
@endsection

@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{ Session::has('route_url') ? Session::get('route_url') : url('home') }}" data-ajax="false" id="back_arw" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active Settings">{{ __('messages.Settings') }}</a></li>

			  <li class="done-btn dataSave" id="dataSave">  <input type="button" value="{{ __('messages.Done') }}" /></li> </ul>
			</div><!-- /navbar -->
		</div>
	</div>

	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('save-setting') }}">
		{{ csrf_field() }}
		<div role="main" data-role="main-content" class="content">
			@if ($message = Session::get('success'))
				<div class="table-content sucess_msg">
					<img src="{{asset('images/icons/Yes_Check_Circle.png')}}">
					 @if(is_array($message))
			            @foreach ($message as $m)
			                {{ $languageStrings[$m] ?? $m }}
			            @endforeach
			        @else
			            {{  __("messages.$message") }}
			        @endif
			    </div>
			@endif
			<div class="setting-list">
				<ul data-role="listview"> 
					<li class="range-sec"><a href="{{url('select-location')}}" data-ajax="false">{{ __('messages.Location') }}
						<p class="ui-li-aside">
							@if(Auth::check())
								@if(Session::get('with_login_address') == null)
									{{ __('messages.Current Location') }}
								@else	
									{{Session::get('with_login_address')}}
								@endif
							@else
								@if(Session::get('address') == null)
									{{ __('messages.Current Location') }}
								@else	
									{{Session::get('address')}}
								@endif
							@endif
						</p></a>
					</li> 
				</ul>
			</div>
			
			<div id="language" class="setting-list">
				<ul data-role="listview"> 
					<li data-role="collapsible" class="range-sec">
						<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Language') }}
						<p class="ui-li-aside">
							@if(App::getLocale() == "en")
								English
							@else
								Swedish
							@endif
						</p></h2>
						@if(Auth::check())
						 <fieldset data-role="controlgroup">
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Session::get('applocale') == 'en') checked="checked" @else checked="checked" @endif>
						        <label for="radio-choice-v-2a">English</label>
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Session::get('applocale') == 'sv') checked="checked" @endif>
						        <label for="radio-choice-v-2b">Swedish</label>
						    </fieldset>
						@else
							<fieldset data-role="controlgroup">
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Session::get('applocale') == 'en') checked="checked" @else checked="checked" @endif>
						        <label for="radio-choice-v-2a">English</label>
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Session::get('applocale') == 'sv') checked="checked" @endif>
						        <label for="radio-choice-v-2b">Swedish</label>
						    </fieldset>
						@endif
					</li>	
					<!-- <li><a href="#">Unit <p class="ui-li-aside">Meter</p></a></li>  -->
				</ul> 
			</div>
			
			<div class="setting-list">
				<ul data-role="listview"> 
					<li data-role="collapsible" class="range-sec">
						<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Range') }}
							<p class="ui-li-aside">
								@if(Auth::check())
									{{Auth::user()->range}} Km
								@else
									{{Session::get('rang')}}
								@endif
							</p>
						</h2>
						<div data-role="rangeslider">
							@if(Auth::check())
						   		<input type="range" name="range-1b" id="range-1b" min="3" max="10" value="{{Auth::user()->range}}">
						   	@else
						   		<input type="range" name="range-1b" id="range-1b" min="3" max="10" value="{{Session::get('rang')}}">
						   	@endif
						</div>
					</li> 
				</ul> 
			</div>
		</div>
	</form>

	<!-- Discount tab -->
	@if(Auth::check())
		<div id="peroid-discount-list" class="setting-list">
			<ul data-role="listview">
				<li data-role="collapsible" class="range-sec">
					<h2 class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Discount') }}</h2>
					<div class="row">
						<h2>{{ __('messages.Add Discount') }}</h2>
						<div data-role="controlgroup">
							<form name="user-discount" id="user-discount" method="post" action="{{ url('add-customer-discount') }}" data-ajax="false">
								{{ csrf_field() }}
								<input type="text" name="code" id="code" placeholder="{{ __('messages.Enter Discount Code') }}" autocomplete="off" data-rule-required>
								<p class="error"></p>
								<input type="hidden" id="discountValidate" value="1">
								<button type="submit" class="btn btn-success">{{ __('messages.Submit') }}</button>		
							</form>
						</div>
					</div>
					@if( !$customerDiscount->isEmpty() )
						<div class="row list-user-discount">
							<h2>{{ __('messages.avalableDiscount') }}</h2>
							@foreach($customerDiscount as $row)
								<div class="ui-grid-a">
									<div class="ui-block-a">
										<div class="ui-bar ui-bar-a">{{ $row['code'] }}</div>
									</div>
									<div class="ui-block-b">
										<div class="ui-bar ui-bar-a">
											<span class="link remove-discount-confirm" data-content="{{ __('messages.deleteAlert', ['item' => strtolower(__('messages.Discount'))]) }}">
												<i class="fa fa-trash" aria-hidden="true"></i>
											</span>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					@endif
				</li>
			</ul>
		</div>
	@endif
	
	<div id="contact-setting-list" class="setting-list">
			<ul data-role="listview"> 
				<li data-role="collapsible" class="range-sec">

					<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Contact Us') }}						
					</h2>
					<div>
						<label class="msg-lbl"><h2>{{ __('messages.Message') }}</h2></label>
					</div>

					<div data-role="controlgroup">
						<form method="post" action="{{ url('contact-us') }}" data-ajax="false">
							{{ csrf_field() }}
							<textarea type="text" name="message" placeholder="{{ __('messages.Contact Us Placeholder') }}" class="msg-txt" required></textarea>
							<button type="submit" class="btn btn-success">{{ __('messages.Send') }}</button>		
						</form>
					</div>
			

				</li> 
			</ul> 
	</div>

	<div class="setting-list">
		<div style="margin-right: 15px; margin-top: 5px;"> 
			<a href="https://dastjar.com/?page_id=71" target="_blank" id="" class="terms btn btn-primary" data-ajax="false">{{ __('messages.About Us') }}
			</a>		
		</div> 
	</div>

	<div class="setting-list">
		<div style="margin-right: 15px; margin-top: 5px; margin-bottom: -2px;"> 
			<a href="{{ url('terms') }}?id={{uniqid()}}" id="" class="terms btn btn-primary" data-ajax="false">{{ __('messages.Terms and Conditions') }}
			</a>		
		</div> 
	</div>

</div>
@endsection

@section('footer-script')
	<!-- Delete cart item popup -->
	<div id="delete-alert" class="actionBox">
		<div class="actionBox-content">
			<div class="mInner">
				<p></p>
				<div class="btnWrapper">
					<span class="close">Cancel</span>
					<span class="delete" id="remove-discount">Delete</span>
					<input type="hidden" name="deletealertdata" id="deletealertdata">
				</div>
			</div>
		</div>
	</div>

	<!-- Delete cart item popup -->
	<div id="replace-discount-alert" class="actionBox">
		<div class="actionBox-content">
			<div class="mInner">
				<p></p>
				<div class="btnWrapper">
					<span class="close">No</span>
					<span class="delete" id="replace-discount">Yes</span>
					<input type="hidden" name="" id="">
				</div>
			</div>
		</div>
	</div>

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

		function makeRedirection(link){
			window.location.href = link;
		}

		// Check if discount is valid and submit
		$('#user-discount').on('submit', function() {
			var isValid = false; var msg = 'This field is required.';
			var code = $('#code').val();

			// Submit without validating if user want to replace discount if code belongs to same restaurant 
			if( $('#discountValidate').val() == '0' )
			{
				return true;
			}

			if(code.length)
			{
				showLoading();

				// Check if code valid
				$.ajax({
					type: 'POST',
					url: "{{ url('is-valid-discount-code') }}",
					data: {
						'_token': "{{ csrf_token() }}",
						'code': code
					},
					async: false,
					dataType: 'json',
					success: function(response) {
						hideLoading();

						msg = response.msg;

						if( response.status == 1 )
						{
							isValid = response.status;
						}
						else if( response.status == 2 )
						{
							$('#replace-discount-alert').find('p').html(msg);
							$('#replace-discount-alert').show();
							msg = '';
						}
					}
				});
			}

			if(!isValid)
			{
				$('#user-discount').find('p.error').text(msg);
				return false;
			}
		});

		// Replace user discount if belongs to the same restaurant
		$('#replace-discount').on('click', function() {
			$('#discountValidate').val(0);
			$('#user-discount').submit();
		});

		// Remove user discount
		$('#remove-discount').on('click', function() {
			showLoading();

			$.ajax({
				type: 'POST',
				url: '{{ url('remove-customer-discount') }}',
				data: {
					'_token': "{{ csrf_token() }}",
					'code': $('#deletealertdata').val()
				},
				async: false,
				dataType: 'json',
				success: function(response) {
					hideLoading();
					
					if(response.status)
					{
						window.location.reload();
					}
				}
			});
		});

		// Confirm Delete discount
		$('.remove-discount-confirm').on('click', function() {
			var content = $(this).data('content');
			$('#delete-alert').find('p').html(content);
			$('#deletealertdata').val($(this).closest('.ui-grid-a').find('.ui-bar-a').html());
			$('#delete-alert').show();
		});

		// Close popup
		$('.actionBox .close').on('click', function() {
			$(this).closest('.actionBox').hide();
		});
	</script>
@endsection