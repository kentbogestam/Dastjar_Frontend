@extends('layouts.master')
@section('content')
<div class="setting-page" data-role="page" data-theme="c">
	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('eat-now')}}" data-ajax="false" class="text-left"><img src="images/icons/backarrow.png" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">Setting</a></li>
			  <li><a data-ajax="false" href="#" class="text-right">Done</a></li> </ul> </div><!-- /navbar -->
		</div>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="setting-list">

			<ul data-role="listview"> 
				<li class="range-sec"><a href="#">Location</a>
				</li> 
			</ul>
		</div>
		<div class="setting-list">
			<ul data-role="listview"> 
				<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">Language<p class="ui-li-aside">{{Auth::user()->language}}</p></h2>
					<form>
					    <fieldset data-role="controlgroup">
					        <input type="radio" value="" name="radio-choice-v-2" id="radio-choice-v-2a" value="on" checked="checked">
					        <label for="radio-choice-v-2a">Hindi</label>
					        <input type="radio" value="" name="radio-choice-v-2" id="radio-choice-v-2b" value="off">
					        <label for="radio-choice-v-2b">Spanish</label>
					        <input type="radio" value="" name="radio-choice-v-2" id="radio-choice-v-2c" value="other">
					        <label for="radio-choice-v-2c">Chinese</label>
					    </fieldset>
					</form>
				</li>	
				<!-- <li><a href="#">Unit <p class="ui-li-aside">Meter</p></a></li>  -->
				<li data-role="collapsible" class="range-sec"><h2  class="ui-btn ui-btn-icon-right ui-icon-carat-r">Range<p class="ui-li-aside">{{Auth::user()->range}}</p></h2>
				<p>
					<form>
						<div data-role="rangeslider">
						    <input type="range" name="range-1b" id="range-1b" min="3" max="10" value="">
						</div>
					</form>
				</p>
				</li> 
			</ul> 
		</div>
	</div>
</div>
@endsection

@section('footer-script')

@endsection