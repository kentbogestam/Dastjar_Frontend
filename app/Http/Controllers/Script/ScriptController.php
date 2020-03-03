<?php
namespace App\Http\Controllers\Script;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use App\Helper;

use DB;
use App\Company;
use App\Store;
use App\DishType;
use App\Product;
use App\ProductPriceList;
use App\ProductOfferSubSloganLangList;
use App\LangText;
use App\ProductOfferSloganLangList;
use App\ProductKeyword;
use Illuminate\Support\Arr;
class ScriptController extends Controller
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

	// 
    public function cloneStoreProduct($companyId)
    {
        // 
        $company = Company::where('company_id' , $companyId)->first();

        if($company)
        {
            // 
            $stores = Store::where(['u_id' => $company->u_id, 's_activ' => '1'])->get();

            // 
            $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name'])->where(['u_id' => $company->u_id, 'dish_activate' => 1, 'parent_id' => null])->orderBy('rank')->get();
        }

        return view('v1.script.clone-store-product', compact('stores', 'dishType', 'company'));
    }

    // 
    function cloneStoreProductPost(Request $request)
    {
        // Validation
        $this->validate($request, [
            'store_id' => 'required',
            'u_id' => 'required',
            'dish_id' => 'required',
            'store_id_to' => 'required',
        ]);

        // 
        $data = $request->except(['_token']);
        $msg = '';

        // Get all cat/subcat
        $helper = new Helper();
        $menuTypes = array();
        $menuTypes[] = (int) $request->dish_id;

        // 
        $dishTypeLevel1 = $helper->getdDishTypeBy(null, $request->dish_id);

        if($dishTypeLevel1)
        {
            foreach($dishTypeLevel1 as $level1)
            {
                $menuTypes[] = $level1->dish_id;

                // 
                $dishTypeLevel2 = $helper->getdDishTypeBy(null, $level1->dish_id);

                if($dishTypeLevel2)
                {
                    foreach($dishTypeLevel2 as $level2)
                    {
                        $menuTypes[] = $level2->dish_id;
                    }
                }
            }
        }

        if(!empty($menuTypes))
        {
            // Get all products by cat/subcat
            array_unique($menuTypes);
            $products = Product::join('dish_type','dish_type.dish_id','=','product.dish_type')
                ->join('product_price_list AS PPL', 'PPL.product_id', '=', 'product.product_id')
                ->whereIn('product.dish_type', array_unique($menuTypes))
                ->where('product.u_id', $data['u_id'])
                ->where('PPL.store_id', $data['store_id'])
                ->where('product.s_activ', '!=' , 2)
                ->where('dish_type.dish_activate',1)
                ->groupBy('product.product_id')
                ->orderBy('product_rank', 'ASC')
                ->get();

            if($products)
            {
                // dd($products->toArray());
                foreach($products as $row)
                {
                    // Check if product is not already cloned, and then clone it
                    $product = Product::select('product.product_id')
                        ->join('product_price_list AS PPL', 'PPL.product_id', '=', 'product.product_id')
                        ->whereIn('product.dish_type', array_unique($menuTypes))
                        ->where(['product.product_name' => $row->product_name, 'product.u_id' => $data['u_id'], 'PPL.store_id' => $data['store_id_to']])
                        ->where('product.s_activ', '!=' , 2)
                        ->first();

                    if(!$product)
                    {
                        DB::transaction(function () use($row, $data, $helper) {
                            // Clone product
                            $product_id = $helper->uuid();

                            while(Product::where('product_id', $product_id)->exists()){
                                $product_id = $helper->uuid();
                            }

                            // 
                            $productArr = array(
                                'product_id' => $product_id,
                                'u_id' => $row->u_id,
                                'product_name' => $row->product_name,
                                'small_image' => $row->small_image,
                                'large_image' => $row->large_image,
                                'product_rank' => $row->product_rank,
                                'lang' => $row->lang,
                                'dish_type' => $row->dish_type,
                                'product_description' => $row->product_description,
                                'preparation_Time' => $row->preparation_Time,
                                'category' => $row->category,
                                'product_number' => $row->product_number,
                                'product_info_page' => $row->product_info_page,
                                'start_of_publishing' => $row->start_of_publishing,
                                'company_id' => $row->company_id,
                            );

                            Product::create($productArr);

                            // Clone product price
                            $productPriceList = ProductPriceList::where('product_id', $row->product_id)->get();
                            
                            if($productPriceList)
                            {
                                foreach($productPriceList as $price)
                                {
                                    $productPriceListArr = array(
                                        'product_id' => $product_id,
                                        'store_id' => $data['store_id_to'],
                                        'text' => $price->text,
                                        'price' => $price->price,
                                        'lang' => $price->lang,
                                        'publishing_start_date' => $price->publishing_start_date,
                                        'publishing_end_date' => $price->publishing_end_date,
                                    );

                                    ProductPriceList::create($productPriceListArr);
                                }
                            }

                            // Add product meta
                            $data = array(
                                'product_id' => $product_id,
                                'company_id' => $row->company_id,
                                'lang' => $row->lang,
                                'product_description' => $row->product_description,
                                'product_name' => $row->product_name,
                            );

                            $this->addProductMeta($data);
                        });
                    }
                }

                $msg = 'Completed';
            }
            else
            {
                $msg = 'No product found.';
            }

            return redirect('script/clone-store-product/'.$data['company_id'])->with('success', $msg);;
        }
    }

    // Add product meta while adding new product
    private function addProductMeta($data)
    {
        $helper = new Helper();
        $sloganSubLangId = $helper->uuid();

        $productOfferSubSloganLangList = new ProductOfferSubSloganLangList();
        $productOfferSubSloganLangList->product_id = $data['product_id'];
        $productOfferSubSloganLangList->offer_sub_slogan_lang_list = $sloganSubLangId;
        $productOfferSubSloganLangList->save();

        // insert product description in lang_text table
        $langText = new LangText();
        $langText->id = $sloganSubLangId;
        $langText->lang = $data['lang'];
        $langText->text = $data['product_description'];
        $langText->save();

        $sloganLangId = $helper->uuid();

        $productOfferSloganLangList = new ProductOfferSloganLangList();
        $productOfferSloganLangList->product_id = $data['product_id'];
        $productOfferSloganLangList->offer_slogan_lang_list = $sloganLangId;
        $productOfferSloganLangList->save();

        // insert product name in lang_text table
        $langText = new LangText();
        $langText->id = $sloganLangId;
        $langText->lang = $data['lang'];
        $langText->text = $data['product_name'];
        $langText->save();

        $SystemkeyId = $helper->uuid();

        // insert product language in lang_text table
        $langText = new LangText();
        $langText->id = $SystemkeyId;
        $langText->lang = $data['lang'];
        $langText->text = $data['product_id'];
        $langText->save();

        // insert product id in product_keyword table
        $productKeyword = new ProductKeyword();
        $productKeyword->product_id = $data['product_id'];
        $productKeyword->system_key = $SystemkeyId;
        $productKeyword->save();

        $Systemkey_companyId = $helper->uuid();

        // insert company id in lang_text table
        $langText = new LangText();
        $langText->id = $Systemkey_companyId;
        $langText->lang = $data['lang'];
        $langText->text = $data['company_id'];
        $langText->save();
   
        // insert company id in product_keyword table
        $productKeyword = new ProductKeyword();
        $productKeyword->product_id = $data['product_id'];
        $productKeyword->system_key = $Systemkey_companyId;
        $productKeyword->save();
    }


    //update catering open close value when catering time is empty
    public function updateCateringValue()
    {
        $data = DB::table('store')->select('store_id','store_open_close_day_time as store_open_close_day_time_catering')->whereNull('store_open_close_day_time_catering')->get()->toArray();
        $array=array();
        foreach($data as $data1)
        {
            $array=(array) $data1;
            DB::table('store')->where('store_id',$array['store_id'])->update(['store_open_close_day_time_catering'=>$array['store_open_close_day_time_catering']]);
        }
    }
}
