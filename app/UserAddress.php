<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
	protected $table = 'customer_addresses';

	protected $fillable = [
        'customer_id', 'full_name', 'mobile', 'zipcode', 'address', 'street', 'landmark', 'city', 'state', 'is_permanent'
    ];
}
