<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Services\LogsService;
use App\Services\OtaGateway\Config as oConfigOtas;
use App\Services\Wubook\WuBook;
use App\DailyPrices;
use App\Rooms;

///admin/Wubook/Availables?detail=1
class WubookRates extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'wubook:sendRates';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Mark Rates rooms in wubook';


  /**
   * The console command result.
   *
   * @var string
   */
  var $result = array();
  var $sLog = null;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->result = array();
    $this->sLog = new LogsService('OTAs_wubook','console');
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $this->check_and_send();
  }

  private function check_and_send()
  {
    $kyControl = 'wubookRate';
    $control = \App\ProcessedData::findOrCreate($kyControl);
    $control = json_decode($control->content);
    if (!$control){
      $this->sLog->info('No rate to send');
      return null;
    }
    $channels = getAptosChannel();

    $items = [];
    $start = date('Y-m-d');
    $end = date('Y-m-d',strtotime("+12 months"));
    
    $oRoom = new Rooms();


    foreach ($channels as $ch) {
      $oRoom = Rooms::where('channel_group',$ch)->first();
      $defaullts = $oRoom->defaultCostPrice($start,$end,1);
      $defaullts = $defaullts['priceDay'];
      $items[$ch] = $this->getPrices($ch, $start, $end,$defaullts);
    }

    $WuBook = new WuBook();
    $rChanels = $WuBook->getRoomsEquivalent(null);
    $roomdays = [];
    foreach ($items as $ch => $data) {
      $rID = $rChanels[$ch];
      if ($rID > 0){
        $roomdays['_int_'.$rID] = $data;
      } 
    }
//dd($roomdays);
    if (count($roomdays) > 0) {
      $WuBook->conect();
        if (!$WuBook->set_Prices($start,$roomdays)) {
          $this->sLog->warning( 'error send rates');
          return null;
        }
        $this->sLog->info('Rates sender');
      $WuBook->disconect();
    }

     // Clear the auxiliar datatable
     $oControl = \App\ProcessedData::findOrCreate($kyControl);
     $contentControl = json_decode($oControl->content);
     if ($contentControl == $control){
       $oControl->content = null;
       $oControl->save();
     }


  }

  function getPrices($ch, $start, $end,$priceDay)
  {
    $oConfig = new oConfigOtas();
   
    foreach ($priceDay as $k=>$v) {
      $priceDay[$k] =  $oConfig->priceAirbnb($v,$ch); 
    }

    $oPrice = DailyPrices::where('channel_group', $ch)
              ->where('date', '>=', $start)
              ->where('date', '<', $end)
              ->get();
      if ($oPrice) {
        foreach ($oPrice as $p) {
          if($p->price>0)
          $priceDay[$p->date] =  $oConfig->priceAirbnb($p->price,$ch); 
        }
      }

    $aux = [];
    foreach ($priceDay as $p) $aux[] = ceil($p);
    return $aux;
  }
}
