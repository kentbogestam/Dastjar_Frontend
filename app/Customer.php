<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    
    public function customerAddressDetail()
    {
        return $this->hasOne('App\CustomerAddress', 'customer_id', 'id');
    }
}
