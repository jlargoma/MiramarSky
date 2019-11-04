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
        Commands\ChatEmails::class,
        Commands\forfaitPaymentReminder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      //Le quito 2 horas de diff
         $schedule->command('partee:check')->everyThirtyMinutes();
         $schedule->command('partee:sendSMS')->dailyAt('7:00')->timezone('Europe/Madrid');
         $schedule->command('partee:sendAlert')->dailyAt('21:00')->timezone('Europe/Madrid');
         $schedule->command('secondPay:sendEmails')->dailyAt('7:00')->timezone('Europe/Madrid');
         $schedule->command('monthLimpieza:create')->monthlyOn(1, '5:00')->timezone('Europe/Madrid');
         $schedule->command('ical:import')->everyFiveMinutes();
         $schedule->command('mails:read')->everyThirtyMinutes();
    }
}
