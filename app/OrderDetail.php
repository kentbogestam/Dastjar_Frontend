<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //
    protected $fillable = [
        'order_id', 'user_id', 'product_id', 'product_quality', 'product_description', 'price', 'time', 'company_id', 'store_id', 'delivery_date', 'created_at','updated_at'
    ];
}
