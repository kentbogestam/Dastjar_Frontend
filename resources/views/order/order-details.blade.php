@extends('layouts.master')

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('kitchenCss/style.css')}}">
<style>
    .nav_fixed {
        padding: 7px 0px;
    }
</style>

<div data-role="header" class="header" id="nav-header"  data-position="fixed"><!--  -->
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

	<div role="main" data-role="main-content" class="content">
		<div class="order_display">
			<div class="order_bg">
				<div class="order-ready-text">
					<p>{{ __('messages.Thanks for your order') }} </p>
					<p>{{ __('messages.Order Number') }} </p>
					<p class="order-no">{{$order->customer_order_id}}</p>
					<p>({{$order->store_name}})</p>
					<p>{{ __('messages.Your order will be ready on') }} {{$order->order_delivery_time}} mins
						@if($order->order_type == 'eat_later')
						{{$order->deliver_date}}
						@endif
					</p>
				</div>
			</div>
			<div class="table-wrap">
				<h2>{{ __('messages.ORDER DETAILS') }}</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive ui-table">
					<tbody>
						@foreach($orderDetails as $orderDetail)
							<tr>
								<td>{{$orderDetail->product_name}}	</td><td>{{$orderDetail->product_quality}} x {{$orderDetail->price}}</td><td>{{$order->currencies}} {{$orderDetail->product_quality*$orderDetail->price}}</td>
							</tr>	
						@endforeach
						<tr class="last-row">
							<td> </td>
							<td>         </td>
							<td>  {{ __('messages.TOTAL') }}    {{$order->currencies}} {{$order->order_total}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

    <div data-role="footer" id="footer" data-position="fixed">
            <div class="ui-grid-c inner-footer center">
            <div class="ui-block-a"><a href="{{ url('eat-now') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
                <div class="img-container">
                    <img src="{{asset('images/icons/select-store_01.png')}}">
                </div>
                <span>{{ __('messages.Restaurant') }}</span>
            </a></div>
            <div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline">
                <div class="img-container">
                    <img src="{{asset('images/icons/select-store_03.png')}}">
                </div>
                <span>{{ __('messages.Send') }}</span>
            </a></div>
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
@endsection

@section('footer-script')


@endsection