@php {{

    $flag='false';
    $menuActivate='false';
    $selectEatLaterTime='false';
echo    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $baseurl=$app->make('url')->to('/')."/";
    if (strpos($_SERVER['REQUEST_URI'], 'eat-now') !== false || strpos($_SERVER['REQUEST_URI'], 'eat-later') !== false || $actual_link === $baseurl || strpos($_SERVER['REQUEST_URI'], 'home') !== false ) {

		$flag = 'true';
    }
    elseif(strpos($_SERVER['REQUEST_URI'], 'restro-menu-list') !== false ){
       
       $menuActivate='true';
      
    }

    elseif(strpos($_SERVER['REQUEST_URI'], 'selectOrder-date') !== false){
       
       $selectEatLaterTime='true';
      
    }
 }}
 @endphp

 @if($flag=='true'):
	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
			<div class="nav_fixed">
				<div class="logo">
					1
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a href="{{url('search-map-eatnow')}}" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>
 @elseif($menuActivate=='true'):

 <div data-role="header" class="header"  data-position="fixed" data-tap-toggle="false">
		<div class="logo">
			2
			<div class="inner-logo">
				<span class="rest-title">{{$storedetails->store_name}}</span>
				@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
			</div>
		</div>
		<a class="ui-btn-right map-btn user-link" href="{{url('search-store-map')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
	</div>
 @elseif($selectEatLaterTime=='true'):

 	<div data-role="header" class="header" data-position="fixed" id="nav-header"  data-position="fixed" data-tap-toggle="false"> 
			<div class="nav_fixed">
				<a href="{{Session::get('route_url')}}" data-ajax="false" class="ui-btn-left text-left backarrow-btn"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a>
				<div class="logo">
					3
					<div class="inner-logo">
						<img src="{{asset('images/logo.png')}}">
						@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
					</div>
				</div>
				<a class="ui-btn-right map-btn user-link" 
				href="{{url('search-map-eatnow')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
			</div>
		</div>
@endif