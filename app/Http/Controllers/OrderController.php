<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\OrderDetail;
use App\OrderCustomerDiscount;
use App\PromotionDiscount;
use App\CustomerDiscount;
use App\PromotionLoyalty;
use App\OrderCustomerLoyalty;
use App\Product;
use App\ProductPriceList;
use Carbon\Carbon;
use DB;
use App\Store;
use App\StoreDeliveryType;
use App\StoreDeliveryPriceModel;
use App\User;
use App\UserAddress;
use App\Company;
use App\Admin;
use App\CompanySubscriptionDetail;
use App\Driver;
use Session;
use App\Helper;
use Stripe;
use App\Traits\PosReceipt;

class OrderController extends Controller
{
    use PosReceipt;

    public function saveOrder(Request $request){
        if(Auth::check()){
            $data = $request->input();

            $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$data['orderid'])->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();


            $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name','order_details.product_id')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$data['orderid'])->get();

            return view('order.paymentIndex', compact('order','orderDetails'));
        }
    }

    public function orderView($orderId){
        if( Session::has('paymentmode') && Session::get('paymentmode') == 0 )
        {
            DB::table('orders')->where('order_id', $orderId)->update([
                'online_paid' => 0,
            ]);
        }

        /*$order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();*/

        $order = Order::from('orders AS O')
            ->select(['O.order_id', 'O.customer_order_id', 'O.store_id', 'O.user_id', 'O.order_type', 'O.delivery_type', 'O.deliver_date', 'O.deliver_time', 'O.order_total', 'O.delivery_charge', 'O.final_order_total', 'O.order_delivery_time', 'O.order_response', 'O.order_accepted', 'O.extra_prep_time','S.store_name','company.currencies', DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.zipcode, ", ", CA.country) AS customer_address'), DB::raw('CONCAT(S.street, ", ", S.city, ", ", S.zip, ", ", S.country) AS store_address')])
            ->join('store AS S','O.store_id', '=', 'S.store_id')
            ->join('company','O.company_id', '=', 'company.company_id')
            ->leftJoin('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
            ->where('order_id', $orderId)
            ->first();

        // If order type is 'home delivery', get driving distance time
        if($order->delivery_type == 3)
        {
            // Get distance b/w origin and destination
            $response = Helper::getDrivingDistance($order->store_address, $order->customer_address, 'address');
            
            if($response['status'] == 'OK')
            {
                $distanceInSec = (int)$response['duration']['value'];
                $order['distanceInSec'] = $distanceInSec;
            }
        }

        $storeDetail = Store::where('store_id', $order->store_id)->first();
        $user = User::where('id',$order->user_id)->first();
        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

        // Get order discount if applied
        $orderDiscount = PromotionDiscount::from('promotion_discount AS PD')
            ->select(['PD.discount_value'])
            ->join('order_customer_discount AS OCD', 'OCD.discount_id', '=', 'PD.id')
            ->where(['OCD.order_id' => $orderId])
            ->first();

        Session::forget('paymentmode');

        // Put order in session 'recentOrderList' until its ready
        // Session::forget('recentOrderList'); Session::save();
        $recentOrder = Order::select('order_id')->where(['order_id' => $orderId, 'order_ready' => 0])->first();

        if($recentOrder)
        {
            $recentOrderList = Session::get('recentOrderList');

            if( !empty($recentOrderList) )
            {
                if( !array_key_exists($recentOrder->order_id, $recentOrderList) )
                {
                    $recentOrderList[$recentOrder->order_id] = 1;
                    Session::put('recentOrderList', $recentOrderList);
                }
            }
            else
            {
                Session::put('recentOrderList', array($recentOrder->order_id => 1));
            }
        }
        // dd(Session::all());
        
        // Create bong receipt to print
        $this->createPOSReceipt($orderId);

        return view('order.index', compact('order','orderDetails', 'orderDiscount','storeDetail','user'));
    }

    /**
     * Check if order accepted, return the text along with order number to show end-user
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    function checkIfOrderAccepted($orderId)
    {
        $status = false; $responseStr ='';

        // If accepted prepare string to show end-user
        if(Order::where(['order_id' => $orderId, 'order_accepted' => 1])->count())
        {
            $status = true;
            
            // Get order and associated store detail
            $order = Order::from('orders AS O')
                ->select(['O.order_id', 'O.customer_order_id', 'O.order_type', 'O.delivery_type', 'O.deliver_date', 'O.deliver_time', 'O.order_delivery_time', 'O.order_response', 'O.order_accepted', 'O.extra_prep_time', 'S.store_name', 'S.phone', 'S.extra_prep_time AS extra_prep_time_store', DB::raw('CONCAT(CA.street, ", ", CA.city, ", ", CA.zipcode, ", ", CA.country) AS customer_address'), DB::raw('CONCAT(S.street, ", ", S.city, ", ", S.zip, ", ", S.country) AS store_address')])
                ->join('store AS S', 'S.store_id', '=', 'O.store_id')
                ->leftJoin('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
                ->where('O.order_id', $orderId)
                ->first();

            //
            $responseStr .= '<p>'.__('messages.Thanks for your order').'</p>';
            $responseStr .= '<p>'.__('messages.Order Number').'</p>';
            $responseStr .= "<p class='large-text'>{$order->customer_order_id}</p>";
            $responseStr .= "<p>({$order->store_name})</p>";

            if( is_numeric($order->phone) )
            {
                $responseStr .= "<p><i class='fa fa-phone' aria-hidden='true'></i> <span>{$order->phone}</span></p>";
            }

            if($order->delivery_type == 3)
            {
                if($order->order_response)
                {
                    $times = array($order->order_delivery_time, $order->deliver_time, $order->extra_prep_time_store);
                }
                else
                {
                    $times = array($order->deliver_time, $order->extra_prep_time);
                }

                $time = Helper::addTimes($times);

                // Get distance b/w origin and destination
                $response = Helper::getDrivingDistance($order->store_address, $order->customer_address, 'address');
                
                if($response['status'] == 'OK')
                {
                    $distanceInSec = (int)$response['duration']['value'];
                    $order['distanceInSec'] = $distanceInSec;
                }
                
                // Add 'travelling time'
                if($distanceInSec)
                {
                    $time = date("H:i", strtotime($time)+$distanceInSec);
                }

                $dateTime = date('Y-m-d H:i:s', strtotime($order->deliver_date.' '.$time));

                $responseStr .= '<p>';
                if($order->order_type == 'eat_later')
                {
                    $responseStr .= __('messages.deliveryDateTimeEatLater').' '.date('Y-m-d H:i:s', strtotime($dateTime));
                }
                else
                {
                    $responseStr .= __('messages.deliveryDateTimeEatNow').' '.date('H:i', strtotime($dateTime));
                }

                $responseStr .= '<br><a href="'.url('track-order/'.$order->order_id).'" class="ui-btn ui-btn-inline track-order" data-ajax="false">'.__('messages.trackOrder').'</a>';
                $responseStr .= '</p>';
            }
            else
            {
                if($order->order_response)
                {
                    $time = $order->order_delivery_time;
                    $time2 = $order->extra_prep_time_store;
                }
                else
                {
                    $time = $order->deliver_time;
                    $time2 = $order->extra_prep_time;
                }
                
                $secs = strtotime($time2)-strtotime("00:00:00");
                $result = date("H:i:s",strtotime($time)+$secs);

                $responseStr .= '<p>';
                if($order->order_type == 'eat_later')
                {
                    $responseStr .= __('messages.Your order will be ready on').' '.$order->deliver_date.' '.date_format(date_create($order->deliver_time), 'G:i');
                }
                else
                {
                    $responseStr .= __('messages.Your order will be ready in about').' ';

                    if($order->order_response) // Automatic
                    {
                        if(date_format(date_create($result), 'H')!="00")
                        {
                            $responseStr .= date_format(date_create($result), 'H').' hours ';
                        }

                        $responseStr .= date_format(date_create($result), 'i').' mins';
                    }
                    else // Manual
                    {
                        $responseStr .= date_format(date_create($order->extra_prep_time), 'i').' mins';
                    }
                }
                $responseStr .= '</p>';
            }
        }

        // Return response data
        return response()->json(['status' => $status, 'responseStr' => $responseStr]);
    }

    /**
     * Check if order is ready from session 'recentOrderList'
     * @return [json]
     */
    function checkIfOrderReady()
    {
        $status = false; $order = null;

        $recentOrderList = Session::get('recentOrderList');

        if( !empty($recentOrderList) )
        {
            $orderList = array_keys($recentOrderList);
            
            $order = Order::select('order_id', 'customer_order_id')
                ->where(['user_id' => Auth::id(), 'order_ready' => 1])
                ->whereIn('order_id', $orderList)
                ->orderBy('order_id')
                ->first();

            if($order)
            {
                $status = true;
            }
        }

        // Return response data
        return response()->json(['status' => $status, 'order' => $order]);
    }

    /**
     * Track order origin (driver) to destination (user) using map
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    function trackOrder($orderId)
    {
        $markerArray = array();
        $order = Order::where('order_id', $orderId)->first();

        // If order type 'home delivery'
        if($order && $order->delivery_type == 3)
        {   
            // Get driver address
            $driver = Driver::from('drivers AS D')
                ->select(['D.latitude', 'D.longitude'])
                ->join('order_delivery AS OD', 'OD.driver_id', '=', 'D.id')
                ->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
                ->where(['O.order_id' => $orderId])
                ->first();

            if($driver)
            {
                $markerArray[] = array('lat' => $driver->latitude, 'lng' => $driver->longitude);
                // $markerArray[] = array('lat' => 41.878113, 'lng' => -87.629799); // chicago, il
            }
            else
            {
                $store = Store::select(['latitude', 'longitude'])
                    ->where('store_id', $order->store_id)
                    ->first();
                
                $markerArray[] = array('lat' => $store->latitude, 'lng' => $store->longitude);
            }
            
            // get order address
            $orderAddress = UserAddress::select(['street', 'city', 'zipcode', 'country'])
                ->join('orders', 'orders.user_address_id', '=', 'customer_addresses.id')
                ->where(['order_id' => $orderId])
                ->first();

            $address = $orderAddress->street.', '.$orderAddress->city.', '.$orderAddress->zipcode.', '.$orderAddress->country;
            $address = Helper::getCoordinates($address);

            if($address)
            {
                $markerArray[] = $address;
                // $markerArray[] = array('lat' => 38.627003, 'lng' => -90.199402); // st louis, mo
            }

            // Encode array
            if(!empty($markerArray))
            {
                $markerArray = json_encode($markerArray);
                // dd($markerArray);
            }

            return view('order.track-order', compact('order', 'markerArray'));
        }

        return redirect('order-view/'.$order->order_id);
    }

    /**
     * Get driver position
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    function getDriverPosition($orderId)
    {
        // Get driver address
        $driver = Driver::from('drivers AS D')
            ->select(['D.latitude', 'D.longitude'])
            ->join('order_delivery AS OD', 'OD.driver_id', '=', 'D.id')
            ->join('orders AS O', 'O.order_id', '=', 'OD.order_id')
            ->where(['O.order_id' => $orderId])
            ->first();

        // array('lat' => $driver->latitude, 'lng' => $driver->longitude);
        return response()->json(['status' => 1, 'driver' => $driver]);
    }

    public function random_num($size) {
        $alpha_key = '';
        $keys = range('A', 'Z');

        for ($i = 0; $i < 3; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }

        $length = $size - 3;

        $key = '';
        $keys = range(0, 9);

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $alpha_key . $key;
    }

    public function order_detail($id, Request $request){
        if(!Order::where('customer_order_id',$id)->exists()){
            return redirect()->route('page_404');
        }

        $customer = new User();
        $logged_in=0;
        $k=0;

        if(Session::has('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d')){
            if($customer->where('id',session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'))->exists()){
                $logged_in=1; 
            }
        }

        $phone = (explode("-",$request->m));

        if($logged_in == 1){
            $cust = $customer->where('id',session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'))->first();

            if(!$customer->where('phone_number_prifix', $phone[0])
                ->where('phone_number', $phone[1])->exists() && $cust->phone_number == null){
                $cust->phone_number_prifix = $phone[0];
                $cust->phone_number = $phone[1];
                $cust->save();
            }

        }else{
            if($customer->where('phone_number_prifix', $phone[0])
                ->where('phone_number', $phone[1])->exists()){
                    $cust = $customer->where('phone_number_prifix', $phone[0])
                    ->where('phone_number', $phone[1])->first();
            }else if($customer->where('email', $phone[1])
                ->where('phone_number', $phone[1])->exists()){
                    $cust = $customer->where('email', $phone[1])
                    ->where('phone_number', $phone[1])->first();
            }
            else{
                $cust = new User();
                $cust->phone_number_prifix = $phone[0];
                $cust->phone_number = $phone[1];                
                $cust->email = $phone[0].$phone[1];
                $cust->language = "ENG";
                $cust->save();

                $k=1;
            }

        }

            $customer_id = $cust->id;            
            $username = $cust->email;            

            $order =  Order::where('customer_order_id',$id);
            $order->update(['user_id' => $customer_id]);
            $order_id = $order->first()->order_id;

            $orderDetail =  new OrderDetail();
            $orderDetail->where('order_id',$order_id)->update(['user_id' => $customer_id]);
            
        $order = Order::select('orders.*','store.store_name','company.currencies')->where('customer_order_id',$id)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

        $storeDetail = Store::where('store_id', $order->store_id)->first();

        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$order->order_id)->get();

        return view('order.order-details', compact('order','orderDetails','storeDetail','username','k'));
    }

    public function cancelOrder(Request $request, $order_number){
        return view('order.order-cancel')->with('order_number',$order_number);
    }    

    public function cancelOrderPost(Request $request){
        $order = new Order();

        if($order->where('order_id',$request->order_id)->first()->cancel == 1){
            Session::flash('order_already_cancelled', 1);            
            return redirect()->route('cancel-order', $request->order_number);
        }

        Session::flash('order_already_cancelled', 0);            

        $message = '<html><body>';
        $message .= '<p style="color:#1275ff;">Order Number: ' . $request->order_number . '</p>';
        $message .= '<p style="color:#080;font-size:18px;">Mobile Number: ' . $request->mobile_number . '</p>';
        $message .= '</body></html>';

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   
        $headers .='X-Mailer: PHP/' . phpversion();
        $headers .= "From: Anar <admin@dastjar.com> \r\n"; // header of mail content

        $email = Store::where('store_id',$request->store_id)->first()->email;

        $user = User::where('id',Auth::user()->id)->first();

        if($user->phone_number == null){
            $user->update(['phone_number_prifix'=>$request->phone_number_prifix,'phone_number'=>$request->mobile_number]);
        }

        mail($email, 'Order Canceled', $message, $headers);
        $order->where('order_id',$request->order_id)->update(['cancel'=>2]);

        return redirect()->route('cancel-order', $request->order_number);
    }

    public function updateBrowser(Request $request){
        $cust = User::where('email',$request->email)->first();
        $cust->browser = $request->browser . " ";
        $cust->save();
    }

    /**
     * Proceed cart if logged-in or data from session else store data into session or show restaurant listing
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function cart(Request $request){
        // Get orderData either from 'session' (right after login) or 'post' (if alreadt logged-in)
        $data = array();

        if( Session::has('orderData') )
        {
            $data = Session::get('orderData');
            Session::forget('orderData');
        }
        elseif( !empty($request->input()) )
        {
            $data = $request->input();
        }

        // 
        if( Auth::check() && !empty($data) )
        {
            $orderInvoice = array();
            $i = 0;
            $total_price = $final_order_total = 0;
            $max_time = "00:00:00";
            
            if($request->session()->get('order_date') != null){
                $pieces = explode(" ", $request->session()->get('order_date'));
                $date=date_create($pieces[3]."-".$pieces[1]."-".$pieces[2]);
                $checkOrderDate = date_format($date,"Y-m-d");
                $orderType = 'eat_later';
                $orderDate = $pieces[0]." ".$pieces[1]." ".$pieces[2]." ".$pieces[3];
                $orderTime = $pieces[4];
                $request->session()->forget('order_date');
            }else{
                $pieces = explode(" ", $data['browserCurrentTime']);
                $date=date_create($pieces[3]."-".$pieces[1]."-".$pieces[2]);
                $checkOrderDate = date_format($date,"Y-m-d");
                $orderType = 'eat_now';
                $orderDate = $pieces[0]." ".$pieces[1]." ".$pieces[2]." ".$pieces[3];
                $orderTime = $pieces[4];
            }

            // Get store detail
            $storeDetail = $storedetails = Store::where('store_id', $data['storeID'])->first();

            // Get loyalty detail and count of 'loyalty' used by user if exist for store
            $promotionLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', DB::raw('GROUP_CONCAT(dish_type_id) AS dish_type_ids')])
                ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
                ->where(['PL.store_id' => Session::get('storeId'), 'PL.status' => '1'])
                ->where('PL.start_date', '<=', Carbon::now()->format('Y-m-d h:i:00'))
                ->where('PL.end_date', '>=', Carbon::now()->format('Y-m-d h:i:00'))
                ->groupBy('PL.id')
                ->first();

            if($promotionLoyalty)
            {
                // Get count of 'loyalty' used number of times
                $orderCustomerLoyalty = OrderCustomerLoyalty::from('order_customer_loyalty AS OCL')
                    ->select([DB::raw('COUNT(OCL.id) AS cntLoyaltyUsed')])
                    ->join('orders', 'orders.order_id', '=', 'OCL.order_id')
                    ->where(['OCL.customer_id' => Auth::id(), 'OCL.loyalty_id' => $promotionLoyalty->id])
                    ->where('orders.online_paid', '!=', 2)
                    ->first();
            }
            
            $loyaltyProducts = array();

            // Create Order and Order Detail
            foreach ($data['product'] as $key => $value) {
                //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                if($value['prod_quant'] != '0'){
                    $productTime = Product::select('dish_type','preparation_Time','company_id')->whereProductId($value['id'])->first();

                    // 'If' create order
                    if($i == 0){
                        $order =  new Order();
                        $order->customer_order_id = $this->random_num(6);
                        $order->user_id = Auth::id();
                        $order->store_id = $data['storeID'];
                        $order->company_id = $productTime->company_id;
                        $order->order_type = $orderType;
                        $order->user_type = 'customer';
                        $order->deliver_date = $orderDate;
                        $order->deliver_time = $orderTime;
                        $order->check_deliveryDate = $checkOrderDate;

                        if( isset($data['delivery_type']) && is_numeric($data['delivery_type']) )
                        {
                            $order->delivery_type = $data['delivery_type'];
                        }

                        if($storeDetail->order_response == 0 && $orderType == 'eat_now')
                        {
                            $order->order_response = 0;
                            $order->order_accepted = 0;
                        }

                        $order->save();
                        $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                        $orderId = $orders->order_id;
                        $i = $i+1;
                    }

                    // Create order_detail
                    $i = 1;
                    if($max_time < $productTime->preparation_Time){
                        $max_time = $productTime->preparation_Time;
                    }

                    $productPrice = ProductPriceList::select('price')
                        ->whereProductId($value['id'])
                        ->where('store_id' , $data['storeID'])
                        ->where('publishing_start_date','<=',Carbon::now())
                        ->where('publishing_end_date','>=',Carbon::now())
                        ->first();
                    $total_price = $total_price + ($productPrice->price * $value['prod_quant']); 
                    $orderDetail =  new OrderDetail();
                    $orderDetail->order_id = $orders->order_id;
                    $orderDetail->user_id = Auth::id();
                    $orderDetail->product_id = $value['id'];
                    $orderDetail->product_quality = $value['prod_quant'];
                    $orderDetail->product_description = $value['prod_desc'];
                    $orderDetail->price = $productPrice->price;
                    $orderDetail->time = $productTime->preparation_Time;
                    $orderDetail->company_id = $productTime->company_id;
                    $orderDetail->store_id = $data['storeID'];
                    $orderDetail->delivery_date = $checkOrderDate;
                    $orderDetail->save();

                    // Check if loyalty exist and if product belongs to associated dish_type 
                    if( $promotionLoyalty && (!$promotionLoyalty->validity || ($promotionLoyalty->validity > $orderCustomerLoyalty->cntLoyaltyUsed)) )
                    {
                        if( in_array($productTime->dish_type, explode(',', $promotionLoyalty->dish_type_ids)) )
                        {
                            OrderDetail::where(['id' => $orderDetail->id])->update(['loyalty_id' => $promotionLoyalty->id]);

                            $loyaltyProducts[] = array('id' => $orderDetail->id, 'price' => $productPrice->price, 'qty' => $value['prod_quant']);
                        }
                    }
                }
            }

            // Update order_total, delivery_time and 'online_paid' => 2 (default)
            $final_order_total = $total_price;

            DB::table('orders')->where('order_id', $orderId)->update([
                'order_delivery_time' => $max_time,
                'order_total' => $total_price,
                'final_order_total' => $final_order_total,
                'online_paid' => 2
            ]);

            // Start applying discount rule
            if( !empty($loyaltyProducts) )
            {
                // Get customer loyalty for order
                $customerLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                    ->select(['OD.loyalty_id', DB::raw('SUM(OD.product_quality) AS quantity_bought')])
                    ->join('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
                    ->join('orders', 'orders.order_id', '=', 'OD.order_id')
                    ->where('PL.id', $promotionLoyalty->id)
                    ->where('OD.user_id', Auth::id())
                    ->whereRaw("(orders.online_paid != 2 OR orders.order_id = {$orderId})")
                    ->first();

                if($customerLoyalty)
                {
                    $quantity_to_buy = $promotionLoyalty->quantity_to_buy;
                    $quantity_get = $promotionLoyalty->quantity_get;
                    $quantity_bought = $customerLoyalty->quantity_bought;

                    // Calculate if 'loyalty' already have been applied
                    // $quantity_bought -= ($quantity_to_buy*$orderCustomerLoyalty->cnt);

                    // Check if eligible to get free item quantity, and update final_total
                    if($quantity_to_buy < $quantity_bought)
                    {
                        // Get offered quantity on applied loyalty
                        $quantity_offered = floor($quantity_bought/$quantity_to_buy)*$quantity_get;

                        // Calculate min price to be deducted and update final_total
                        $productPrices = array_column($loyaltyProducts, 'price');
                        $index = array_search(min($productPrices), $productPrices, true);
                        $itemMinPrice = $loyaltyProducts[$index]['price'];
                        $loyalty_discount = $itemMinPrice * $quantity_offered;
                        $final_order_total -= $loyalty_discount;
                        $orderInvoice['loyalty_quantity_free'] = $quantity_offered;
                        $orderInvoice['loyaltyOfferApplied'] = __('messages.loyaltyOfferApplied', ['loyalty_quantity_free' => $quantity_offered]);

                        // Insert into customer loyalty
                        OrderCustomerLoyalty::create(['customer_id' => Auth::id(), 'loyalty_id' =>$promotionLoyalty->id, 'order_id' => $orderId]);

                        // Update 'quantity_free' in order_detail
                        OrderDetail::where(['id' => $loyaltyProducts[$index]['id']])->update(['quantity_free' => $quantity_offered]);
                    }
                }
            }

            // If final total exists, then check for discount
            $customerDiscount = null;

            if($final_order_total > 0)
            {
                // Get discount if user has applied
                $todayDate = Carbon::now()->format('Y-m-d h:i:00');
                $customerDiscount = PromotionDiscount::from('promotion_discount AS PD')
                    ->select(['PD.id', 'PD.code', 'PD.discount_value'])
                    ->join('customer_discount AS CD', 'CD.discount_id', '=', 'PD.id')
                    ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.status' => '1', 'PD.store_id' => Session::get('storeId')])
                    ->where('PD.start_date', '<=', $todayDate)
                    ->where('PD.end_date', '>=', $todayDate)
                    ->first();

                if($customerDiscount)
                {
                    // Apply discount on order
                    if( !OrderCustomerDiscount::where(['order_id' => $orderId])->count() )
                    {
                        OrderCustomerDiscount::create(['order_id' => $orderId, 'discount_id' =>$customerDiscount->id]);

                        // Update final_total
                        $discount = ($final_order_total*$customerDiscount->discount_value)/100;
                        $final_order_total -= $discount;
                        $orderInvoice['discount'] = $discount;
                    }
                }
            }
            // End apply discount
            
            // Start: Home delivery
            $homeDelivery = array('delivery_charge' => NULL);
            $store_delivery_type = StoreDeliveryType::where('store_id', Session::get('storeId'))
                ->pluck('delivery_type')
                ->toArray();
            
            // Check if store support 'home delivery'
            if( !empty($store_delivery_type) && in_array(3, $store_delivery_type) && Helper::isPackageSubscribed(12) )
            {
                $option = array('final_order_total' => $final_order_total);
                $homeDelivery = $this->applyHomeDeliveryPriceModel($option);
                $final_order_total = $homeDelivery['final_order_total'];
            }

            $orderInvoice['homeDelivery'] = $homeDelivery;
            // End

            // Update final_total for order
            if( $final_order_total != $total_price )
            {
                Order::where('order_id', $orderId)
                    ->update(['final_order_total' => $final_order_total, 'delivery_charge' => $homeDelivery['delivery_charge']]);
            }

            //
            // User::where('id',Auth::id())->update(['browser' => $data['browser']]);
            $agent = $request->server('HTTP_USER_AGENT');
            User::where('id',Auth::id())->update(['browser' => $agent]);

            //
            $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
            
            $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name','order_details.product_id')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

            $user = User::find(Auth::id());

            $request->session()->put('currentOrderId', $order->order_id);

            //If store support ontine payment then if condition run.
            if($storeDetail->online_payment == 1){
                $request->session()->put('paymentmode',1);
                $request->session()->put('paymentAmount', $order->final_order_total);
                $request->session()->put('OrderId', $order->order_id);

                /*$companyDetail = Company::where('company_id', $productTime->company_id)->first();
                $companyUserDetail = Admin::where('u_id', $companyDetail->u_id)->first();*/
                $companyUserDetail = CompanySubscriptionDetail::where('company_id', $productTime->company_id)->first();

                if(isset($companyUserDetail->stripe_user_id) && !is_null($companyUserDetail->stripe_user_id))
                {
                    $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);
                }
            }else{
                $request->session()->put('paymentmode',0);
            }

            // Get customer's Stripe 'PaymentMethod'
            $paymentMethod = array();
            if( !is_null($user->stripe_customer_id) )
            {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                $paymentMethod = \Stripe\PaymentMethod::all(["customer" => $user->stripe_customer_id, "type" => "card"]);
            }

            return view('order.cart', compact('order','orderDetails', 'customerDiscount', 'user', 'orderInvoice', 'storedetails', 'store_delivery_type', 'paymentMethod'));
        }
        else
        {
            // If not logged-in, put data into session else, list restaurant
            if( !empty($request->input()) )
            {
                $data = $request->input();
                Session::put('orderData', $data);
                return redirect()->route('customer-login');
            }
            else
            {
                $todayDate = $request->session()->get('browserTodayDate');
                $currentTime = $request->session()->get('browserTodayTime');
                $todayDay = $request->session()->get('browserTodayDay');

                if($request->session()->get('with_login_lat') == null){
                    $lat = $request->session()->get('with_out_login_lat');
                }else{
                    $lat = $request->session()->get('with_login_lat');
                }

                if($request->session()->get('with_login_lng') == null){
                    $lng = $request->session()->get('with_out_login_lng');
                }else{
                    $lng = $request->session()->get('with_login_lng');
                }
                
                $userDetail = User::whereId(Auth()->id())->first();

                if(!isset($userDetail->range)){
                  $range = 10;
                }else{
                    $range = $userDetail->range;
                }

                $companydetails = Store::getListRestaurants($lat,$lng,$range,'1','3',$todayDate,$currentTime,$todayDay);
                
                return view('index', compact('companydetails'));
            }
        }
    }

    // Page to test Stripe SCA
    function cartScaTest()
    {
        return view('order.cart-sca-test');
    }

    /**
     * View cart function for testing
     */
    function viewCart(Request $request, $orderId)
    {
        // 
        $orderInvoice = array();

        // Get order detail and calculate total and other discount
        $orderDetail = OrderDetail::from('order_details AS OD')
            ->select(['OD.product_quality', 'OD.price', 'P.dish_type'])
            ->join('product AS P', 'P.product_id', '=', 'OD.product_id')
            ->where(['OD.order_id' => $orderId])
            ->get();

        $storeDetail = $storedetails = Store::where('store_id', Session::get('storeId'))->first();

        if($orderDetail)
        {
            // Get loyalty detail and count of 'loyalty' used by user if exist for store
            $promotionLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', DB::raw('GROUP_CONCAT(dish_type_id) AS dish_type_ids')])
                ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
                ->where(['PL.store_id' => Session::get('storeId'), 'PL.status' => '1'])
                ->where('PL.start_date', '<=', Carbon::now()->format('Y-m-d h:i:00'))
                ->where('PL.end_date', '>=', Carbon::now()->format('Y-m-d h:i:00'))
                ->groupBy('PL.id')
                ->first();

            if($promotionLoyalty)
            {
                // Get count of 'loyalty' used number of times
                $orderCustomerLoyalty = OrderCustomerLoyalty::from('order_customer_loyalty AS OCL')
                    ->select([DB::raw('COUNT(OCL.id) AS cntLoyaltyUsed')])
                    ->join('orders', 'orders.order_id', '=', 'OCL.order_id')
                    ->where(['OCL.customer_id' => Auth::id(), 'OCL.loyalty_id' => $promotionLoyalty->id])
                    ->where('orders.online_paid', '!=', 2)
                    ->first();
            }

            $loyaltyProducts = array();

            $order_total = $final_order_total = 0;

            foreach($orderDetail as $row)
            {
                $order_total = $order_total + ($row->price * $row->product_quality);

                // Check if loyalty exist and if product belongs to associated dish_type 
                if( $promotionLoyalty && (!$promotionLoyalty->validity || ($promotionLoyalty->validity > $orderCustomerLoyalty->cntLoyaltyUsed)) )
                {
                    if( in_array($row->dish_type, explode(',', $promotionLoyalty->dish_type_ids)) )
                    {
                        $loyaltyProducts[] = array('id' => $row->id, 'price' => $row->price, 'qty' => $row->product_quality);
                    }
                }
            }

            $final_order_total = $order_total;

            // Start applying discount rule
            if( !empty($loyaltyProducts) )
            {
                // Get customer loyalty for order
                $customerLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                    ->select(['OD.loyalty_id', DB::raw('SUM(OD.product_quality) AS quantity_bought')])
                    ->join('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
                    ->join('orders', 'orders.order_id', '=', 'OD.order_id')
                    ->where('PL.id', $promotionLoyalty->id)
                    ->where('OD.user_id', Auth::id())
                    ->whereRaw("(orders.online_paid != 2 OR orders.order_id = {$orderId})")
                    ->first();

                // echo '<pre>'; print_r($customerLoyalty->toArray()); exit;

                if($customerLoyalty)
                {
                    $quantity_to_buy = $promotionLoyalty->quantity_to_buy;
                    $quantity_get = $promotionLoyalty->quantity_get;
                    $quantity_bought = $customerLoyalty->quantity_bought;

                    // Calculate if 'loyalty' already have been applied
                    // $quantity_bought -= ($quantity_to_buy*$orderCustomerLoyalty->cntLoyaltyUsed);

                    // Check if eligible to get free item quantity, and update final_total
                    if($quantity_to_buy < $quantity_bought)
                    {
                        // Get offered quantity on applied loyalty
                        $quantity_offered = floor($quantity_bought/$quantity_to_buy)*$quantity_get;

                        // Calculate min price to be deducted and update final_total
                        $productPrices = array_column($loyaltyProducts, 'price');
                        $index = array_search(min($productPrices), $productPrices, true);
                        $itemMinPrice = $loyaltyProducts[$index]['price'];
                        $loyalty_discount = $itemMinPrice * $quantity_offered;
                        $final_order_total -= $loyalty_discount;
                        $orderInvoice['loyalty_quantity_free'] = $quantity_offered;
                        $orderInvoice['loyaltyOfferApplied'] = __('messages.loyaltyOfferApplied', ['loyalty_quantity_free' => $quantity_offered]);
                    }
                }
            }
        }

        // If final total exists, then check for discount
        $customerDiscount = null;

        if($final_order_total > 0)
        {
            // Get discount if user has applied
            $todayDate = Carbon::now()->format('Y-m-d h:i:00');
            $customerDiscount = PromotionDiscount::from('promotion_discount AS PD')
                ->select(['PD.id', 'PD.code', 'PD.discount_value'])
                ->join('customer_discount AS CD', 'CD.discount_id', '=', 'PD.id')
                ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.status' => '1', 'PD.store_id' => Session::get('storeId')])
                ->where('PD.start_date', '<=', $todayDate)
                ->where('PD.end_date', '>=', $todayDate)
                ->first();

            if($customerDiscount)
            {
                $discount = ($final_order_total*$customerDiscount->discount_value)/100;
                $final_order_total -= $discount;
                $orderInvoice['discount'] = $discount;
            }
        }

        // Start: Home delivery
        $homeDelivery = array('delivery_charge' => NULL);
        $store_delivery_type = StoreDeliveryType::where('store_id', Session::get('storeId'))
            ->pluck('delivery_type')
            ->toArray();
        
        // Check if store support 'home delivery'
        if( !empty($store_delivery_type) && in_array(3, $store_delivery_type) && Helper::isPackageSubscribed(12) )
        {
            $option = array('final_order_total' => $final_order_total);
            $homeDelivery = $this->applyHomeDeliveryPriceModel($option);
            $final_order_total = $homeDelivery['final_order_total'];
        }

        $orderInvoice['homeDelivery'] = $homeDelivery;
        // End

        //
        $order = Order::select('orders.*','store.store_name','company.currencies', 'company.company_id')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
            
        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name','order_details.product_id')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

        $user = User::find(Auth::id());

        //If store support ontine payment then if condition run.
        if( isset($storeDetail->online_payment) && $storeDetail->online_payment == 1 ){
            $request->session()->put('paymentmode',1);
            $request->session()->put('paymentAmount', $order->final_order_total);
            $request->session()->put('OrderId', $order->order_id);
            $request->session()->put('paymentAmount', $final_order_total);

            $companyUserDetail = CompanySubscriptionDetail::where('company_id', $order->company_id)->first();

            if(isset($companyUserDetail->stripe_user_id))
            {
                $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);
            }
        }
        else
        {
            $request->session()->put('paymentmode',0);
            // $request->session()->put('paymentmode',1);
        }

        // Get customer's Stripe 'PaymentMethod'
        $paymentMethod = array();
        if( !is_null($user->stripe_customer_id) )
        {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $paymentMethod = \Stripe\PaymentMethod::all(["customer" => $user->stripe_customer_id, "type" => "card"]);
        }

        /*$customerLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
            ->select(['OD.loyalty_id', DB::raw('SUM(OD.product_quality) AS quantity_bought')])
            ->join('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
            ->join('orders', 'orders.order_id', '=', 'OD.order_id')
            ->where(['PL.id' => $promotionLoyalty->id, 'OD.user_id' => Auth::id()])
            ->where('orders.online_paid', '!=', 2)
            ->where('OD.loyalty_id', '!=', null)
            ->groupBy('OD.loyalty_id')
            ->toSql();*/
        // echo '<pre>'; print_r($orderInvoice); exit;

        return view('order.cart', compact('order','orderDetails', 'user', 'customerDiscount', 'orderInvoice', 'storedetails', 'store_delivery_type', 'paymentMethod'));
    }

    /**
     * Apply home delivery and return
     * @param  [type] $option [description]
     * @return [type]         [description]
     */
    function applyHomeDeliveryPriceModel($option)
    {
        // Default
        $homeDelivery = array(
            'is_home_delivery_eligible' => 1,
            'delivery_charge' => NULL,
            'final_order_total' => $option['final_order_total']
        );

        // Get store 'Delivery price model'
        $storeDeliveryPrice = StoreDeliveryPriceModel::select(['delivery_rule_id', 'delivery_charge', 'threshold'])
            ->where(['store_id' => Session::get('storeId'), 'status' => '1'])
            ->first();

        if($storeDeliveryPrice)
        {
            // Fixed 'delivery price'
            if($storeDeliveryPrice->delivery_rule_id == 1)
            {
                $homeDelivery['delivery_charge'] = $storeDeliveryPrice->delivery_charge;
                $homeDelivery['final_order_total'] = $option['final_order_total'] + $homeDelivery['delivery_charge'];
            }
            // delivery price if 'order total' not passes the threshold
            elseif($storeDeliveryPrice->delivery_rule_id == 2 && $option['final_order_total'] < $storeDeliveryPrice->threshold)
            {
                $homeDelivery['delivery_charge'] = $storeDeliveryPrice->delivery_charge;
                $homeDelivery['final_order_total'] = $option['final_order_total'] + $homeDelivery['delivery_charge'];
            }
            // Can't deliver if 'order total' not passes the threshold
            elseif($storeDeliveryPrice->delivery_rule_id == 3 && $option['final_order_total'] < $storeDeliveryPrice->threshold)
            {
                $homeDelivery['is_home_delivery_eligible'] = 0;
            }
        }

        return $homeDelivery;
    }

    /**
     * Update order delivery type from cart page before make payment or confirm order
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function orderUpdateDeliveryType(Request $request)
    {
        $status = 0;

        if( Order::where('order_id', $request->input('order_id'))->update(['delivery_type' => $request->input('delivery_type')]) )
        {
            $status = 1;
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Get 'home delivery' content (addresses etc. if eligible) on cart page
     * @param  [type] $order_id [description]
     * @return [type]           [description]
     */
    function getHomeDeliveryPartContent($order_id)
    {
        $html = '';

        // Get order
        $order = Order::select(['orders.final_order_total', 'company.currencies'])
            ->join('company','orders.company_id', '=', 'company.company_id')
            ->where(['orders.order_id' => $order_id])
            ->first();
        
        $is_home_delivery_eligible = 1;

        // Get store 'Delivery price model'
        $storeDeliveryPrice = StoreDeliveryPriceModel::select(['delivery_rule_id', 'delivery_charge', 'threshold'])
            ->where(['store_id' => Session::get('storeId'), 'status' => '1'])
            ->first();

        if($storeDeliveryPrice)
        {
            // Add order value when a home delivery is possible
            if($storeDeliveryPrice->delivery_rule_id == 3 && $order->final_order_total < $storeDeliveryPrice->threshold)
            {
                $is_home_delivery_eligible = 0;
            }
        }

        // If 'home_delivery eligible'
        if($is_home_delivery_eligible)
        {
            // Get user address
            $userAddresses = UserAddress::where(['customer_id' => Auth::id()])
                ->get();

            if($userAddresses)
            {
                $html .= '
                <form method="post" id="frm-user-address" data-ajax="false">
                    <div class="ui-grid-a">';
                
                $i = 1;
                foreach($userAddresses as $address)
                {
                    $strAddress = Helper::convertAddressToStr($address);
                    $cls = ($i % 2 != 0) ? 'ui-block-a' : 'ui-block-b';
                    
                    $html .= "
                    <div class='{$cls}'>
                        <div class='ui-bar ui-bar-a'>
                            <div class='ui-radio'>
                                <label for='{$address->id}' class='ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-radio-off'>{$strAddress}</label>
                                <input type='radio' name='user_address_id' id='{$address->id}' value='{$address->id}'>
                            </div>
                        </div>
                    </div>";

                    $i++;
                }

                $html .= '
                    </div>
                </form>';
            }

            $html .= '
            <div class="ui-grid-solo">
                <div class="ui-block-a">
                    <div id="add-new-address" data-role="collapsible">
                        <h4>'.__('messages.addAddress').'</h4>
                        <div class="add-address-form">
                            <div class="ui-bar ui-bar-a">
                                <form method="post" id="save-address" data-ajax="false">
                                    <input type="text" name="full_name" id="full_name" placeholder="'.__('messages.fullName').'*" data-mini="true" data-rule-required="true" data-msg-required="'.__('messages.fieldRequired').'">
                                    <input type="text" name="mobile" id="mobile" placeholder="'.__('messages.mobileNumber').'*" data-mini="true" data-rule-required="true" data-rule-number="true" data-msg-required="'.__('messages.fieldRequired').'" data-msg-number="'.__('messages.fieldNumber').'">
                                    <input type="text" name="address" id="address" placeholder="'.__('messages.address1').'" data-mini="true">
                                    <input type="text" name="street" id="street" placeholder="'.__('messages.address2').'*" data-mini="true" data-rule-required="true" data-msg-required="'.__('messages.fieldRequired').'">
                                    <input type="text" name="zipcode" id="zipcode" placeholder="Zipcode" data-mini="true" data-rule-number="true" data-msg-number="'.__('messages.fieldNumber').'">
                                    <input type="text" name="city" id="city" placeholder="'.__('messages.city').'*" data-mini="true" data-rule-required="true" data-msg-required="'.__('messages.fieldRequired').'">
                                    <input type="text" name="country" id="country" placeholder="'.__('messages.country').'*" data-mini="true" data-rule-required="true" data-msg-required="'.__('messages.fieldRequired').'">
                                    <fieldset data-role="controlgroup">
                                        <label for="is_permanent">'.__('messages.saveAddress').'</label>
                                        <input type="checkbox" name="is_permanent" value="1" checked="" id="is_permanent">
                                    </fieldset>
                                    <input type="submit" data-inline="true" value="'.__('messages.save').'" data-theme="b">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
        else
        {
            $threshold = $storeDeliveryPrice->threshold.' '.$order->currencies;
            $html .= __('messages.homeDeliveryNotEligible', ['threshold' => $threshold]);
        }

        // Return
        return response()->json(['html' => $html]);
    }

    /**
     * Save user address
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function saveUserAddress(Request $request)
    {
        // Validation
        $this->validate($request, [
            'full_name' => 'required',
            'mobile'    => 'required|numeric',
            'street'    => 'required',
            'zipcode'   => 'nullable|numeric',
            'city'      => 'required',
            'country'   => 'required',
        ]);

        // Create
        $status = 0; $addresses = '';
        $data = $request->only(['full_name', 'mobile', 'zipcode', 'address', 'street', 'city', 'country', 'is_permanent']);
        $data['customer_id'] = Auth::id();

        if(UserAddress::create($data))
        {
            $status = 1;
        }

        // Return
        return response()->json(['status' => $status]);
    }

    /**
     * Update order user address from cart page (if Home Delivery) before confirm order
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function updateOrderUserAddress(Request $request)
    {
        $status = 0;
        $msg = __('messages.homeDeliveryNotInRange');

        // Check if delivery address is in store delivery range
        $store = Store::select(['street', 'city', 'zip', 'country', 'delivery_range'])
            ->where('store_id', Session::get('storeId'))->first();
        $origin = $store->street.', '.$store->city.', '.$store->zip.', '.$store->country;

        $userAddress = UserAddress::where('id', $request->input('user_address_id'))
            ->first();
        $destination = $userAddress->street.', '.$userAddress->city.', '.$userAddress->zipcode.', '.$userAddress->country;

        // Get distance b/w origin and destination
        $response = Helper::getDrivingDistance($origin, $destination, 'address');
        
        if($response['status'] == 'OK')
        {
            $distance = (int)$response['distance']['text'];

            if($store->delivery_range > $distance)
            {
                Order::where('order_id', $request->input('order_id'))->update(['user_address_id' => $request->input('user_address_id')]);
                $status = 1;
            }
        }

        return response()->json(['status' => $status, 'msg' => $msg, 'response' => $response]);
    }

    /**
     * Update cart
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateCart(Request $request){
        $data = $request->input();
        $orderInvoice = array();
        $deleteWholecart = 0; $status = 0;

        if($data['productId'])
        {
            $orderId = $data['orderid'];

            // Update order_detail quantity or delete if quantity is 0
            if($data['qty'] != 0)
            {
                OrderDetail::where(['order_id' => $data['orderid'], 'product_id' => $data['productId']])
                    ->update(['product_quality' => $data['qty']]);
            }
            else
            {
                // Update order_detail quantity
                OrderDetail::where(['order_id' => $data['orderid'], 'product_id' => $data['productId']])
                    ->delete();
            }

            // Get order
            $order = Order::select(['delivery_type'])
                ->where(['order_id' => $data['orderid']])
                ->first();

            // Get order detail and calculate total and other discount
            $orderDetail = OrderDetail::from('order_details AS OD')
                ->select(['OD.id', 'OD.product_quality', 'OD.price', 'P.dish_type'])
                ->join('product AS P', 'P.product_id', '=', 'OD.product_id')
                ->where(['OD.order_id' => $data['orderid']])
                ->get();

            if($orderDetail)
            {
                $status = 1;

                // Get loyalty detail and count of 'loyalty' used by user if exist for store
                $promotionLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                    ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', DB::raw('GROUP_CONCAT(dish_type_id) AS dish_type_ids')])
                    ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
                    ->where(['PL.store_id' => Session::get('storeId'), 'PL.status' => '1'])
                    ->where('PL.start_date', '<=', Carbon::now()->format('Y-m-d h:i:00'))
                    ->where('PL.end_date', '>=', Carbon::now()->format('Y-m-d h:i:00'))
                    ->groupBy('PL.id')
                    ->first();

                if($promotionLoyalty)
                {
                    // Get count of 'loyalty' used number of times
                    $orderCustomerLoyalty = OrderCustomerLoyalty::from('order_customer_loyalty AS OCL')
                        ->select([DB::raw('COUNT(OCL.id) AS cntLoyaltyUsed')])
                        ->join('orders', 'orders.order_id', '=', 'OCL.order_id')
                        ->where(['OCL.customer_id' => Auth::id(), 'OCL.loyalty_id' => $promotionLoyalty->id])
                        ->where('orders.online_paid', '!=', 2)
                        ->first();
                }
                
                $is_order_customer_loyalty = 0;
                $loyaltyProducts = array();

                $order_total = $final_order_total = 0;

                foreach($orderDetail as $row)
                {
                    $order_total = $order_total + ($row->price * $row->product_quality);

                    // Check if loyalty exist and if product belongs to associated dish_type 
                    if( $promotionLoyalty && (!$promotionLoyalty->validity || ($promotionLoyalty->validity > $orderCustomerLoyalty->cntLoyaltyUsed)) )
                    {
                        if( in_array($row->dish_type, explode(',', $promotionLoyalty->dish_type_ids)) )
                        {
                            $loyaltyProducts[] = array('id' => $row->id, 'price' => $row->price, 'qty' => $row->product_quality);
                        }
                    }
                }

                $final_order_total = $order_total;

                // Start applying discount rule
                if( !empty($loyaltyProducts) )
                {
                    // Get customer loyalty for order
                    $customerLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                        ->select(['OD.loyalty_id', DB::raw('SUM(OD.product_quality) AS quantity_bought')])
                        ->join('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
                        ->join('orders', 'orders.order_id', '=', 'OD.order_id')
                        ->where('PL.id', $promotionLoyalty->id)
                        ->where('OD.user_id', Auth::id())
                        ->whereRaw("(orders.online_paid != 2 OR orders.order_id = {$orderId})")
                        ->first();

                    if($customerLoyalty)
                    {
                        $quantity_to_buy = $promotionLoyalty->quantity_to_buy;
                        $quantity_get = $promotionLoyalty->quantity_get;
                        $quantity_bought = $customerLoyalty->quantity_bought;

                        // Calculate if 'loyalty' have already been applied
                        // $quantity_bought -= ($quantity_to_buy*$orderCustomerLoyalty->cntLoyaltyUsed);

                        // Check if eligible to get free item quantity, and update final_total
                        if($quantity_to_buy < $quantity_bought)
                        {
                            // Get offered quantity on applied loyalty
                            $quantity_offered = floor($quantity_bought/$quantity_to_buy)*$quantity_get;

                            // Calculate min price to be deducted and update final_total
                            $productPrices = array_column($loyaltyProducts, 'price');
                            $index = array_search(min($productPrices), $productPrices, true);
                            $itemMinPrice = $loyaltyProducts[$index]['price'];
                            $loyalty_discount = $itemMinPrice * $quantity_offered;
                            $final_order_total -= $loyalty_discount;
                            $orderInvoice['loyalty_quantity_free'] = $quantity_offered;
                            $orderInvoice['loyaltyOfferApplied'] = __('messages.loyaltyOfferApplied', ['loyalty_quantity_free' => $quantity_offered]);

                            // Insert into customer loyalty
                            if( !OrderCustomerLoyalty::where(['order_id' => $orderId])->count() )
                            {
                                OrderCustomerLoyalty::create(['customer_id' => Auth::id(), 'loyalty_id' =>$promotionLoyalty->id, 'order_id' => $orderId]);
                            }

                            // Update 'quantity_free' in order_detail
                            OrderDetail::where(['id' => $loyaltyProducts[$index]['id']])->update(['quantity_free' => $quantity_offered]);

                            $is_order_customer_loyalty = 1;
                        }
                    }
                }

                // Delete 'OrderCustomerLoyalty' if exist and not applying on order after update cart
                if(!$is_order_customer_loyalty)
                {
                    // Delete 'OrderCustomerLoyalty' if not applying
                    if( OrderCustomerLoyalty::where(['order_id' => $orderId])->count() )
                    {
                        OrderCustomerLoyalty::where(['order_id' => $orderId])->delete();
                    }

                    // Reset 'free_quantity' for in 'order_detail'
                    OrderDetail::where('order_id', $orderId)
                        ->where('quantity_free', '<>', 0)
                        ->update(['quantity_free' => 0]);
                }

                // Get discount if order has applied
                if($final_order_total > 0)
                {
                    $customerDiscount = PromotionDiscount::from('promotion_discount AS PD')
                        ->select(['PD.id', 'PD.code', 'PD.discount_value'])
                        ->join('order_customer_discount AS OCD', 'OCD.discount_id', '=', 'PD.id')
                        ->where(['order_id' => $data['orderid']])
                        ->first();

                    if($customerDiscount)
                    {
                        // Update final_total
                        $discount = ($final_order_total*$customerDiscount->discount_value)/100;
                        $final_order_total -= $discount;
                        $orderInvoice['discount'] = $discount;
                    }
                }
                // End apply discount
                
                // Start: Home delivery
                $homeDelivery = array('delivery_charge' => NULL);
                $store_delivery_type = StoreDeliveryType::where('store_id', Session::get('storeId'))
                    ->pluck('delivery_type')
                    ->toArray();
                
                // Check if store support 'home delivery', and order's 'delivery_type' is 'home delivery'
                if( !empty($store_delivery_type) && in_array(3, $store_delivery_type) && $order->delivery_type == 3 )
                {
                    $option = array('final_order_total' => $final_order_total);
                    $homeDelivery = $this->applyHomeDeliveryPriceModel($option);
                    $final_order_total = $homeDelivery['final_order_total'];
                }

                $orderInvoice['homeDelivery'] = $homeDelivery;
                // End

                // Update order_total and final_order_total
                Order::where(['order_id' => $data['orderid']])
                    ->update(['order_total' => $order_total, 'final_order_total' => $final_order_total, 'delivery_charge' => $homeDelivery['delivery_charge']]);
                
                $orderInvoice['order_total'] = $order_total;
                $orderInvoice['final_order_total'] = $final_order_total;

                $request->session()->put('paymentAmount', $final_order_total);
            }
            else
            {
                $deleteWholecart = 1;
            }
        }
        else
        {
            $deleteWholecart = 1;
        }

        if($deleteWholecart)
        {
            $request->session()->forget('paymentAmount');
            $this->deleteWholecart($data['orderid']);
        }

        $data = array('orderInvoice' => $orderInvoice);
        return response()->json(['status' => $status, 'response' => true, 'data'=> $data]);
    }

    public function emptyCart(Request $request){

        $data = $request->input();

        $this->deleteWholecart($data['orderid']);
        $url=$request->session()->get('route_url');

        if (strpos($url, 'selectOrder-date') !=false){
            return redirect()->action('HomeController@selectOrderDate');
        }elseif(strpos($url, 'eat-now') !=false){
            return redirect()->action('HomeController@index');
        }
        else {
            return redirect()->action('HomeController@eatLater');
        }
    }

    public function deleteWholecart($orderid){
        DB::table('orders')->where('order_id', $orderid)->delete();
        DB::table('order_details')->where('order_id', $orderid)->delete();
        DB::table('order_customer_discount')->where('order_id', $orderid)->delete();
        DB::table('order_customer_loyalty')->where('order_id', $orderid)->delete();
    }

    /**
     * Check and apply if promocode is applicable for store
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    /*public function ajaxApplyPromocode(Request $request)
    {
        $status = 0;

        // If storeId exist in session and then check for promocode
        if( Session::has('storeId') )
        {
            $data = $request->input();
            $storeId = Session::get('storeId');

            // Get the order date in UTC
            if( Session::has('order_date') )
            {
                $orderDate = substr(Session::get('order_date'), 0, strpos(Session::get('order_date'), '('));
                $orderDate = date('Y-m-d H:i:00', strtotime($orderDate));
            }
            else
            {
                $orderDate = Carbon::now()->format('Y-m-d h:i:00');
            }
            
            // Get promocode if exist
            $promoCode = PromotionDiscount::select(['id', 'discount_value'])
                ->where(['code' => $data['code'], 'status' => 1, 'store_id' => Session::get('storeId')])
                ->where('start_date', '<=', $orderDate)
                ->where('end_date', '>=', $orderDate)
                ->first();

            if($promoCode)
            {
                $status = 1;
            }
        }

        return response()->json(['status' => $status]);
    }*/
}
