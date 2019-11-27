<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';
    // public $timestamps = false;
    public $incrementing = false; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['product_id', 'dish_type', 'product_name', 'product_description', 'lang', 'preparation_Time', 'brand_name', 'small_image', 'large_image', 'category', 'start_of_publishing', 'is_sponsored', 'coupon_delivery_type', 'offer_type', 'product_info_page', 'link', 'is_public', 'ean_code', 'product_number', 'u_id', 'company_id', 's_activ', 'reseller_status', 'product_rank'];

    public function menuPrice()
    {
    	return $this->hasMany('App\ProductPriceList','product_id','product_id');
    }

    public function menuType()
    {
        return $this->hasMany('App\DishType','u_id','u_id');
    }
}
