@php {{

    $flag='false';
    $menuActivate='false';
    $cart='false';
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $baseurl=$app->make('url')->to('/')."/";
    if (strpos($_SERVER['REQUEST_URI'], 'eat-now') !== false || strpos($_SERVER['REQUEST_URI'], 'eat-later') !== false || $actual_link === $baseurl ) {

		$flag = 'true';
    }
    elseif(strpos($_SERVER['REQUEST_URI'], 'restro-menu-list') !== false){
       
       $menuActivate='true';
      
    }
    elseif( strpos($_SERVER['REQUEST_URI'], 'cart') !== false || strpos($_SERVER['REQUEST_URI'], 'save-order') !== false ){
       
       $cart='true';  
    }
 }}
 @endphp

<div data-role="footer" id="footer" data-position="fixed">
	<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a<?php echo ($flag == 'true' || $cart=='true') ? ' active' : ''; ?>">
	        @if($flag=='true')
				<a href="javascript:void(0)" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			@else
	             @if($cart=='true')
	             	<a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false" onclick="deleteFullCart('{{ url("emptyCart/") }}','1','{{ __("messages.Leave Cart Page") }}')"> 
	             @else
			     	<a href="{{Session::get('route_url')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
	            @endif
			@endif
				<div class="img-container">
					<img src="{{asset('images/icons/select-store_01.png')}}">
				</div>
				<!-- <span>{{ __('messages.Restaurant') }}</span> -->
			</a>
	  	</div>

        @if($menuActivate=='false')
			<div class="ui-block-b<?php echo ($cart=='true') ? ' active' : ''; ?>">
				<a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
					<div class="img-container">
						<span class="badge cart-badge">0</span>
						<img src="{{asset('images/icon-cart.png')}}">
					</div>
					<!-- <span>{{ __('messages.Send') }}</span> -->
				</a>
			</div>
        @else
			<div class="ui-block-b">
				<a href="#" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline actibe-icon-img" id="menudataSave" data-ajax="false">
					<div class="img-container">
						<span class="badge cart-badge">0</span>
						<img src="{{asset('images/icon-cart.png')}}">
					</div>
					<!-- <input type="button" value="{{ __('messages.Send') }}" id="dataSave"/> -->
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