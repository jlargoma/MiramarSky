<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Auth;
use App\Services\OtaGateway\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;
use App\Rooms;
use App\DailyPrices;
use App\ChannelManagerQueues;

class OtaGate extends Controller {

  private $aptos;
  private $sOta;
       
  function __construct() {
   $this->sOta = new OtaGateway();
    $this->aptos = configZodomusAptos();
  }
  
  function index($apto = null) {
    $oConfig = new oConfig();
    $aptos = configZodomusAptos();
    $channels = $oConfig->Channels();
    if (!$apto) {
      $aux = reset($aptos);
      $apto = null;
    }

    return view('backend/zodomus/index', [
        'aptos' => $aptos,
        'apto' => $apto,
        'channels' => $channels,
    ]);
  }


  function generate_config() {
    ///admin/channel-manager/config
//    $condif = configZodomusAptos(); dd($condif); die;
//    $confFile = \Illuminate\Support\Facades\File::get(storage_path('app/config/zodomus.php'));
//  eval($confFile);
    include_once dirname(dirname(dirname(__FILE__))).'/Help/zodomus.php';
    
  }
  
  
  
  /**
   * /admin/otaGate/test
   */
  function test(){
//   $oConfig = new oConfig();
//   $rooms = $oConfig->getRooms();
//   dd($rooms);
    $conexion = $this->sOta;
    if (!$conexion->conect()){
      die('error de conexión');
    }
//    $this->createWebHook();
//    $this->createRoom();
//    $this->createRestrictionPlans();
//    $this->createRatesPlans();
//    $this->setRates();
//    $this->setMinStay();
//    $this->createOTA();
   // $this->asociateOTA();
    $this->sendAvail();
  }
  
  function sendAvail(){
    
     //Prepara la disponibilidad por día de la reserva
    $oneDay = 24*60*60;
      $startAux = time();
      $endAux = strtotime('2020-10-20');
      $ogAvail = [];
      while ($startAux<$endAux){
        $ogAvail[date('Y-m-d',$startAux)] = 5;
        $startAux+=$oneDay;
      }
    $return = $this->sOta->sendAvailability(['availability'=>[1719=>$ogAvail]]);
      dd($return,$this->sOta->response);
  }
  function createOTA(){
    //["ota_settings_id"]=> string(2) "90"
    //https://api.sandbox.reservationsteps.ru/v1/api/ota_settings?token=afb87beeccf16e94c49951dcc069c3f3&account_id=346&ota_settings_id=90
     $param = [
         'code'=>91,
        "ota_id"=> 'airbnb',
        "credentials"=>['code'=>91,'user_id'=>357488599,'expires_at'=>1595946351,'access_token'=>'2asl9b3q3nm1hn7k85stydzrj',"refresh_token"=> "ekurv59b04osaks3i1da0xrc8"], 
        'extra' =>['code'=>91],
      ]; 
     
//     "{\"user_id\": 357488599, \"expires_at\": 1595946351, \"access_token\": \"2asl9b3q3nm1hn7k85stydzrj\", \"refresh_token\": \"ekurv59b04osaks3i1da0xrc8\"}",
     $return = $this->sOta->createOTA($param);
      dd($return);
  }
  function setMinStay(){
    $stay = ['min_stay' => 1];
    $stay2 = ['min_stay' => 3];
    $aux = ["2020-08-07"=> $stay,"2020-08-08"=> $stay2];
    $restrictions = [46964=>$aux, 1719=>$aux];//RoomsID
     $param = [
        "restriction_plan_id"=> 1592050, //restriction_plan_id "Booking.com",
        "restrictions"=> $restrictions
      ]; 
     $return = $this->sOta->setMinStay($param);
      dd($return);
    
  }
  
  function setRates(){
    $aux = ["2020-08-21"=> 5600,"2020-08-22"=> 6600,"2020-08-23"=> 7600,"2020-08-24"=> 5600];
    $prices = [46964=>$aux, 46965=>$aux];//RoomsID
     $param = [
        "plan_id"=> 17633, //Plan "Booking.com",
        "price"=> $prices
      ]; 
     $return = $this->sOta->setRates($param);
      dd($return);
    
  }
  
