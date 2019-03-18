<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCustomerDiscount extends Model
{
	protected $table = 'order_customer_discount';

	protected $fillable = [
        'order_id', 'discount_id'
    ];
}
