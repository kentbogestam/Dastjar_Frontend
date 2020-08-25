<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;

use Auth;
use Session;
use Storage;
use App\Helper;
use App\DishType;
use App\Company;
use App\Product;
use App\ProductsExtra;

use \Gumlet\ImageResize;

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
        $helper = new Helper();
        $dishType = $helper->getDishTypeTree(Auth::user()->u_id);
        
        // Create Category tree
        $dishTypeList = array(); $i; $j;
        foreach ($dishType as $key => $row) {
            if($row['level'] == 0)
            {
                $dishTypeList[] = $row;
                $i = count($dishTypeList)-1;
            }
            elseif($row['level'] == 1)
            {
                if(isset($dishTypeList[$i]))
                {
                    $dishTypeList[$i]['cat1'][] = $row;
                    $j = count($dishTypeList[$i]['cat1'])-1;
                }
            }
            elseif($row['level'] == 2)
            {
                if(isset($dishTypeList[$i]['cat1']))
                {
                    $dishTypeList[$i]['cat1'][$j]['cat2'][] = $row;
                }
            }
        }

        $extraDishTypes = array();

        $dishData = DishType::select('DT.dish_name','DT.dish_id', 'DT.parent_id', 'DT.rank')
            ->from('dish_type AS DT')
            ->join('product AS P', 'P.dish_type', '=', 'DT.dish_id')
            ->join('product_price_list AS PPL', 'PPL.product_id', '=', 'P.product_id')
            ->where('P.u_id', Auth::user()->u_id)
            ->where('P.s_activ', '!=' , 2)
            ->where('DT.dish_activate', 1)
            ->where('DT.extras', '1')
            ->where('PPL.store_id', Session::get('kitchenStoreId'))
            ->groupBy('DT.dish_id')
            ->orderBy('DT.rank')
            ->orderBy('DT.dish_id')
            ->get();

        if($dishData)
        {
            $dishIds = array();

            foreach($dishData as $dish)
            {
                if( !is_null($dish->parent_id) )
                {
                    $dishTypeLevel0 = DishType::from('dish_type AS DT1')
                        ->select(['DT1.dish_id', 'DT1.dish_name', 'DT1.rank'])
                        ->leftJoin('dish_type AS DT2', 'DT2.parent_id', '=', 'DT1.dish_id')
                        ->leftJoin('dish_type AS DT3', 'DT3.parent_id', '=', 'DT2.dish_id')
                        ->whereRaw("(DT1.dish_id = '{$dish->dish_id}' OR DT2.dish_id = '{$dish->dish_id}' OR DT3.dish_id = '{$dish->dish_id}') AND DT1.parent_id IS NULL")
                        ->groupBy('DT1.dish_id')
                        ->first();
                    
                    if($dishTypeLevel0)
                    {
                        if( !in_array($dishTypeLevel0->dish_id, $dishIds) )
                        {
                            $dishIds[] = $dishTypeLevel0->dish_id;

                            $extraDishTypes[$dishTypeLevel0->dish_id] = $dishTypeLevel0->dish_name;
                        }
                    }
                }
                else
                {
                    if( !in_array($dish->dish_id, $dishIds) )
                    {
                        $dishIds[] = $dish->dish_id;

                        $extraDishTypes[$dish->dish_id] = $dish->dish_name;
                    }
                }
            }
        }
        // dd($dishTypeList);
        return view('kitchen.dishType.index', compact('dishType', 'dishTypeList','extraDishTypes'));
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
                    return $query->where(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'u_id' => Auth::user()->u_id, 'dish_activate' => 1, 'parent_id' => $request->parent_id]);
                })
            ],
            'dish_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token', 'dish_image']);

        // 
        $data['u_id'] = Auth::user()->u_id;
        $data['company_id'] = Company::where('u_id', Auth::user()->u_id)->first()->company_id;

        // 
        if ($request->hasFile('dish_image'))
        {
            $file = $request->file('dish_image');
            $newFile = new ImageResize($file);
            $newFile->gamma(false);
            $newFile->resizeToWidth(500);
            $imageName = 'cat-img-'.time().'.'.$file->getClientOriginalExtension();
            $filePath = 'upload/category/'.$imageName;
            $t = Storage::disk('s3')->put($filePath, $newFile, 'public');
            
            if($t)
            {
                $data['dish_image'] = $filePath;
            }
        }
        
        // Set rank
        $rank = DishType::orderBy('rank', 'DESC')->where(['u_id' => Auth::user()->u_id, 'company_id' => $data['company_id'], 'dish_activate' => 1]);
        
        if(is_null($request->parent_id))
        {
            $rank->where('parent_id', null);
        }
        else
        {
            $rank->where('parent_id', $request->parent_id);
        }

        $rank = $rank->first();
        
        if($rank)
        {
            $data['rank'] = ($rank->rank + 1);
        }
        else
        {
            $data['rank'] = 1;
        }

        if(isset($data['extra_dish'])){
            $data['extras'] = '1';
        }else{
            $data['extras'] = '0';
        }

        // Create DishType
        $item = DishType::create($data);
        echo $item;
        if($item->extras == '0'){
            if(isset($request->extra_dish_type)){
                foreach($request->extra_dish_type as $key => $val){
                    ProductsExtra::create([
                        'dish_type_id' => $item->id,
                        'extra_dish_type_id' => $val,
                        'store_id' => Session::get('kitchenStoreId'),
                    ]);
                }
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
        $dishType = DishType::with('extraDishTypeName')

                        ->where(['dish_id' => $id, 'dish_activate' => 1])->first();
        
        $extraDishTypes = array();

        $dishData = DishType::select('DT.dish_name','DT.dish_id', 'DT.parent_id', 'DT.rank')
            ->from('dish_type AS DT')
            ->join('product AS P', 'P.dish_type', '=', 'DT.dish_id')
            ->join('product_price_list AS PPL', 'PPL.product_id', '=', 'P.product_id')
            ->where('P.u_id', Auth::user()->u_id)
            ->where('P.s_activ', '!=' , 2)
            ->where('DT.dish_activate', 1)
            ->where('DT.extras', '1')
            ->where('PPL.store_id', Session::get('kitchenStoreId'))
            ->groupBy('DT.dish_id')
            ->orderBy('DT.rank')
            ->orderBy('DT.dish_id')
            ->get();

        if($dishData)
        {
            $dishIds = array();

            foreach($dishData as $dish)
            {
                if( !is_null($dish->parent_id) )
                {
                    $dishTypeLevel0 = DishType::from('dish_type AS DT1')
                        ->select(['DT1.dish_id', 'DT1.dish_name', 'DT1.rank'])
                        ->leftJoin('dish_type AS DT2', 'DT2.parent_id', '=', 'DT1.dish_id')
                        ->leftJoin('dish_type AS DT3', 'DT3.parent_id', '=', 'DT2.dish_id')
                        ->whereRaw("(DT1.dish_id = '{$dish->dish_id}' OR DT2.dish_id = '{$dish->dish_id}' OR DT3.dish_id = '{$dish->dish_id}') AND DT1.parent_id IS NULL")
                        ->groupBy('DT1.dish_id')
                        ->first();
                    
                    if($dishTypeLevel0)
                    {
                        if( !in_array($dishTypeLevel0->dish_id, $dishIds) )
                        {
                            $dishIds[] = $dishTypeLevel0->dish_id;

                            $extraDishTypes[$dishTypeLevel0->dish_id] = $dishTypeLevel0->dish_name;
                        }
                    }
                }
                else
                {
                    if( !in_array($dish->dish_id, $dishIds) )
                    {
                        $dishIds[] = $dish->dish_id;

                        $extraDishTypes[$dish->dish_id] = $dish->dish_name;
                    }
                }
            }
        }
        $selecteds = array();
        if($dishType->extras == '0'){
            $checked = $display = '';
        }else{
            $checked = 'checked';
            $display = 'style="display:none"';
        }
        $output = '<label for="extra_dish">'.__('messages.extra').'</label><input type="checkbox" name="extra_dish" id="extra_dish" value="1" '.$checked.'></div><div class="col-md-12 extra_dish_type_div" '.$display.'>';
        $output .= '<label for="extra_dish_type">'.__("messages.extras").':</label><select class="extra_dish_type_append" name="extra_dish_type[]" multiple="multiple">';

        if(!empty($dishType->extraDishTypeName->toArray())){
            foreach($dishType->extraDishTypeName as $item){
                $selecteds[] = $item->extra_dish_type_id;
            }
        }
        foreach($extraDishTypes as $key => $val){
            if(in_array($key, $selecteds)){
                $output .= '<option value="'.$key.'" selected>'.$val.'</option>';
            }else{
                $output .= '<option value="'.$key.'">'.$val.'</option>';
            }
        }
        $output .='</select><br>';
        return response()->json(['dishType' => $dishType, 'output' => $output]);
    }

    /**
     * Remove category image
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function removeCategoryImage($id)
    {
        $status = false;

        $dishType = DishType::where('dish_id', $id)->first();

        if($dishType)
        {
            DishType::where('dish_id', $id)->update(['dish_image' => null]);
            Storage::disk('s3')->delete($dishType->dish_image);
            $status = true;
        }

        return response()->json(['status' => $status]);
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
                    return $query->where(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'u_id' => Auth::user()->u_id, 'dish_activate' => 1, 'parent_id' => $request->parent_id])->where('dish_id', '!=', $request->dish_id);
                })
            ],
            'dish_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'dish_name.required' => __('messages.fieldRequired'),
            'dish_name.unique' => __('messages.dishTypeUnique'),
        ]);

        $data = $request->except(['_token', 'dish_image']);

        // Update category
        if($request->dish_id != $request->parent_id)
        {
            $filePath = null;

            // Check if image exist, and then delete
            $dishType = DishType::where('dish_id', $request->dish_id)->first();

            if ($request->hasFile('dish_image'))
            {   
                if( !is_null($dishType->dish_image) )
                {
                    Storage::disk('s3')->delete($dishType->dish_image);
                }

                // Upload image
                $file = $request->file('dish_image');
                $newFile = new ImageResize($file);
                $newFile->gamma(false);
                $newFile->resizeToWidth(500);
                $imageName = 'cat-img-'.time().'.'.$file->getClientOriginalExtension();
                $filePath = 'upload/category/'.$imageName;
                // Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');
                Storage::disk('s3')->put($filePath, $newFile, 'public');
            }else{
                $filePath = $dishType->dish_image;
            }

            // 
            if(isset($request->extra_dish)){
                $extra_dish = '1';
            }else{
                $extra_dish = '0';
            }

            // update dish type
            DishType::where('dish_id', $request->dish_id)
                ->update(['dish_lang' => $request->dish_lang, 'dish_name' => $request->dish_name, 'parent_id' => $request->parent_id, 'extras' => $extra_dish, 'dish_image' => $filePath]);

            ProductsExtra::where('dish_type_id',$request->dish_id)->where('store_id',Session::get('kitchenStoreId'))->delete();
            if($extra_dish == '0'){
                if(isset($request->extra_dish_type)){
                    foreach($request->extra_dish_type as $key => $val){
                        ProductsExtra::create([
                            'dish_type_id' => $request->dish_id,
                            'extra_dish_type_id' => $val,
                            'store_id' => Session::get('kitchenStoreId'),
                        ]);
                    }
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
        $dishType = DishType::where('dish_id', $id)->first();

        if(!$dishType)
        {
            return redirect('kitchen/dishtype/list')->with('error', __('messages.dishTypeNotFound'));
        }

        // 
        if( is_null($dishType->parent_id) )
        {
            $dishType2 = DishType::where('parent_id', $dishType->dish_id)->get();

            if($dishType2)
            {
                foreach($dishType2 as $level2)
                {
                    $dishType3 = DishType::where('parent_id', $level2->dish_id)->get();

                    if($dishType3)
                    {
                        foreach($dishType3 as $level3)
                        {
                            // Remove category 'level3'
                            DishType::where('dish_id', $level3->dish_id)
                                ->update(['dish_activate' => 0]);

                            if( !is_null($level3->dish_image) )
                            {
                                Storage::disk('s3')->delete($level3->dish_image);
                            }
                        }
                    }

                    // Remove category 'level2'
                    DishType::where('dish_id', $level2->dish_id)
                        ->update(['dish_activate' => 0]);

                    if( !is_null($level2->dish_image) )
                    {
                        Storage::disk('s3')->delete($level2->dish_image);
                    }
                }
            }

            // Remove category 'level1'
            DishType::where('dish_id', $dishType->dish_id)
                ->update(['dish_activate' => 0]);

            if( !is_null($dishType->dish_image) )
            {
                Storage::disk('s3')->delete($dishType->dish_image);
            }
        }
        else
        {
            // Delete level2/level3 category, and image from S3 if exist
            $dishTypes = DishType::where('dish_id', $dishType->dish_id)
                ->orWhere('parent_id', $dishType->dish_id)
                ->get();

            if($dishTypes)
            {
                DishType::where('dish_id', $dishType->dish_id)
                    ->orWhere('parent_id', $dishType->dish_id)
                    ->update(['dish_activate' => 0]);

                foreach($dishTypes as $row)
                {
                    if( !is_null($row->dish_image) )
                    {
                        Storage::disk('s3')->delete($row->dish_image);
                    }
                }
            }
        }

        return redirect('kitchen/dishtype/list')->with('success', __('messages.dishTypeDeleted'));
    }
}
