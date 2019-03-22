<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDiscount extends Model
{
	protected $table = 'customer_discount';

	protected $fillable = [
        'customer_id', 'discount_id'
    ];
}
