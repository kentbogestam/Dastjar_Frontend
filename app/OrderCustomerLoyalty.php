<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCustomerLoyalty extends Model
{
	protected $table = 'order_customer_loyalty';

	protected $fillable = [
        'customer_id', 'loyalty_id', 'order_id'
    ];
}
