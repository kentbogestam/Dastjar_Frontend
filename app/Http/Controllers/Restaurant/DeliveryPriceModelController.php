<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use App\Helper;
use App\Store;
use App\StoreDeliveryPriceModel;
use App\StoreDeliveryPriceModelDistance;

class DeliveryPriceModelController extends Controller
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

	/**
	 * Display listing of resource
	 * 
	 * @return [type] [description]
	 */
    public function index()
    {
        $deliveryRule = array(
            array('id' => 1, 'summary' => __('messages.ruleDeliveryType1')),
            array('id' => 2, 'summary' => __('messages.ruleDeliveryType2')),
            array('id' => 3, 'summary' => __('messages.ruleDeliveryType3')),
            array('id' => 4, 'summary' => __('messages.ruleDeliveryType4')),
            array('id' => 5, 'summary' => __('messages.ruleDeliveryType5')),
        );
        
        $deliveryPriceModel = StoreDeliveryPriceModel::where(['store_id' => Session::get('kitchenStoreId'), 'status' => '1'])
            ->get();
    	
        return view('kitchen.delivery-price-model.index', compact('deliveryRule', 'deliveryPriceModel'));
    }

    /**
     * Create
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'delivery_rule_id'  => 'required|numeric',
            'delivery_charge'   => 'required_if:delivery_rule_id,1|required_if:delivery_rule_id,2|required_if:delivery_rule_id,4',
            'threshold'         => 'required_if:delivery_rule_id,2|required_if:delivery_rule_id,3|required_if:delivery_rule_id,4',
        ]);

        $data = $request->only(['delivery_rule_id', 'delivery_charge', 'threshold', 'dp_distance', 'distance_delivery_charge']);
        $data['store_id'] = Session::get('kitchenStoreId');

        $helper = new Helper();
        $id = $helper->uuid();

        while(StoreDeliveryPriceModel::where('id', $id)->exists()){
            $id = $helper->uuid();
        }

        $data['id'] = $id;

        // 
        if(!StoreDeliveryPriceModel::where(['store_id' => Session::get('kitchenStoreId'), 'status' => '1'])->first())
        {
            // 
            if(StoreDeliveryPriceModel::create($data))
            {
                // Create 'distance based' delivery rule
                if($data['delivery_rule_id'] == 5)
                {
                    if( !empty($data['dp_distance']) )
                    {
                        foreach($data['dp_distance'] as $key => $value)
                        {
                            if( !is_null($data['dp_distance'][$key]) && !is_null($data['distance_delivery_charge'][$key]) )
                            {
                                StoreDeliveryPriceModelDistance::create([
                                    'store_delivery_price_model_id' => $data['id'],
                                    'distance' => $data['dp_distance'][$key],
                                    'delivery_charge' => $data['distance_delivery_charge'][$key],
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return redirect('kitchen/delivery-price-model/list')->with('success', __('messages.deliveryPriceCreated'));
    }

    /**
     * Get by ID
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function ajaxGetDeliveryPriceById($id)
    {
        $deliveryPriceModel = StoreDeliveryPriceModel::where(['id' => $id, 'store_id' => Session::get('kitchenStoreId')])->with('deliveryPriceDistance')->first();

        return response()->json(['deliveryPriceModel' => $deliveryPriceModel]);
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function update(Request $request)
    {
        // Validation
        $this->validate($request, [
            'delivery_rule_id_upd'  => 'required|numeric',
            'delivery_charge_upd'   => 'required_if:delivery_rule_id_upd,1|required_if:delivery_rule_id_upd,2||required_if:delivery_rule_id_upd,4',
            'threshold_upd'         => 'required_if:delivery_rule_id_upd,2|required_if:delivery_rule_id_upd,3||required_if:delivery_rule_id_upd,4',
        ]);

        $data['delivery_rule_id'] = $request->delivery_rule_id_upd;
        $data['delivery_charge'] = $request->delivery_charge_upd;
        $data['threshold'] = $request->threshold_upd;
        $data['dp_distance'] = $request->dp_distance_upd;
        $data['distance_delivery_charge'] = $request->distance_delivery_charge_upd;

        // 
        $response = StoreDeliveryPriceModel::where(['id' => $request->id, 'store_id' => Session::get('kitchenStoreId')])
            ->update(['delivery_rule_id' => $data['delivery_rule_id'], 'delivery_charge' => $data['delivery_charge'], 'threshold' => $data['threshold']]);

        if($response)
        {
            StoreDeliveryPriceModelDistance::where('store_delivery_price_model_id', $request->id)->delete();
            
            // Create 'distance based' delivery rule
            if($data['delivery_rule_id'] == 5)
            {
                if( !empty($data['dp_distance']) )
                {
                    foreach($data['dp_distance'] as $key => $value)
                    {
                        if( !is_null($data['dp_distance'][$key]) && !is_null($data['distance_delivery_charge'][$key]) )
                        {
                            StoreDeliveryPriceModelDistance::create([
                                'store_delivery_price_model_id' => $request->id,
                                'distance' => $data['dp_distance'][$key],
                                'delivery_charge' => $data['distance_delivery_charge'][$key],
                            ]);
                        }
                    }
                }
            }
        }

        return redirect('kitchen/delivery-price-model/list')->with('success', __('messages.deliveryPriceUpdated'));
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function destroy($id)
    {
        $storeDeliveryPrice = StoreDeliveryPriceModel::where(['id' => $id, 'store_id' => Session::get('kitchenStoreId')])->get();

        if($storeDeliveryPrice)
        {
            // 
            StoreDeliveryPriceModel::where(['id' => $id, 'store_id' => Session::get('kitchenStoreId')])->update(['status' => '0']);
            StoreDeliveryPriceModelDistance::where('store_delivery_price_model_id', $id)->delete();
        }

        return redirect('kitchen/delivery-price-model/list')->with('success', __('messages.deliveryPriceDeleted'));
    }
}
