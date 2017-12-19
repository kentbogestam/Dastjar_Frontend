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


    protected $fillable = ['product_id', 'store_id', 'text', 'lang'];
    
}
