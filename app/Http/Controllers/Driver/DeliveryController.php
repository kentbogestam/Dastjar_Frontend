<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderDetail;
use App\OrderDelivery;

class DeliveryController extends Controller
{
	/**
	 * Show list of delivery to driver
	 * @return [type] [description]
	 */
	public function listDelivery($driverId)
	{
		$orderDelivery = OrderDelivery::from('order_delivery AS OD')
			->select(['OD.id', 'O.order_id', 'O.customer_order_id', 'CA.full_name', 'CA.mobile', 'CA.address', 'CA.street', 'CA.landmark', 'CA.city', 'CA.state'])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
			->where(['OD.driver_id' => $driverId, 'paid' => 0])
			->get();
		
		return view('driver.delivery', compact('orderDelivery'));
	}

	/**
	 * Return order detail
	 * @param  [type] $orderId [description]
	 * @return [type]          [description]
	 */
	function getOrderDetail($orderId)
	{
		$html = '';

		// Get order detail
		$orderDetail = OrderDetail::from('order_details AS OD')
			->select(['OD.product_quality', 'P.product_name' ,'O.customer_order_id'])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('product AS P', 'P.product_id', '=', 'OD.product_id')
			->where(['OD.order_id' => $orderId])
			->get();

		if($orderDetail)
		{
			foreach($orderDetail as $row)
			{
				$html .= "
					<tr>
						<td>{$row->customer_order_id}</td>
						<td>{$row->product_name}</td>
						<td>{$row->product_quality}</td>
					</tr>
				";
			}
		}

		return response()->json(['html' => $html]);
	}

	/**
	 * Mark order delivered
	 * @param  [type] $orderId [description]
	 * @return [type]          [description]
	 */
	function orderDeliver($orderId)
	{
		Order::where(['customer_order_id' => $orderId])
			->update(['paid' => 1]);
        
        return redirect()->back();
	}
}
