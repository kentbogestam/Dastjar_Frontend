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
use App\UserAddress;
use App\Helper;

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
		$dateTime = date('Y-m-d H:i:s', strtotime('-5 minutes'));

		// Get deliver order list
		$orderDelivery = OrderDelivery::from('order_delivery AS OD')
			->select(['OD.id', 'O.order_id', 'O.customer_order_id', 'O.online_paid', 'O.deliver_time', 'O.order_delivery_time', 'O.order_response', 'O.extra_prep_time AS o_extra_prep_time', 'O.paid', 'CA.full_name', 'CA.mobile', 'CA.entry_code', 'CA.apt_no', 'CA.company_name', 'CA.other_info', 'CA.address', 'CA.street', 'CA.city', DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.zipcode, ", ", CA.country) AS customer_address'), 'S.store_name', 'S.phone', 'S.extra_prep_time', 'S.buffer_time', DB::raw('CONCAT(S.street, ", ", S.city, ", ", S.zip, ", ", S.country) AS store_address')])
			->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
			->join('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
			->join('store AS S', 'S.store_id', '=', 'O.store_id')
			->where(['OD.driver_id' => $driverId, 'OD.status' => '2'])
			->where(function($query) use ($dateTime) {
				$query->where('O.paid', 0)->orWhere('O.updated_at', '>=', $dateTime);
			})
			->get();

		return response()->json(['orderDelivery' => $orderDelivery]);
	}

	/**
	 * Show driving direction to delivery
	 * @return [type] [description]
	 */
	function deliveryDirection($orderId)
	{
		$driverId = Auth::guard('driver')->user()->id;

		// Get order
		$order = Order::select(['user_address_id'])
			->where(['order_id' => $orderId])
			->first();
		
		// 
		$driver = Driver::select(['latitude', 'longitude'])
			->where(['id' => $driverId])
			->first();
		
		$markerArray = array();
		$markerArray[] = array('lat' => $driver->latitude, 'lng' => $driver->longitude);
		// $markerArray[] = array('lat' => 59.3150, 'lng' => 17.9999);

		$userAddress = UserAddress::from('customer_addresses AS CA')
			->select([DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.zipcode, ", ", CA.country) AS customer_address')])
			->where('id' , $order->user_address_id)
			->first();
		$address = Helper::getCoordinates($userAddress->customer_address);

		if($address)
		{
			$markerArray[] = $address;
		}

		// Encode array
        if(!empty($markerArray))
        {
            $markerArray = json_encode($markerArray);
        }
		// dd($markerArray);
		return view('driver.pickup-direction', compact('markerArray'));
	}

	/**
     * [Update order payment manually from 'Orders' page]
     * @param  [type] $order_id [primary key of table 'order']
     * @return [type]           [status]
     */
    function orderPayManually($orderId)
    {
        $status = false;
        $order = Order::findOrFail($orderId);

        if( $order->where('order_id', $orderId)->update(['online_paid' => 3]) )
        {
           $status = true;
        }

        return response()->json(['status' => $status, 'order' => $order]);
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

		// Get order deliver status
		$paid = 1;
		$order = Order::select(['paid'])->where(['customer_order_id' => $orderId])->first();
		if($order->paid)
		{
			$paid = 0;
		}

		// Update order deliver status
		if(Order::where(['customer_order_id' => $orderId])->update(['paid' => $paid]))
		{
			// Check and update driver engage status
			$driverId = Auth::guard('driver')->user()->id;

			$orderDelivery = OrderDelivery::from('order_delivery AS OD')
				->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
				->where(['OD.driver_id' => $driverId, 'OD.status' => '2', 'paid' => 0])
				->count();

			if($orderDelivery == 0)
			{
				Driver::where(['id' => $driverId])->update(['is_engaged' => '0']);
			}
		}
        
        return redirect()->back();
	}
}
