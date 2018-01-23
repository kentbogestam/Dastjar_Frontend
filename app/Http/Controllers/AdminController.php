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
        return view('kitchen.order.index');
    }

    public function orderDetail(){

        if(Auth::guard('admin')->user()->store_id == null){

            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $reCompanyId = $companydetails->company_id;

            $orderDetailscustomer = Order::select('orders.*','customer.name as name')->where(['company_id' => $reCompanyId])->where('user_type','=','customer')->where('check_deliveryDate',Carbon::now()->toDateString())->join('customer','orders.user_id','=','customer.id');
            $orderDetails = Order::select('orders.*','user.fname as name')->where('orders.company_id', '=' ,$reCompanyId)->where('user_type','=','admin')->where('check_deliveryDate',Carbon::now()->toDateString())->join('user','orders.user_id','=','user.id');
            $results = $orderDetailscustomer->union($orderDetails)->get();

        }else{
            //In this function where condition work store_id
            $reCompanyId = Auth::guard('admin')->user()->store_id;

            $orderDetailscustomer = Order::select('orders.*','customer.name as name')->where(['store_id' => $reCompanyId])->where('user_type','=','customer')->where('check_deliveryDate',Carbon::now()->toDateString())->join('customer','orders.user_id','=','customer.id');
            $orderDetails = Order::select('orders.*','user.fname as name')->where('orders.store_id', '=' ,$reCompanyId)->where('user_type','=','admin')->where('check_deliveryDate',Carbon::now()->toDateString())->join('user','orders.user_id','=','user.id');
            $results = $orderDetailscustomer->union($orderDetails)->get();
        }
        // $user = Admin::where(['u_id' => Auth::guard('admin')->user()->company_id])->first();
        return response()->json(['status' => 'success', 'response' => true,'data'=>$results]);
    }

    public function kitchenOrderDetail(){
       return view('kitchen.order.kitchen_order_list', compact(''));
    }

    public function kitchenOrders(){

        if(Auth::guard('admin')->user()->store_id == null){

            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $reCompanyId = $companydetails->company_id;
            
            $kitchenorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time')->where(['order_details.company_id' => $reCompanyId])->where('delivery_date',Carbon::now()->toDateString())->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();

        }else{
            $reCompanyId = Auth::guard('admin')->user()->store_id;

            $kitchenorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time')->where(['order_details.store_id' => $reCompanyId])->where('delivery_date',Carbon::now()->toDateString())->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();

        }

        //$user = Admin::where(['u_id' => '32130ad3-e08c-5fc5-b863-1336a3ba4bde'])->first();
        return response()->json(['status' => 'success', 'response' => true,'data'=>$kitchenorderDetails]);
    }

    public function orderStarted(Request $request, $orderID){
        DB::table('order_details')->where('id', $orderID)->update([
                            'order_started' => 1,
                        ]);
        return view('kitchen.order.kitchen_order_list');
    }

    public function orderReadyKitchen(Request $request, $orderID){
        DB::table('order_details')->where('id', $orderID)->update([
                            'order_ready' => 1,
                        ]);
        $userOrderId = OrderDetail::where('id' , $orderID)->first();
        $userOrderStatus = OrderDetail::where('order_id' , $userOrderId->order_id)->get();
        $readyOrderStatus = OrderDetail::where('order_id' , $userOrderId->order_id)->where('order_ready' , '1')->get();
        if(count($userOrderStatus) == count($readyOrderStatus)){
            DB::table('orders')->where('order_id', $userOrderId->order_id)->update([
                            'order_ready' => 1,
                        ]);
        }
        return view('kitchen.order.kitchen_order_list');
    }

    public function orderReady(Request $request, $orderID){
        return view('order.alert-ready',compact('orderID'));
    }

    public function cateringDetails(){
        return view('kitchen.order.catering', compact('')); 
    }

    public function cateringOrders(){

        if(Auth::guard('admin')->user()->store_id == null){

            $companydetails = Company::where('u_id' , Auth::guard('admin')->user()->u_id)->first();
            $reCompanyId = $companydetails->company_id;

            $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time')->where(['order_details.company_id' => $reCompanyId])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();  //->orderBy('order_details.created_at','DESC
        }else{
            $reCompanyId = Auth::guard('admin')->user()->store_id;

            $cateringorderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time')->where(['order_details.store_id' => $reCompanyId])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();  //->orderBy('order_details.created_at','DESC
        }

        //$user = Admin::where(['u_id' => '32130ad3-e08c-5fc5-b863-1336a3ba4bde'])->first();')

        return response()->json(['status' => 'success', 'response' => true,'data'=>$cateringorderDetails]);
    }

    public function kitchenPreOrder(Request $request){
        $menuTypes = null;
        $request->session()->forget('order_date');
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
        return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes'));
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
                $dt = Carbon::now();
                $checkOrderDate = Carbon::now()->toDateString();
                $orderType = 'eat_now';
                $orderDate = $dt->formatLocalized('%A %d %B %Y');
                $orderTime = Carbon::now()->toTimeString();
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

                $order = Order::select('*')->where('order_id',$orderId)->first();

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
            return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes'));
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
        return view('kitchen.order.kitchen-pre-order', compact('menuDetails','companydetails','menuTypes'));
    }

    public function kitchenOrderView($orderId){
        $order = Order::select('*')->where('order_id',$orderId)->first();

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
}
