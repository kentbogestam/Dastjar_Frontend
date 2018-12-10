@extends('layouts.master')

@section('head-scripts')
	
	<script src="{{asset('js/restolist/restroListCommon.js')}}"></script>
    <script src="{{asset('js/restolist/restroListEatLater.js')}}"></script>
	<script src="{{asset('locationJs/currentLocation.js')}}"></script>
	 <script type="text/javascript">
	  var noImageUrl = "{{url('images/placeholder-image.png')}}";
		  $(function(){
		 
	        add("{{url('eat-later-data')}}","{{url('restro-menu-list/')}}",noImageUrl,"{{Session::get('order_date')}}");

	      });
    </script>
@endsection

@section('content')
	<div data-role="header" class="header" id="nav-header"  data-position="fixed"><!--  -->
		<div class="nav_fixed">
			<div class="logo">
				<div class="inner-logo">
					<img src="{{asset('images/logo.png')}}">
					@if(Auth::check())<span>{{ Auth::user()->name}}</span>@endif
				</div>
			</div>
			<a class="ui-btn-right map-btn user-link" href="{{url('search-map-eatlater')}}" data-ajax="false"><img src="{{asset('images/icons/map-icon.png')}}" width="30px"></a>
		</div>
	</div>
	<div class="cat-btn">
		<div class="ui-grid-a top-btn">
			<div class="ui-block-a"><a href="{{url('eat-now')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-inactive active" onclick="iconEatInactive()" data-ajax="false"><img src="{{asset('images/icons/icon-eat-now-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-now-inactive.png')}}" class="inactive">{{ __('messages.Eat Now') }}</a></div>
			<div class="ui-block-b"><a href="{{url('selectOrder-date')}}" class="ui-btn ui-shadow small-con-30 ui-corner-all icon-eat-active" data-ajax="false"><img src="{{asset('images/icons/icon-eat-later-active.png')}}" class="active"><img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" class="inactive">{{ __('messages.Eat Later') }}</a></div>
		</div>
	</div>
	<div role="main" data-role="main-content" id="content">
		<div class="cat-list-sec">
			<ul data-role="listview" data-inset="true" id="companyDetailContianer">

				
			</ul>
		</div>


	</div>	
	@include('includes.fixedfooter')

	<div id="loading-img" class="ui-loader ui-corner-all ui-body-a ui-loader-default"><span class="ui-icon-loading"></span><h1>loading</h1></div>

	  <div id="overlay" onclick="off()">
	  </div>
@endsection

@section('footer-script')

<script type="text/javascript">

	$(document).on("scrollstop", function (e) {
		var tempCount = list.length
    	var activePage = $.mobile.pageContainer.pagecontainer("getActivePage"),
        screenHeight = $.mobile.getScreenHeight(),
        contentHeight = $(".ui-content", activePage).outerHeight(),
        header = $(".ui-header", activePage).outerHeight() - 1,
        scrolled = $(window).scrollTop(),
        footer = $(".ui-footer", activePage).outerHeight() - 1,
        scrollEnd = contentHeight - screenHeight + header + footer;

    	$(".ui-btn-left", activePage).text("Scrolled: " + scrolled);
    	//$(".ui-btn-right", activePage).text("ScrollEnd: " + scrollEnd);

    	
    	//if in future this page will get it, then add this condition in and in below if activePage[0].id == "home" 
    	if (scrolled >= scrollEnd) {
       // console.log(list);
        $.mobile.loading("show", {
        text: "loading more..",
        textVisible: true,
        theme: "b"
    	});
    	setTimeout(function () {
          addMore(tempCount,"{{url('restro-menu-list/')}}",noImageUrl,"{{Session::get('order_date')}}");
         tempCount += 10;
         $.mobile.loading("hide");
     },500);
    	}
});

</script>

@endsection
