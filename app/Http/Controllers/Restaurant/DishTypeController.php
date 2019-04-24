<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;

use Auth;
use Session;
use App\Helper;
use App\DishType;
use App\Company;

class DishTypeController extends Controller
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
    	// Get
    	$dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])
    		->where(['u_id' => Auth::user()->u_id, 'dish_activate' => 1])
            ->orderBy('rank')
    		->paginate(5);

        // Add custom link in pagination
        $links = $dishType->links();
        $links = str_replace("<a", "<a data-ajax='false' ", $links);

    	return view('kitchen.dishType.index', compact('dishType', 'links'));
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
            'dish_lang' => 'required',
            'dish_name' => [
                'required',
                Rule::unique('dish_type')->where(function($query) use ($request) {
                    return $query->where(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'u_id' => Auth::user()->u_id, 'dish_activate' => 1]);
                })
            ],
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token']);

        // 
        $data['u_id'] = Auth::user()->u_id;
        $data['company_id'] = Company::where('u_id', Auth::user()->u_id)->first()->company_id;
        DishType::create($data);

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeCreated'));
    }

    /**
     * Get dish type by ID
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function ajaxGetDishTypeById($id)
    {
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])
            ->where(['dish_id' => $id, 'dish_activate' => 1])->first();

        return response()->json(['dishType' => $dishType]);
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
            'dish_lang' => 'required',
            'dish_name' => [
                'required',
                Rule::unique('dish_type')->where(function($query) use ($request) {
                    return $query->where(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'u_id' => Auth::user()->u_id, 'dish_activate' => 1])->where('dish_id', '!=', $request->dish_id);
                })
            ],
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token']);

        // 
        DishType::where('dish_id', $request->dish_id)
            ->update(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name]);

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeUpdated'));
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function destroy($id)
    {
        $dishType = DishType::where('dish_id', $id)->get();

        if(!$dishType)
        {
            return redirect('kitchen/dishtype/list')->with('error', __('messages.dishTypeNotFound'));
        }

        DishType::where('dish_id', $id)->update(['dish_activate' => 0]);
        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeDeleted'));
    }
}
