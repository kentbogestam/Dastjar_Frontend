<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationFee extends Model
{
	protected $table = 'application_fee';

	/**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['currency', 'stripe_fee_percent', 'stripe_fee_fixed', 'application_fee'];
}
