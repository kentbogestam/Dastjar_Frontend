<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Store;
use DB;

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
            $store = Store::select(['store_id', 'store_name', 'street', 'city', 'country', 'zip', 'store_image', 'large_image'])
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
}
