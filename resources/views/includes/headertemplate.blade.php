@php {{

    $flag='false';
    $menuActivate='false';
    $selectEatLaterTime='false';
    $map='false';
    $cart='false';
    $storeMap=false;
    $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
    $actual_link = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $baseurl=$app->make('url')->to('/')."/";
    if (strpos($_SERVER['REQUEST_URI'], 'eat-now') !== false || strpos($_SERVER['REQUEST_URI'], 'eat-later') !== false || $actual_link === $baseurl || strpos($_SERVER['REQUEST_URI'], 'home') !== false  || strpos($_SERVER['REQUEST_URI'], 'save-order') !== false) {

		$flag = 'true';
    }
    elseif(strpos($_SERVER['REQUEST_URI'], 'restro-menu-list') !== false ){
       
       $menuActivate='true';
      
    }

    elseif(strpos($_SERVER['REQUEST_URI'], 'selectOrder-date') !== false){
       
       $selectEatLaterTime='true';
      
    }
     elseif(strpos($_SERVER['REQUEST_URI'], 'search-map-eatnow') !== false || strpos($_SERVER['REQUEST_URI'], 'search-store-map') !== false ){
       
       $map='true';
       if(strpos($_SERVER['REQUEST_URI'], 'search-store-map') !== false){
          
          $storeMap='true';
       }
      
    }
    elseif(strpos($_SERVER['REQUEST_URI'], 'cart') !== false){
       
       $cart='true';
      
    }

 }}
 @endphp

 @if($flag=='true')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
			<div class="nav_fixed">
				<div class="logo">
					
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>

 @elseif($menuActivate=='true')

 <div data-role="header" class="header"  data-position="fixed" data-tap-toggle="false">
		<div class="logo">
			
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="{{url('search-store-map')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>

 @elseif($selectEatLaterTime=='true')

 	<div data-role="header" class="header" data-position="fixed" id="nav-header"  data-position="fixed" data-tap-toggle="false"> 
			<div class="nav_fixed">
				<a href="{{Session::get('route_url')}}" data-ajax="false" class="ui-btn-left text-left backarrow-btn"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>
				<div class="logo">
					
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a class="ui-btn-right map-btn user-link" 
				href="{{url('search-map-eatnow')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>

 @elseif($map=='true')
 <div data-role="header" class="header" id="nav-header"  data-position="fixed" >
 	<div class="nav_fixed">
          
          @if($storeMap=='true')
          <a href="{{url('restro-menu-list/'.$storedetails->store_id)}}" data-ajax="false" class="ui-btn-left text-left backarrow-btn"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>

           
           @else

			<a href="{{Session::get('route_url')}}" data-ajax="false" class="ui-btn-left text-left backarrow-btn"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>
		  @endif

		<div class="logo">
			<div class="inner-logo">
				<!-- <span class="rest-title">Domino's</span> -->
				<img src="{{asset('images/logo.png')}}">
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="#left-side-bar"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
	</div>

	 @elseif($cart=='true')
	 <div data-role="header" class="header" id="nav-header"  data-position="fixed">
			<div class="nav_fixed">
				<div class="logo">
					<!--<a href="#" data-ajax="false" class="ui-btn-left text-left backarrow-btn">

						<img src="{{asset('images/icons/backarrow.png')}}" width="11px" ></a>-->
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>
	@else
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
			<div class="nav_fixed">
				<div class="logo">
					
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>

@endif