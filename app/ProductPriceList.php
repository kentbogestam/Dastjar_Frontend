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
    	return $this->hasMany('App\Product','product_id','product_id');
    }

    protected $fillable = ['product_id', 'store_id', 'text', 'lang'];
    
}
