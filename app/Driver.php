<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable
{
	use Notifiable;

	protected $gaurd = 'driver' ;
	protected $table = 'drivers';
	protected $fillable = ['id', 'company_id', 'name', 'email', 'phone_prefix', 'phone', 'password', 'status'];
	protected $hidden = ['password', 'remember_token'];

	// public $incrementing = false;
	protected $casts = [
	    'id' => 'string',
	];

	/*public function setPasswordAttribute($password)
    {
        if( !empty($password) )
        {
            $this->attributes['password'] = bcrypt($password);
        }
    }*/
}
