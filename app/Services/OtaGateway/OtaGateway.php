<?php

namespace App\Services\OtaGateway;

use App\Services\OtaGateway\Config as oConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\DailyPrices;
use App\Services\LogsService;

class OtaGateway {

  public $response;
  public $responseCode;
  public $rooms;
  protected $token;
  protected $URL;
  protected $oConfig;
  protected $account_id;
  private $sLog;

  public function __construct() {
    $this->URL = env('OTA_GATEWAY_URL');
    $this->account_id = env('OTA_GATEWAY_USR_ID');
    $this->oConfig = new oConfig();
    $this->sLog = new LogsService('OTAs','OtaGateway');
  }

  /**
   * 
   * @param type $endpoint
   * @param type $method
   * @param type $data
   * @return boolean
   */
  public function call($endpoint, $method = "POST", $data = [], $fixParam = '') {

    if ($method == "POST" || $method == "PUT") {

      $data_string = json_encode($data);
      $ch = curl_init($this->URL . $endpoint);

      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
              )
      );
    } else {
      $url = $this->URL . $endpoint;
      if (count($data)) {
        $param = [];
        foreach ($data as $k => $d) {
          $param[] = "$k=$d";
        }
        $url .= '?' . implode('&', $param);
      }
      $url .= $fixParam;
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 10); //  CURLOPT_TIMEOUT => 10,
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
      ));
    }

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    var_dump(\json_decode($result),$result,$httpCode);
    curl_close($ch);
    if ($httpCode!=200){
      if (isset($data['username'])){
        unset($data['username']);
        unset($data['password']);
      }
      $data['endpoint'] = $this->URL . $endpoint;
      $this->sLog->error($result,$data);
      return false;
    }
    
    $this->response = null;
    $this->responseCode = $httpCode;
    $this->response = \json_decode($result);
    
     if (!is_object($this->response) || !$this->response){
       if (isset($data['username'])){
        unset($data['username']);
        unset($data['password']);
        }
       $data['endpoint'] = $this->URL . $endpoint;
      $this->sLog->error($result,$data);
      return false;
    }
    
    return TRUE;
   
  }

  public function conect() {

    if (isset($_COOKIE["OTA_GATE_TOKEN"])) {
      $this->token = $_COOKIE["OTA_GATE_TOKEN"];
      return true;
    }
    $params = array(
        'username' => env('OTA_GATEWAY_USR'),
        'password' => env('OTA_GATEWAY_PSW')
    );
    $Response = $this->call('auth', "POST", $params);
    if ($Response) {
      $this->token = strval($this->response->token);
      setcookie("OTA_GATE_TOKEN", $this->token, time() + 3000);
      return true;
    }
    return false;
  }

  public function disconect() {
    if (isset($_COOKIE["OTA_GATE_TOKEN"])) {
      $this->token = $_COOKIE["OTA_GATE_TOKEN"];
      $Response = $this->call('auth', "DELETE", []);
      setcookie("OTA_GATE_TOKEN", $this->token, time() - 3000);
    }
  }

  public function createRoom($params) {

    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $this->call('roomtypes', 'POST', $params);
    return ($this->response);
  }

  public function newRestrictionPlan($params) {

    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $this->call('restriction_plans', 'POST', $params);
    return ($this->response);
  }

  public function createWebhook($params) {

    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $this->call('webhook', 'POST', $params);
    return ($this->response);
  }

  public function newRatesPlan($params) {

    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $this->call('restriction_plans', 'POST', $params);
    return ($this->response);
  }

  public function setRates($params) {
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $params['plan_id'] = $this->oConfig->Plans();
//    $this->call('prices', 'POST', $params);
    
    $agencyLst = $this->oConfig->getAllAgency();
    foreach ($agencyLst as $agenc => $id)
      $this->setRatesOta($params,$id);

    return ($this->responseCode);
  }
  public function setRatesGHotel($params) {
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $params['plan_id'] = $this->oConfig->Plans();
    $this->setRatesOta($params,99);
    return ($this->responseCode);
  }
  public function setRatesOta($params,$ota_id) {
     $priceBase = $params['price'];
    /* SEND TO AirBnb */
    foreach ($priceBase as $room=>$prices){
      $aux = $params['price'][$room];
      foreach ($prices as $day=>$price){
        $aux[$day] =$this->oConfig->priceByChannel($price,$ota_id,$room,false,1,$day);
      }
      $params['price'][$room] = $aux;
    }
    $params['plan_id'] = $this->oConfig->Plans($ota_id);
    if ($params['plan_id']>0)  $this->call('prices', 'POST', $params);
  }
  
  public function setMinStay($params) {
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
//    $params['restriction_plan_id'] = $this->oConfig->restriction_plan();
//    $this->call('restrictions', 'POST', $params);
    
    $agencyLst = $this->oConfig->getAllAgency();
    foreach ($agencyLst as $agenc => $id)
      $this->setMinStayOta($params,$id);
    return ($this->responseCode);
  }
  public function setMinStayOta($params,$ota_id) {
    $params['restriction_plan_id'] = $this->oConfig->restriction_plan($ota_id);
    $this->call('restrictions', 'POST', $params);
    return ($this->responseCode);
  }
  public function createOTA($params) {
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
//      dd($params);
    $this->call('ota_settings', 'POST', $params);
    return ($this->response);
  }

  public function sendAvailability($params) {
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;

    $this->call('availability', 'POST', $params);
    return ($this->responseCode);
  }

  public function sendAvailabilityByCh($ch,$aLstDays) {
    $return = null;
    $allRoomsOta = $this->oConfig->getRooms();
    if (isset($allRoomsOta[$ch])){
      $return = $this->sendAvailability([
          'availability'=>[$allRoomsOta[$ch]=>$aLstDays]
        ]);
    }
    return $return;
  }
  
  function getBooking($booking_numbers) {
    $params = [];
    $fixParam = "";
    if (count($booking_numbers) == 1) {
      $params['booking_number'] = $booking_numbers[0];
    } else {
      $paranName = strval('booking_numbers[]');
      foreach ($booking_numbers as $b) {
        $fixParam .= '&' . $paranName . '=' . $b;
      }
    }
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
//    $params['booking_number'] = "F2DLL-130820";
//    $params['created_from'] = '2020-06-09';
//    $params['created_to'] = date('Y-m-d');
//    $params['arrival_from'] = '2020-12-28';
//    $params['arrival_to'] = '2021-01-26';
    
    $this->call('bookings', 'GET', $params, $fixParam);
    return $this->response;
  }

  function getBookings($from, $to) {
    $params = [
        'created_from' => $from,
        'created_to' => $to,
        'token' => $this->token,
        'account_id' => $this->account_id,
      ];
    $this->call('bookings', 'GET', $params);
    return $this->response;
  }

  function getBookingsCheckin($date) {
    $params = [
        'arrival_from' => $date,
        'token' => $this->token,
        'account_id' => $this->account_id,
      ];
    $this->call('bookings', 'GET', $params);
    return $this->response;
  }

  
  public function calculateRoomToFastPayment($apto, $start, $finish, $roomID = null) {

    $room = new \App\Rooms();
    return $room->calculateRoomToFastPayment($apto, $start, $finish, $roomID);
  }

  function reservations_cc($channelId, $propertyId, $reservationId) {
    $params = [
        "channelId" => $channelId,
        "propertyId" => $propertyId,
        "reservationId" => $reservationId,
    ];

    $this->call('reservations-cc', 'GET', $params, true);
    return $this->response;
  }

  /**
   * Add/Edit Booking
   * 
   */
  public function addBook($cg, $reserv) {
    $update = null;
       /*     * ******************************************************** */
    /** CANCEL THE BOOKING * */
    //Booking Status. 1 - new, 2 - canceled, 3 - pending
    $this->reservaModificada($reserv);
    $alreadyExist_qry = \App\Book::where('bkg_number', $reserv['bkg_number']);
    if (isset($reserv['reser_id']) && $reserv['reser_id'] > 0) {
      $alreadyExist_qry->Where('external_id', $reserv['reser_id']);
    }
    $alreadyExist = $alreadyExist_qry->first();
    if ($alreadyExist) {
      if ($reserv['status'] == 2) {//Cancelada
        $response = $alreadyExist->changeBook(98, "", $alreadyExist);
        if ($response['status'] == 'success' || $response['status'] == 'warning') {
          //Ya esta disponible
          $alreadyExist->sendAvailibilityBy_status();
        }
//        echo $alreadyExist->id.',';
        return $alreadyExist->id;
      } else {
        $update = $alreadyExist->id;
      }
    } else {
       if ($reserv['status'] == 2) {//Cancelada
         return null; //la ignoro
       }
    }

    $book = new \App\Book();
    $schedule = $scheduleOut = null;
    /////////////////////////////
    $auxTime = explode(' ',$reserv['start']);
    if (is_array($auxTime) && count($auxTime) == 2){
      $start = $auxTime[0];
      $aux2 =  explode(':',$auxTime[1]);
      if (is_array($aux2))  $schedule = $aux2[0];
    } else {
      $start = $reserv['start'];
    }
    /////////////////////////////
    $auxTime = explode(' ',$reserv['end']);   
    if (is_array($auxTime) && count($auxTime) == 2){
      $finish = $auxTime[0];
      $aux2 =  explode(':',$auxTime[1]);
      if (is_array($aux2))  $scheduleOut = $aux2[0];
    } else {
      $finish = $reserv['start'];
    }
    /////////////////////////////
    $nigths = calcNights($start, $finish);
    $reserv['start_date'] = $start;
    $reserv['end_date'] = $finish;
    $reserv['nigths'] = $nigths;
    $reserv['schedule'] = $schedule;
    $reserv['scheduleOut'] = $scheduleOut;

    /** UPDATE THE BOOKING * */
    if ($update) {
      // Customer
      $customer = \App\Customers::find($alreadyExist->customer_id);
      if ($customer && $customer->id == $alreadyExist->customer_id) {
        $customer->name = $reserv['customer_name'];
        $customer->email = $reserv['customer_email'];
        $customer->phone = '+'.str_replace('+','',$reserv['customer_phone']);
        $customer->DNI = "";
        $customer->email_notif = $reserv['customer_email'];
        $customer->send_notif = 1;
        $customer->country = '';
        $customer->city = '';
        $customer->zipCode = '';
        $customer->save();
      }

      if (!in_array($alreadyExist->type_book,$alreadyExist->typeBooksReserv)){
        $alreadyExist->changeBook(11, "", $alreadyExist);
        $alreadyExist->type_book = 11; // por las dudas que falle
      }
      $this->updBooking($alreadyExist, $reserv);
      return $update;
    }

    /** CREATE THE BOOKING * */
    $roomID = $this->calculateRoomToFastPayment($cg, $start, $finish);
    if ($roomID < 0) {
      $roomID = 142;
    }
    $book = new \App\Book();

    // Customer
    $customer = new \App\Customers();
    $customer->user_id = 23;
    $customer->name = $reserv['customer_name'];
    $customer->email = $reserv['customer_email'];
    $customer->phone = $reserv['customer_phone'];
    $customer->DNI = "";
    $customer->email_notif = $reserv['customer_email'];
    $customer->send_notif = 1;
    $customer->country = null;
    $customer->city = -1;
    $customer->zipCode = '';
    $customer->save();

    //Create Book
    $book->user_id = 39;
    $book->customer_id = $customer->id;
    $book->room_id = $roomID;
    $book->external_id = $reserv['reser_id'];
    $book->bkg_number = $reserv['bkg_number'];
    $book->external_roomId = $reserv['external_roomId'];
    $book->type_book = 11;
    $book->type_park = 1;
    
    $room = \App\Rooms::find($roomID);
    if ($room && $room->luxury == 1){
      $book->type_luxury = 1;
    }
    
    $book->save();

    $this->updBooking($book, $reserv);
    return $book->id;
  }

  /**
   * 
   * @param type $book
   * @param type $reserv
   */
  private function updBooking($book, $reserv) {

    $pax = $reserv['adults'] + $reserv['children'];
    $book_comments = $book->book_comments . "\n"
            . 'Adultos: ' . $reserv['adults'] . ' - '
            . 'Niños: ' . $reserv['children'];

    $agency = $reserv['agency'];
    $amount = floatval($reserv['totalPrice']);
    $PVPAgencia = 0;


    $Cc = null;
    foreach ($reserv['extra_array'] as $k => $v) {
      if ($k == 'Cc')
        $Cc = $v;
      if ($k == 'Ota commission'){
        $reserv['comision'] = $v;
        if ($reserv['channel'] == 'airbnb')
          $reserv['totalPrice'] += $v;
      }
    }


    if ($agency == 99)
      $reserv['comision'] = intval($reserv['totalPrice']) * 0.12;
    $comment = $reserv['customer_comment'];

    $book->start = $reserv['start'];
    $book->finish = $reserv['end'];
//    $book->schedule = $reserv['schedule'];
//    $book->scheduleOut = $reserv['scheduleOut'];
    $book->nigths = $reserv['nigths'];
    $book->comment = $comment;
    $book->book_comments = $book_comments;
    
    $book->agency = $reserv['agency'];
    $book->pax = $pax;
    $book->real_pax = $pax;
    $book->PVPAgencia = $reserv['comision'];
    $book->total_price = $reserv['totalPrice'];
    $book->priceOTA = $reserv['totalPrice'];
    $book->total_price = $reserv['totalPrice'];

    $book->save();

    $totales = $book->getPriceBook($book->start, $book->finish, $book->room_id);

    $book->sup_limp = $totales['price_limp'];
    $book->cost_limp = $totales['cost_limp'];
    $book->sup_park = $totales['parking'];
    $book->cost_park = $totales['cost_parking'];
    $book->sup_lujo = $totales['price_lux'];
    $book->cost_lujo = $totales['cost_lux'];
    $book->cost_apto = $totales['cost_apto'];
    $book->extraCost = $totales['cost_extr'];
    $book->extraPrice = $totales['price_extr'];

    $book->cost_total = $book->get_costeTotal();
    $book->real_price = $totales['price_total'];
    $book->total_ben = $book->total_price - $book->cost_total;
    $book->save();

    $book->sendAvailibility($book->room_id, $reserv['start_date'], $reserv['end_date']);


    if (false) { //no usamos mas los datos de la Visa
      $oVisa = DB::table('book_visa')
              ->where('book_id', $book->id)
              ->where('customer_id', $book->customer_id)
              ->first();
      if ($oVisa) {
        DB::table('book_visa')
                ->where('id', $oVisa->id)
                ->update([
                    'visa_data' => json_encode($Cc),
                    'updated_at' => date('Y-m-d H:m:s'),
                    'imported' => 1]);
      } else {
        DB::table('book_visa')->insert([
            'book_id' => $book->id,
            'user_id' => 39,
            'customer_id' => $book->customer_id,
            'visa_data' => json_encode($Cc),
            'imported' => 0,
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s'),
        ]);
      }
    }
  }
  
  function reservaModificada($reserv){
    if ($reserv['modified_from']){
     
      $alreadyExist_qry = \App\Book::where('bkg_number', $reserv['modified_from']);
      if (isset($reserv['reser_id']) && $reserv['reser_id'] > 0) {
        $alreadyExist_qry->Where('external_id', $reserv['reser_id']);
      }
      $alreadyExist = $alreadyExist_qry->first();
      if ($alreadyExist){
        $alreadyExist->bkg_number = $reserv['bkg_number'];
        $alreadyExist->save();
         \Illuminate\Support\Facades\Mail::send('backend.emails.base-admin', [
             'content' => 'La reserva '.$reserv['modified_from'].
              ' tiene el external_id '.$reserv['reser_id'].' modifica a bkg_number '.$reserv['bkg_number'],
          ], function ($message){
              $message->from(env('MAIL_FROM'));
              $message->to('pingodevweb@gmail.com');
              $message->subject('Actualización de reservas');
          });
          
      }
    }
    
  }

  /*   * ********************************************************** */
  /*   * ***********    AUX FUNCTIONS             ***************** */
  /*   * ********************************************************** */
  
  
  public function getAvailability($dfrom,$dto) {
    $params = [];
    $params['token'] = $this->token;
    $params['account_id'] = $this->account_id;
    $params['dfrom'] = $dfrom;
    $params['dto'] = $dto;
   
    $this->call('availability', 'GET', $params);
    return ($this->response);
  }
}
