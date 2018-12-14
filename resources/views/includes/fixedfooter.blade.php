@php {{

    $flag='false';
    $menuActivate='false';
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $baseurl=$app->make('url')->to('/')."/";
    if (strpos($_SERVER['REQUEST_URI'], 'eat-now') !== false || strpos($_SERVER['REQUEST_URI'], 'eat-later') !== false || $actual_link === $baseurl ) {

		$flag = 'true';
    }
    elseif(strpos($_SERVER['REQUEST_URI'], 'restro-menu-list') !== false){
       
       $menuActivate='true';
      
    }
 }}
 @endphp

<div data-role="footer" id="footer" data-position="fixed">
<div class="ui-grid-c inner-footer center">
	
		<div class="ui-block-a">

         @if($flag=='true'):
			<a href="javascript:void(0)" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
		@else
		     <a href="{{Session::get('route_url')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">

		@endif
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_01.png')}}">
			</div>
			<span>{{ __('messages.Restaurant') }}</span>
		</a>
	  </div>

         @if($menuActivate=='false'):

		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			<div class="img-container">
				<img src="{{asset('images/icons/select-store_03.png')}}">
			</div>
			<span>{{ __('messages.Send') }}</span>
		</a></div>

        @else

		  <div class="ui-block-b">
				<a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" id="menudataSave" data-ajax="false">
					<div class="img-container">
						<img src="{{asset('images/icons/select-store_03.png')}}">
					</div>
					<input type="button" value="{{ __('messages.Send') }}" id="dataSave"/>
				</a>
			</div>

		@endif

		@include('orderQuantity')

		<div class="ui-block-d">
			<a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_07.png')}}">
				</div>
			</a>
		</div>
  </div>
</div>