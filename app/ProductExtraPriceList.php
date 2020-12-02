<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductExtraPriceList extends Model
{
    protected $table = 'product_extra_price_list';
    public $timestamps = false;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = ['ppl_id', 'product_id', 'price', 'publishing_start_time', 'publishing_end_time'];
    
}
