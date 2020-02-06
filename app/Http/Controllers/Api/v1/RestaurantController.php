<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Store;
use App\StoreDeliveryPriceModel;
use DB;

use Helper;

class RestaurantController extends Controller
{
    // 
    function getStoresByUser($uId = null)
    {
        $status = 'exception';
        $response = null;

        // 
        if( !is_null($uId) && !empty($uId) )
        {
            $status = 'empty';

            // 
            $stores = Store::select(['store_id', 'store_name', 'tagline', 'street', 'city', 'country', 'zip', 'store_image'])
                ->where(['u_id' => $uId, 's_activ' => '1'])
                ->get();

            if(!$stores->isEmpty())
            {
                $status = 'success';
                $response = $stores;
            }
        }

        return response()->json(['status' => $status, 'response' => $response]);
    }

    // 
    function getStore($storeId = null)
    {
        $status = 'exception';
        $response = null;

        if( !is_null($storeId) && !empty($storeId) )
        {
            $status = 'empty';

            // 
            $store = Store::select(['store_id', 'tagline', 'latitude', 'longitude', 'store_name', 'street', 'city', 'country', 'phone', 'email', 'delivery_type', 'zip', 'store_image', 'large_image', 'store_open_close_day_time', 'store_close_dates'])
                ->where(['store_id' => $storeId, 's_activ' => '1'])
                ->first();

            if($store)
            {
                $status = 'success';
                $response = $store;
            }
        }

        return response()->json(['status' => $status, 'response' => $response]);
    }

    // 
    function getStoreDeliveryPriceModel($storeId = null)
    {
        $status = 'exception';
        $response = null;

        if( !is_null($storeId) && !empty($storeId) )
        {
            $status = 'empty';

            // 
            $storeDeliveryPriceModel = StoreDeliveryPriceModel::select(['delivery_rule_id', 'delivery_charge', 'threshold'])
                ->where(['store_id' => $storeId, 'status' => '1'])
                ->first();

            if($storeDeliveryPriceModel)
            {
                $status = 'success';
                $response = $storeDeliveryPriceModel;
            }
        }

        return response()->json(['status' => $status, 'response' => $response]);
    }

    /**
     * [getStorePackages description]
     * @param  [type] $storeId [description]
     * @return [type]          [description]
     */
    function getStorePackages($storeId = null)
    {
        $status = 'exception';
        $response = null;

        if( !is_null($storeId) && !empty($storeId) )
        {
            $status = 'empty';

            $helper = new Helper();
            $packages = $helper->getStorePackages($storeId);

            if( !empty($packages) )
            {
                $status = 'success';
                $response = $packages;
            }
        }

        return response()->json(['status' => $status, 'response' => $response]);
    }
}
