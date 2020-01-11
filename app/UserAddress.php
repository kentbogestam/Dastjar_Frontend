<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
	protected $table = 'customer_addresses';

	protected $fillable = [
        'customer_id', 'full_name', 'phone_prefix', 'mobile', 'entry_code', 'apt_no', 'company_name', 'other_info', 'zipcode', 'address', 'street', 'landmark', 'city', 'state', 'country', 'is_permanent'
    ];
}
