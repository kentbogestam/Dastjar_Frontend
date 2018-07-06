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
    //
    public function saveOrder(Request $request){
    	//$request->session()->forget('order_date');
    	//dd($request->session()->get('order_date') != null);
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
                            $order->save();
                            $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                            $orderId = $orders->order_id;
                            $i = $i+1;
                        }else{}

                        $i = 1;
                        if($max_time < $productTime->preparation_Time){
                            $max_time = $productTime->preparation_Time;
                        }else{}
                        $productPrice = ProductPriceList::select('price')->whereProductId($value['id'])->where('store_id' , $data['storeID'])->first();
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

                $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

                $request->session()->put('currentOrderId', $order->order_id);
                //$orderDetails = OrderDetail::select('*')->where('order_id',$orderId)->get();
                $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

                $storeDetail = Store::where('store_id', $data['storeID'])->first();
                //If store support ontine payment then if condition run.
                if($storeDetail->online_payment == 1){
                    $companyDetail = Company::where('company_id', $productTime->company_id)->first();
                    $companyUserDetail = Admin::where('u_id', $companyDetail->u_id)->first();


                    DB::table('orders')->where('order_id', $orderId)->update([
                            'online_paid' => 2,
                        ]);
                    $request->session()->put('paymentAmount', $order->order_total);
                    $request->session()->put('OrderId', $order->order_id);
                                      //  dd($companyUserDetail->toArray());

                    if(isset($companyUserDetail->stripe_user_id))
                    $request->session()->put('stripeAccount', $companyUserDetail->stripe_user_id);

                     return view('order.paymentIndex', compact('order','orderDetails'));
                }else{
                    return view('order.index', compact('order','orderDetails'));
                }
            }else{


                $todayDate = $request->session()->get('browserTodayDate');
                $currentTime = $request->session()->get('browserTodayTime');
                $todayDay = $request->session()->get('browserTodayDay');
                $userDetail = User::whereId(Auth()->id())->first();
                $companydetails = Store::getListRestaurants($request->session()->get('with_login_lat'),$request->session()->get('with_login_lng'),$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
                
                return view('index', compact('companydetails'));
            } 
        }else{
          $data = $request->input();
          Session::put('orderData', $data);
          return view('auth.login', compact(''));
        }
    }

    public function withOutLogin(Request $request){
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
                        $order->save();
                        $orders = Order::select('*')->whereUserId(Auth::id())->orderBy('order_id', 'DESC')->first();
                        $orderId = $orders->order_id;
                        $i = $i+1;
                    }else{}

                    $i = 1;
                    if($max_time < $productTime->preparation_Time){
                        $max_time = $productTime->preparation_Time;
                    }else{}
                    $productPrice = ProductPriceList::select('price')->whereProductId($value['id'])->where('store_id' , $data['storeID'])->first();
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

            $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

            $request->session()->put('currentOrderId', $order->order_id);
            //$orderDetails = OrderDetail::select('*')->where('order_id',$orderId)->get();
            $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();

            $storeDetail = Store::where('store_id', $data['storeID'])->first();
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
                 return view('order.paymentIndex', compact('order','orderDetails'));
            }else{
                return view('order.index', compact('order','orderDetails'));
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
            
            $companydetails = Store::getListRestaurants($lat,$lng,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
            
            return view('index', compact('companydetails'));
        } 
    }

    public function orderView($orderId){
        $order = Order::select('orders.*','store.store_name','company.currencies')->where('order_id',$orderId)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();
        //dd($order->currencies);
        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$orderId)->get();
        return view('order.index', compact('order','orderDetails'));
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
        $customer = new User();
        $logged_in=0;

        // dd($request->session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'));

        if(Session::has('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d')){
            if($customer->where('id',session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'))->exists()){
                $logged_in=1; 
            }
        }

        if($logged_in == 1){
            $cust = $customer->where('id',session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'))->first();
            $customer_id = $cust->id;

            if($cust->phone_number == null){
                $phone = (explode("-",$request->m));
                $cust->phone_number_prifix = $phone[0];
                $cust->phone_number = $phone[1];
                $cust->save();
            }
        }else{
            $phone = (explode("-",$request->m));
            $cust = $customer->firstOrNew(['phone_number_prifix' => $phone[0], 'phone_number' => $phone[1]]);
            $cust->email = $phone[1];
            $cust->save();
            $customer_id = $cust->id;            
        }

            $order =  Order::where('customer_order_id',$id);
            $order->update(['user_id' => $customer_id]);
            $order_id = $order->first()->order_id;

            $orderDetail =  new OrderDetail();
            $orderDetail->where('order_id',$order_id)->update(['user_id' => $customer_id]);
            
        $order = Order::select('orders.*','store.store_name','company.currencies')->where('customer_order_id',$id)->join('store','orders.store_id', '=', 'store.store_id')->join('company','orders.company_id', '=', 'company.company_id')->first();

        $orderDetails = OrderDetail::select('order_details.order_id','order_details.user_id','order_details.product_quality','order_details.product_description','order_details.price','order_details.time','product.product_name')->join('product', 'order_details.product_id', '=', 'product.product_id')->where('order_details.order_id',$order->order_id)->get();

     //   dd($orderDetails);

        return view('order.order-details', compact('order','orderDetails'));
    }
}
