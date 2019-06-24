<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
	protected $table = 'drivers';

	protected $fillable = ['id', 'company_id', 'name', 'email', 'phone_prefix', 'phone', 'password', 'status'];

	protected $hidden = ['password', 'remember_token'];

	// public $incrementing = false;
	protected $casts = [
	    'id' => 'string',
	];
}
