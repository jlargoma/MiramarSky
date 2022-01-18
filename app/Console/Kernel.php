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
        Commands\SafeBoxUnassing::class,
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
        Commands\ProcessMultipleRoomLock::class,
        Commands\SendAvailibilityMonth::class,
        Commands\CheckBookings::class,
        Commands\ClearSessions::class,
        Commands\ProcessPaylandSeasson::class,
        Commands\CheckBookingsCheckin::class,
        Commands\BookingsDays::class,
        Commands\SendParteeReminder::class,
        Commands\SendCheckinMsg::class,
        Commands\CheckPrices::class,
        Commands\icalOld::class,
        Commands\DataDis::class,
        Commands\CreateMonthAgency::class,
        Commands\zAutomaticTask::class,
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
      $schedule->command('partee:sendReminder')->dailyAt('7:00')->timezone('Europe/Madrid');
      $schedule->command('SafeBoxUnassing:unasign')->dailyAt('6:00')->timezone('Europe/Madrid');
      $schedule->command('SafeBox:asignAndSend')->dailyAt('14:00')->timezone('Europe/Madrid');
      $schedule->command('SendPoll:sendEmails')->dailyAt('12:00')->timezone('Europe/Madrid');
      $schedule->command('checking:sendMsg')->dailyAt('19:00')->timezone('Europe/Madrid');
      $schedule->command('FFSeasson:get')->dailyAt('4:00')->timezone('Europe/Madrid');
      $schedule->command('OTAs:SendAvailibilityMonth')->dailyAt('2:00')->timezone('Europe/Madrid');
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
      $schedule->command('MultipleRoomLock:Process')->hourly();
      $schedule->command('OTAs:checkBookings')->dailyAt('1:00')->timezone('Europe/Madrid');
      $schedule->command('ClearSessions:process')->dailyAt('4:00')->timezone('Europe/Madrid');
      $schedule->command('BookingsDays:load')->hourly();
      $schedule->command('OTAs:checkBookingsCheckin')->dailyAt('1:40')->timezone('Europe/Madrid');
      $schedule->command('PaylandSeasson:process')->dailyAt('4:00')->timezone('Europe/Madrid');
        
            //everyThreeHours
      $schedule->command('OTAs:SendAvailibilityMonth')->dailyAt('7:00')->timezone('Europe/Madrid');
      $schedule->command('OTAs:SendAvailibilityMonth')->dailyAt('11:00')->timezone('Europe/Madrid');
      $schedule->command('OTAs:SendAvailibilityMonth')->dailyAt('15:00')->timezone('Europe/Madrid');
      $schedule->command('OTAs:SendAvailibilityMonth')->dailyAt('19:00')->timezone('Europe/Madrid');
      $schedule->command('OTAs:SendAvailibilityMonth')->dailyAt('22:00')->timezone('Europe/Madrid');
      
      
      $schedule->command('monthAgency:create')->monthly()->timezone('Europe/Madrid');
        
      $schedule->command('OTAs:CheckPrices')->everyThirtyMinutes();
      $schedule->command('DataDis:load')->dailyAt('2:00')->timezone('Europe/Madrid');
    }
}
