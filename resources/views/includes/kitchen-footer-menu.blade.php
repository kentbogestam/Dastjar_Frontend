<style>
    i.badge{
        margin-left: 0px;
        font-family: cursive;
        text-shadow: none;
        font-size: 12px;
        color: #fff;
    }
</style>

<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer_container">
	<div class="ui-grid-a center">
		<div class="ui-block-a left-side_menu">
			<div class="ui-block-a{{ request()->is('kitchen/store') ? ' block_div active' : '' }}" title="{{ __('messages.Orders') }}">
				<a href="{{ request()->is('kitchen/store') ? 'javascript:void(0)' : url('kitchen/store') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('kitchenImages/icon-1.png')}}">
				</div>
				<span>{{ __('messages.Orders') }}</span>
				</a>
			</div>
			<div class="ui-block-b{{ request()->is('kitchen/kitchen-detail') ? ' block_div active' : '' }}" title="{{ __('messages.Kitchen') }}">
				<a href = "{{ request()->is('kitchen/kitchen-detail') ? 'javascript:void(0)' : url('kitchen/kitchen-detail') }}" id="menu-kitchen" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline{{ !Session::has('subscribedPlans.kitchen') ? ' ui-state-disabled' : '' }}" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('kitchenImages/icon-2.png')}}">
				</div>
				<span>{{ __('messages.Kitchen') }}</span>
				</a>
			</div>
			<div class="ui-block-b{{ request()->is('kitchen/catering') ? ' block_div active' : '' }}" title="{{ __('messages.Catering') }}">
				<a href = "{{ request()->is('kitchen/catering') ? 'javascript:void(0)' : url('kitchen/catering') }}" id="menu-catering" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline{{ !Session::has('subscribedPlans.catering') ? ' ui-state-disabled' : '' }}" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('kitchenImages/icon-3.png')}}">
				</div>
				<span>{{ __('messages.Catering') }}</span>
                <i class="badge catering-badge">0</i>
				</a>
			</div>
		</div>
		<div class="ui-block-b right-side_menu" title="Kitchen Setting">
			<div class="ui-block-a drop_down"><a href = "{{ url('kitchen/kitchen-setting') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('kitchenImages/icon-6.png')}}">
				</div>
			</a></div>

			<div class="ui-block-b{{ request()->is('kitchen/menu') ? ' block_div active' : '' }}" title="{{ __('messages.Menu') }}"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false" href="{{ request()->is('kitchen/menu') ? 'javascript:void(0)' : url('kitchen/menu') }}">
				<div class="img-container">
					<img src="{{asset('kitchenImages/icon-7.png')}}">
				</div>
				<span>{{ __('messages.Menu') }}</span>
			</a></div>

			<div class="ui-block-c{{ request()->is('kitchen/kitchen-order-onsite') ? ' block_div active' : '' }}" title="{{ __('messages.Order Onsite') }}"><a href = "{{ request()->is('kitchen/kitchen-order-onsite') ? 'javascript:void(0)' : url('kitchen/kitchen-order-onsite') }}" id="menu-orderonsite" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline{{ !Session::has('subscribedPlans.orderonsite') ? ' ui-state-disabled' : '' }}" data-ajax="false">
				<div class="img-container">
					<img src="{{asset('kitchenImages/icon-4.png')}}">
				</div>
				<span>{{ __('messages.Order Onsite') }}</span>
			</a></div>
		</div>
	</div>
</div>