<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Book;
use App\Years;
use App\Seasons;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\DB;


class zAutomaticTask extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'zAutomaticTask:proccess';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '';
  private $sLog;

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

    $books = DB::select("SELECT book.id,pax,rooms.minOcu,room_id, type_book,cost_apto,start,finish FROM `book` "
            . "INNER JOIN rooms on book.room_id = rooms.id "
            . "WHERE `start` > '2021-06-01' AND type_book != 4 AND type_book != 0 AND pax>rooms.minOcu");
    
    foreach ($books as $b){
      $oRoom = \App\Rooms::find($b->room_id);
      $prices = $oRoom->defaultCostPrice($b->start, $b->finish, $b->pax);
      $costDay = array_sum($prices['costDay']);
//      Book::where('id',$b->id)->update(['cost_apto'=>$costDay]);
      
      echo '--------------------------------'."\n";
      echo $b->pax."\n";
     var_dump($prices['costDay']);
      echo $b->id.': '.ceil($b->cost_apto).' => '.$costDay."\n";
    }
    
  }

}
