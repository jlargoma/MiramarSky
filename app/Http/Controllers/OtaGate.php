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


class OtaGate extends Controller {

  use OtasTraits;
  
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
//   $oConfig = new oConfig();
//   $rooms = $oConfig->getRooms();
//   dd($rooms);
    $conexion = $this->sOta;
//    $conexion = $this->loadBooking(null);
    
    
//    
//    $data = '';
//    $b = [];
//    $a = json_decode($data);
//    foreach ($a as $i){
//    foreach ($i as $j){
//      $b[] = ($j->number);
//    }
//    }
    $b = ["KM77W_271220","P34LH_271220","5A6V6_261220",];
          
    $return = [
        'data'=>[
            'booking_numbers' =>$b
        ]
    ];
    echo json_encode($return);
    die;
    
    
    
    
    
    
    
    
    
    
//    if (!$conexion->conect()){
//      die('error de conexión');
//    }
//    $this->getBooking();
//    echo $this->oConfig->priceByChannel(100,99,'DDL',false,1,"2020-12-31");
//    $this->createWebHook();
//    $this->createRoom();
//    $this->createRestrictionPlans();
//    $this->createRatesPlans();
//    $this->setRates();
//    $this->setMinStay();
//    $this->createOTA();
   // $this->asociateOTA();
//    $this->sendAvail();
  }
  
  function getBooking(){

//  $param = ['D953V_280720','NUDDS_280720','VUPSA_280720'];
//  $this->loadBooking($param);
//  $cg='DDE';
//    $ext = ['2931064261','3856183780','2610785042','1593726240','3918198990', 
//'2409530217','3922578176','3717109572','3358547680','2766376803', 
//'1579622319', '2150440729', '3317338448', '2976529004', '3959797450', '3521844214'];
//  $response = $this->sOta->getBooking(null);
//  dd($response);
//  foreach ($response->bookings as $b){
//    if (in_array($b->ota_booking_id, $ext))
//      echo $b->surname .' '.$b->surname.' > '.$b->number.' - '. $b->ota_booking_id.'      '.$b->status_id.'<br>';
////    if($b->status_id ==2 ){
////      echo '"'. $b->ota_booking_id.'",<br>';
////    }
//  }
//  die;
//  dd($response);
  
  }
  function sendAvail(){
    
     //Prepara la disponibilidad por día de la reserva
    $oneDay = 24*60*60;
    //desde el 28-03-2021
      $startAux = strtotime('2021-03-28');//time();
      $endAux = strtotime('2021-03-28'. ' +1 year');
      $ogAvail = [];
      while ($startAux<$endAux){
        $ogAvail[date('Y-m-d',$startAux)] = 1;
//        $startAux+=$oneDay;
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
         $this->loadBooking($data['booking_numbers']);
         $this->sOta->disconect();
      } else {
        var_dump('empty data',$data);
      }
    } else {
        var_dump('Unset data',$data);
    }
    return response('',200);
  }
  
  private function loadBooking($booking_numbers) {

    $response = $this->sOta->getBooking($booking_numbers);
    $oBookings = null;
    if ( isset($response->booking) )  $oBookings = [$response->booking];
    if ( isset($response->bookings) )  $oBookings = $response->bookings;
    if (!$oBookings){
      var_dump('booking no found',$booking_numbers);
      return null;
    }
    
    $oConfig = new oConfig();
    foreach ($oBookings as $oBooking){
      $channel_group = $oConfig->getChannelByRoom($oBooking->roomtype_id);
      
      $extra_array = is_string($oBooking->extra_array) ? json_decode($oBooking->extra_array) : $oBooking->extra_array;
      if (isset($extra_array->from_google) && $extra_array->from_google){
          $oBooking->ota_id = 'google-hotel';
      }
      
      $reserv = [
                'channel' => $oBooking->ota_id,
                'bkg_number' => $oBooking->number,
                'rate_id' => $oBooking->plan_id,
                'external_roomId' => $oBooking->roomtype_id,
                'reser_id' => $oBooking->ota_booking_id,
                'comision'=>0,
                'channel_group' => $channel_group,
                'status' => $oBooking->status_id,
                'agency' => $oConfig->getAgency($oBooking->ota_id),
                'customer_name' => $oBooking->name.' '.$oBooking->surname,
                'customer_email' => $oBooking->email,
                'customer_phone' => $oBooking->phone,
                'customer_comment' => $oBooking->comment,
                'totalPrice' => $oBooking->amount,
                'adults' => $oBooking->adults,
                'children' => $oBooking->children,
                'extra_array' => $extra_array,
                'start' => $oBooking->arrival,
                'end' => $oBooking->departure,
              ];
      
      $bookID = $this->sOta->addBook($channel_group,$reserv);
      
      
    if ($bookID && $oBooking->ota_id == 'google-hotel'){
        
      $book = \App\Book::find($bookID);
      $body = 'Hola, ha entrado una nueva reserva desde Google Hotel:<br/><br/>';
       
      $customer = $book->customer;
      $subject = 'RESERVA GOOGLEHOTELS : '.$customer->name;
      $body .= '<b>Nombre:</b>: '.$customer->name.'<br/><br/>';
      $body .= '<b>e-mail:</b>: '.$customer->email.'<br/><br/>';
      $body .= '<b>Teléfono:</b>: '.$customer->phone.'<br/><br/>';
      $body .= '<b>Habitación:</b> '.$book->room->name.'<br/><br/>';
      $body .= '<b>PVP:</b>: '.number_format($book->total_price, 0, '', '.').'<br/><br/>';
      $body .= '<b>Fechas:</b> '.convertDateToShow_text($book->start).' - '. convertDateToShow_text($book->finish).'<br/><br/>';
      $body .= '<b>Noches:</b> '.$book->nigths.'<br/><br/>';
      $body .= '<b>Paxs:</b> '.$book->pax.'<br/><br/>';
      $body .= '<b>Comtentarios:</b> '.$book->book_comments.'<br/><br/>';
      
       $sended = \Illuminate\Support\Facades\Mail::send('backend.emails.base', [
            'mailContent' => $body,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(config('mail.from.address'));
            $message->to("reservas@apartamentosierranevada.net");
            $message->subject($subject);
            $message->replyTo(config('mail.from.address'));
        });
        
    }
    
//      var_dump($reserv,$oBooking);
    }
    
  }
}
