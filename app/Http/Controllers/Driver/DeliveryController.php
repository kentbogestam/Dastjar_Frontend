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
use App\CompanySubscriptionDetail;
use App\Payment;

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
		// Get order deliver status
		$order = Order::select(['order_id', 'store_id', 'paid', 'updated_at'])
			->where(['customer_order_id' => $orderId])
			->first();

		if($order)
		{
			// Check driver engage status
			$driverId = Auth::guard('driver')->user()->id;
			$paid = 1;
			$isDelivered = 0;

			// If order not delivered, capture the payment and deliver it, else undo delivered order within 5 min
			if($order->paid)
			{
				$dateTime = date('Y-m-d H:i:s');
				$dateTime = new \DateTime($dateTime);
				$dateDiff = $dateTime->diff(new \DateTime($order->updated_at));

				if($dateDiff->i <= 5)
				{
					$paid = 0;
				}
				else
				{
					return \Redirect::back()->with(['error' => 'You can\'t undo order after 5 minutes!' ]);
				}
			}
			else
			{
				// Get the payment if exist and not captured
	            $payment = Payment::select(['transaction_id', 'status'])
	                ->where(['order_id' => $order->order_id, 'status' => '2'])->first();

	            if($payment)
	            {
					if($payment->status == '2')
					{
						// Get the Stripe Account
	                    $companySubscriptionDetail = CompanySubscriptionDetail::from('company_subscription_detail AS CSD')
	                        ->select('CSD.stripe_user_id')
	                        ->join('company AS C', 'C.company_id', '=', 'CSD.company_id')
	                        ->join('store AS S', 'S.u_id', '=', 'C.u_id')
	                        ->where('S.store_id', $order->store_id)->first();

	                    if($companySubscriptionDetail)
	                    {
	                    	$stripeAccount = $companySubscriptionDetail->stripe_user_id;

	                        // Initilize Stripe
	                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

	                        try {
	                            // capture-later
	                            $payment_intent = \Stripe\PaymentIntent::retrieve($payment->transaction_id, ['stripe_account' => $stripeAccount]);
	                            $payment_intent->capture();

	                            if($payment_intent->status == 'succeeded')
	                            {
	                                $isDelivered = 1;

	                                // Update payment as captured
	                                Payment::where(['order_id' => $order->order_id, 'status' => '2'])
	                                    ->update(['status' => '1']);
	                            }
	                        } catch (\Stripe\Error\Base $e) {
	                            # Display error on client
	                            $response = array('error' => $e->getMessage());
	                            return \Redirect::back()->with($response);
	                        }
	                    }
					}
					elseif($payment->status == '1')
	                {
	                    $isDelivered = 1;
	                }
	            }
	            else
	            {
	            	$isDelivered = 1;
	            }
			}

            // 
            if($isDelivered || $paid == 0)
            {
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
            }
		}
        
        return redirect()->back();
	}
}
