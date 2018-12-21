@extends('layouts.master')
@section('content')
@include('includes.headertemplate')

	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">
			
			<div class="table-content">
				<h2>{{ __('messages.CART DETAILS') }}</h2>
            <div class="del-cart">

            	<a href="#" data-ajax="false" onclick=deleteFullCart("{{ url('emptyCart/') }}")>Delete Full Cart</a>


             </div>
				
	<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
					<?php $j=1 ;?>
					<input type="hidden" name="redirectUrl" id="redirectUrl" value="{{ url('restro-menu-list/').'/'.$order->store_id }}"/>
					<input type="hidden" name="orderid" id="orderid" value="{{ $order->order_id }}" />
					<input type="hidden" name="baseUrl" id="baseUrl" value="{{ url('/')}}"/>
                   {{ csrf_field() }}
					<td>
					@foreach($orderDetails as $value)

						<tr id="row_{{$j}}">
		                    <td><input type="hidden" name="prod[{{$j}}]" id="prod{{$j}}" value="{{ $value->product_id }}">
							<td>{{ $value->product_name }}	</td>
							<td>{{ $value->price }} <input type="hidden" name="itemprice[{{$j}}]" id="itemprice{{$j}}" value="{{$value->price}}"/></td>
							<td>
								<div class="qty-sec">
									<input type="button" onclick="decrementCartValue('{{$j}}')" value="-"  class="min" />
									<input type="text" name="product[{{$j}}][prod_quant]" value="{{ $value->product_quality }}" maxlength="2" readonly size="1" id="qty{{$j}}" />
									<input type="button" onclick="incrementCartValue('{{$j}}')" value="+" class="max" />
								</div>
							</td>
							<td>{{ $order->currencies }} <span id="itemtotalDisplay{{$j}}">{{ $value->price*$value->product_quality }}</span>
                              <input type="hidden" name="itemtotal[{{$j}}]" id="itemtotal{{$j}}" value="{{ $value->price*$value->product_quality }}" class="itemtotal"/>
							</td>
						</tr>	
						<?php $j=$j+1 ;?>
					@endforeach
				<tr class="last-row" id="last-row">	
					<td> </td>
					<td> </td>
					<td>  TOTAL:-    {{$order->currencies}}<span id="grandTotalDisplay"> {{$order->order_total}}</span>
                    <input type="hidden" name="grandtotal" id="grandtotal" value="{{$order->order_total}}"/>
					</td>
				</tr>
				
	</table>

				<div id="saveorder"><a href="{{url('save-order').'/?orderid='.$order->order_id}}" data-ajax="false">send order and pay in restaurant</a></div>
			</div>
		</div>
	</div>


@include('includes.fixedfooter')

@endsection

@section('footer-script')

@endsection