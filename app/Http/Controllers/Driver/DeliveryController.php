<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use App\Driver;
use App\Company;
use App\Order;
use App\OrderDetail;
use App\OrderDelivery;

class DeliveryController extends Controller
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

    /*public function index()
    {
    	return redirect('driver/list-delivery');
    }*/

	/**
	 * Show list of delivery to driver
	 * @return [type] [description]
	 */
	public function delivery()
	{
		$companyId = Auth::guard('driver')->user()->company_id;
		$company = Company::select(['company_name'])
			->where(['company_id' => $companyId])
			// ->where('c_activ', '!=', null)
			->first();
		
		return view('driver.delivery', compact('orderDelivery', 'company'));
	}

	/**
	 * Get order list to deliver
	 * @return [type] [description]
	 */
	function getDeliverOrderList()
	{
		$driverId = Auth::guard('driver')->user()->id;

		$orderDelivery = OrderDelivery::from('order_delivery AS OD')
			->select(['OD.id', 'O.order_id', 'O.customer_order_id', 'O.online_paid', 'CA.full_name', 'CA.mobile', 'CA.address', 'CA.street', 'CA.city', DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.state, ", ", CA.zipcode) AS full_address')])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
			->where(['OD.driver_id' => $driverId, 'OD.status' => '1', 'paid' => 0])
			->get();

		return response()->json(['orderDelivery' => $orderDelivery]);
	}

	/**
	 * Mark order delivered
	 * @param  [type] $orderId [description]
	 * @return [type]          [description]
	 */
	function orderDeliver($orderId)
	{
		// Check driver engage status
		$driverId = Auth::guard('driver')->user()->id;

		if(Order::where(['customer_order_id' => $orderId])->update(['paid' => 1]))
		{
			// Check and update driver engage status
			$driverId = Auth::guard('driver')->user()->id;

			$orderDelivery = OrderDelivery::from('order_delivery AS OD')
				->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
				->where(['OD.driver_id' => $driverId, 'OD.status' => '1', 'paid' => 0])
				->count();

			if($orderDelivery == 0)
			{
				Driver::where(['id' => $driverId])->update(['is_engaged' => '0']);
			}
		}
        
        return redirect()->back();
	}
}
