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
    		->where(['u_id' => Auth::user()->u_id, 'dish_activate' => 1, 'parent_id' => null])
            ->orderBy('rank')
    		->paginate(10);

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

        $data = $request->except(['_token', 'parent_id']);

        // 
        $data['u_id'] = Auth::user()->u_id;
        $data['company_id'] = Company::where('u_id', Auth::user()->u_id)->first()->company_id;
        
        // Set rank
        $rank = DishType::orderBy('rank', 'DESC')->where(['u_id' => Auth::user()->u_id, 'company_id' => $data['company_id'], 'dish_activate' => 1, 'parent_id' => null])->first();
        
        if($rank)
        {
            $data['rank'] = ($rank->rank + 1);
        }
        else
        {
            $data['rank'] = 1;
        }

        // Create DishType
        $dishId = DishType::create($data)->id;

        // Create sub-category
        if($dishId)
        {
            $data['sub_category'] = array_filter($request->input('sub_category'));

            if(!empty($data['sub_category']))
            {
                $arrSubCat = array();
                foreach($data['sub_category'] as $row)
                {
                    $arrSubCat[] = array(
                        'dish_lang' => $data['dish_lang'],
                        'dish_name' => $row,
                        'parent_id' => $dishId
                    );
                }

                DishType::insert($arrSubCat);
            }
        }

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeCreated'));
    }

    /**
     * Get dish type by ID
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function ajaxGetDishTypeById($id)
    {
        // Get category
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])
            ->where(['dish_id' => $id, 'dish_activate' => 1])->first();

        // Get sub-category
        $dishType['subcategory'] = null;
        if($dishType)
        {
            $dishType['subcategory'] = DishType::select(['dish_id', 'dish_name'])
                ->where(['parent_id' => $dishType->dish_id, 'dish_activate' => 1])
                ->get();
        }


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

        // Update category
        DishType::where('dish_id', $request->dish_id)
            ->update(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name]);

        // Update existing or create new sub-category
        $data['sub_category'] = array_filter($request->input('sub_category'));
        if(!empty($data['sub_category']))
        {
            foreach($data['sub_category'] as $key => $value)
            {
                if(DishType::where(['dish_id' => $key, 'parent_id' => $data['dish_id']])->count())
                {
                    DishType::where('dish_id', $key)->update(['dish_lang' => $data['dish_lang'], 'dish_name' => $value]);
                }
                else
                {
                    DishType::create(array(
                        'dish_lang' => $data['dish_lang'],
                        'dish_name' => $value,
                        'parent_id' => $data['dish_id']
                    ));
                }
            }
        }
        
        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeUpdated'));
    }

    /**
     * Remove category
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

    /**
     * Remove subcategory
     * @param  [type] $parentId [description]
     * @param  [type] $dishId   [description]
     * @return [type]           [description]
     */
    function removeSubcategory($parentId, $dishId)
    {
        $status = 0;
        $dishType = DishType::where(['dish_id' => $dishId, 'parent_id' => $parentId])->get();

        if($dishType)
        {
            DishType::where('dish_id', $dishId)->update(['dish_activate' => 0]);
            $status = 1;
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Get subcategory of selected category
     * @param  [type] $parentId [description]
     * @return [type]           [description]
     */
    function getSubcategories($dishId)
    {
        // Get category
        $subCategory = DishType::select(['dish_id', 'dish_name'])
            ->where(['parent_id' => $dishId, 'dish_activate' => 1])->get();

        return response()->json(['subCategory' => $subCategory]);
    }
}
