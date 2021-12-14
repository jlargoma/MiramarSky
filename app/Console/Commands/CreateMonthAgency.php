<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Expenses;
use Illuminate\Support\Facades\DB;
use App\Settings;
use App\Book;

class CreateMonthAgency extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'monthAgency:create';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create the monthly expense for Airbnb Agency';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
//    for($i=1;$i<12;$i++){
//      $date = strtotime("-$i months");
//      $this->create($date);
//    }
      
    $this->create(strtotime('-1 months'));
  }
  
  public function create($date) {
    
//    $date = strtotime('-1 months');
    $dateStart = date('Y-m-01', $date);
    $dateEnd = date('Y-m-t', $date);
//    var_dump($dateStart,$dateEnd);
    $cost = \App\Book::where('start', '>=', $dateStart)
            ->where('start', '<=', $dateEnd)
            ->where('agency', 4)
            ->sum('PVPAgencia');
    if ($cost > 0) {
      $item = \App\Expenses::where('date', '=', $dateEnd)
              ->where('type', 'agencias')
              ->where('concept', 'AIRBNB MENSUAL')
              ->first();
      if (!$item) {
        $monthItem = new \App\Expenses();
        $monthItem->type = 'agencias';
        $monthItem->concept = 'AIRBNB MENSUAL';
        $monthItem->comment = 'AIRBNB MENSUAL';
        $monthItem->date = $dateEnd;
        $monthItem->import = $cost;
        $monthItem->save();
      }
    }
  }

}
