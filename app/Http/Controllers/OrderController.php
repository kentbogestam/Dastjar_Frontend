<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\ProductPriceList;
use Carbon\Carbon;
use DB;
use App\Store;
use App\User;
use App\Company;
use App\Admin;
use Session;

class OrderController extends Controller
{
   /* public function saveOrder(Request $request){

        if(Auth::check()){

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

                // Get store detail
                $storeDetail = Store::where('store_id', $data['storeID'])->first();

                //
                foreach ($data['product'] as $key => $value) {
                    //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                    if($value['prod_quant'] != '0'){
                        $productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
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

                            if($storeDetail->order_response == 0 && $orderType == 'eat_now')
                            {
                                $order->order_accepted = 0;
                            }

                            $order->save();
                            $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                            $orderId = $orders->order_id;
                            $i = $i+1;
                        }

                        $i = 1;
                        if($max_time < $productTime->preparation_Time){
                            $max_time = $productTime->preparation_Time;
                        }else{}
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
                    }
                }

                DB::table('orders')->where('order_id', $orderId)->update([
                            'order_delivery_time' => $max_time,
                            'order_total' => $total_price,
                        ]);

                User::where('id',Auth::id())->update(['browser' => $data['browser']]);

                $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

                $request->session()->put('currentOrderId', $order->order_id);
                //$orderDetails = OrderDetail::select('*')->where('order_id',$orderId)->get();
                $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

                //If store support ontine payment then if condition run.
                if($storeDetail->online_payment == 1){
                    $companyDetail = Company::where('company_id', $productTime->company_id)->first();
                    $companyUserDetail = Admin::where('u_id', $companyDetail->u_id)->first();


                    DB::table('orders')->where('order_id', $orderId)->update([
                            'online_paid' => 2,
                        ]);
                    $request->session()->put('paymentAmount', $order->order_total);
                    $request->session()->put('OrderId', $order->order_id);

                    if(isset($companyUserDetail->stripe_user_id))
                    $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);

                    return view('order.paymentIndex', compact('order','orderDetails'));
                }else{
                    $user = User::where('id',$order->user_id)->first();      
                    // return view('order.index', compact('order','orderDetails','storeDetail','user'));

                    return redirect()->route('order-view', $orderId);
                }
            }else{
                $todayDate = $request->session()->get('browserTodayDate');
                $currentTime = $request->session()->get('browserTodayTime');
                $todayDay = $request->session()->get('browserTodayDay');
                $userDetail = User::whereId(Auth()->id())->first();

                if(!isset($userDetail->range)){
                  $range = 10;
                }else{
                    $range = $userDetail->range;
                }

                $companydetails = Store::getListRestaurants($request->session()->get('with_login_lat'),$request->session()->get('with_login_lng'),$range,'1','3',$todayDate,$currentTime,$todayDay);
                
                return view('index', compact('companydetails'));
            } 
        }else{

          $data = $request->input();
          Session::put('orderData', $data);
          return redirect()->route('customer-login');
        }
    }*/

