<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanySubscriptionDetail extends Model
{
	protected $table = 'company_subscription_detail';

	protected $fillable = [
        'company_id', 'stripe_customer_id', 'access_token', 'stripe_user_id', 'refresh_token', 'stripe_publishable_key'
    ];
}
