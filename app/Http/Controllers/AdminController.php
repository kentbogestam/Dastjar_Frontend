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
use DB;
use Carbon\Carbon;
use App\Store;
use Session;
use App\User;

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

    public function index(){
            if(Auth::guard('admin')->user()->store_id == null){
                $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
                $storeName = $companydetails->company_name;
            }else{
                $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
                $storeName = $storedetails->store_name;
            }

              return view('kitchen.order.index', compact('storeName'));
    }

    public function orderDetail(){

        if(Auth::guard('admin')->user()->store_id == null){

            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $reCompanyId = $companydetails->company_id;

            $orderDetailscustomer = Order::select('orders.*','customer.name as name')->where(['company_id' => $reCompanyId])->where('user_type','=','customer')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->join('customer','orders.user_id','=','customer.id');
            $orderDetails = Order::select('orders.*','user.fname as name')->where('orders.company_id', '=' ,$reCompanyId)->where('user_type','=','admin')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->join('user','orders.user_id','=','user.id');
            $results = $orderDetailscustomer->union($orderDetails)->get();

        }else{
            //In this function where condition work store_id
            $reCompanyId = Auth::guard('admin')->user()->store_id;

            $orderDetailscustomer = Order::select('orders.*','customer.name as name')->where(['store_id' => $reCompanyId])->where('user_type','=','customer')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->join('customer','orders.user_id','=','customer.id');
            $orderDetails = Order::select('orders.*','user.fname as name')->where('orders.store_id', '=' ,$reCompanyId)->where('user_type','=','admin')->where('check_deliveryDate',Carbon::now()->toDateString())->where('orders.paid', '0')->whereNotIn('orders.online_paid', [2])->join('user','orders.user_id','=','user.id');
            $results = $orderDetailscustomer->union($orderDetails)->get();           
        }
        // $user = Admin::where(['u_id' => Auth::guard('admin')->user()->company_id])->first();
        return response()->json(['status' => 'success', 'response' => true,'data'=>$results]);
    }

    public function kitchenOrderDetail(){
        if(Auth::guard('admin')->user()->store_id == null){
            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $storeName = $companydetails->company_name;
        }else{
            $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
            $storeName = $storedetails->store_name;
        }
        
       return view('kitchen.order.kitchen_order_list', compact('storeName'));
    }

    public function kitchenOrders(){

        if(Auth::guard('admin')->user()->store_id == null){

            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $reCompanyId = $companydetails->company_id;
            
            $kitchenorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.company_id' => $reCompanyId])->where('delivery_date',Carbon::now()->toDateString())->where('order_details.order_ready', '0')->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();

        }else{
            $reCompanyId = Auth::guard('admin')->user()->store_id;

            $kitchenorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('delivery_date',Carbon::now()->toDateString())->where('order_details.order_ready', '0')->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();

        }

        //$user = Admin::where(['u_id' => '32130ad3-e08c-5fc5-b863-1336a3ba4bde'])->first();
        $text_speech = Auth::guard('admin')->user()->text_speech;
        return response()->json(['status' => 'success', 'user' => $text_speech,'data'=>$kitchenorderDetails]);
    }

    public function orderStarted(Request $request, $orderID){
        DB::table('order_details')->where('id', $orderID)->update([
                            'order_started' => 1,
                        ]);
        return redirect()->action('AdminController@kitchenOrderDetail')->with('success', 'Order Started Successfully.');
        //return view('kitchen.order.kitchen_order_list');
    }

    public function orderReadyKitchen(Request $request, $orderID){
        DB::table('order_details')->where('id', $orderID)->update([
                            'order_ready' => 1,
                        ]);
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
                //dd($recipients);
                $url = "https://gatewayapi.com/rest/mtsms";
                $api_token = "Q67Aydr2SNmYJax7B0yxtGe5VwjL3_nDxc9-XIiaEl9Wk2Y1t9THIMFemCDcqafb";
                $message = env('APP_URL').'/public/ready-notifaction/'.$orderID;;
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
                $this->sendNotifaction($OrderId->customer_order_id , $message);
            }
            return redirect()->action('AdminController@kitchenOrderDetail')->with('success', 'Order Ready Nofifaction Send Successfully.');
        }
        return redirect()->action('AdminController@kitchenOrderDetail')->with('success', 'Order Ready Successfully.');
        //return view('kitchen.order.kitchen_order_list');
    }
    
    public function cateringDetails(){
        if(Auth::guard('admin')->user()->store_id == null){
            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $storeName = $companydetails->company_name;
        }else{
            $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
            $storeName = $storedetails->store_name;
        }
        return view('kitchen.order.catering', compact('storeName')); 
    }

    public function cateringOrders(){

        if(Auth::guard('admin')->user()->store_id == null){

            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $reCompanyId = $companydetails->company_id;

            $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time', 'orders.customer_order_id','orders.online_paid')->where(['order_details.company_id' => $reCompanyId])->where('order_details.delivery_date','>', Carbon::now()->toDateString())->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->orderBy('order_details.delivery_date','ASC')->get();  //->orderBy('order_details.created_at','DESC
        }else{
            $reCompanyId = Auth::guard('admin')->user()->store_id;

            $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time', 'orders.customer_order_id','orders.online_paid')->where(['order_details.store_id' => $reCompanyId])->where('order_details.delivery_date','>', Carbon::now()->toDateString())->whereNotIn('orders.online_paid', [2])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->orderBy('order_details.delivery_date','ASC')->get();  //->orderBy('order_details.created_at','DESC
        }

        //$user = Admin::where(['u_id' => '32130ad3-e08c-5fc5-b863-1336a3ba4bde'])->first();')

        return response()->json(['status' => 'success', 'response' => true,'data'=>$cateringorderDetails]);
    }

    public function kitchenPreOrder(Request $request){
        $menuTypes = null;
        $request->session()->forget('order_date');

        if(Auth::guard('admin')->user()->store_id){
            $menuDetails = ProductPriceList::where('store_id',Auth::guard('admin')->user()->store_id)->with('menuPrice')->with('storeProduct')->get();
            if(count($menuDetails) != 0){

                foreach ($menuDetails as $menuDetail) {
                    foreach ($menuDetail->storeProduct as $storeProduct) {
                        $companyId = $storeProduct->company_id;
                        $dish_typeId[] = $storeProduct->dish_type;
                    }
                }
                //dd($menuDetails);
                //dd(array_unique($dish_typeId));
                $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->where('dish_lang','ENG')->get();
                $dish_typeId = null;
                //$request->session()->put('storeId'.Auth()->id(), $storeId);
            }


            $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();
            if(count($companydetails) == 0){
                $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            }
            //dd($companydetails->currencies);
            $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
            return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes','storedetails'));
        }else{
            return view('kitchen.order.kitchen-main-admin');
        }
    }


    public function kitchenOrderSave(Request $request){

        if(!empty($request->input())){

            $data = $request->input();
            //dd($data['storeID']);
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

            foreach ($data['product'] as $key => $value) {
                    //if commant and quantity require then use condition "$value['prod_quant'] != '0' && $value['prod_desc'] != null"
                    if($value['prod_quant'] != '0'){
                        $productTime = Product::select('preparation_Time','company_id')->whereProductId($value['id'])->first();
                        if($i == 0){
                            $order =  new Order();
                            $order->customer_order_id = $this->random_num(6);
                            $order->user_id = Auth::guard('admin')->user()->id;
                            $order->store_id = Auth::guard('admin')->user()->store_id;
                            $order->company_id = $productTime->company_id;
                            $order->order_type = $orderType;
                            $order->user_type = 'admin';
                            $order->deliver_date = $orderDate;
                            $order->deliver_time = $orderTime;
                            $order->check_deliveryDate = $checkOrderDate;
                            $order->save();
                            $orders = Order::select('*')->whereUserId(Auth::guard('admin')->user()->id)->orderBy('order_id', 'DESC')->first();
                            $orderId = $orders->order_id;
                            $i = $i+1;
                        }else{}

                        $i = 1;
                        if($max_time < $productTime->preparation_Time){
                            $max_time = $productTime->preparation_Time;
                        }else{}
                        $productPrice = ProductPriceList::select('price')->whereProductId($value['id'])->first();
                        $total_price = $total_price + ($productPrice->price * $value['prod_quant']); 
                        $orderDetail =  new OrderDetail();
                        $orderDetail->order_id = $orders->order_id;
                        $orderDetail->user_id = Auth::guard('admin')->user()->id;
                        $orderDetail->product_id = $value['id'];
                        $orderDetail->product_quality = $value['prod_quant'];
                        $orderDetail->product_description = $value['prod_desc'];
                        $orderDetail->price = $productPrice->price;
                        $orderDetail->time = $productTime->preparation_Time;
                        $orderDetail->company_id = $productTime->company_id;
                        $orderDetail->store_id = Auth::guard('admin')->user()->store_id;
                        $orderDetail->delivery_date = $checkOrderDate;
                        $orderDetail->save();
                    }
                }

                DB::table('orders')->where('order_id', $orderId)->update([
                            'order_delivery_time' => $max_time,
                            'order_total' => $total_price,
                        ]);

                $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

                $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

                return view('kitchen.order.order-detail', compact('order','orderDetails'));
        }else{
            $menuTypes = null;
            $menuDetails = ProductPriceList::where('store_id',Auth::guard('admin')->user()->store_id)->with('menuPrice')->with('storeProduct')->get();
            if(count($menuDetails) != 0){

                foreach ($menuDetails as $menuDetail) {
                    foreach ($menuDetail->storeProduct as $storeProduct) {
                        $companyId = $storeProduct->company_id;
                        $dish_typeId[] = $storeProduct->dish_type;
                    }
                }
                //dd($menuDetails);
                //dd(array_unique($dish_typeId));
                $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->where('dish_lang','ENG')->get();
                $dish_typeId = null;
                //$request->session()->put('storeId'.Auth()->id(), $storeId);
            }


            $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();
            if(count($companydetails) == 0){
                $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            }

            $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
            return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes','storedetails'));
        }
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
        $menuDetails = ProductPriceList::where('store_id',Auth::guard('admin')->user()->store_id)->with('menuPrice')->with('storeProduct')->get();
        if(count($menuDetails) != 0){

            foreach ($menuDetails as $menuDetail) {
                foreach ($menuDetail->storeProduct as $storeProduct) {
                    $companyId = $storeProduct->company_id;
                    $dish_typeId[] = $storeProduct->dish_type;
                }
            }
            //dd($menuDetails);
            //dd(array_unique($dish_typeId));
            $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->where('dish_lang','ENG')->get();
            $dish_typeId = null;
            //$request->session()->put('storeId'.Auth()->id(), $storeId);
        }


        $companydetails = Company::where('company_id' , Auth::guard('admin')->user()->company_id)->first();
        if(count($companydetails) == 0){
            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
        }

        $storedetails = Store::where('store_id' , Auth::guard('admin')->user()->store_id)->first();
        return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes','storedetails'));
    }

    public function kitchenOrderView($orderId){
        $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
        //dd($order->currencies);
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
        return view('kitchen.setting.index');
    }

    public function saveKitchenSetting(Request $request){
        //dd($request->input());
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
        return redirect()->back()->with('success', 'Setting updated successfully.');
    }

    public function sendNotifaction($orderID, $message){
        $order = Order::select('*')->where('customer_order_id',$orderID)->first();
        if($order->user_type == 'customer'){
            $userDetail = User::whereId($order->user_id)->first();
            $userName =$userDetail->email;
        }else{
            $userDetail = Admin::whereId($order->user_id)->first();
            $userName =$userDetail->email;
        }

        if($message == 'orderDeliver'){
            $url = env('APP_URL').'/public/deliver-notifaction';
            $message = "{'alert':'Your Order Deliver.','badge':1,'sound':'default','Url':" ."'". $url."'" . "}";
        }else{
            $url = env('APP_URL').'/public/ready-notifaction/'.$orderID;
            $message = "{'alert':'Your Order Ready.','badge':1,'sound':'default','Url':" ."'". $url."'" . "}";
        }
        //dd(Config::get('app.php.varname'));
        //dd(env('APP_URL').'/ready-notifaction/'.$orderID);
        //dd($request->url());
        App42API::initialize("cc9334430f14aa90c623aaa1dc4fa404d1cfc8194ab2fd144693ade8a9d1e1f2","297b31b7c66e206b39598260e6bab88e701ed4fa891f8995be87f786053e9946"); 
        $pushNotificationService = App42API::buildPushNotificationService(); 
        $pushNotification = $pushNotificationService->sendPushMessageToUser($userName,$message);
        $jsonResponse = $pushNotification->toString();
    }

    public function updateTextspeach($id){
        //dd($id);
        DB::table('order_details')->where('id', $id)->update([
                    'is_speak' => 1,
                ]);
         return response()->json(['status' => 'success', 'response' => true,'data'=>$id]);
    }
}
