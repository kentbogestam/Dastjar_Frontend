<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'customer';
    
    protected $fillable = [
        'name', 'email', 'password', 'fac_id', 'phone_number', 'otp', 'phone_number_prifix', 'web_version'
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

    public function paidOrderList()
    {
        // return $this->hasMany('App\Order','user_id')->where('paid', 0)->whereNotIn('online_paid', [2])->where('orders.cancel', '!=', 1)->orderBy('order_id', 'desc');
        
        return $this->hasMany('App\Order','user_id')
            ->where(['paid' => 0])
            ->where('orders.check_deliveryDate', '>=', date('Y-m-d'))
            ->whereNotIn('online_paid', [2])
            ->where('orders.cancel', '!=', 1)
            ->orderBy('order_id', 'desc');
    }

    /**
     * Get user's all addresses
     * @return [type] [description]
     */
    public function addresses()
    {
        return $this->hasMany('App\UserAddress', 'customer_id', 'id')
            ->where(['is_permanent' => '1']);
    }
}
