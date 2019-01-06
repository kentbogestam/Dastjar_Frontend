<div class="logo_header">
	<img src="{{asset('kitchenImages/logo-img.png')}}">
	<a href = "{{ url('kitchen/logout') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline link-logout" onclick="closeConnection()" data-ajax="false">{{ __('messages.Logout') }}</a>
</div>