 public function saveOrder(Request $request){

   if(Auth::check()){

      $data = $request->input();

     $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$data['orderid'])->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

                
    $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name','order_details.product_id')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$data['orderid'])->get();

      return view('order.paymentIndex', compact('order','orderDetails'));
   }   
        
}

    /*public function withOutLogin(Request $request){
        $data = Session::get('orderData');
        if(!empty($data)){
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

            // Get store detail
            $storeDetail = Store::where('store_id', $data['storeID'])->first();

            //
            foreach ($data['product'] as $key => $value) {
                //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                if($value['prod_quant'] != '0'){
                    $productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
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

                        if($storeDetail->order_response == 0 && $orderType == 'eat_now')
                        {
                            $order->order_accepted = 0;
                        }

                        $order->save();
                        $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                        $orderId = $orders->order_id;
                        $i = $i+1;
                    }else{}

                    $i = 1;
                    if($max_time < $productTime->preparation_Time){
                        $max_time = $productTime->preparation_Time;
                    }else{}
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
                }
            }

            DB::table('orders')->where('order_id', $orderId)->update([
                        'order_delivery_time' => $max_time,
                        'order_total' => $total_price,
                    ]);

            User::where('id',Auth::id())->update(['browser' => $data['browser']]);

            $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

            $request->session()->put('currentOrderId', $order->order_id);
            //$orderDetails = OrderDetail::select('*')->where('order_id',$orderId)->get();
            $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

            //If store support ontine payment then if condition run.
            if($storeDetail->online_payment == 1){
                $companyDetail = Company::where('company_id', $productTime->company_id)->first();
                $companyUserDetail = Admin::where('u_id', $companyDetail->u_id)->first();
                DB::table('orders')->where('order_id', $orderId)->update([
                        'online_paid' => 2,
                    ]);
                $request->session()->put('paymentAmount', $order->order_total);
                $request->session()->put('OrderId', $order->order_id);
                if(isset($companyUserDetail->stripe_user_id)){
                    $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);
                }
                Session::forget('orderData');
                 return view('order.cart', compact('order','orderDetails'));
            }else{
                $user = User::where('id',$order->user_id)->first();                      
                return redirect()->route('order-view', $orderId);
            }
        }else{
            $todayDate = $request->session()->get('browserTodayDate');
            $currentTime = $request->session()->get('browserTodayTime');
            $todayDay = $request->session()->get('browserTodayDay');

            $userDetail = User::whereId(Auth()->id())->first();

            
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

            $todayDate = Carbon::now()->format('d-m-Y');

            // dd($userDetail);
            // dd("lat: " .$lat . ", lng: " . $lng . ", userDetail-range: " . $userDetail->range . ",todayDate: " . $todayDate . ",currentTime: " . $currentTime . ",todayDay: " . $todayDay);

            if(!isset($userDetail->range)){
                $range = 10;
            }else{
                $range = $userDetail->range;
            }
            
            $companydetails = Store::getListRestaurants($lat,$lng,$range,'1','3',$todayDate,$currentTime,$todayDay);
            
            return view('index', compact('companydetails'));
        } 
    }*/

    public function orderView($orderId){
        if( Session::has('paymentmode') && Session::get('paymentmode') == 0 )
        {
            DB::table('orders')->where('order_id', $orderId)->update([
                'online_paid' => 0,
            ]);
        }

        $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

        $storeDetail = Store::where('store_id', $order->store_id)->first();
        $user = User::where('id',$order->user_id)->first();

        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

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

        return view('order.index', compact('order','orderDetails','storeDetail','user'));
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
            // Get order and associated store detail
            $order = Order::select('orders.customer_order_id', 'orders.order_type', 'orders.deliver_date', 'orders.order_delivery_time', 'orders.order_accepted', 'orders.extra_prep_time', 'store.store_name', 'store.phone', 'store.extra_prep_time AS extra_prep_time_store', 'store.order_response')
            ->join('store', 'store.store_id', '=', 'orders.store_id')
            ->where('orders.order_id', $orderId)
            ->first();

            $status = true;

            //
            $responseStr .= '<p>'.__('messages.Thanks for your order').'</p>';
            $responseStr .= '<p>'.__('messages.Order Number').'</p>';
            $responseStr .= "<p class='large-text'>{$order->customer_order_id}</p>";
            $responseStr .= "<p>({$order->store_name})</p>";

            if( is_numeric($order->phone) )
            {
                $responseStr .= "<p><i class='fa fa-phone' aria-hidden='true'></i> <span>{$order->phone}</span></p>";
            }

            $time = $order->order_delivery_time;
            $time2 = $order->extra_prep_time_store;
            $secs = strtotime($time2)-strtotime("00:00:00");
            $result = date("H:i:s",strtotime($time)+$secs);

            $responseStr .= '<p>';
            if($order->order_type == 'eat_later')
            {
                $responseStr .= __('messages.Your order will be ready on').' '.$order->deliver_date.' '.date_format(date_create($order->deliver_time), 'G:i');;
            }
            else
            {
                $responseStr .= __('messages.Your order will be ready in about').' ';

                if(!$order->order_response && $order->extra_prep_time)
                {
                    $responseStr .= $order->extra_prep_time.' mins';
                }
                else
                {
                    if(date_format(date_create($result), 'H')!="00")
                    {
                        $responseStr .= date_format(date_create($result), 'H').' hours ';
                    }

                    $responseStr .= date_format(date_create($result), 'i').' mins';
                }
            }
            $responseStr .= '</p>';
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

 public function cartWithOutLogin(Request $request){
       
        $data = Session::get('orderData');
        if(!empty($data)){
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

            // Get store detail
            $storeDetail = Store::where('store_id', $data['storeID'])->first();

            //
            foreach ($data['product'] as $key => $value) {
                //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                if($value['prod_quant'] != '0'){
                    $productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
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

                        if($storeDetail->order_response == 0 && $orderType == 'eat_now')
                        {
                            $order->order_accepted = 0;
                        }

                        $order->save();
                        $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                        $orderId = $orders->order_id;
                        $i = $i+1;
                    }else{}

                    $i = 1;
                    if($max_time < $productTime->preparation_Time){
                        $max_time = $productTime->preparation_Time;
                    }else{}
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
                }
            }

            DB::table('orders')->where('order_id', $orderId)->update([
                        'order_delivery_time' => $max_time,
                        'order_total' => $total_price,
                    ]);

            User::where('id',Auth::id())->update(['browser' => $data['browser']]);

            $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

            $request->session()->put('currentOrderId', $order->order_id);
           
            $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name','order_details.product_id')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();
            
            //If store support ontine payment then if condition run.
            if($storeDetail->online_payment == 1){
                $companyDetail = Company::where('company_id', $productTime->company_id)->first();
                $companyUserDetail = Admin::where('u_id', $companyDetail->u_id)->first();
                DB::table('orders')->where('order_id', $orderId)->update([
                        'online_paid' => 2,
                    ]);
                $request->session()->put('paymentAmount', $order->order_total);
                $request->session()->put('OrderId', $order->order_id);
                if(isset($companyUserDetail->stripe_user_id)){
                    $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);
                }
                Session::forget('orderData');
                 $request->session()->put('paymentmode',1);
                 return view('order.cart', compact('order','orderDetails'));
            }else{
                $user = User::where('id',$order->user_id)->first(); 
                   $request->session()->put('paymentmode',0);
                return view('order.cart', compact('order','orderDetails'));                     
               //return redirect()->route('order-view', $orderId);
            }
        }else{
            $todayDate = $request->session()->get('browserTodayDate');
            $currentTime = $request->session()->get('browserTodayTime');
            $todayDay = $request->session()->get('browserTodayDay');

            $userDetail = User::whereId(Auth()->id())->first();

            
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

            $todayDate = Carbon::now()->format('d-m-Y');


            if(!isset($userDetail->range)){
                $range = 10;
            }else{
                $range = $userDetail->range;
            }
            
            $companydetails = Store::getListRestaurants($lat,$lng,$range,'1','3',$todayDate,$currentTime,$todayDay);
            
            return view('index', compact('companydetails'));
        } 
    }

    public function cart(Request $request){
        if(Auth::check()){
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

                // Get store detail
                $storeDetail = Store::where('store_id', $data['storeID'])->first();

                //
                foreach ($data['product'] as $key => $value) {
                    //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                    if($value['prod_quant'] != '0'){
                        $productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
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

                            if($storeDetail->order_response == 0 && $orderType == 'eat_now')
                            {
                                $order->order_accepted = 0;
                            }

                            $order->save();
                            $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                            $orderId = $orders->order_id;
                            $i = $i+1;
                        }

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
                    }
                }

                DB::table('orders')->where('order_id', $orderId)->update([
                            'order_delivery_time' => $max_time,
                            'order_total' => $total_price,
                        ]);

                User::where('id',Auth::id())->update(['browser' => $data['browser']]);

                $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

                $request->session()->put('currentOrderId', $order->order_id);
                
                $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name','order_details.product_id')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

                //If store support ontine payment then if condition run.
                if($storeDetail->online_payment == 1){
                 //   echo "else with payment";exit;
                    $companyDetail = Company::where('company_id', $productTime->company_id)->first();
                    $companyUserDetail = Admin::where('u_id', $companyDetail->u_id)->first();


                    DB::table('orders')->where('order_id', $orderId)->update([
                            'online_paid' => 2,
                        ]);
                    $request->session()->put('paymentAmount', $order->order_total);
                    $request->session()->put('OrderId', $order->order_id);

                    if(isset($companyUserDetail->stripe_user_id))
                    $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);
                     $request->session()->put('paymentmode',1);
                    return view('order.cart', compact('order','orderDetails'));
                }else{
                   
                     DB::table('orders')->where('order_id', $orderId)->update([
                            'online_paid' => 2,
                        ]);
                     $request->session()->put('paymentmode',0);
                    $user = User::where('id',$order->user_id)->first();      
                    
                    return view('order.cart', compact('order','orderDetails'));
                  //  return redirect()->route('order-view', $orderId);
                }
            }else{
                $todayDate = $request->session()->get('browserTodayDate');
                $currentTime = $request->session()->get('browserTodayTime');
                $todayDay = $request->session()->get('browserTodayDay');
                $userDetail = User::whereId(Auth()->id())->first();

                if(!isset($userDetail->range)){
                  $range = 10;
                }else{
                    $range = $userDetail->range;
                }

                $companydetails = Store::getListRestaurants($request->session()->get('with_login_lat'),$request->session()->get('with_login_lng'),$range,'1','3',$todayDate,$currentTime,$todayDay);
                
                return view('index', compact('companydetails'));
            }
        }else{
            $data = $request->input();
            Session::put('orderData', $data);
            return redirect()->route('customer-login');
        }
    }

 public function updateCart(Request $request){

    $data = $request->input();

    if($data['qty']!=0){

        $order = Order::select('orders.*')->where('orders.order_id',$data['orderid'])->get();
          
        $orderDetail = OrderDetail::select('*')->where('order_id',$data['orderid'])->where('product_id',$data['productId'])->get();

        
        DB::table('order_details')->where('order_id', $data['orderid'])->where('product_id',$data['productId'])->update([
                                'product_quality' => $data['qty']
                            ]);

        DB::table('orders')->where('order_id', $data['orderid'])->update([
                                'order_total' => $data['grandtotal']
                            ]);

        $request->session()->put('paymentAmount', $data['grandtotal']);

    }elseif($data['grandtotal']!=0){

        DB::table('order_details')->where('order_id', $data['orderid'])->where('product_id',$data['productId'])->delete();

         $request->session()->put('paymentAmount', $data['grandtotal']);
    }
    elseif($data['grandtotal']==0 && $data['productId']==0 && $data['qty']==0){

        $this->deleteWholecart($data['orderid']);
    }
      
         return response()->json(['status' => 'success', 'response' => true,'data'=>'Logs written successfully']);

           
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
 }

public function deleteWholecart($orderid){

          DB::table('orders')->where('order_id', $orderid)->delete();

          DB::table('order_details')->where('order_id', $orderid)->delete();
  }

    
}
