@extends('v1.user.layouts.master')

@section('head-scripts')
	<script src="{{asset('js/restolist/resturantSelection.js')}}"></script>
	<script src="{{asset('js/restolist/restroListCommon.js')}}"></script>
	<script type="text/javascript">
	$(function(){
		setCurrentLatLong("{{url('update-location')}}");
		var d=$('#browserCurrentTime').val();
	});
	</script>
@endsection

@section('content')
	<div class="container-fluid text-center">
		<h1>{{ __('messages.Welcome To Anar') }}</h1>
		<p>{{ __('messages.Select Restaurant') }}</p>
		<div class="row">
			<div class="col-md-6">
				<button type="button" class="btn btn-block" onclick=setResttype("{{url('setResttype')}}","eatnow")>
					<img src="{{asset('images/icons/icon-eat-now-active.png')}}" width="30" />{{ __('messages.Eat Now') }}
				</button>
			</div>
			<div class="col-md-6">
				<button type="button" class="btn btn-block" onclick=setResttype("{{url('setResttype')}}","eatlater")>
					<img src="{{asset('images/icons/icon-eat-later-inactive.png')}}" width="30" />{{ __('messages.Eat Later') }}
				</button>
			</div>
		</div>
	</div>
@endsection

@section('footer-script')
<script type="text/javascript">
	// Update global variable call 'lat/lng' value
	@if(Auth::check())
		@if(Session::get('with_login_lat') != null)
			setLngLat("{{Session::get('with_login_lat')}}","{{Session::get('with_login_lng')}}");
		@elseif(Session::get('with_out_login_lat') != null)
			setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");
		@else
			setLngLat(null,null);
		@endif
	@else
		@if(Session::get('with_out_login_lat') != null)
			setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");
		@endif
	@endif
</script>
@endsection