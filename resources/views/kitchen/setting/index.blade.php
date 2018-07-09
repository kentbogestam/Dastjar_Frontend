@extends('layouts.kitchenSetting')

<style type="text/css">
	.btn_blk{
		background-color: #fff;
		border-radius: 0.5em;
	}

	.btn_blk h2{
		text-align: left;
		padding-left: 2em;
	}
</style>

@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header"  data-position="fixed" data-tap-toggle="false" class="header">
		<div class="logo_header">
			<img src="{{asset('kitchenImages/logo-img.png')}}">
			<a href = "{{ url('kitchen/logout') }}"  class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
			</a>
		</div>
		<div class="order_background setting_head_container">
			<div class="ui-grid-b center">
				<div class="ui-block-a">
					<a href="{{ URL::to('kitchen/store') }}" class="back_btn_link ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline right_arrow" data-ajax="false">
					<img src="{{asset('kitchenImages/backarrow.png')}}" width="11px">
				</a>
				</div>
				<div class="ui-block-b middle_section">
					<a class="title_name ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
					{{ __('messages.Settings') }}
				</a>
				</div>
				<div class="ui-block-c">
					<a id="dataSave" class="done_btn ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline left_text" data-ajax="false">
					{{ __('messages.Done') }}
				</a>
				</div>
			</div>
		</div>
	</div>
	<form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/save-kitchenSetting') }}">
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
				<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.Language") }} <span>
					@if(Auth::guard('admin')->user()->language == 'ENG')
					English
					@elseif(Auth::guard('admin')->user()->language == 'SWE')
					Swedish
					@elseif(Auth::guard('admin')->user()->language == 'GER')
					German
					@endif</span></h2>
				    <fieldset data-role="controlgroup">
				        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2a" value="ENG" @if(Auth::guard('admin')->user()->language == 'ENG') checked="checked" @else checked="checked" @endif>
				        <label for="radio-choice-v-2a">English</label>
				        <input type="radio" name="radio-choice-v-2" id="radio-choice-v-2b" value="SWE" @if(Auth::guard('admin')->user()->language == 'SWE') checked="checked" @endif>
				        <label for="radio-choice-v-2b">Swedish</label>
				    </fieldset>
				</li>
				<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">{{  __("messages.Text To Speech") }} <span>
					@if(Auth::guard('admin')->user()->text_speech == 0)
					Off
					@elseif(Auth::guard('admin')->user()->text_speech == 1)
					On
					@endif</span></h2>
				    <fieldset data-role="controlgroup">
				        <input type="radio" name="text_speech" id="radio-choice-v-2d" value="0" @if(Auth::guard('admin')->user()->text_speech == 0) checked="checked" @else checked="checked" @endif>
				        <label for="radio-choice-v-2d">Off</label>
				        <input type="radio" name="text_speech" id="radio-choice-v-2e" value="1" @if(Auth::guard('admin')->user()->text_speech == 1) checked="checked" @endif>
				        <label for="radio-choice-v-2e">On</label>
				    </fieldset>
				</li>

				<li id="about_us" class="range-sec btn_blk">
					<h2 class="ui-btn">{{  __("messages.About Us") }}</h2>
				</li>

				<li id="admin" class="range-sec btn_blk">
					<h2 class="ui-btn">{{  __("messages.Admin") }}</h2>
				</li>
			</div>
		</div>
	</form>
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
			window.open("https://admin.dastjar.com/admin/");
		});
	</script>

@endsection