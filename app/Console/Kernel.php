<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DepositService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Если по депозиту нужно начислить проценты и снять комиссию в один и тот же день,
        // то сначала начислятся проценты в 00:00, затем снимется комиссия в 01:00.
        $schedule->call(function () {
            DepositService::interest_accrual();
        })->daily();

        $schedule->call(function () {
            DepositService::commission_withdrawal();
        })->monthlyOn(1, '01:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