  function createRestrictionPlans(){
    // restriction_plan_id = 764  1592056
    $param = [
        "name"=> "Restriction plan 1",
        "min_hours_before_arrival"=> 0,
        "max_hours_before_arrival"=> 720,
        "restrictions"=> []
      ]; 
     $return = $this->sOta->newRestrictionPlan($param);
      dd($return);
    
  }
  
  
  function createRatesPlans(){
    $weekPrices = [99,99,99,99,99,99,99];
    $roomsChannels = [];
    foreach ($this->aptos as $k=>$data){
      foreach ($data->rooms as $item){
        if (!isset($roomsChannels[$item->channel])) $roomsChannels[$item->channel] = [];
        $roomsChannels[$item->channel][] = $data->roomtype_id;
      }
    }
    unset($roomsChannels[1]);
    foreach ($roomsChannels as $k=>$data){
      $default_price = [];
      foreach ($data as $item) $default_price[$item] = $weekPrices;
      
      $param = [
        "default_price"=>$default_price,
        "name"=>"Rate OTA $k",
        "warranty_type"=>"no",
        "default"=>0,
        "enabled"=>1,
        "enabled_ota"=>1,
        "description"=>"Tarifa sobre la OTA nro $k",
        "name_ru"=>"Rate ru",
        "description_ru"=>"Rate description ru",
        "name_en"=>"Rate en",
        "description_en"=>"Rate description en",
        "booking_guarantee_link"=>"",
        "booking_guarantee_sum"=>50,
        "booking_guarantee_unit"=>"percentage",
        "booking_guarantee_percentage_from"=>"all",
        "booking_guarantee_services"=>0,
        "booking_guarantee_till"=>0,
        "booking_guarantee_till_days"=>0,
        "booking_guarantee_till_hours"=>0,
        "booking_guarantee_till_condition"=>0,
        "booking_guarantee_auto_booking_cancel"=>0,
        "cancellation_rules"=>"Cancellation rules",
        "cancellation_rules_ru"=>"Cancellation rules ru",
        "cancellation_rules_en"=>"Cancellation rules en",
        "booking_guarantee_description"=>"Cancellation rules",
        "booking_guarantee_description_ru"=>"Cancellation rules ru",
        "booking_guarantee_description_en"=>"Cancellation rules en",
        "restriction_plan_id"=>1592056,
        "nutrition"=>"000",
        "for_promo_code"=>0,
        "legal_entity_id"=>18,
        "order"=>8,
        "warranty_card_card_types"=>[
          "VIS",
          "ECA",
          "AMX"
        ],
        "warranty_card_need_cvv"=>0
      ];
      $return = $this->sOta->newRatesPlan($param);
      dd($return);
    }
    
  }
  
  function createWebHook(){
//    webhook_id": 4758
    $url = 'https://apartamentosierranevada.net/Ota-Gateway-Webhook';
    $param = [
      "type"=> "bookings",
      "url"=> $url,
    ]; 
    $return = $this->sOta->createWebhook($param);
    dd($return);
  }
  
  function createRoom(){
    foreach ($this->aptos as $k=>$data){
      $param = [
        "name" =>  $k,
        "adults" =>  10,
        "price" =>  2400,
        "parent_id" =>  0,
        "children" =>  10,
        "enabled" =>  1,
        "enabled_ota" =>  1,
        "description" =>  $data->name,
        "accommodation_type" =>  0,
        "name_en" => $data->name,
        "name_es" => $data->name,
        "description_en" =>  $data->name,
        "description_es" =>  $data->name,
      ];
      $return = $this->sOta->createRoom($param);
//      var_dump($param);
    }
    die('finish');
  }
  
  
  
    /**
   * 
   * @param Request $request
   * rcode (the reservation code) 
   * lcode (the property identifier, 
   */
  public function webHook(Request $request) {
    
    $rcode = $request->input('rcode');
    $lcode = $request->input('lcode');
    
         
    //save the params to response quikly the HTTP 200
    $oData = \App\ProcessedData::findOrCreate('wubook_webhook');
    $content = json_decode($oData->content,true);
    if (!$content || !is_array($content)) $content = [];
      
    $content[] = [
          'date' =>time(),
          'rcode'=>$rcode,
          'lcode'=>$lcode,
      ];
    
    $oData->content = json_encode($content);
    $oData->save();
    
    return response('',200);
  }
  
}
