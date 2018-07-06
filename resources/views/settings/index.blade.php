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

	#language fieldset{
		display: block;
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
			
			<div id="language" class="setting-list">
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
	
	<div id="contact-setting-list" class="setting-list">
			<ul data-role="listview"> 
				<li data-role="collapsible" class="range-sec">

					<h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Contact Us') }}
						<p class="ui-li-aside">
							{{ __('messages.Contact Us') }}
						</p>
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
		<div style="margin-right: 15px; margin-top: 5px; margin-bottom: -2px;"> 
			<a href="{{ url('terms') }}" id="" class="terms btn btn-primary" data-ajax="false">{{ __('messages.Terms and Conditions') }}
			</a>		
		</div> 
	</div>

</div>
@endsection

@section('footer-script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


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
	</script>
@endsection