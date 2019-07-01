<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use App\Driver;
use App\Company;
use App\Order;
use App\OrderDetail;
use App\OrderDelivery;

class PickupController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:driver');
    }

    /**
	 * List of orders to pickup from restaurant
	 * @return [type] [description]
	 */
	function orderPickup()
	{
		$companyId = Auth::guard('driver')->user()->company_id;
		$company = Company::select(['company_name'])
			->where(['company_id' => $companyId])
			// ->where('c_activ', '!=', null)
			->first();
		
		return view('driver.pickup', compact('company'));
	}

	/**
	 * Get order list needs to be picked-up from restaurant
	 * @return [type] [description]
	 */
	function getPickupOrderList()
	{
		$driverId = Auth::guard('driver')->user()->id;

		$orderDelivery = OrderDelivery::from('order_delivery AS OD')
			->select(['OD.id', 'O.order_id', 'O.customer_order_id', 'O.online_paid', 'S.store_name', 'S.phone', 'CA.full_name', 'CA.mobile', 'CA.address', 'CA.street', 'CA.landmark', 'CA.city', 'CA.state'])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
			->join('store AS S', 'S.store_id', '=', 'O.store_id')
			->where(['OD.driver_id' => $driverId, 'OD.status' => '0', 'O.paid' => 0])
			->get();

		return response()->json(['orderDelivery' => $orderDelivery]);
	}

	/**
	 * Accept order to pickup
	 * @param  [type] $orderDeliveryId [description]
	 * @return [type]                  [description]
	 */
	function orderPickupAccept($orderDeliveryId)
	{
		$status = 0;

		if(OrderDelivery::where(['id' => $orderDeliveryId])->update(['status' => '1']))
		{
			$status = 1;
		}

		return response()->json(['status' => $status]);
	}

	/**
	 * Return order detail
	 * @param  [type] $orderId [description]
	 * @return [type]          [description]
	 */
	function getOrderDetail($customerOrderId)
	{
		$html = '';

		// Get order detail
		$orderDetail = OrderDetail::from('order_details AS OD')
			->select(['OD.product_quality', 'P.product_name' ,'O.customer_order_id'])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('product AS P', 'P.product_id', '=', 'OD.product_id')
			->where(['O.customer_order_id' => $customerOrderId])
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
	 * Update drive status 'active/inactive'
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	function updateStatus($currentStatus)
	{
		$status = 0;

		$res = Driver::where(['id' => Auth::guard('driver')->user()->id])
			->update(['status' => $currentStatus]);

		if($res)
		{
			$status = 1;
		}

		return response()->json(['status' => $status]);
	}
}
