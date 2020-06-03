<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Admin;
use DB;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\DishType;
use App\Store;
use Carbon\Carbon;
use Auth;
use App\User;
use App\ProductPriceList;
use App\ProductOfferSloganLangList;
use App\ProductOfferSubSloganLangList;
use App\LangText;
use App\StoreVirtualMapping;
use App\Helper;

class KitchenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function orderDetail($reCompanyId){
        // Update store's 'islive'
        Helper::updateStoreIslive($reCompanyId);

        $stores[] = $reCompanyId;

        // Get virtual restaurant if mapped
        $storeMapping = StoreVirtualMapping::where('store_id', $reCompanyId)
            ->get();

        if($storeMapping)
        {
            foreach($storeMapping as $row)
            {
                $stores[] = $row['virtual_store_id'];
            }
        }

        $orderDetailscustomer = Order::select(['orders.*','customer.name as name', 'OCD.discount_id', 'PD.discount_value', DB::raw('COUNT(OCL.id) AS cntLoyaltyUsed'), 'OD.status AS orderDeliveryStatus', 'CA.street'])
            ->whereIn('orders.store_id', $stores)
            ->where('user_type','=','customer')
            ->where('orders.check_deliveryDate', '>=', date("Y-m-d"))
            ->where('orders.check_deliveryDate', '<=', date("Y-m-d",strtotime("+1 day")))
            ->where('orders.paid', '0')
            ->whereNotIn('orders.online_paid', [2])
            ->where('orders.cancel','!=', 1)
            ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('customer','orders.user_id','=','customer.id')
            ->leftJoin('customer_addresses AS CA','CA.id','=','orders.user_address_id')
            ->leftJoin('order_customer_discount AS OCD', 'orders.order_id', '=', 'OCD.order_id')
            ->leftJoin('promotion_discount AS PD', 'OCD.discount_id', '=', 'PD.id')
            ->leftJoin('order_customer_loyalty AS OCL', 'OCL.order_id', '=', 'orders.order_id')
            ->leftJoin('order_delivery AS OD', 'OD.order_id', '=', 'orders.order_id')
            ->where('orders.is_verified', '1')
            ->where('orders.catering_order_status', '2')
            ->groupBy('orders.order_id');

        $store = Store::select(['extra_prep_time', 'order_response'])->where('store_id', $reCompanyId)->first();
        $extra_prep_time = $store->extra_prep_time;
        
        // $results = $orderDetailscustomer->union($orderDetails)->get();

        $results = $orderDetailscustomer->get();

        // Get order items
        $orderItems = array();
        if($results)
        {
            $orderItems = OrderDetail::select('order_details.id', 'order_details.product_quality', 'order_details.product_description', 'product.product_name')
                ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->join('product', 'product.product_id', '=', 'order_details.product_id')
                ->whereIn('orders.store_id', $stores)
                ->where(['user_type' => 'customer', 'orders.order_started' => '0', 'orders.paid' => '0'])
                ->where('orders.check_deliveryDate', '>=', date("Y-m-d"))
                ->where('orders.check_deliveryDate', '<=', date("Y-m-d",strtotime("+1 day")))
                ->whereNotIn('orders.online_paid', [2])
                ->where('orders.cancel','!=', 1)
                ->get();
        }

        $catCount = Order::whereIn('store_id',$stores)
            ->where('order_type', 'eat_later')
            ->where('cancel','!=', 1)
            ->where('online_paid', '>', 0)
            ->where('delivery_timestamp', '>', time())
            ->where('is_verified', '0')
            ->where('catering_order_status', '0')->count();

        return response()->json(['status' => 'success', 'response' => true, 'store' => $store, 'extra_prep_time' => $extra_prep_time, 'data'=>$results, 'orderItems' => $orderItems, 'catCount' => $catCount]);
    }
    
    /**
     * Update order item as speak
     * @param  [int] $id [description]
     * @return [json]     [description]
     */
    public function updateTextspeach($id){
        DB::table('order_details')->where('id', $id)->update([
            'is_speak' => 1,
        ]);

        return response()->json(['status' => 'success', 'response' => true,'data'=>$id]);
    }

    public function orderSpecificOrderDetail($orderId){
         $orderDetails = OrderDetail::select('order_details.*','product.product_name','orders.deliver_date','orders.deliver_time','orders.order_delivery_time','orders.customer_order_id','orders.online_paid')->where(['order_details.order_id' => $orderId])->join('product','product.product_id','=','order_details.product_id')->join('orders','orders.order_id','=','order_details.order_id')->get();
        return response()->json(['status' => 'success', 'data'=>$orderDetails]);
    }

    /**
     * Update product rank belongs to same category
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateProductRank(Request $request){
        $status = 0;

        if($request->has('products'))
        {
            $products = json_decode($request->products);
            $product = new Product();

            // Update all menu rank belongs to same restaurant
            if( !empty($products) && is_array($products) )
            {
                $status = 1;
                $i = 1;

                foreach($products as $id)
                {
                    // $product->where(['product_id' => $id, 'dish_type' => $request->dish_type])->update(['product_rank' => $i]);
                    $product->where(['product_id' => $id])->update(['product_rank' => $i]);
                    $i++;
                }
            }
        }
        
        return response()->json(['status' => $status]);
    }

    /**
     * Update restaurant menu order to display
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateMenuRank(Request $request)
    {
        $status = 0;

        if($request->has('items'))
        {
            $items = json_decode($request->items);

            // Update all menu rank belongs to same restaurant
            if( !empty($items) && is_array($items) )
            {
                $status = 1;
                $i = 1;
                $dishType = new DishType();

                foreach($items as $id)
                {
                    $dishType->where(['dish_id' => $id, 'u_id' => $request->u_id])->update(['rank' => $i]);
                    $i++;
                }
            }
        }

        return response()->json(['status' => $status]);
    }

}
