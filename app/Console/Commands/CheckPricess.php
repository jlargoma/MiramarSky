<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Services\OtaGateway\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;
use Illuminate\Support\Facades\Mail;
use App\ProcessedData;
use App\Book;

class CheckPricess extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'OTAs:CheckPricess';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check prices on the otas';

  /**
   * The console command result.
   *
   * @var string
   */
  var $result = array();
  var $resultIDs = array();
  var $from;
  var $to;
  var $aAgencies;
  var $priceDay;
  private $oConfig = null;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    $this->result = array();
    $this->oConfig = new oConfig();
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->from = date('Y-m-d', strtotime('+1 days'));
//    $this->to = date('Y-m-d', strtotime('+6 days'));
    $this->to = date('Y-m-d', strtotime('+6 months'));

    $this->aAgencies = $this->oConfig->getAllAgency();
    $this->preparePrices();
    $pricesOta = $this->get_otaGateway();
    $errors = $this->check_Prices($pricesOta);

    file_put_contents(storage_path()."/app/OTAPriceErrors",json_encode($errors));

//    $this->sendMessage();
  }

  private function get_otaGateway() {


//    // if Exist the cache, return it
//    $cKey = md5('OTAPrice' . $this->from . $this->to);
//    $sCache = new \App\Services\CacheData($cKey);
//    $cache = $sCache->get();
//    if ($cache)
//      return $cache;
    
//     $sCache->set($result);

    //Prepare Plans_id
    $aPlans = [];
    foreach ($this->aAgencies as $k => $v)
      $aPlans[$v] = $this->oConfig->Plans($v);


    //Prepare Roons Channels
    $aRoomIDs = $this->oConfig->getRooms();

    ////-  test remove ----------------------------------- 
//    $aPlans = [1 => 862, 4 => 863];
//    $aRoomIDs = [
//        'DDE' => 1692,
//        'DDL' => 1695,
//        'EstS' => 1696,
//        'EstL' => 1698,
//        'ESTG' => 1693,
//        'CHLT' => 1697,
//        '7J' => 1694,
//        '9R' => 1718,
//        '9F' => 1719,
//    ];
    ////-  test remove ----------------------------------- 


    $auxDay = arrayDays($this->from, $this->to, 'Y-m-d', 0);
    $aRooms = [];
    foreach ($aRoomIDs as $ch => $r) {
      $aRooms[$ch] = $auxDay;
    }



    $OtaGateway = new \App\Services\OtaGateway\OtaGateway();
    if (!$OtaGateway->conect())
      return 'Ota no conectada';

    //------------------------------------------------
    $result = [];
    foreach ($aPlans as $k => $planID) {
      $aux = $aRooms;
      $rates = $OtaGateway->getRates($planID, $this->from, $this->to);
      if (isset($rates->prices)) {
        foreach ($rates->prices as $rID => $a) {
          $ch = array_search($rID, $aRoomIDs);
          if ($ch) {
            foreach ($a as $date => $price)
              $aux[$ch][$date] = $price->price;
          }
        }
      }
      $result[$k] = $aux;
    }

   
    $OtaGateway->disconect();
    return $result;
  }

  //-----------------------------------------------------------
  private function preparePrices() {
    $otaGateway = new \App\Services\OtaGateway\Config();
    $dailyPrices = [];
    foreach ($otaGateway->getRooms() as $chGroup => $v) {
      $oRoom = \App\Rooms::where('channel_group', $chGroup)->first();
      if ($oRoom) {
        $this->dailyPrice($oRoom);
      }
    }
  }

  private function dailyPrice($oRoom) {

    $to = date('Y-m-d', strtotime('+1 day', strtotime($this->to)));
    //increase 1 day
    $defaults = $oRoom->defaultCostPrice($this->from, $to , $oRoom->minOcu);
    $priceDay = $defaults['priceDay'];
    $oPrice = \App\DailyPrices::where('channel_group', $oRoom->channel_group)
            ->where('date', '>=', $this->from)
            ->where('date', '<=', $this->to)
            ->get();

    if ($oPrice) {
      foreach ($oPrice as $p) {
        if (isset($priceDay[$p->date]) && $p->price)
          $priceDay[$p->date] = $p->price;
      }
    }

    $this->priceDay[$oRoom->channel_group] = $priceDay;
  }

  //-----------------------------------------------------------

  private function check_Prices($pricesOta) {
    $toControl = [];
    foreach ($pricesOta as $plan => $channels) {
      foreach ($channels as $ch => $lst) {
        $adminPrice = $this->priceDay[$ch];
        foreach ($lst as $d => $p) {
          $pAdmin = 0;
          if (isset($adminPrice[$d]))
          $pAdmin = $this->oConfig->priceByChannel($adminPrice[$d], $plan, $ch);
          
          $pAdmin = ceil($pAdmin);
          $priceOta = ceil($p);
          if ($pAdmin != $priceOta) {
            if (!isset($toControl[$plan]))
              $toControl[$plan] = [];
            if (!isset($toControl[$plan][$ch]))
              $toControl[$plan][$ch] = [];

            $toControl[$plan][$ch][$d] = [$pAdmin, $priceOta];
          }
        }
      }
    }
   
    return $toControl;
  }

  //-----------------------------------------------------------

  private function sendMessage() {
    $subject = 'Atención: Control de Reservas OTAs';

    $mailContent = '<h3>Las siguientes reservas deben controlarse:</h3>';
    $send = false;
    if (count($this->result) > 0) {
      $send = true;
      $mailContent .= '<div><h4>Booking Numbers:</h4>' . implode(',', $this->result) . '</div>';
    }
    if (count($this->resultIDs) > 0) {
      $send = true;
      $mailContent .= '<div><h4>Booking ID:</h4>' . implode(',', $this->resultIDs) . '</div>';
    }

    if ($send)
      Mail::send('backend.emails.base', [
          'mailContent' => $mailContent,
          'title' => $subject
              ], function ($message) use ($subject) {
                $message->from(config('mail.from.address'));
                $message->to(config('mail.from.address'));
                $message->cc('pingodevweb@gmail.com');
                $message->subject($subject);
              });
  }

}
