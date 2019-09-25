@if(Auth::check())
	@if(count(Auth::user()->paidOrderList) == 0)
		<li>
			<a href="javascript:void(0)">
				<i class="fa fa-file-text"></i> 
			</a>
		</li>
	@else
		<li data-toggle="modal" data-target="#order-popup">
			<a href="javascript:void(0)" class="ordersec">
				<i class="fa fa-file-text"></i> 
				<sup><span class="badge sCartBage order-number<?php echo !(count(Auth::user()->paidOrderList)) ? ' hidden' : ''; ?>">{{count(Auth::user()->paidOrderList)}}</span></sup>
			</a>
		</li>
	@endif
@else
	<li>
		<a href="javascript:void(0)">
			<i class="fa fa-file-text"></i> 
		</a>
	</li>
@endif