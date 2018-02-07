<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'user';
    
    protected $fillable = [
        'u_id', 'email', 'passwd','password', 'remember_token', 'fname','lname', 'role', 'phone', 'mobile_phone','saddr', 'street_addr', 'city_addr', 'home_zip','country', 'caddr', 'resellers_bank', 'social_number','Date', 'company_id', 'activ', 'temp','email_varify_code','language','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function orderList()
    {
        return $this->hasMany('App\Order','user_id');
    }

    public function kitchenPaidOrderList()
    {
        return $this->hasMany('App\Order','user_id')->where('paid', 0);
    }
}
