
@extends('layouts.master')
@section('head-scripts')

 <script src="{{asset('js/restolist/resturantSelection.js')}}"></script>
 <script src="{{asset('js/restolist/restroListCommon.js')}}"></script>

<script type="text/javascript">
	
	$(function(){
      setCurrentLatLong();
      browserPhoneSetting();
      var d=$('#browserCurrentTime').val();
      
	});
</script>

@endsection

@section('content')

@include('includes.headertemplate')

<div class="popupSelection">

	<div class="static-content">
		<h3>Welcome to anar !</h3>
		<p>Please choose to Eat now or later!</p>
	</div>
		
    <div class="select-restaurant">
        <ul>
        	<li><a class="eatnow-btn-df" href="#" data-ajax="false" onclick=setResttype("{{url('setResttype')}}","eatnow")>Eat Now</a></li>
        	<li><a class="eatnow-btn-fg" href="#" data-ajax="false" onclick=setResttype("{{url('setResttype')}}","eatlater")>Eat Later</a></li>
        </ul>
      
     </div>


</div>

@include('includes.fixedfooter')

@endsection

@section('footer-script')
 
<?php
	$helper = new Helper();

	if(Auth::check()){
			
      if(Session::get('with_login_lat') != null){

		?>
        <script type="text/javascript">
         
         setLngLat("{{Session::get('with_login_lat')}}","{{Session::get('with_login_lng')}}");
           
        </script>
		<?php
	}else if(Session::get('with_out_login_lat') != null){

		?>
        <script type="text/javascript">
        
 		setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");

        </script>
		<?php
	}else{
		?>
        <script type="text/javascript">
        	
			setLngLat(null,null);

        </script>
		<?php
	}
	}
	else{
		if(Session::get('with_out_login_lat') != null){
	?>
	<script type="text/javascript">
			
	setLngLat("{{Session::get('with_out_login_lat')}}","{{Session::get('with_out_login_lng')}}");

	</script>
			<?php
		}
	}
?>

@endsection


	