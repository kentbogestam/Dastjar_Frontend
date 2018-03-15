<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPriceList extends Model
{
    protected $table = 'product_price_list';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

 	public function menuPrice()
    {
    	return $this->hasMany('App\ProductPriceList','product_id','product_id');
    }

    public function storeProduct()
    {
//Jab description vali error aaye tb ye use krna.
        // return $this->hasMany('App\Product','product_id','product_id')->join('product_offer_sub_slogan_lang_list', 'product.product_id','=', 'product_offer_sub_slogan_lang_list.product_id')->join('lang_text', 'product_offer_sub_slogan_lang_list.offer_sub_slogan_lang_list','=', 'lang_text.id');

    	return $this->hasMany('App\Product','product_id','product_id')->where('s_activ', 0);
    }

    protected $fillable = ['product_id', 'store_id', 'text', 'lang'];
    
}
