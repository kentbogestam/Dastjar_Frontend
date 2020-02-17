<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use App\Helper;
use App\Store;
use App\PromotionDiscount;

class DiscountController extends Controller
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
    	// Get store
    	$store = Store::select(['store_id', 'store_name'])->where(['u_id' => Auth::user()->u_id, 's_activ' => '1'])->get();
    	
    	// Get discount
    	$discount = PromotionDiscount::from('promotion_discount AS PD')
    		->select(['PD.id', 'PD.code', 'PD.discount_value', 'PD.start_date', 'PD.description', 'PD.end_date', 'S.store_name'])
    		->join('store AS S', 'S.store_id', '=', 'PD.store_id')
    		// ->where(['PD.store_id' => Session::get('storeId')])
    		->where(['S.u_id' => Auth::user()->u_id, 'S.s_activ' => '1'])
    		->get();

    	return view('kitchen.discount.index', compact('discount', 'store'));
    }

    /**
     * Create discount
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function store(Request $request)
    {
    	// Validation
		$this->validate($request, [
			'store_id' 			=> 'required',
			'code'				=> 'required',
			'discount_value' 	=> 'required|numeric',
			'start_date_utc' 	=> 'required',
			'end_date_utc' 		=> 'required|after:start_date_utc',
		], [
            'end_date_utc.after' => __('messages.discountDateAfter'),
        ]);

		$data = $request->only(['store_id', 'code', 'discount_value', 'description']);
		$data['start_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->start_date_utc);
        $data['end_date'] = \DateTime::createFromFormat('Y/m/d H:i', $request->end_date_utc);

        // dd($data);

		// 
		PromotionDiscount::create($data);

		return redirect('kitchen/discount/list')->with('success', __('messages.discountCreated'));
    }

    /**
     * Check if discount not already exist
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function remoteValidateDiscount(Request $request)
    {
    	$data = $request->input();
    	$status = 'true';

    	// Check if discount is not already exist
    	$promotionDiscount = PromotionDiscount::select(['id'])
    		->where(['code' => $data['code'], 'status' => '1'])->first();

    	if($promotionDiscount)
    	{
    		$status = 'false';
    	}

    	echo $status;
		exit;
    }

    /**
     * Return random number
     * @return [type] [description]
     */
    function ajaxGetDiscountCode()
    {
    	$helper = new Helper();
    	$code = $helper->random_num(5);

    	return response()->json(['code' => $code]);
    }
}
