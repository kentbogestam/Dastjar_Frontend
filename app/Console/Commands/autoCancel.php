<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;

class autoCancel extends Command
{
    protected $signature = 'auto:cancel';

    protected $description = 'On hitting this cron un-paid data having lesser than 12 hour time get cancelled utomatically.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // update status to cancel to 3 means auto cancel on non payment
        Order::where('order_type','eat_later')
            ->whereNotIn('online_paid', ['1','4'])
            ->where('cancel', '0')
            ->where('delivery_timestamp', '>', time())
            ->where('delivery_timestamp', '<', strtotime('+12 hour'))
            ->update(['cancel' => 3]);

        // if payment is done then change verified status to 1
        Order::where('order_type','eat_later')
            ->whereIn('online_paid', ['1','4'])
            ->where('catering_order_status', '2')
            ->where('cancel', '0')
            ->where('delivery_timestamp', '>', time())
            ->where('delivery_timestamp', '<', strtotime('+12 hour'))
            ->update(['is_verified' => '1']);
    }
}
