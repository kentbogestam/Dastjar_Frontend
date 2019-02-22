@php
    $flag='false';
    $menuActivate='false';
    $cart='false';
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $baseurl=$app->make('url')->to('/')."/";
    // if (strpos($_SERVER['REQUEST_URI'], 'eat-now') !== false || strpos($_SERVER['REQUEST_URI'], 'eat-later') !== false || $actual_link === $baseurl ) {
    if( \Request::is('/') || \Request::is('home') || \Request::is('eat-now') || \Request::is('eat-later') ) {
		$flag = 'true';
    }
    elseif(strpos($_SERVER['REQUEST_URI'], 'restro-menu-list') !== false){
       $menuActivate='true';
    }
    elseif( strpos($_SERVER['REQUEST_URI'], 'cart') !== false || strpos($_SERVER['REQUEST_URI'], 'save-order') !== false ){
       $cart='true';  
    }
 @endphp

<div data-role="footer" id="footer" data-position="fixed">
	<div class="ui-grid-c inner-footer center customF">
		<div class="ui-block-a<?php echo ($flag == 'true') ? ' active' : ''; ?>">
	        @if($flag=='true')
				<a href="javascript:void(0)" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
			@else
	             @if($cart=='true')
	             	<a href="javascript:void(0)" id="leave-cart" data-content="{{ __("messages.Leave Cart Page") }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
	             @else
			     	<a href="{{Session::get('route_url')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
	            @endif
			@endif
				<div class="img-container fa fa-cutlery Resto">
					<!-- <img src="{{asset('images/icons/select-store_01.png')}}"> -->
				</div>
				<!-- <span>{{ __('messages.Restaurant') }}</span> -->
			</a>
	  	</div>

        @if($menuActivate=='false')
			<div class="ui-block-b<?php echo ($cart=='true') ? ' active' : ''; ?>">
				<a class="ui-shadow ui-corner-all icon-img ui-btn-inline">
					<div class="img-container fa fa-shopping-basket Scart">
						<span class="badge cart-badge sCartBage hidden">0</span>
						<!-- <img src="{{asset('images/icon-cart.png')}}"> -->
					</div>
					<!-- <span>{{ __('messages.Send') }}</span> -->
				</a>
			</div>
        @else
			<div class="ui-block-b <?php echo ($menuActivate=='true') ? ' active' : ''; ?>">
				<a href="#" class="ui-shadow ui-corner-all icon-img ui-btn-inline actibe-icon-img" id="menudataSave" data-ajax="false">
					<div class="img-container fa fa-shopping-basket Scart">
						<span class="badge cart-badge sCartBage hidden">0</span>
						<!-- <img src="{{asset('images/icon-cart.png')}}"> -->
					</div>
					<!-- <input type="button" value="{{ __('messages.Send') }}" id="dataSave"/> -->
				</a>
			</div>
		@endif

		@include('orderQuantity')

		<div class="ui-block-d">
			<a href = "{{url('user-setting')}}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container Q uSeting">
					<img src="{{asset('images/icons/select-store_07.png')}}">
				</div>
			</a>
		</div>
	</div>
</div>