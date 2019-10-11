<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ImportICal::class,
        Commands\CheckPartee::class,
        Commands\SendParteeSMS::class,
        Commands\CreateMonthLimpieza::class,
        Commands\RoomsPhotosMigrate::class,
        Commands\SendSecondPay::class,
        Commands\SendParteeAdmin::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('partee:check')->everyThirtyMinutes();
         $schedule->command('partee:sendSMS')->dailyAt('7:00');
         $schedule->command('partee:sendAlert')->dailyAt('21:00');
         $schedule->command('secondPay:sendEmails')->dailyAt('7:00');
         $schedule->command('monthLimpieza:create')->monthlyOn(1, '5:00');
         $schedule->command('ical:import')->everyFiveMinutes();
    }
}
