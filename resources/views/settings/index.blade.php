@extends('layouts.master')
@section('head-scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

<style>
	.msg-lbl{
		color: #000; 
		padding-left: 0px !important;
	}

	.msg-txt{
		margin-bottom: 10px !important; 
		border: 1px solid #777 !important;
	}

	#delete-me-btn{
		background-color: #d25229;
    	color: #fff;
	    border-radius: 10px;
		width: 100%;
	}

	.ui-dialog{
		background-color: #fff !important;
	}

	.ui-controlgroup, #dialog-confirm + fieldset.ui-controlgroup {
    	width: 100%;
	}

	#dialog-confirm{
		display: none;
		/* color: #fff; */
	} 

	.ui-dialog .ui-dialog-buttonpane{
		text-align: center;
	}

	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
		float: none;
	}
	
	#dialog-confirm .ui-icon{
		float:left; 
		margin:12px 12px 20px 0;
		color: #fff;
	}

	#dialog-confirm .ui-icon-alert{
		color: #fff;
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

	.ui-widget-overlay{
	    opacity: 0.5;		
	}

	.dialog-no{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%) !important;
		color: #fff !important;
	}

	.dialog-no:hover{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%);
		color: #fff;
	}	
</style>
<script src="{{asset('locationJs/currentLocation.js')}}"></script>
@endsection

@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('eat-now')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">{{ __('messages.Settings') }}</a></li>

			  <li class="done-btn" id="dataSave">  <input type="button" value="{{ __('messages.Done') }}" /></li> </ul>
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
			                {{ $languageStrings[$m] or $m }}
			            @endforeach
			        @else
			            {{  __("messages.$message") }}
			        @endif
			    </div>
			@endif
			<div class="setting-list">
				<ul data-role="listview"> 
					<li class="range-sec"><a onClick="makeRedirection('{{url('select-location')}}')" data-ajax="false">{{ __('messages.Location') }}
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
			
			<div class="setting-list">
				<ul data-role="listview"> 
					<li data-role="collapsible" class="range-sec">
						<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Language') }}
						<p class="ui-li-aside">
							@if(Auth::check())
								@if(Auth::user()->language == 'ENG')
								English
								@elseif(Auth::user()->language == 'SWE')
								Swedish
								@endif
							@else
								@if(Session::get('browserLanguageWithOutLogin') == 'ENG')
								English
								@elseif(Session::get('browserLanguageWithOutLogin') == 'SWE')
								Swedish
								@endif
							@endif
						</p></h2>
						@if(Auth::check())
						    <fieldset data-role="controlgroup">
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Auth::user()->language == 'ENG') checked="checked" @else checked="checked" @endif>
						        <label for="radio-choice-v-2a">English</label>
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Auth::user()->language == 'SWE') checked="checked" @endif>
						        <label for="radio-choice-v-2b">Swedish</label>
						    </fieldset>
						@else
							<fieldset data-role="controlgroup">
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Session::get('browserLanguageWithOutLogin') == 'ENG') checked="checked" @else checked="checked" @endif>
						        <label for="radio-choice-v-2a">English</label>
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Session::get('browserLanguageWithOutLogin') == 'SWE') checked="checked" @endif>
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
	
	<div class="setting-list">
			<ul data-role="listview"> 
				<li data-role="collapsible" class="range-sec">
					@if(Auth::check())
						@if(Auth::user()->language == 'ENG')
							<?php $lan = "eng" ?>
						@elseif(Auth::user()->language == 'SWE')
							<?php $lan = "swe" ?>
						@endif
					@else
						@if(Session::get('browserLanguageWithOutLogin') == 'ENG')
							<?php $lan = "eng" ?>
						@elseif(Session::get('browserLanguageWithOutLogin') == 'SWE')
							<?php $lan = "swe" ?>
						@endif
					@endif

					@if($lan == "eng")
					<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">Contact Us
						<p class="ui-li-aside">
							Contact Us
						</p>
					</h2>
					<div>
						<label class="msg-lbl"><h2>Message</h2></label>
					</div>

					<div data-role="controlgroup">
						<form method="post" action="{{ url('contact-us') }}" data-ajax="false">
							{{ csrf_field() }}
							<textarea type="text" name="message" placeholder="Enter Your Message" class="msg-txt" required></textarea>
							<button type="submit" class="btn btn-success">Send</button>		
						</form>
					</div>
					@elseif($lan == "swe")
						<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">Kontakta oss
							<p class="ui-li-aside">
								Kontakta oss
							</p>
						</h2>
						<div>
							<label class="msg-lbl"><h2>Meddelande</h2></label>
						</div>
	
						<div data-role="controlgroup">
							<form method="post" action="{{ url('contact-us') }}" data-ajax="false">
								{{ csrf_field() }}
								<textarea type="text" name="message" placeholder="Skriv ditt meddelande och lägg till din email adress för svar" class="msg-txt" required></textarea>
								<button type="submit" class="btn btn-success">Skicka</button>		
							</form>
						</div>
					@endif

				</li> 
			</ul> 
	</div>

	@if(Auth::check())
	<div class="setting-list">
		<div> 
					<form method="post" id="delete-me-form" action="{{ url('delete-me') }}" data-ajax="false">
						{{ csrf_field() }}
						<button type="submit" id="delete-me-btn" class="btn btn-danger">Delete Me</button>		
					</form>
		</div> 
	</div>
	@endif

	<div id="dialog-confirm" title="Delete Account">
		<p>Are you sure?</p>
	</div>

</div>
@endsection

@section('footer-script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


	<script type="text/javascript">
		$('#delete-me-form').submit(function(event){
			event.preventDefault();

			$( "#dialog-confirm" ).dialog({
					resizable: false,
					modal: true,
					buttons: [						
						{
							text: "No",
							"class": 'dialog-no',
							click: function() {
								$(this).dialog("close");
							}					
						},
						{
							text: "Yes",
							"class": 'dialog-yes',
							click: function() {
								$(this).dialog("close");
								$('#delete-me-form').unbind().submit();
							}
						}
		        ]
				
			}); 
	
		});

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

		$('#delete-me-form2').submit(function(event){
			event.preventDefault();

			var r = confirm("Are you sure you want to delete your account?");
			if (r == true) {
				$(this).unbind().submit();
			}			
		});
	</script>
@endsection