<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Admin;
use App\Order;
use App\OrderDetail;
use App\ProductPriceList;
use App\Product;
use App\DishType;
use App\Company;
use App\Helper;
use DB;
use Carbon\Carbon;
use App\Store;
use App\Employer;
use Session;
use App\User;
use App\Payment;
use App\Coupon;
use App\CouponKeywordsLangList;
use App\C_S_Rel;
use App\CouponOfferSloganLangList;
use App\CouponOfferTitleLangList;
use App\App42\PushNotificationService;
use App\App42\DeviceType;
use App\App42\App42Log;
use App\App42\App42Exception;
use App\App42\App42NotFoundException;
use App\App42\App42BadParameterException;
use App\App42\StorageService;
use App\App42\QueryBuilder;
use App\App42\Query;
use App\App42\App42API;
use App\App42\Util;

use Storage;
use AWS;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Log;
use App\LangText;
use App\ProductOfferSloganLangList;
use App\ProductOfferSubSloganLangList;
use App\ProductKeyword;
use App\Resizer;
use App\PhocaGalleryRenderProcess;

use App\SubscriptionPlan;
use App\UserPlan;
use App\PromotionDiscount;

//use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function checkStore(){
        if(Session::get('checkStore') == 1){
            return redirect('kitchen/store');
        }else{
            $storeDetails = null;
            $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();
            if($companydetails){
                $storeDetails = Store::where('u_id' , $companydetails->u_id)->get();
            }else{
                $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
                $storeDetails = Store::where('u_id' , $companydetails->u_id)->get();
            }

            if(count($storeDetails) == 0){
                $storeDetails = [];
            }

            return view('kitchen.storeList', compact('storeDetails'));
        }
    }

    public function checkStoreFirst(){
        if(Session::get('checkStore') == 1){
            $url = url('kitchen/store');
            return response()->json(['status' => 'success', 'response' => true,'data'=>$url]);
        }
        return response()->json(['status' => 'success', 'response' => true,'data'=>true]);
    }

    public function index(Request $request){
        if(!empty($request->input())){
            $data = $request->input();
            Session::put('storeId', $data['storeId']);
            $storeId = $data['storeId'];
        }else{
            $storeId = Session::get('storeId');
        }

        Session::put('checkStore', 1);
        $store = Store::where('store_id' , $storeId);

        if(!$store->exists()){
            $storeDetails = [];
            return view('kitchen.storeList', compact('storeDetails'));
        }

        // Update subscription plan for logged-in store
        $this->updateStoreSubscriptionPlan();
        // dd($request->session()->all());

        $storedetails = $store->first();
        $storeName = $storedetails->store_name;

        // Get if Text2Speech is on/off
        $textSpeech = Auth::guard('admin')->user()->text_speech;

        return view('kitchen.order.index', compact('storedetails', 'storeName', 'textSpeech'));    
    }

    /**
     * Get subscribed plan for logged-in store and set them in session to access the module
     * @return [type] [description]
     */
    private function updateStoreSubscriptionPlan()
    {
        // Destroy existing subscription from session if any
        Session::forget('subscribedPlans');
        Session::save();

        $store = Store::select('check_subscription')
            ->where('store_id', Session::get('storeId'))
            ->first();

        // Check if store's 'check_subscription' has set to '0', give access of all modules without subscription
        if($store->check_subscription)
        {
            // Get subscribed plan for store
            $storePlanDetail = SubscriptionPlan::from('billing_products AS BP')
                ->select('BP.id', 'BPP.package_id', 'UP.plan_id')
                ->join('billing_product_packages AS BPP', 'BP.id', '=', 'BPP.billing_product_id')
                ->join('anar_packages AS AP', 'AP.id', '=', 'BPP.package_id')
                ->join('user_plan AS UP', 'BP.plan_id', '=', 'UP.plan_id')
                ->where('BP.s_activ', 1)
                ->whereDate('UP.subscription_start_at', '<=', Carbon::parse(Carbon::now())->format('Y-m-d'))
                ->whereDate('UP.subscription_end_at', '>=', Carbon::parse(Carbon::now())->format('Y-m-d'))
                ->where('UP.user_id', Auth::user()->u_id)
                ->where('UP.store_id', Session::get('storeId'))
                ->get();
            // dd($storePlanDetail->toArray());

            if($storePlanDetail)
            {
                // Get package and associated meta detail
                $storePlan = array();

                foreach($storePlanDetail as $row)
                {
                    $storePlan[] = $row['package_id'];
                }

                $storePlan = array_unique($storePlan);

                // Kitchen
                if( in_array('2', $storePlan) )
                {
                    Session::put('subscribedPlans.kitchen', 1);
                }

                // Order on Site
                if( in_array('3', $storePlan) )
                {
                    Session::put('subscribedPlans.orderonsite', 1);
                }

                // Catering
                if( in_array('4', $storePlan) )
                {
                    Session::put('subscribedPlans.catering', 1);
                }

                // Payment
                if( in_array('5', $storePlan) )
                {
                    Session::put('subscribedPlans.payment', 1);
                }

                // Discount
                if( in_array('10', $storePlan) )
                {
                    Session::put('subscribedPlans.discount', 1);
                }

                // Loyalty
                if( in_array('11', $storePlan) )
                {
                    Session::put('subscribedPlans.loyalty', 1);
                }

                Session::save();
            }
        }
        else
        {
            Session::put('subscribedPlans.kitchen', 1);
            Session::put('subscribedPlans.orderonsite', 1);
            Session::put('subscribedPlans.catering', 1);
            Session::put('subscribedPlans.payment', 1);
            Session::put('subscribedPlans.discount', 1);
            Session::put('subscribedPlans.loyalty', 1);

            Session::save();
        }

        return Session::get('subscribedPlans');
    }

    /**
     * Keep checking if subscription plan got updated for store
     * @return [type] [description]
     */
    public function checkStoreSubscriptionPlan()
    {
        $result = array();
        $staticPlans = array('kitchen', 'orderonsite', 'catering');
        
        // Check and update subscription plan for logged-in store
        if( is_array($this->updateStoreSubscriptionPlan()) && !empty($this->updateStoreSubscriptionPlan()) )
        {
            $currentPlans = array_keys($this->updateStoreSubscriptionPlan());
            $result = array_values(array_intersect($staticPlans, $currentPlans));
        }

        return response()->json(['data' => $result]);

        /*$response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() {
            $staticPlans = array('kitchen', 'orderonsite', 'catering');
            $currentPlans = Session::get('subscribedPlans');
            $data = array_keys($currentPlans);

            while (true) {
                // if (!empty($data)) {}
                echo 'data: ' . json_encode($data) . "\n\n";
                ob_flush();
                flush();

                if ( connection_aborted() ) break;

                //sleep for x seconds
                sleep(20);

                // Check and update subscription plan for logged-in store
                $currentPlans = array_keys($this->updateStoreSubscriptionPlan());
                $data = array_values(array_intersect($staticPlans, $currentPlans));
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;*/
    }

    /**
     * Update all the 'order items' and 'order' status as started from 'Order Menu'
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function startOrder($id)
    {
        $status = false;

        if( OrderDetail::where('order_id', $id)->update(['order_started' => 1]) )
        {
            $helper = new Helper();
            
            $status = true;

            $arrOrderUpdate['order_started'] = 1;

            // Check and update order as accepted if not already accepted
            if( Order::where(['order_id' => $id, 'order_accepted' => 0])->count() )
            {
                $arrOrderUpdate['order_accepted'] = 1;
            }

            Order::where('order_id', $id)->update($arrOrderUpdate);

            // If order accepted, send 'order accepted' text/notification
            if( isset($arrOrderUpdate['order_accepted']) )
            {
                $this->onOrderAccepted($id);
                $helper->logs("Order Accepted: Order ID - " . $id);
            }

            $helper->logs("Order Started: Order ID - " . $id);
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Update 'order' and 'order item' status as ready and send push notification from 'Order Menu'
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    function makeOrderReady(Request $request, $orderId)
    {
        $helper = new Helper();

        try{
            $helper->logs("Order Ready: Order ID - " . $orderId);

            // Get order detail
            $order = Order::select(['user_id', 'user_type', 'customer_order_id'])
                ->where('order_id' , $orderId)
                ->first();
            
            // Update order and order items as ready
            DB::table('order_details')->where('order_id', $orderId)->update(['order_ready' => 1]);
            DB::table('orders')->where('order_id', $orderId)->update(['order_ready' => 1]);

            if($order->user_id != 0)
            {
                $recipients = [];
                if($order->user_type == 'customer'){
                    $adminDetail = User::where('id' , $order->user_id)->first();
                    if(isset($adminDetail->phone_number_prifix) && isset($adminDetail->phone_number)){
                        $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];
                    }
                }
                else{
                    $adminDetail = Admin::where('id' , $order->user_id)->first();
                    $recipients = ['+'.$adminDetail->mobile_phone];
                }

                if(isset($adminDetail->browser)){
                    $pieces = explode(" ", $adminDetail->browser);
                }else{
                    $pieces[0] = '';
                }

                // Send message/notification to user
                // if($pieces[0] == 'Safari')
                if( ($pieces[0] == 'Safari') || ( isset($adminDetail->browser) && strpos($adminDetail->browser, 'Mobile/') !== false ) || ( isset($adminDetail->browser) && strpos($adminDetail->browser, 'wv') !== false ) )
                {
                    // $message = "Your Order Ready Please click on Link \n ".env('APP_URL').'ready-notification/'.$order->customer_order_id;
                    $message = __('messages.notificationOrderReady', ['order_id' => $order->customer_order_id]);
                    $result = $this->apiSendTextMessage($recipients, $message);
                    
                    $helper->logs("Order Ready: IOS notification sent. Order ID - " . $orderId);
                }
                else
                {
                    $message = 'orderReady';
                    $result = $this->sendNotifaction($order->customer_order_id , $message);
                    
                    $helper->logs("Order Ready: Android notification sent. Order ID - " . $orderId);
                }
            }
            else
            {
                $helper->logs("Order Ready: ELSE; Order ID - ".$orderId);
            }

            return redirect()->back()->with('success', 'Order Ready Notification Send Successfully.');
        } catch(\Exception $ex){
            $helper->logs("Step 6: Exception = " .$ex->getMessage());
        }
    }

    /**
     * [Update order payment manually from 'Orders' page]
     * @param  [type] $order_id [primary key of table 'order']
     * @return [type]           [status]
     */
    function orderPayManually($order_id)
    {
        $status = false;
        $order = Order::findOrFail($order_id);

        if( $order->where('order_id', $order_id)->update(['online_paid' => 3]) )
        {
           $status = true;
        }

        return response()->json(['status' => $status, 'order' => $order]);
    }

    public function kitchenOrderDetail(){
        $store = Store::where('store_id' , Session::get('storeId'));

        if(!$store->exists()){
            $storeDetails = [];
            return view('kitchen.storeList', compact('storeDetails'));
        }

        $store = $store->first();
        $storeName = $store->store_name;
        
       return view('kitchen.order.kitchen_order_list', compact('store', 'storeName'));
    }

    /*public function orderStarted(Request $request, $orderItemId){
        if( DB::table('order_details')->where('id', $orderItemId)->update(['order_started' => 1]) )
        {
            $arrOrderUpdate['order_accepted'] = 1;

            // Check if all item has started for an order and update order as 'order_started' too
            $orderId = OrderDetail::select(['order_id'])->where('id', $orderItemId)->first()->order_id;

            if( !OrderDetail::where(['order_id' => $orderId, 'order_started' => 0])->count() )
            {
                $arrOrderUpdate['order_started'] = 1;
            }

            // Check and update order as accepted if not already accepted
            if( Order::where(['order_id' => $orderId, 'order_accepted' => 0])->count() )
            {
                $arrOrderUpdate['order_accepted'] = 1;
            }

            Order::where('order_id', $orderId)->update($arrOrderUpdate);

            // If order accepted, send 'order accepted' notification
            if( isset($arrOrderUpdate['order_accepted']) )
            {
                $this->onOrderAccepted($orderId);
            }
        }

        return redirect()->action('AdminController@kitchenOrderDetail')->with('success', 'Order Started Successfully.');
    }*/
    
    public function cateringDetails(){
        $store = Store::where('store_id' , Session::get('storeId'));

        if(!$store->exists()){
            $storeDetails = [];
            return view('kitchen.storeList', compact('storeDetails'));
        }

        $storedetails = $store->first();
        $storeName = $storedetails->store_name;

        return view('kitchen.order.catering', compact('storeName')); 
    }

    public function kitchenPreOrder(Request $request){
        $menuTypes = null;
        $request->session()->forget('order_date');
        
        if(Session::get('storeId')){
            $menuDetails = ProductPriceList::where('store_id',Session::get('storeId'))->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())
            ->with('menuPrice')->with('storeProduct')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->orderBy('product.product_rank', 'ASC')
            ->get();

            if($menuDetails){
                $helper = new Helper();

                foreach ($menuDetails as $menuDetail) {
                        foreach ($menuDetail->storeProduct as $storeProduct) {
                            $companyId = $storeProduct->company_id;
                            $dish_typeId[] = $storeProduct->dish_type;
                            /*try{
                                getimagesize($storeProduct->small_image);
                            } catch (\Exception $ex) {
                                $storeProduct->small_image = asset('images/placeholder-image.png');
                            }*/
                        }
                }

                if(isset($companyId)){
                    $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->get();
                }else{
                    $menuTypes = null;
                }

                $dish_typeId = null;
            }


            $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();
            if(!$companydetails){
                $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            }

            $storedetails = Store::where('store_id' , Session::get('storeId'))->first();

            // Get discount
            $todayDate = Carbon::now()->format('Y-m-d h:i:00');
            $discount = PromotionDiscount::from('promotion_discount AS PD')
                ->select(['PD.id', 'PD.code', 'PD.discount_value', 'PD.start_date', 'PD.description', 'PD.end_date', 'S.store_name'])
                ->join('store AS S', 'S.store_id', '=', 'PD.store_id')
                ->where(['PD.store_id' => Session::get('storeId')])
                ->where(['S.u_id' => Auth::user()->u_id])
                ->where('PD.start_date', '<=', $todayDate)
                ->where('PD.end_date', '>=', $todayDate)
                ->get();
            
            return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes','storedetails', 'discount'));
        }else{
            return view('kitchen.order.kitchen-main-admin');
        }
    }

    
    public function kitchenOrders(){
        $reCompanyId = Session::get('storeId');

        $kitchenorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.delivery_type','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('delivery_date',Carbon::now()->toDateString())->where('order_details.order_ready', '0')->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();

        $extra_prep_time = Store::where('store_id', $reCompanyId)->first()->extra_prep_time;

        $text_speech = Auth::guard('admin')->user()->text_speech;
        return response()->json(['status' => 'success', 'user' => $text_speech, 'extra_prep_time' => $extra_prep_time, 'data'=>$kitchenorderDetails]);
    }

    public function kitchenOrdersNew($id){
        $reCompanyId = Session::get('storeId');

        $kitchenorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.delivery_type','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('delivery_date',Carbon::now()->toDateString())->where('order_details.order_ready', '0')->where('order_details.id', '>', $id)->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();

        $extra_prep_time = Store::where('store_id', $reCompanyId)->first()->extra_prep_time;
        
        $text_speech = Auth::guard('admin')->user()->text_speech;
        return response()->json(['status' => 'success', 'user' => $text_speech, 'extra_prep_time' => $extra_prep_time,'data'=>$kitchenorderDetails]);
    }

    public function kitchenOrderSave(Request $request){
        if(!empty($request->input())){

            $data = $request->input();
            $i = 0;
            $total_price = 0;
            $max_time = "00:00:00";
            $orderType;
            $orderDate;
            $orderTime;
            $checkOrderDate;

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

            $afterRemoveFirstZeroNumber = substr($data['mobileNo'], 0, 1);

            if($afterRemoveFirstZeroNumber == 0){
                $number = substr($data['mobileNo'], -9);
            }else{
               $number =  $data['mobileNo'];
            }

            $customer = new User();

            if($customer->where('phone_number_prifix',$data['phone_number_prifix'])->where('phone_number',$number)->exists()) {
                $customer_id = $customer->where('phone_number_prifix',$data['phone_number_prifix'])->where('phone_number',$number)->first()->id;
            }else{
                $customer_id = 0;
            }

            // Get store detail
            $storeDetail = Store::where('store_id', Session::get('storeId'))->first();

            //
            foreach ($data['product'] as $key => $value) {
                //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                if($value['prod_quant'] != '0'){
                    $productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
                    if($i == 0){
                        $order =  new Order();
                        $order->customer_order_id = $this->random_num(6);
                        $order->user_id = $customer_id;
                        $order->store_id = Session::get('storeId');
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
                            $order->order_accepted = 0;
                        }

                        $order->save();
                        $orders = Order::select('*')->whereUserId($customer_id)->orderBy('order_id', 'DESC')->first();
                        $orderId = $orders->order_id;
                        $i = $i+1;
                    }else{}

                    $i = 1;
                    if($max_time < $productTime->preparation_Time){
                        $max_time = $productTime->preparation_Time;
                    }else{}
                    $productPrice = ProductPriceList::select('price')->whereProductId($value['id'])->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())->first();
                    $total_price = $total_price + ($productPrice->price * $value['prod_quant']); 
                    $orderDetail =  new OrderDetail();
                    $orderDetail->order_id = $orders->order_id;
                    $orderDetail->user_id = $customer_id;
                    $orderDetail->product_id = $value['id'];
                    $orderDetail->product_quality = $value['prod_quant'];
                    $orderDetail->product_description = $value['prod_desc'];
                    $orderDetail->price = $productPrice->price;
                    $orderDetail->time = $productTime->preparation_Time;
                    $orderDetail->company_id = $productTime->company_id;
                    $orderDetail->store_id = Session::get('storeId');
                    $orderDetail->delivery_date = $checkOrderDate;
                    $orderDetail->save();
                }
            }

            DB::table('orders')->where('order_id', $orderId)->update([
                        'order_delivery_time' => $max_time,
                        'order_total' => $total_price,
                    ]);

            $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

            $request->session()->put('currentOrderId', $order->order_id);

            $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

            $recipients = ['+'.$data['phone_number_prifix'].$number];
            $url = "https://gatewayapi.com/rest/mtsms";
            $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
            $message = env('APP_URL') . "order/" . $order->customer_order_id . "?m=" . $data['phone_number_prifix'] . "-" . $number;
            $json = [
                'sender' => 'Dastjar',
                'message' => ''.$message.'',
                'recipients' => [],
            ];
            foreach ($recipients as $msisdn) {
                $json['recipients'][] = ['msisdn' => $msisdn];
            }

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            return redirect('kitchen/store');
            // return view('kitchen.order.order-detail', compact('order','orderDetails','storeDetail'));
        }else{
            $menuTypes = null;
            $menuDetails = ProductPriceList::where('store_id',Session::get('storeId'))->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())->with('menuPrice')->with('storeProduct')->get();
            if(count($menuDetails) != 0){

                foreach ($menuDetails as $menuDetail) {
                    foreach ($menuDetail->storeProduct as $storeProduct) {
                        $companyId = $storeProduct->company_id;
                        $dish_typeId[] = $storeProduct->dish_type;
                    }
                }

                $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->where('dish_lang','ENG')->get();
                $dish_typeId = null;
            }


            $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();

            if(Company::where('company_id' , Auth::guard('admin')->user()->company_id)->exists()){
                if(count($companydetails->toArray()) == 0){
                    $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
                }
            }else{
                    $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();                
            }

            $storedetails = Store::where('store_id' , Session::get('storeId'))->first();
            return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes','storedetails'));
        }
    }

    /**
     * Send promotional discount link to user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendPromotionalDiscount(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'prefix'        => 'required',
            'mobile'        => 'required|numeric',
            'discount_code' => 'required',
            'store_id'      => 'required'
        ]);

        $status = 1;

        if($validator->fails())
        {
            $status = 0;
            return response()->json(['status' => $status, 'errors' => $validator->errors()->all()]);
        }

        // Remove '0' if exist as first digit
        $mobile = $request->mobile;
        $firstDigit = substr($mobile, 0, 1);

        if($firstDigit == 0)
        {
            $mobile = substr($mobile, -9);
        }

        // Send promotional link
        $recipients = ['+'.$request->prefix.$mobile];
        $message = env('APP_URL') . "promotion/apply-user-discount/{$request->store_id}/{$request->discount_code}";
        // $message = __('messages.notificationOrderReady', ['order_id' => $OrderId->customer_order_id]);
        $result = $this->apiSendTextMessage($recipients, $message);

        return response()->json(['status' => $status, 'result' => $result]);
    }

    /**
     * Send promotion URL
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function sendPromotionalApp(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'prefix' => 'required',
            'mobile' => 'required|numeric'
        ]);

        $status = 1;

        if($validator->fails())
        {
            $status = 0;
            return response()->json(['status' => $status, 'errors' => $validator->errors()->all()]);
        }

        // Remove '0' if exist as first digit
        $mobile = $request->mobile;
        $firstDigit = substr($mobile, 0, 1);

        if($firstDigit == 0)
        {
            $mobile = substr($mobile, -9);
        }

        // Send promotional link
        $recipients = ['+'.$request->prefix.$mobile];
        $message = env('APP_URL');
        $result = $this->apiSendTextMessage($recipients, $message);

        return response()->json(['status' => $status, 'result' => $result]);
    }

    public function selectOrderDateKitchen(){
        return view('kitchen.select-datetime'); 
    }

    public function kitchenEatLater(Request $request){
        if(!empty($request->input())) {
            $data = $request->input();
            $request->session()->put('order_date', $data['dateorder']);
        }else{}

        $menuTypes = null;
        $menuDetails = ProductPriceList::where('store_id',Session::get('storeId'))->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())->with('menuPrice')->with('storeProduct')->get();

        if(count($menuDetails) != 0){
            foreach ($menuDetails as $menuDetail) {
                foreach ($menuDetail->storeProduct as $storeProduct) {
                    $companyId = $storeProduct->company_id;
                    $dish_typeId[] = $storeProduct->dish_type;
                }
            }

            $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->where('dish_lang','ENG')->get();
            $dish_typeId = null;
        }


        $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();
        if(count($companydetails) == 0){
            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
        }

        $storedetails = Store::where('store_id' , Session::get('storeId'))->first();
        return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes','storedetails'));
    }

    public function kitchenOrderView($orderId){
        $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

        return view('kitchen.order.order-detail', compact('order','orderDetails'));
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

    public function kitchenSetting(){
        // Get logged-in store detail
        $store = Store::select(['order_response'])
            ->where('store_id' , Session::get('storeId'))->first();

        return view('kitchen.setting.index', compact('store'));
    }

    public function saveKitchenSetting(Request $request){
        $data = $request->input();
        if($data['radio-choice-v-2'] == 'ENG'){
            Session::put('applocale', 'en');
        }else{
            Session::put('applocale', 'sv');
        }
        DB::table('user')->where('id', Auth::guard('admin')->id())->update([
                    'language' => $data['radio-choice-v-2'],
                    'text_speech' => $data['text_speech'],
                ]);

        // Update store setting
        Store::where('store_id', Session::get('storeId'))
            ->update(['order_response' => $data['order_response']]);

        return redirect()->back()->with('success', 'Setting updated successfully.');
    }

    /**
     * Send test notification
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    function testSendNotifaction($order_id=null)
    {
        if( is_numeric($order_id) )
        {
            $order = Order::select(['user_id', 'user_type', 'customer_order_id'])
                ->where('order_id' , $order_id)
                ->first();

            if($order)
            {
                $message = 'orderAccepted';
                $result = $this->sendNotifaction($order->customer_order_id , $message);

                echo 'sent';
            }
        }
    }

    public function sendNotifaction($orderID, $message){
        $helper = new Helper();                
        $helper->logs("App42 Step1: " . $orderID);

        $order = Order::select('*')->where('customer_order_id',$orderID)->first();
        if($order->user_type == 'customer'){
            $userDetail = User::whereId($order->user_id)->first();
        }else{
            $userDetail = Admin::whereId($order->user_id)->first();
        }

        if(!isset($userDetail->email)){
            return "Notification Send Successfully";
        }

        $userName = $userDetail->email;

        if($message == 'orderDeliver')
        {
            $helper->logs("App42 Step1: " . $orderID);

            /*$url = env('APP_URL').'deliver-notification/'.$order->customer_order_id;
            $message = "{'alert': 'Your Order Deliver.','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': 'Your Order Deliver.','type':'openUrl','content':" ."'". $url."'" . "}}";*/
        }
        elseif($message == 'orderAccepted')
        {
            $messageDelever = __('messages.notificationOrderReceived', ['order_id' => $order->customer_order_id]);
            $url = env('APP_URL').'order-view/'.$order->order_id;
            $message = "{'alert': '".$messageDelever."','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': '".$messageDelever."','type':'openUrl','content':" ."'". $url."'" . "}}";
        }
        elseif($message == 'orderReady')
        {
            $messageDelever = __('messages.notificationOrderReady', ['order_id' => $order->customer_order_id]);
            $url = env('APP_URL').'ready-notification/'.$order->customer_order_id;
            $message = "{'alert': '".$messageDelever."','_App42Convert': true,'mutable-content': 1,'_app42RichPush': {'title': '".$messageDelever."','type':'openUrl','content':" ."'". $url."'" . "}}";
        }

        try{
            $helper->logs("App42 Step2: Username- " . $userName);
            App42API::initialize(env('APP42_API_KEY'),env('APP42_API_SECRET'));
            Log::channel('pushnotifaction')->info('Before pushNotification time : '.Carbon::now());

            $pushNotificationService = App42API::buildPushNotificationService(); 
    
            $pushNotification = $pushNotificationService->sendPushMessageToUser($userName,$message);
            $helper->logs("App42 Step3: " . $pushNotification);
            Log::channel('pushnotifaction')->info('After pushNotification time : '.Carbon::now());
        }catch(\Exception $e){
            $helper->logs("App42 Exception: " . $e->getMessage());            
            return $e->getMessage();
        }


        return $pushNotification;
    }

    public function kitchenMenu(){
        if(Session::get('storeId')){
            $products = new Product();
            $productPriceList = new ProductPriceList();
            $productOfferSloganLangList = new ProductOfferSloganLangList();
            $productOfferSubSloganLangList = new ProductOfferSubSloganLangList();
            $langText = new LangText();

            $allData = [];

            /*$prods = $products->join('dish_type','dish_type.dish_id','=','product.dish_type')->where('product.u_id', Auth::user()->u_id)->where('s_activ', '!=' , 2)->where('dish_activate',1)
            ->orderBy('product_rank', 'ASC')
            ->get()->groupBy('dish_type');
            $prodprices = $productPriceList->where('store_id', Session::get('storeId'))->get()->groupBy('product_id');
        
            foreach($prods as $k=>$r){
                foreach($r as $k2=>$r2){
                    if(isset($prodprices[$r2->product_id])){
                        $data = [];
                        $data['product_id'] = $r2->product_id;

                        $sloganLangId = $productOfferSloganLangList->where('product_id',$data['product_id'])->first()->offer_slogan_lang_list;
                        $sloganSubLangId = $productOfferSubSloganLangList->where('product_id',$data['product_id'])->first()->offer_sub_slogan_lang_list;

                        $prodName = $langText->where('id',$sloganLangId)->first()->text;
                        $prodDesc = $langText->where('id',$sloganSubLangId)->first()->text;

                        $data['product_name'] = $prodName;
                        $data['product_description'] = $prodDesc;
                        try{
                            getimagesize($r2->small_image);
                            $data['small_image'] = $r2->small_image;
                        }catch(\Exception $ex){
                            $data['small_image'] = asset('images/placeholder-image.png');
                        }

                        $data['publishing_start_date'] = $r2->publishing_start_date;
                        $data['publishing_end_date'] = $r2->publishing_end_date;

                        foreach($prodprices[$r2->product_id] as $pk=>$pr){
                            $prices['price_id'] = $pr->id;
                            $prices['price'] = $pr->price;
                            $prices['publishing_start_date'] = $pr->publishing_start_date;
                            $prices['publishing_end_date'] = $pr->publishing_end_date;

                            $data['prices'][] = $prices;
                        }

                        if(!empty($r2->dish_type)){
                            $allData[$r2->dish_type][] = $data;
                        }
                    }
                }
            }*/

            $storedetails = Store::where('store_id' , Session::get('storeId'))->first();
            $storeName = $storedetails->store_name;

            $employer = new Employer();
            $companyId = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

            // Strange logic to get menu types for specific store ID 
            //$menuTypes = DishType::where('company_id', $companyId)->orderBy('rank')->orderBy('dish_id')->pluck('dish_name','dish_id');

            $menuTypes = DishType::select('DT.dish_name','DT.dish_id')
                ->from('dish_type AS DT')
                ->join('product AS P', 'P.dish_type', '=', 'DT.dish_id')
                ->join('product_price_list AS PPL', 'PPL.product_id', '=', 'P.product_id')
                ->where('P.u_id', Auth::user()->u_id)
                ->where('P.s_activ', '!=' , 2)
                ->where('DT.dish_activate', 1)
                ->where('PPL.store_id', Session::get('storeId'))
                ->groupBy('DT.dish_id')
                ->orderBy('DT.rank')
                ->orderBy('DT.dish_id')
                ->pluck('DT.dish_name','DT.dish_id');
            //echo '<pre>'; print_r($menuTypes->toArray()); exit;

            $companydetails = new Company();
            $currency = $companydetails->where('company_id' , '=', $companyId)->first()->currencies;

            return view('kitchen.menulist.index', compact('menuTypes','storeName', 'currency', 'allData'));
        }
    }

    /**
     * Return products for specific menu along with the current price
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxGetProductByDishType(Request $request) {
        // Get all products by dish
        $products = Product::select('product.product_id', 'product.product_name', 'product.product_description', 'product.small_image')
            ->join('dish_type','dish_type.dish_id','=','product.dish_type')
            ->join('product_price_list AS PPL', 'PPL.product_id', '=', 'product.product_id')
            ->where('product.dish_type', $request->dish_id)
            ->where('product.u_id', Auth::user()->u_id)
            ->where('PPL.store_id', Session::get('storeId'))
            ->where('product.s_activ', '!=' , 2)
            ->where('dish_type.dish_activate',1)
            ->groupBy('product.product_id')
            ->orderBy('product_rank', 'ASC')
            ->orderBy('product_id')
            ->get();

        $data = array();

        if($products)
        {
            foreach($products as $key => $value)
            {
                $data[$key] = $value;

                if( strpos($value->small_image, '.png') == false && strpos($value->small_image, '.jpg') == false )
                {
                    $data[$key]['small_image'] = asset('images/placeholder-image.png');
                }
                else
                {
                    $data[$key]['small_image'] = $value->small_image;
                }

                // Get current product price detail
                $data[$key]['current_price'] = null;
                $current_date = Carbon::now()->format('Y-m-d h:i:00');

                $currentProductPrice = ProductPriceList::select('id', 'text', 'price', 'publishing_start_date', 'publishing_end_date')
                    ->where('product_id', $value->product_id)
                    ->where('store_id', Session::get('storeId'))
                    ->where('publishing_start_date', '<=', $current_date)
                    ->where('publishing_end_date', '>=', $current_date)
                    ->first();
                    //->toSql();
                
                if($currentProductPrice)
                {
                    $data[$key]['current_price'] = $currentProductPrice;
                }
            }
        }

        return response()->json(['products' => $data]);
    }

    /**
     * Return future prices for specific product
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxGetFuturePriceByProduct(Request $request)
    {
        //
        $current_date = Carbon::now()->format('Y-m-d h:i:00');
        $data = null;

        $futureProductPrices = ProductPriceList::select('id', 'product_id', 'text', 'price', 'publishing_start_date', 'publishing_end_date')
            ->where('product_id', $request->product_id)
            ->where('store_id', Session::get('storeId'))
            ->where('publishing_start_date', '>', $current_date)
            ->orderBy('publishing_start_date')
            ->get();

        if($futureProductPrices)
        {
            $data = $futureProductPrices;
        }

        return response()->json(['futureProductPrices' => $data]);
    }

    public function kitchenCreateMenu(Request $request){        
        $store = Store::where('store_id' , Session::get('storeId'));

        if(!$store->exists()){
            $storeDetails = [];
            return view('kitchen.storeList', compact('storeDetails'));
        }

        $storedetails = $store->first();
        $storeName = $storedetails->store_name;

        $employer = new Employer();
        $companyId = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

        $dishType = new DishType();
        $listDishes = $dishType->where('company_id' , '=', $companyId)->where('dish_activate', '=', '1')->pluck('dish_name','dish_id');

        $companydetails = new Company();
        $currency = $companydetails->where('company_id' , '=', $companyId)->first()->currencies;

        return view('kitchen.menulist.createMenu', compact('storeName', 'listDishes' ,'currency'));
    }

    public function kitchenCreateMenuPost(Request $request){
        $helper = new Helper();

        $product_id = $helper->uuid();

        while(Product::where('product_id',$product_id)->exists()){
            $product_id = $helper->uuid();
        }
   
        $store_id = Session::get('storeId');
        $message = "Dish Created Successfully.";

        $util = new Util(env('APP42_API_KEY'),env('APP42_API_SECRET'));

        $publish_start_date = \DateTime::createFromFormat('d/m/Y H:i', $request->publish_start_date);
        $publish_end_date = \DateTime::createFromFormat('d/m/Y H:i', $request->publish_end_date);

        $employer = new Employer();
        $company_id = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

        // 
        $product = new Product();
        $product->product_id = $product_id;        
        $product->u_id = Auth::user()->u_id;
        $product->product_name = $request->prodName;

        // Product image
        if (!empty($_FILES["prodImage"]["name"]))
        {
            // 
            $basePath = app_path();
            define('UPLOAD_DIR', public_path() . '/upload/images/');
            define('IMAGE_AMAZON_PATH', 'https://s3-eu-west-1.amazonaws.com/dastjar-coupons/upload/');
            define('IMAGE_DIR_PATH', $basePath . '/lib/bin/cumbari_s3.sh ');

            // 
            $info = pathinfo($_FILES["prodImage"]["name"]);
            $file_extension = strtolower($info['extension']);

            if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "gif" || $file_extension == "bmp")
            {
                if ($_FILES["prodImage"]["error"] > 0)
                {
                    $error.=$_FILES["prodImage"]["error"] . "<br />";
                }
                else
                {
                    try{
                        $helper = new Helper();

                        $fileOriginal = $_FILES['prodImage']['tmp_name'];
                        $path = UPLOAD_DIR . "category/";

                        // Resize image (small and large)
                        $fileName = 'dish-thumbnail-'.time().'.jpg';
                        $smallImg = $helper->gumletImageResize($fileOriginal, $fileName, $path, 256);

                        $fileName = 'dish-large-'.time().'.jpg';
                        $largeImg = $helper->gumletImageResize($fileOriginal, $fileName, $path, 1024);

                        // Upload image to AWS
                        $file1 = $path.$smallImg;
                        $dir1 = "category";
                        $command = IMAGE_DIR_PATH . $file1 . " " . $dir1;
                        system($command);

                        $file2 = $path.$largeImg;
                        $dir2 = "coupon";
                        $command2 = IMAGE_DIR_PATH . $file2 . " " . $dir2;
                        system($command2);

                        // 
                        $product->small_image = IMAGE_AMAZON_PATH . 'category/' . $smallImg;
                        $product->large_image = IMAGE_AMAZON_PATH . 'coupon/' . $largeImg;
                    } catch (\Exception $ex) {
                        echo $ex->getMessage();
                        die();
                    }
                }
            }
        }
        else
        {
            $product->small_image = $product->large_image = 'https://s3-eu-west-1.amazonaws.com/dastjar-coupons/upload/category/cat_icon_b738a523d72867d1fc84e1f9d3c18b29.png';
        }

        // Set rank
        $rank = Product::orderBy('product_rank', 'DESC')->where(['dish_type' => $request->dishType, 'u_id' => Auth::user()->u_id, 'company_id' => $company_id, 's_activ' => 0])->first();
        
        if($rank)
        {
            $product->product_rank = ($rank->product_rank + 1);
        }
        else
        {
            $product->product_rank = 1;
        }

        $minutes = $request->prepTime;
        $hours = intdiv($minutes, 60).':'. ($minutes % 60).':00';

        $product->lang = $request->dishLang;
        $product->dish_type = $request->dishType;
        $product->product_description = $request->prodDesc;
        $product->preparation_Time = $hours;
        $product->category = "7099ead0-8d47-102e-9bd4-12313b062day";
        $product->product_number = "";
        $product->product_info_page = "";
        $product->start_of_publishing = Carbon::now();
        $product->company_id = $company_id;
        $product->save();

        $sloganSubLangId = $helper->uuid();

        $productOfferSubSloganLangList = new ProductOfferSubSloganLangList();
        $productOfferSubSloganLangList->product_id = $product_id;
        $productOfferSubSloganLangList->offer_sub_slogan_lang_list = $sloganSubLangId;
        $productOfferSubSloganLangList->save();

        /*** insert product description in lang_text table */
        $langText = new LangText();
        $langText->id = $sloganSubLangId;
        $langText->lang = $request->dishLang;
        $langText->text = $request->prodDesc;
        $langText->save();

        $sloganLangId = $helper->uuid();

        $productOfferSloganLangList = new ProductOfferSloganLangList();
        $productOfferSloganLangList->product_id = $product_id;
        $productOfferSloganLangList->offer_slogan_lang_list = $sloganLangId;
        $productOfferSloganLangList->save();

        /*** insert product name in lang_text table */
        $langText = new LangText();
        $langText->id = $sloganLangId;
        $langText->lang = $request->dishLang;
        $langText->text = $request->prodName;
        $langText->save();

        $SystemkeyId = $helper->uuid();

        /*** insert product language in lang_text table */
        $langText = new LangText();
        $langText->id = $SystemkeyId;
        $langText->lang = $request->dishLang;
        $langText->text = $product_id;
        $langText->save();

        /*** insert product id in product_keyword table */
        $productKeyword = new ProductKeyword();
        $productKeyword->product_id = $product_id;
        $productKeyword->system_key = $SystemkeyId;
        $productKeyword->save();

        $Systemkey_companyId = $helper->uuid();

        /*** insert company id in lang_text table */
        $langText = new LangText();
        $langText->id = $Systemkey_companyId;
        $langText->lang = $request->dishLang;
        $langText->text = $company_id;
        $langText->save();
   
        /*** insert company id in product_keyword table */
        $productKeyword = new ProductKeyword();
        $productKeyword->product_id = $product_id;
        $productKeyword->system_key = $Systemkey_companyId;
        $productKeyword->save();

        $product_price_list = ProductPriceList::firstOrNew(['product_id' => $product_id]);
        $product_price_list->store_id = $store_id;
        $product_price_list->text = "Price:" . $request->prodPrice . $request->currency;
        $product_price_list->price = $request->prodPrice;
        $product_price_list->lang = $request->dishLang;
        $product_price_list->publishing_start_date = $publish_start_date;
        $product_price_list->publishing_end_date = $publish_end_date;
        $product_price_list->save();

        return redirect()->route('menu')->with('success', $message);
    }


    public function kitchenUpdateMenuPost(Request $request){
        // Validate if publish start/end datetime shouldn't already exist
        $publishing_start_date_time = \DateTime::createFromFormat('d/m/Y H:i', $request->publish_start_date);
        $publishing_end_date_time = \DateTime::createFromFormat('d/m/Y H:i', $request->publish_end_date);

        $publishing_start_date = $publishing_start_date_time->format('Y-m-d');
        $publishing_end_date = $publishing_end_date_time->format('Y-m-d');
        $publishing_start_time = $publishing_start_date_time->format('H:i:00');
        $publishing_end_time = $publishing_end_date_time->format('H:i:00');

        $product_price_list = new ProductPriceList();
        $product_price_list = ProductPriceList::where('product_id', $request->product_id)->where('store_id', $request->store_id);

        $product_price = ProductPriceList::where(['id' => $request->price_id])->first();

        if($product_price)
        {
            $product_price_list->where('id', '!=', $product_price->id);
        }

        $product_price_list->whereRaw("( DATE(publishing_start_date) BETWEEN '{$publishing_start_date}' AND '{$publishing_end_date}' OR DATE(publishing_end_date) BETWEEN '{$publishing_start_date}' AND '{$publishing_end_date}' OR (DATE(publishing_start_date) <= '{$publishing_start_date}' AND DATE(publishing_end_date) >= '{$publishing_end_date}') ) AND ( TIME(publishing_start_date) BETWEEN '{$publishing_start_time}' AND '{$publishing_end_time}' OR TIME(publishing_end_date) BETWEEN '{$publishing_start_time}' AND '{$publishing_end_time}' OR (TIME(publishing_start_date) <= '{$publishing_start_time}' AND TIME(publishing_end_date) >= '{$publishing_end_time}') )");

        // Validate
        if($product_price_list->exists()){
            return back()->with('error','Invalid date');
        }

        // 
        $helper = new Helper();

        $product_id = $request->product_id;
        $store_id = $request->store_id;
        $price_id = $request->price_id;
        $message = "Dish Updated Successfully.";

        $util = new Util(env('APP42_API_KEY'),env('APP42_API_SECRET'));

        $employer = new Employer();
        $company_id = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

        // 
        $product = Product::where(['product_id' => $product_id])->first();
        $product->product_name = $request->prodName;

        if (!empty($_FILES["prodImage"]["name"]))
        {
            // 
            $basePath = app_path();
            define('UPLOAD_DIR', public_path() . '/upload/images/');
            define('IMAGE_AMAZON_PATH', 'https://s3-eu-west-1.amazonaws.com/dastjar-coupons/upload/');
            define('IMAGE_DIR_PATH', $basePath . '/lib/bin/cumbari_s3.sh ');

            // 
            $info = pathinfo($_FILES["prodImage"]["name"]);
            $file_extension = strtolower($info['extension']);

            if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "gif" || $file_extension == "bmp")
            {
                if ($_FILES["prodImage"]["error"] > 0)
                {
                    $error.=$_FILES["prodImage"]["error"] . "<br />";
                }
                else
                {
                    try{
                        $helper = new Helper();

                        $fileOriginal = $_FILES['prodImage']['tmp_name'];
                        $path = UPLOAD_DIR . "category/";

                        // Resize image (small and large)
                        $fileName = 'dish-thumbnail-'.time().'.jpg';
                        $smallImg = $helper->gumletImageResize($fileOriginal, $fileName, $path, 256);

                        $fileName = 'dish-large-'.time().'.jpg';
                        $largeImg = $helper->gumletImageResize($fileOriginal, $fileName, $path, 1024);

                        // Upload image to AWS
                        $file1 = $path.$smallImg;
                        $dir1 = "category";
                        $command = IMAGE_DIR_PATH . $file1 . " " . $dir1;
                        system($command);

                        $file2 = $path.$largeImg;
                        $dir2 = "coupon";
                        $command2 = IMAGE_DIR_PATH . $file2 . " " . $dir2;
                        system($command2);

                        // 
                        $product->small_image = IMAGE_AMAZON_PATH . 'category/' . $smallImg;
                        $product->large_image = IMAGE_AMAZON_PATH . 'coupon/' . $largeImg;
                    } catch (\Exception $ex) {
                        echo $ex->getMessage();
                        die();
                    }
                }
            }
        }
        elseif(!isset($request->smallImage))
        {
            $product->small_image = $product->large_image = 'https://s3-eu-west-1.amazonaws.com/dastjar-coupons/upload/category/cat_icon_b738a523d72867d1fc84e1f9d3c18b29.png';
        }

        $minutes = $request->prepTime;
        $hours = intdiv($minutes, 60).':'. ($minutes % 60).':00';

        $product->lang = $request->dishLang;
        $product->dish_type = $request->dishType;
        $product->product_description = $request->prodDesc;
        $product->preparation_Time = $hours;
        $product->category = "7099ead0-8d47-102e-9bd4-12313b062day";
        $product->product_number = "";
        $product->product_info_page = "";
        $product->start_of_publishing = Carbon::now();
        $product->company_id = $company_id;
        $product->save();

        $sloganSubLangId = ProductOfferSubSloganLangList::where('product_id',$product_id)->first()->offer_sub_slogan_lang_list;

        /*** insert product description in lang_text table */
        $langText = LangText::where('id',$sloganSubLangId)->first();
        $langText->lang = $request->dishLang;
        $langText->text = $request->prodDesc;
        $langText->save();

        $sloganLangId = ProductOfferSloganLangList::where('product_id',$product_id)->first()->offer_slogan_lang_list;

        /*** insert product name in lang_text table */
        $langText = LangText::where('id',$sloganLangId)->first();
        $langText->lang = $request->dishLang;
        $langText->text = $request->prodName;
        $langText->save();

        /*** insert product language in lang_text table */
        $langText = LangText::where('text',$product_id)->first();
        $langText->lang = $request->dishLang;
        $langText->save();

        // 
        $product_price_list = ProductPriceList::where(['id' => $price_id])->first();
        $product_price_list->store_id = $store_id;
        $product_price_list->text = "Price:" . $request->prodPrice . $request->currency;
        $product_price_list->price = $request->prodPrice;
        $product_price_list->lang = $request->dishLang;
        if($request->publish_start_date!=null){
            $publish_start_date = \DateTime::createFromFormat('d/m/Y H:i', $request->publish_start_date);
            $product_price_list->publishing_start_date = $publish_start_date;
        }
        if($request->publish_end_date!=null){
            $publish_end_date = \DateTime::createFromFormat('d/m/Y H:i', $request->publish_end_date);       
            $product_price_list->publishing_end_date = $publish_end_date;
        }
        $product_price_list->save();

        return redirect()->route('menu')->with('success', $message);
    }

    public function kitchenEditDish(Request $request){
        $productid = $request->product_id;
        $store_id = $request->store_id;
        $price_id = $request->price_id;

        $product = Product::where('product_id','=',$productid)->first();

        try{
                getimagesize($product->small_image);
                $product->small_image = $product->small_image;
            }catch(\Exception $ex){
                $product->small_image = null;
        }

        $product_price_list = ProductPriceList::where('id','=',$price_id)->first();

        $storedetails = Store::where('store_id' , Session::get('storeId'))->first();
        $storeName = $storedetails->store_name;

        $dishType = new DishType();

        $listDishes = $dishType->where('u_id' , '=', Auth::user()->u_id)->where('dish_activate', '=', '1')->pluck('dish_name','dish_id');

        $employer = new Employer();
        $companyId = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

        $companydetails = new Company();
        $currency = $companydetails->where('company_id' , '=', $companyId)->first()->currencies;

        $hour = explode(':', $product->preparation_Time)[0];
        $minute = explode(':', $product->preparation_Time)[1];
        $time = ($hour*60) + $minute;

        $product->preparation_Time = $time;

        return view('kitchen.menulist.createMenu',compact('product', 'product_price_list', 'store_id', 'storeName', 'listDishes', 'currency'));
    }

    /**
     * Delete product price
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function deleteDishPrice(Request $request)
    {
        $productPriceList = new ProductPriceList();
        $productPriceList->where('id', '=', $request->price_id)->delete();

        return back()->with('success','Dish Price deleted successfully');
    }

    public function kitchenDeleteDish(Request $request){
        $productid = $request->product_id;
        
        /*$productPriceList = new ProductPriceList();

        if($productPriceList->where('product_id', '=', $productid)->count() > 1){
            $productPriceList->where('id', '=', $request->price_id)->delete();
            return back()->with('success','Dish Price deleted successfully');
        }*/

        $c_s_rel = new C_S_Rel();

        $res1 = $c_s_rel->where('product_id', '=', $productid)->get();

        foreach ($res1 as $rs1) {
            $productId = $rs1['product_id'];
            $couponId = $rs1['coupon_id'];
            $storeId = $rs1['store_id'];
            
            if ($productId) {
                $res2 = $c_s_rel->where('product_id', '=', $productid)->update(['activ' => '2']);
            }

            if ($couponId) {
                $coupon = new Coupon();
                $res3 = $coupon->where('coupon_id','=',$couponId)->delete();

                $coupon_offer_slogan_lang_list = new CouponOfferSloganLangList();
                $res4 = $coupon_offer_slogan_lang_list->where('coupon','=',$couponId)->get();

                foreach ($res4 as $rs4) {
                    $offslogen = $rs4['offer_slogan_lang_list'];
                    $res5 = $coupon_offer_slogan_lang_list->where('coupon','=',$couponId)->delete();
                }

                $coupon_offer_title_lang_list = new CouponOfferTitleLangList();
                $res7 = $coupon_offer_title_lang_list->where('coupon','=',$couponId)->get();

                foreach ($res7 as $rs7) {
                    $offtitle = $rs7['offer_title_lang_list'];
                    $res8 = $coupon_offer_title_lang_list->where('coupon','=',$couponId)->delete();
                }

                $coupon_keywords_lang_list = new CouponKeywordsLangList();
                $res10 = $coupon_keywords_lang_list->where('coupon','=',$couponId)->get();

                foreach ($res10 as $rs10) {
                    $ckeyword = $rs10['keywords_lang_list'];
                    $res8 = $coupon_keywords_lang_list->where('coupon','=',$couponId)->delete();
                }
            }
        }

        $product = new Product();
        $q = $product->where('product_id', '=', $productid)->update(['s_activ' => '2']);

        return back()->with('success','Dish deleted successfully');
    }

    /**
     * Check if future date belongs to any of existing product dates
     * @param  Request $request [description]
     * @return boolean          [description]
     */
    public function isFutureDateAvailable(Request $request)
    {
        $publishing_start_date_time = \DateTime::createFromFormat('d/m/Y H:i', $request->publishing_start_date);
        $publishing_end_date_time = \DateTime::createFromFormat('d/m/Y H:i', $request->publishing_end_date);

        $publishing_start_date = $publishing_start_date_time->format('Y-m-d');
        $publishing_end_date = $publishing_end_date_time->format('Y-m-d');
        $publishing_start_time = $publishing_start_date_time->format('H:i:00');
        $publishing_end_time = $publishing_end_date_time->format('H:i:00');

        $status = 1;
        $product_price_list = new ProductPriceList();

        // $product_price_list->where('product_id', $request->product_id)->where('store_id', $request->store_id)->where('publishing_start_date','<=',$publishing_start_date)->where('publishing_end_date','>=',$publishing_start_date)->exists() || $product_price_list->where('product_id', $request->product_id)->where('store_id', $request->store_id)->where('publishing_start_date','<=',$publishing_end_date)->where('publishing_end_date','>=',$publishing_end_date)->exists() || $product_price_list->where('product_id', $request->product_id)->where('store_id', $request->store_id)->where('publishing_start_date','>=',$publishing_start_date)->where('publishing_end_date','<=',$publishing_end_date)->exists()

        if($product_price_list->where('product_id', $request->product_id)->where('store_id', $request->store_id)->whereRaw("( DATE(publishing_start_date) BETWEEN '{$publishing_start_date}' AND '{$publishing_end_date}' OR DATE(publishing_end_date) BETWEEN '{$publishing_start_date}' AND '{$publishing_end_date}' OR (DATE(publishing_start_date) <= '{$publishing_start_date}' AND DATE(publishing_end_date) >= '{$publishing_end_date}') ) AND ( TIME(publishing_start_date) BETWEEN '{$publishing_start_time}' AND '{$publishing_end_time}' OR TIME(publishing_end_date) BETWEEN '{$publishing_start_time}' AND '{$publishing_end_time}' OR (TIME(publishing_start_date) <= '{$publishing_start_time}' AND TIME(publishing_end_date) >= '{$publishing_end_time}') )")->exists())
        {
            $status = 0;
        }

        return response()->json(['status' => $status, 'publishing_start_date' => $publishing_start_date]);
    }

    public function addDishPrice(Request $request){
        $publishing_start_date_time = \DateTime::createFromFormat('d/m/Y H:i', $request->publishing_start_date);
        $publishing_end_date_time = \DateTime::createFromFormat('d/m/Y H:i', $request->publishing_end_date);

        $publishing_start_date = $publishing_start_date_time->format('Y-m-d');
        $publishing_end_date = $publishing_end_date_time->format('Y-m-d');
        $publishing_start_time = $publishing_start_date_time->format('H:i:00');
        $publishing_end_time = $publishing_end_date_time->format('H:i:00');

        $product_price_list = new ProductPriceList();

        // dd($publishing_start_date);

        // Validate
        if($product_price_list->where('product_id', $request->product_id)->where('store_id', $request->store_id)->whereRaw("( DATE(publishing_start_date) BETWEEN '{$publishing_start_date}' AND '{$publishing_end_date}' OR DATE(publishing_end_date) BETWEEN '{$publishing_start_date}' AND '{$publishing_end_date}' OR (DATE(publishing_start_date) <= '{$publishing_start_date}' AND DATE(publishing_end_date) >= '{$publishing_end_date}') ) AND ( TIME(publishing_start_date) BETWEEN '{$publishing_start_time}' AND '{$publishing_end_time}' OR TIME(publishing_end_date) BETWEEN '{$publishing_start_time}' AND '{$publishing_end_time}' OR (TIME(publishing_start_date) <= '{$publishing_start_time}' AND TIME(publishing_end_date) >= '{$publishing_end_time}') )")->exists()){
            return back()->with('error','Invalid date');
        }

        $request->merge(['publishing_start_date' => $publishing_start_date_time]);
        $request->merge(['publishing_end_date' => $publishing_end_date_time]);

        $employer = new Employer();
        $companyId = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

        $companydetails = new Company();
        $currency = $companydetails->where('company_id' , '=', $companyId)->first()->currencies;

        $request->request->add(['text' => "Price:" . $request->price . $currency]);

        $product_price_list->product_id = $request->product_id;
        $product_price_list->store_id = $request->store_id;
        $product_price_list->text = "Price:" . $request->price . $request->currency;
        $product_price_list->price = $request->price;
        $product_price_list->publishing_start_date = $request->publishing_start_date;
        $product_price_list->publishing_end_date = $request->publishing_end_date;
        $product_price_list->save();

        // Check if time doesn't cover the working hours of the restaurant
        $store = Store::select(['store_open_close_day_time'])
                    ->where('store_id', $request->store_id)
                    ->first();

        if($store)
        {
            $openTimeRestaurant = $closeTimeRestaurant = null;
            $dayOfPriceDate = $publishing_start_date_time->format('D');
            $openCloseList = explode(",", $store->store_open_close_day_time);
            $startTimeFuturePrice = \DateTime::createFromFormat('d/m/Y - H:i', $request->dateStart)->format('H:i:00');
            $endTimeFuturePrice = \DateTime::createFromFormat('d/m/Y - H:i', $request->dateEnd)->format('H:i:00');

            // Get restaurant working hours
            if( (count($openCloseList) == 1) && strpos($store->store_open_close_day_time, 'All') >= 0 )
            {
                $getDay = explode("::", $openCloseList[0]);
                $getTime = explode("to", $getDay[1]);
                $openTimeRestaurant = $getTime[0];
                $closeTimeRestaurant = $getTime[1];
            }

            // Check if future price time doesn't cover restaurant working hours
            if( $openTimeRestaurant != null & $closeTimeRestaurant != null )
            {
                if( strtotime($startTimeFuturePrice) < strtotime($openTimeRestaurant) || 
                    strtotime($endTimeFuturePrice) > strtotime($closeTimeRestaurant) )
                {
                    $request->session()->flash('warningAddFuturePrice', 1);
                }
            }
        }

        return back()->with('success','Price added successfully');
    }

    // Kitchen Payment 
    public function payment(Request $request){
        if(!empty($request->input())){
            $amount = $request->session()->get('paymentAmount') * 100;
            $stripeAccount = $request->session()->get('stripeAccount');
            $orderId = $request->session()->get('OrderId');
          try {
            $token = $request->stripeToken;
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $charge = Charge::create(array(
                'amount' => $amount,
                'currency' => 'sek',
                'description' => 'Order charge',
                'source' => $token
            ), array('stripe_account' => $stripeAccount));
            if($charge->status == "succeeded"){

                DB::table('orders')->where('order_id', $orderId)->update([
                            'online_paid' => 1,
                        ]);

                $paymentSave =  new Payment();
                $paymentSave->user_id = Auth()->id();
                $paymentSave->order_id = $orderId;
                $paymentSave->transaction_id = $charge->application;
                $paymentSave->amount = $charge->amount;
                $paymentSave->balance_transaction = $charge->balance_transaction;
                $paymentSave->save();

                $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
                $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();
                return view('kitchen.order.order-detail', compact('order','orderDetails'))->with('success', 'Payment Done Successfully');

            }else{

            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
          }
        }else{
            if(Auth::guard('admin')->user()->store_id == null){
                $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
                $storeName = $companydetails->company_name;
            }else{
                $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
                $storeName = $storedetails->store_name;
            }

              return view('kitchen.order.index', compact('storeName'));
        }
    }

    public function sentOtp(Request $request){
        if(!empty($request->input())){
            $data = $request->input();
            $afterRemoveFirstZeroNumber = substr($data['mobileNo'], 0, 1);
            if($afterRemoveFirstZeroNumber == 0){
                $number = substr($data['mobileNo'], -9);
            }else{
               $number =  $data['mobileNo'];
            }
            $user = User::where(['phone_number' => $number])->first();
            if($user){
                //$afterRemoveFirstZeroNumber = substr($user->phone_number, -9);
                $request->session()->put('userPhoneNumber', $user->phone_number);
                $recipients = ['+'.$user->phone_number_prifix.$user->phone_number];
                $otp = rand(1000, 9999);
                DB::table('customer')->where('phone_number', $number)->update(['otp' => $otp,]);
                $url = "https://gatewayapi.com/rest/mtsms";
                $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
                $message =  $otp;
                $json = [
                    'sender' => 'Dastjar',
                    'message' => ''.$message.'',
                    'recipients' => [],
                ];
                foreach ($recipients as $msisdn) {
                    $json['recipients'][] = ['msisdn' => $msisdn];}

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch); 
               
                $json = json_decode($result);
                return view('auth.otp');
                if($json->message == 'Insufficient credit'){
                    return redirect()->action('Auth\LoginController@mobileLogin')->with('success', 'Otp is not sent due to some technical issue.');
                }else{
                    return view('auth.otp');
                }
            }
            return redirect()->action('Auth\RegisterController@userRegister')->with('success', 'Your Number is not register.Please register mobile number');

        }else{
            return view('auth.otp');
        }
    }

    /**
     * Check the count of item of order that have been started
     * @param  Request $request [description]
     * @param  [type]  $orderId [description]
     * @return boolean          [description]
     */
    function isManualPrepTimeForOrder(Request $request, $orderId)
    {
        $count = OrderDetail::where(['order_id' => $orderId, 'order_started' => 1])->count();

        return response()->json(['count' => $count]);
    }

    /**
     * [addManualPrepTime description]
     * @param Request $request [description]
     */
    function addManualPrepTime(Request $request)
    {
        $status = 0;

        // Update extra preparation time
        $result = Order::where('order_id', $request->order_id)
            ->update(['extra_prep_time' => $request->extra_prep_time]);

        if($result)
        {
            $status = 1;
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Update 'order item' as started from 'Kitchen Menu'
     * @param  Request $request     [description]
     * @param  [type]  $orderItemId [description]
     * @return [type]               [description]
     */
    public function orderStartedKitchen(Request $request, $orderItemId){
        if( DB::table('order_details')->where('id', $orderItemId)->update(['order_started' => 1]) )
        {
            $helper = new Helper();

            // Check if all item has started for an order and update order as 'order_started' too
            $orderId = OrderDetail::select(['order_id'])->where('id', $orderItemId)->first()->order_id;

            if( !OrderDetail::where(['order_id' => $orderId, 'order_started' => 0])->count() )
            {
                $arrOrderUpdate['order_started'] = 1;
            }

            // Check and update order as accepted if not already accepted
            if( Order::where(['order_id' => $orderId, 'order_accepted' => 0])->count() )
            {
                $arrOrderUpdate['order_accepted'] = 1;
            }

            Order::where('order_id', $orderId)->update($arrOrderUpdate);

            // If order accepted, send 'order accepted' notification
            if( isset($arrOrderUpdate['order_accepted']) )
            {
                $this->onOrderAccepted($orderId);
                $helper->logs("Order Accepted: Order ID - " . $orderId);
            }

            $helper->logs("Order Item Started: ID - " . $orderItemId);
        }

        return response()->json(['status' => 'success', 'data'=>true]);
    }

    /**
     * Update 'order' and 'order item' status as ready from 'Kitchen Menu'
     * @param  Request $request [description]
     * @param  [type]  $orderID [description]
     * @return [type]           [description]
     */
    public function orderReadyKitchen(Request $request, $orderID)
    {
        $helper = new Helper();        
        try{
            $helper->logs("Order Ready: Order Item ID - " . $orderID);

            DB::table('order_details')->where('id', $orderID)->update([
                'order_ready' => 1,
            ]);

            $orderDetail = OrderDetail::where('id' , $orderID)->first();

            $userOrderStatus = OrderDetail::where('order_id' , $orderDetail->order_id)->get();
            $readyOrderStatus = OrderDetail::where('order_id' , $orderDetail->order_id)->where('order_ready' , '1')->get();

            $cntOrderNotReady = OrderDetail::where(['order_id' => $orderDetail->order_id, 'order_ready' => 0])->count();

            if( !$cntOrderNotReady ){
                $OrderId = Order::where('order_id' , $orderDetail->order_id)->first();
                DB::table('orders')->where('order_id', $orderDetail->order_id)->update([
                    'order_ready' => 1,
                ]);

                if($OrderId->user_id != 0)
                {
                    $recipients = [];                
                    if($OrderId->user_type == 'customer'){
                        $adminDetail = User::where('id' , $OrderId->user_id)->first();
                        if(isset($adminDetail->phone_number_prifix) && isset($adminDetail->phone_number)){
                            $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];
                        }
                    }
                    else{
                        $adminDetail = Admin::where('id' , $OrderId->user_id)->first();
                        $recipients = ['+'.$adminDetail->mobile_phone];
                    }                

                    if(isset($adminDetail->browser)){
                        $pieces = explode(" ", $adminDetail->browser);
                    }else{
                        $pieces[0] = '';                              
                    }
                }
                else
                {
                    $pieces[0] = '';               
                }

                if( ($pieces[0] == 'Safari') || ( isset($adminDetail->browser) && strpos($adminDetail->browser, 'Mobile/') !== false ) || ( isset($adminDetail->browser) && strpos($adminDetail->browser, 'wv') !== false ) )
                {
                    /*$url = "https://gatewayapi.com/rest/mtsms";
                    $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
                    $message = "Your Order Ready Please click on Link \n ".env('APP_URL').'ready-notification/'.$OrderId->customer_order_id;
                    $json = [
                        'sender' => 'Dastjar',
                        'message' => ''.$message.'',
                        'recipients' => [],
                    ];
                    foreach ($recipients as $msisdn) {
                        $json['recipients'][] = ['msisdn' => $msisdn];}

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                    curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    curl_close($ch);   */

                    $message = __('messages.notificationOrderReady', ['order_id' => $OrderId->customer_order_id]);
                    $result = $this->apiSendTextMessage($recipients, $message);

                    $helper->logs("Order Ready: IOS notification sent. Order Item ID - " . $orderID);
                }
                else
                {
                    if($OrderId->user_id != 0){
                        $message = 'orderReady';
                        $result = $this->sendNotifaction($OrderId->customer_order_id , $message);
                        
                        $helper->logs("Order Ready: Android notification sent. Order Item ID - " . $orderID);
                    }
                }

                return redirect()->back()->with('success', 'Order Ready Notification Send Successfully.');
            }

            return redirect()->back()->with('success', 'Order Item Ready Successfully.');
        }
        catch(\Exception $ex){
            $helper->logs("Step 6: Exception = " .$ex->getMessage());            
        }
    }

    /**
     * Send message/notification on order accepted
     * @return [type] [description]
     */
    function onOrderAccepted($orderId)
    {
        $helper = new Helper();

        try{
            $helper->logs("Step 1: Order Accepted, order id - ".$orderId);

            // Get order detail
            $order = Order::select(['user_id', 'user_type', 'customer_order_id'])
                ->where('order_id' , $orderId)
                ->first();

            if($order->user_id != 0)
            {
                // Get the user's browser detail and phone number
                $recipients = [];

                if($order->user_type == 'customer'){
                    $adminDetail = User::where('id' , $order->user_id)->first();
                    
                    if(isset($adminDetail->phone_number_prifix) && isset($adminDetail->phone_number)){
                        $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];
                    }
                }
                else{
                    $adminDetail = Admin::where('id' , $order->user_id)->first();
                    $recipients = ['+'.$adminDetail->mobile_phone];
                }

                //
                if(isset($adminDetail->browser)){
                    $pieces = explode(" ", $adminDetail->browser);
                }else{
                    $pieces[0] = '';
                }

                $helper->logs("Step 2: Recipient calculation - ".$orderId." and browser - ".$pieces[0]);

                // Send message/notification to user
                // if($pieces[0] == 'Safari')
                if( ($pieces[0] == 'Safari') || ( isset($adminDetail->browser) && strpos($adminDetail->browser, 'Mobile/') !== false ) || ( isset($adminDetail->browser) && strpos($adminDetail->browser, 'wv') !== false ) )
                {
                    // $message = "Your recent order has been accepted. Your order number is: {$order->customer_order_id}";
                    $message = __('messages.notificationOrderReceived', ['order_id' => $order->customer_order_id]);
                    $result = $this->apiSendTextMessage($recipients, $message);

                    $helper->logs("Step 3: IOS notification sent - ".$orderId." and result - ".$result);
                }
                else
                {
                    $message = 'orderAccepted';
                    $result = $this->sendNotifaction($order->customer_order_id , $message);

                    $helper->logs("Step 3: Android notification sent - ".$orderId." and result - " .$result);
                }
            }
            else
            {
                $helper->logs("Step 2: ELSE; Order ID - ".$orderId);
            }
        } catch(\Exception $ex) {
            $helper->logs("Order ID: ".$orderId."; Exception - ".$ex->getMessage());
        }
    }

    /**
     * Send text message to recipients using API
     * @return [type] [description]
     */
    function apiSendTextMessage($recipients = array(), $message = '')
    {
        if( !is_array($recipients) && empty($recipients) )
        {
            return false;
        }

        //
        $url = "https://gatewayapi.com/rest/mtsms";
        $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
        
        $json = [
            'sender' => 'Dastjar',
            'message' => ''.$message.'',
            'recipients' => [],
        ];

        foreach ($recipients as $msisdn)
        {
            $json['recipients'][] = ['msisdn' => $msisdn];
        }

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /*public function onReadyAjax(Request $request, $orderID){
        dd(DB::table('order_details')->where('id', $orderID)->first());
        DB::table('order_details')->where('id', $orderID)->update(['order_ready' => 1]);
        $userOrderId = OrderDetail::where('id' , $orderID)->first();
        $userOrderStatus = OrderDetail::where('order_id' , $userOrderId->order_id)->get();
        $readyOrderStatus = OrderDetail::where('order_id' , $userOrderId->order_id)->where('order_ready' , '1')->get();

        if(count($userOrderStatus) == count($readyOrderStatus)){
            $OrderId = Order::where('order_id' , $userOrderId->order_id)->first();
            DB::table('orders')->where('order_id', $userOrderId->order_id)->update([
                            'order_ready' => 1,
                        ]);

            $message = 'orderReady';
            if($OrderId->user_type == 'customer'){
                $adminDetail = User::where('id' , $OrderId->user_id)->first();
                $recipients = ['+'.$adminDetail->phone_number_prifix.$adminDetail->phone_number];
            }else{
                $adminDetail = Admin::where('id' , $OrderId->user_id)->first();
                $recipients = ['+'.$adminDetail->mobile_phone];
            }
            $pieces = explode(" ", $adminDetail->browser);
            if($pieces[0] == 'Safari'){
               // dd($recipients);
                $url = "https://gatewayapi.com/rest/mtsms";
                $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
                $message = "Your order ready please click on link \n ".env('APP_URL').'ready-notification/'.$OrderId->customer_order_id;
                $json = [
                    'sender' => 'Dastjar',
                    'message' => ''.$message.'',
                    'recipients' => [],
                ];
                foreach ($recipients as $msisdn) {
                    $json['recipients'][] = ['msisdn' => $msisdn];}

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);   
            }else{
                    $result = $this->sendNotifaction($OrderId->customer_order_id , $message); 
            }
            return response()->json(['status' => 'success', 'data'=>'Order Ready Notification Send Successfully.']);
        }

        return response()->json(['status' => 'ready', 'data'=>'Order Ready Successfully.']);
    }*/
      
    public function extraPrepTime(){
        $storeId = Session::get('storeId');

        $store = new Store();
        
        if($store->where('store_id', $storeId)->first()->extra_prep_time == null){
            $prep_time = 0;
        }else{
            $prep_time = $store->where('store_id', $storeId)->first()->extra_prep_time;
            $hour = explode(':', $prep_time)[0];
            $minute = explode(':', $prep_time)[1];
            $prep_time = ($hour*60) + $minute;
        }

        return view('kitchen.setting.prep-time')->with("prep_time", $prep_time);
    }

    public function addExtraTime(Request $request){
        $storeId = Session::get('storeId');

        $minutes = $request->extra_prep_time;
        $hours = intdiv($minutes, 60).':'. ($minutes % 60).':00';

        $store = new Store();
        $store->where('store_id', $storeId)->update(['extra_prep_time' => $hours]);

        return response()->json(['status' => 'success', 'data'=>'Preperation Time Added Successfully.']);        
    }

    public function support(Request $request){
        $data = array('msg'=>$request->message);

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   
        $headers .='X-Mailer: PHP/' . phpversion();
        $headers .= "From: Anar <admin@dastjar.com> \r\n"; // header of mail content

        mail('support@dastjar.com', 'Dastjar Support Mail', $request->message, $headers);

        return redirect()->back()->with('success', 'Thank you for contacting us.');
    }

    public function removeOrder(Request $request){
        $order = new Order();
        $order->where('order_id',$request->order_id)->update(['cancel'=>1]);
        $order_number = $order->where('order_id',$request->order_id)->first()->customer_order_id;

        $message = 'Your order ' . $order_number . ' has been cancelled according to your request';

        $phone_number_prifix = User::where('id',$request->user_id)->first()->phone_number_prifix;
        $phone_number = User::where('id',$request->user_id)->first()->phone_number;

        $url = "https://gatewayapi.com/rest/mtsms";
        $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";

        $json = [
                'sender' => 'Dastjar',
                'message' => ''.$message.'',
                'recipients' => [],
                ];

        $recipients = ['+'.$phone_number_prifix.$phone_number];

        foreach ($recipients as $msisdn) {
                    $json['recipients'][] = ['msisdn' => $msisdn];}

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
                curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);   

        return response()->json(['status' => 'success', 'data'=>'Order Cancelled Successfully.']);        
    }

    /**
     * [updateOrderDetailStatus function to update order status if order is new]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function updateOrderDetailStatus(Request $request) {
        $orderDetail = OrderDetail::where('id', $request->id)
            ->update(['is_new' => 0]);

        if($orderDetail) {
            return response()->json(['status' => 'success', 'data'=>'Updated Successfully.']);        
        }
    }

    public function getDates(){
        $product_price_list = new ProductPriceList();
        dd($product_price_list->where('product_id', '0093170c-91ff-c18f-7e65-8fde17826111')->get()->toArray());

    }

     public function kitchenMenuNew($dish_id, $storeId){
            $products = new Product();
            $productPriceList = new ProductPriceList();
            $productOfferSloganLangList = new ProductOfferSloganLangList();
            $productOfferSubSloganLangList = new ProductOfferSubSloganLangList();
            $langText = new LangText();

            $allData = [];

            $employer = new Employer();
            $companyId = $employer->where('u_id' , '=', Auth::user()->u_id)->first()->company_id;

            $menuTypes = DishType::where('company_id', $companyId)
                ->where('dish_id','>',$dish_id)
                ->orderBy('dish_id', 'ASC')->first();

            if($menuTypes == null){
                $data = null;
                return response()->json(['status' => 'success', 'response' => true,'data'=>$data]);
            }

            $dishName = $menuTypes->dish_name;
            $dishId = $menuTypes->dish_id;

            $prods = $products->where('u_id', Auth::user()->u_id)
                ->join('product_price_list', 'product_price_list.product_id','=','product.product_id')
                ->join('product_offer_slogan_lang_list','product_offer_slogan_lang_list.product_id','=','product.product_id')
                ->join('product_offer_sub_slogan_lang_list','product_offer_sub_slogan_lang_list.product_id','=','product.product_id')
                ->join('lang_text as prod_name','prod_name.id','=','product_offer_slogan_lang_list.offer_slogan_lang_list')
                ->join('lang_text as prod_desc','prod_desc.id','=','product_offer_sub_slogan_lang_list.offer_sub_slogan_lang_list')
                ->where('store_id', Session::get('storeId'))
                ->where('s_activ', '!=' , 2)
                ->where('dish_type',$dishId)
                ->orderBy('product_rank', 'ASC')                
                ->get()->groupBy('dish_type');
            // dd($prods->toArray());

        /*
             foreach($prods as $k=>$r){
                foreach($r as $k2=>$r2){
                    if(isset($prodprices[$r2->product_id])){
                        $data = [];
                        $data['product_id'] = $r2->product_id;

                        $sloganLangId = $productOfferSloganLangList->where('product_id',$data['product_id'])->first()->offer_slogan_lang_list;
                        $sloganSubLangId = $productOfferSubSloganLangList->where('product_id',$data['product_id'])->first()->offer_sub_slogan_lang_list;

                        $prodName = $langText->where('id',$sloganLangId)->first()->text;
                        $prodDesc = $langText->where('id',$sloganSubLangId)->first()->text;

                        $data['product_name'] = $prodName;
                        $data['product_description'] = $prodDesc;
                        try{
                            getimagesize($r2->small_image);
                            $data['small_image'] = $r2->small_image;
                        }catch(\Exception $ex){
                            $data['small_image'] = asset('images/placeholder-image.png');
                        }

                        $data['publishing_start_date'] = $r2->publishing_start_date;
                        $data['publishing_end_date'] = $r2->publishing_end_date;

                        foreach($prodprices[$r2->product_id] as $pk=>$pr){
                            $prices['price_id'] = $pr->id;
                            $prices['price'] = $pr->price;
                            $prices['publishing_start_date'] = $pr->publishing_start_date;
                            $prices['publishing_end_date'] = $pr->publishing_end_date;

                            $data['prices'][] = $prices;
                        }

                        if(!empty($r2->dish_type)){
                            $allData[$r2->dish_type][] = $data;
                        }
                    }
                }
                break;
            }*/

            foreach($prods as $k2=>$r2){
                foreach($r2 as $k=>$r){
                    if(isset($r->product_id)){
                        $data = [];
                        $data['product_id'] = $r->product_id;
                        $data['product_name'] = $r->product_name;
                        $data['product_description'] = $r->product_description;

                        $data['small_image'] = $r->small_image;
                        $data['publishing_start_date'] = $r->publishing_start_date;
                        $data['publishing_end_date'] = $r->publishing_end_date;

                        $prices = [];

                        $prices['price_id'] = $r->id;
                        $prices['price'] = $r->price;
                        $prices['publishing_start_date'] = $r->publishing_start_date;
                        $prices['publishing_end_date'] = $r->publishing_end_date;

                        $data['prices'][] = $prices;
                                            // dd($r->dish_type);

                        if(!empty($r->dish_type)){
                            $allData[$r->product_id][] = $data;
                        }
                    }
                }
            }
                // dd($allData);


            $storedetails = Store::where('store_id' , Session::get('storeId'))->first();
            $storeName = $storedetails->store_name;

            $companydetails = new Company();
            $currency = $companydetails->where('company_id' , '=', $companyId)->first()->currencies;

            // dd($allData);

            $data = compact('dishName', 'dishId','storeName', 'currency', 'allData');

            return response()->json(['status' => 'success', 'response' => true,'data'=>$data]);

    }

    /**
     * Return new orders detail that havn't been started yet 
     * @return [type] [description]
     */
    function getNewOrdersDetailToSpeak()
    {
        $storeId = Session::get('storeId');

        // 
        $orderDetail = OrderDetail::select('order_details.id', 'order_details.product_quality', 'order_details.product_description', 'order_details.is_speak', 'product.product_name')
            ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->join('product', 'product.product_id', '=', 'order_details.product_id')
            ->where(['orders.store_id' => $storeId, 'user_type' => 'customer', 'check_deliveryDate' => Carbon::now()->toDateString(), 'orders.order_started' => '0', 'orders.paid' => '0'])
            ->whereNotIn('orders.online_paid', [2])
            ->where('orders.cancel','!=', 1)
            ->get();

        // Logged-in user setting's detail
        $text_speech = Auth::guard('admin')->user()->text_speech;

        return response()->json(['orderDetail' => $orderDetail, 'text_speech' => $text_speech, 'kitchenTextToSpeechDefault' => __('messages.kitchenTextToSpeechDefault')]);
    }
}
