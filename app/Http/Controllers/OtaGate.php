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
use App\Traits\OtasTraits;
use \App\Traits\Bookings\LoadByOTA;


class OtaGate extends Controller {

  use OtasTraits,LoadByOTA;
  
  private $aptos;
  private $sOta;
  var $oConfig;
       
  function __construct() {
    $this->sOta = new OtaGateway();
    $this->aptos = configZodomusAptos();
    $this->oConfig = new oConfig();
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
    $conexion = $this->sOta;
    $conexion = $this->createBooking();
  }
  
  function createBooking() {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/tests/ota-bookings.php';
    $oBookings = \json_decode($bookings);
//    dd($oBookings);
    if (isset($oBookings->bookings))
      $oBookings = $oBookings->bookings;
    if (!$oBookings) {
      var_dump('booking no found');
      return null;
    }
    $this->loadBooking($oBookings);
  }
  function sendAvail(){
    
     //Prepara la disponibilidad por día de la reserva
        //desde el 28-03-2021
      $startAux = strtotime('2021-03-28');//time();
      $endAux = strtotime('2021-03-28'. ' +1 year');
      $ogAvail = [];
      while ($startAux<$endAux){
        $ogAvail[date('Y-m-d',$startAux)] = 1;
        $startAux = strtotime("+1 day", $startAux);
      }
    $return = $this->sOta->sendAvailability(['availability'=>[47077=>$ogAvail]]);
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
    $url = 'https://admin.apartamentosierranevada.net/Ota-Gateway-Webhook';
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
    
    $params = $request->all();
    //save a copy
    $json = json_encode($params);
    $dir = storage_path().'/OtaWateway';
    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($dir."/".time(),$json);
    
    $data =  $request->input('data',null);
    if (!is_array($data)){
      $data = json_decode($data,true);
    }
    
    if (isset($data)){
      $data = (isset($data['request'])) ?  $data['request']['data'] : $data;
      if (isset($data['booking_numbers'])){
         $this->sOta->conect();
         //BEGIN: getBookings
          $response = $this->sOta->getBooking($data['booking_numbers']);
          $oBookings = null;
          if ( isset($response->booking) )  $oBookings = [$response->booking];
          if ( isset($response->bookings) )  $oBookings = $response->bookings;
          if (!$oBookings){
            var_dump('booking no found',$data['booking_numbers']);
          } else {
           $this->loadBooking($oBookings);
          }
         //END: getBookings
         $this->sOta->disconect();
      } else {
        var_dump('empty data',$data);
      }
    } else {
        var_dump('Unset data',$data);
    }
    return response('',200);
  }

  /* --------------------------------------------------------------------------------- */
  //WUBOOK
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function wBookFunctions() {
    
    $WuBook = new \App\Services\Wubook\Wubook();
    $WuBook->conect();
    $url = 'https://admin.apartamentosierranevada.net/wubook-Webhook';
    // $WuBook->pushURL($url);
    $WuBook->get_pushURL();
    
  }
  
  /**
   * 
   * @param Request $request
   * rcode (the reservation code) 
   * lcode (the property identifier, 
   */
  public function webHook_Wubook(Request $request) {
    
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
    
    //save a copy
    $json = json_encode($request->all());
    $dir = storage_path().'/wubook';
    if (!file_exists($dir)) {
      mkdir($dir, 0775, true);
    }
    file_put_contents($dir."/".date('Hms').'-'.$rcode,$json);
    
    return response('',200);
  }
  /* END WUBOOK */
  /* --------------------------------------------------------------------------------- */
}
