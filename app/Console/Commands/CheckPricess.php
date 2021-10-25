<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Services\OtaGateway\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;
use Illuminate\Support\Facades\Mail;
use App\PricesOtas;
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
  var $pricesFx;
  var $aPlans;
  var $aRoomIDs;
  var $OtaGateway;
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
    $this->from = date('Y-m-d');
//    $this->to = date('Y-m-d', strtotime('+6 days'));
    $this->to = date('Y-m-d', strtotime('+7 months'));

    $this->OtaGateway = new \App\Services\OtaGateway\OtaGateway();
    if (!$this->OtaGateway->conect()){
      $oLog = new \App\LogsData();
      $oLog->infoProceess('OTAs_prices','Error al conectarse a la API');
      return 'Ota no conectada';
    }
    
      $this->aAgencies = $this->oConfig->getAllAgency();
      $this->pricesFx  = unserialize(\App\Settings::getContent('prices_ota'));

      $this->prepareDatas();
      $this->preparePrices();
      $pricesOta = $this->get_otaGateway();
      $errors = $this->check_Prices($pricesOta);
//      file_put_contents(storage_path()."/app/OTAPriceErrors",json_encode($errors));
      $pOtas = new PricesOtas();
      $pOtas->truncate();
      $pOtas->insert($errors);

      $this->sendPrices($errors);

//    $this->sendMessage();
    $this->OtaGateway->disconect();
  }

  //-------------------------------------------------------
  
  function prepareDatas() {
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
    $this->aPlans = $aPlans;
    $this->aRoomIDs = $aRoomIDs;
  }
  //-------------------------------------------------------
  
  /**
   * Get array prices from OTA
   * @return string
   */
  private function get_otaGateway() {

    $auxDay = arrayDays($this->from, $this->to, 'Y-m-d', 0);
    $aRooms = [];
    foreach ($this->aRoomIDs as $ch => $r) {
      $aRooms[$ch] = $auxDay;
    }

    //------------------------------------------------
    $result = [];
    foreach ($this->aPlans as $k => $planID) {
      $aux = $aRooms;
      $rates = $this->OtaGateway->getRates($planID, $this->from, $this->to);
      if (isset($rates->prices)) {
        foreach ($rates->prices as $rID => $a) {
          $ch = array_search($rID, $this->aRoomIDs);
          if ($ch) {
            foreach ($a as $date => $price)
              $aux[$ch][$date] = $price->price;
          }
        }
      }
      $result[$k] = $aux;
    }

    return $result;
  }

  //-----------------------------------------------------------
  /**
   * Prepare Dayly Prices of the channels rooms
   */
  private function preparePrices() {
    $dailyPrices = [];
    foreach ($this->oConfig->getRooms() as $chGroup => $v) {
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
  
  
  /**
   * Compare the admin OTAs prices with the OTA prices
   * @param type $pricesOta
   * @return type
   */
  private function check_Prices($pricesOta) {
    $toControl = [];
    $today = date('Y-m-d H:i:s');
    foreach ($pricesOta as $plan => $channels) {
      foreach ($channels as $ch => $lst) {
        $adminPrice = $this->priceDay[$ch];
        foreach ($lst as $d => $p) {
          $pAdmin = 0;
          if (isset($adminPrice[$d]))
          $pAdmin = $this->priceByChannel($adminPrice[$d], $plan, $ch);
          
          $pAdmin = ceil($pAdmin);
          $priceOta = ceil($p);
          if ($pAdmin != $priceOta) {
            $toControl[] = [
              'plan' => $plan,
              'ch'   => $ch,
              'date' => $d,
              'price_admin' => round($pAdmin,2),
              'price_ota'   => round($priceOta,2),
              'created_at'  => $today ];
             
          }
        }
      }
    }
   
    return $toControl;
  }

  //-----------------------------------------------------------
  
  /**
   * Return Admin OTAs Prices
   * @param type $p
   * @param type $ota
   * @param type $ch
   * @return type
   */
  function priceByChannel($p,$ota,$ch){
      if (is_array($this->pricesFx) && isset($this->pricesFx[$ch . $ota])){
        $priceData = $this->pricesFx[$ch . $ota];
        //incremento el valor fijo por noche
        if ($priceData['f'] && $priceData['f']>0){
          $p += $priceData['f'];
        }
        
        //incremento el valo por porcentaje
        if ($priceData['p'] && $priceData['p']>0){
          $p = $p * (1+ ($priceData['p'] / 100));
        }
      }
      return $p;
  }
  //-----------------------------------------------------------
  
  /**
   * Send the prices to OTAs
   * @param type $lst
   */
  function sendPrices($lst){
    if (count($lst) == 0) return;
    $aux = [];
    foreach ($lst as $item){
      $plan = $item['plan'];
      $ch = $item['ch'];
      if (!isset($aux[$plan])) $aux[$plan] = [];
      if (!isset($aux[$plan][$ch]))  $aux[$plan][$ch] = [];
      $aux[$plan][$ch][] = [$item['date'],$item['price_admin']];
    }
    
    foreach ($aux as $plan => $aCh){
      $toSend = [];
      foreach ($aCh as $ch=>$i){
        if (isset($this->aRoomIDs[$ch])){
          $rID = $this->aRoomIDs[$ch];
          $aux2 = [];
          foreach ($i as $j){
            $aux2[$j[0]] = $j[1];
          }
          $toSend[$rID] = $aux2;
        }
      }
      if (count($toSend) == 0) continue;
      $response = $this->OtaGateway->sendRatesPrices([
                  "plan_id"=>$plan,
                  "price"=>$toSend
              ]);
    }
    
  }
  //-----------------------------------------------------------
  private function sendMessage() {
    $subject = 'Atención: Control de Precios OTAs';
    $mailContent = '';
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
