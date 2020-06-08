<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\autoCancel::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // to hit this command in 5:00, 11:00, 17:00, 23:00 in background
        $schedule->command('auto:cancel')->cron('0 5,11,17,23 * * *');
    }

    
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
