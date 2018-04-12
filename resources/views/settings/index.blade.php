@extends('layouts.master')
@section('head-scripts')
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
					<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Range') }}
						<p class="ui-li-aside">
							@if(Auth::check())
								{{Auth::user()->range}} Km
							@else
								{{Session::get('rang')}}
							@endif
						</p></h2>
					<p>
						<div data-role="rangeslider">
							@if(Auth::check())
						   		<input type="range" name="range-1b" id="range-1b" min="3" max="10" value="{{Auth::user()->range}}">
						   	@else
						   		<input type="range" name="range-1b" id="range-1b" min="3" max="10" value="{{Session::get('rang')}}">
						   	@endif
						</div>
					</p>
					</li> 
				</ul> 
			</div>
		</div>
	</form>
</div>
@endsection

@section('footer-script')
	<script type="text/javascript">
		$("#dataSave").click(function(e){
			console.log('gggg');
			var flag = true;
			// var x = $('form input[type="radio"]').each(function(){
	  //       // Do your magic here
	  //       	var checkVal = parseInt($(this).val());
	  //       	console.log(checkVal);
	  //       	if(checkVal > 0){
	  //       		flag = true;
	  //       		return flag;
	  //       	}
			// });

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