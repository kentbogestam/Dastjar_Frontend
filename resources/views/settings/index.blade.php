@extends('layouts.master')
@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('eat-now')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">{{ __('messages.Setting') }}</a></li>

			  <li class="done-btn" id="dataSave">  <input type="button" value="{{ __('messages.Done') }}" /></li> </ul> 
			  <span class="location_icon"><a href="#"><img src="{{asset('images/icons/location.png')}}"></a></span>
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
					<li class="range-sec"><a onClick="makeRedirection('{{url('select-location')}}')" data-ajax="false">{{ __('messages.Location') }}<p class="ui-li-aside">@if(Auth::user()->address == null)
						{{ __('messages.Current Location') }}
					@else	
						{{Auth::user()->address}}
					@endif</p></a>
					</li> 
				</ul>
			</div>
			<div class="setting-list">
				<ul data-role="listview"> 
					<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Language') }}<p class="ui-li-aside">
						@if(Auth::user()->language == 'ENG')
						English
						@elseif(Auth::user()->language == 'SWE')
						Swedish
						@elseif(Auth::user()->language == 'GER')
						German
						@endif </p></h2>
						
						    <fieldset data-role="controlgroup">
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Auth::user()->language == 'ENG') checked="checked" @else checked="checked" @endif>
						        <label for="radio-choice-v-2a">English</label>
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Auth::user()->language == 'SWE') checked="checked" @endif>
						        <label for="radio-choice-v-2b">Swedish</label>
						        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2c" value="GER" >
						        <label for="radio-choice-v-2c" @if(Auth::user()->language == 'GER') checked="checked" @endif>German</label>
						    </fieldset>
						
					</li>	
					<!-- <li><a href="#">Unit <p class="ui-li-aside">Meter</p></a></li>  -->
					<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{ __('messages.Range') }}<p class="ui-li-aside">{{Auth::user()->range}} Km</p></h2>
					<p>
						<div data-role="rangeslider">
						    <input type="range" name="range-1b" id="range-1b" min="3" max="10" value="{{Auth::user()->range}}">
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