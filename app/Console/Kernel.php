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
        Commands\SendPoll::class,
        Commands\SafeBox::class,
        Commands\CheckPartee::class,
        Commands\SendParteeSMS::class,
        Commands\CreateMonthLimpieza::class,
        Commands\SendSecondPay::class,
        Commands\SendParteeAdmin::class,
        Commands\ChatEmails::class,
        Commands\forfaitPaymentReminder::class,
        Commands\CreatePaymentFianza::class,
        Commands\GetDailyFFSeason::class,
        Commands\SendFFAdmin::class,
        Commands\OG_ImportAll::class,
        Commands\ProcessData::class,
        Commands\WubookAvailables::class,
        Commands\PricesSeason::class,
        Commands\MinStaySeason::class,
//        Commands\WubookGetBookings::class,
//        Commands\ZodomusImport::class,
//        Commands\RoomsPhotosMigrate::class,
//        Commands\ImportICal::class,
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
      
        $schedule->command('SafeBox:asignAndSend')->dailyAt('13:00')->timezone('Europe/Madrid');
        $schedule->command('SendPoll:sendEmails')->dailyAt('12:00')->timezone('Europe/Madrid');
        $schedule->command('FFSeasson:get')->dailyAt('4:00')->timezone('Europe/Madrid');
        $schedule->command('sendFFAdmin:sendForfaits')->dailyAt('6:45')->timezone('Europe/Madrid');
        $schedule->command('partee:sendSMS')->dailyAt('7:00')->timezone('Europe/Madrid');
        $schedule->command('secondPay:sendEmails')->dailyAt('7:00')->timezone('Europe/Madrid');
        $schedule->command('forfait:sendReminder')->dailyAt('8:00')->timezone('Europe/Madrid');
        $schedule->command('partee:sendAlert')->dailyAt('21:00')->timezone('Europe/Madrid');
        $schedule->command('partee:check')->everyThirtyMinutes();
        $schedule->command('OGImportAll:import')->hourly();
        $schedule->command('OTAs:sendPricesSeason')->everyMinute();
        $schedule->command('wubook:sendAvaliables')->everyFiveMinutes();
        $schedule->command('ProcessData:all')->everyFiveMinutes();
        $schedule->command('OTAs:sendMinStaySeason')->everyFiveMinutes();
//         $schedule->command('ical:import')->everyTenMinutes();
//         $schedule->command('monthLimpieza:create')->monthlyOn(1, '5:00')->timezone('Europe/Madrid');
//         $schedule->command('zodomus:import')->everyTenMinutes();
//         $schedule->command('zodomus:importAll')->hourly();
//         $schedule->command('OTAs:MinStaySeason')->everyMinute();
//         $schedule->command('mails:read')->everyThirtyMinutes();
//         $schedule->command('wubook:getBookings')->everyMinute();
    }
}
