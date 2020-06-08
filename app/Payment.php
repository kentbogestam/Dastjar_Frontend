<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $table = 'payment';
	
     protected $fillable = [
        'user_id', 'order_id', 'amount', 'transaction_id', 'balance_transaction', 'status', 'created_at', 'updated_at'
    ];
}
