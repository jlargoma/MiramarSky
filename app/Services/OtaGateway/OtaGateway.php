<?php

namespace App\Services\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\DailyPrices;


class OtaGateway{
   
  public $response;
  public $responseCode;
  public $rooms;
  protected   $token;
  protected   $URL;
  protected   $oConfig;
  protected   $account_id;
  
  
   public function __construct()
    {
//      $this->URL = config('app.zodomus.base_uri');
//      $this->usr = config('app.zodomus.usr');
//      $this->psw = config('app.zodomus.psw');
//      $this->psw_card = config('app.zodomus.psw_cc');
      
      $this->URL = "https://api.sandbox.reservationsteps.ru/v1/api/";
//      $this->URL = "https://api.reservationsteps.ru/v1/api/";
//      $this->account_id = 4768;
      $this->account_id = 346;


      $this->oConfig = new oConfig();
    }
    
    /**
     * 
     * @param type $endpoint
     * @param type $method
     * @param type $data
     * @return boolean
     */
    public function call( $endpoint,$method = "POST", $data = [],$cc=false)
    {
       
      if ($method == "POST" || $method == "PUT" || $method == "DELETE"  ){
        
        $data_string = json_encode($data);   
        $ch = curl_init($this->URL.$endpoint);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
        curl_setopt($ch, CURLOPT_TIMEOUT , 10); //  CURLOPT_TIMEOUT => 10,
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',   
            )                                                                       
        );          
      } else {
        $url = $this->URL.$endpoint;
        if (count($data)){
          $param=[];
          foreach ($data as $k=>$d){
            $param[] = "$k=$d";
          }
          $url .='?'. implode('&',$param);
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
        curl_setopt($ch, CURLOPT_TIMEOUT , 10); //  CURLOPT_TIMEOUT => 10,
        curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',        
        ));     
      }
      
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
//      dd(\json_decode($result),$result,$httpCode);
      curl_close($ch);
      $this->response = null;
      $this->responseCode = $httpCode;
       $this->response = \json_decode($result); return TRUE; 
      switch ($httpCode){
        case 200:
          if(!$result) 
          { 
            $this->response = null;
            return FALSE; 
          } 
          $this->response = \json_decode($result);
          return TRUE; 
          break;
        case 400:
          $this->response = 'Wrong data - Bad Request';
          break;
        case 401:
          $this->response = $result;
          break;
        case 404:
          $this->response = $result;//'NotFound';
          break;
        default :
          $this->response = 'Server error';
          break;
      }

      return FALSE; 
      
    }
    
    
    public function conect(){
      
      if (isset($_COOKIE["OTA_GATE_TOKEN"])){
        $this->token = $_COOKIE["OTA_GATE_TOKEN"];
        return true; 
      }
      $params = array(
          'username' => 'jlargo@mksport.es',
//          'password' => 'jAlEmqiEVNs0VaSQ91kVhMi6qRWGiBQV',
          'password' => 'eNpcwGUwFZotHapIMWvmEIhr7UKhwfmO',
          );
      $Response = $this->call('auth',"POST", $params);
//      dd($Response,$this->response);
      if ($Response){
        $this->token = strval($this->response->token);
        setcookie("OTA_GATE_TOKEN",  $this->token, time()+3000); 
        return true; 
      } 
      return false;
    }
    
    public function disconect(){
      if (isset($_COOKIE["OTA_GATE_TOKEN"])){
        $this->token = $_COOKIE["OTA_GATE_TOKEN"];
        $Response = $this->call('auth',"DELETE", $params);
        setcookie("OTA_GATE_TOKEN",  $this->token, time()-3000); 
      }
    }
    
    public function createRoom($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('roomtypes','POST',$params);
      return  ($this->response);
    }
    public function newRestrictionPlan($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('restriction_plans','POST',$params);
      return  ($this->response);
    }
    public function createWebhook($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('webhook','POST',$params);
      return  ($this->response);
    }
    public function newRatesPlan($params) {
     
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('restriction_plans','POST',$params);
      return  ($this->response);
    }
    
    public function setRates($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $params['plan_id'] = $this->oConfig->Plans();
      $this->call('prices','POST',$params);
      return  ($this->responseCode);
    }
    public function setMinStay($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $params['restriction_plan_id'] = $this->oConfig->restriction_plan();
      $this->call('restrictions','POST',$params);
      return  ($this->responseCode);
    }
    public function createOTA($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
//      dd($params);
      $this->call('ota_settings','POST',$params);
      return  ($this->response);
    }
    public function sendAvailability($params) {
      $params['token'] = $this->token;
      $params['account_id'] = $this->account_id;
      $this->call('availability','POST',$params);
      return  ($this->responseCode);
    }
    
    
    
    
    
    
    
    public function checkProperty($apto) {
//      echo '"propertyId"=> "2798863"<br>';
      $params = [
        "channelId"=> 1,
        "propertyId"=> $apto,
      ];
      $this->call('property-check','POST',$params);
      return $this->response;
    }
    
    /**
     * Activate Apt: must map all Rooms and Rates
     * @param type $params
     * @return type
     */
    public function activateRoom($params) {
      $this->call('rooms-activation','POST',$params);
      return $this->response;
      
    }
    public function getRoomsAvailability($apto,$startDate,$endDate) {
      
      $params = [
        "channelId"=> 2,
        "propertyId"=> $apto,
        "dateFrom"=> $startDate,
        "dateTo"=> $endDate,
      ];
      $this->call('availability','GET',$params);
      return $this->response;
      
    }
    public function setRoomsAvailability($params) {
      
    
      $this->call('availability','POST',$params);
      return $this->response;
      
    }
    
    public function getSummary($apto) {
      $params = [
        "channelId"=> 1,
        "propertyId"=> $apto,
      ];
      $this->call('reservations-summary','GET',$params);
      return $this->response;
    }
    
    public function getRates($apto) {
      $params = [
        "channelId"=> 1,
        "propertyId"=> $apto,
      ];
      $this->call('room-rates','GET',$params);
      return $this->response;
    }
    
  
    
    public function getBookings($channelId,$apto) {
      
      
       $params = [
        "channelId"=> $channelId,
        "propertyId"=> $apto,
      ];
      
      $this->call('reservations-summary','GET',$params);
      return $this->response;
      
    }
    
    public function getBookingsQueue($channelId,$apto) {
      
      
       $params = [
        "channelId"=> $channelId,
        "propertyId"=> $apto,
      ];
      
      $this->call('reservations-queue','GET',$params);
      return $this->response;
      
    }
    
    
    public function createTestReserv($apto) {
      
      
      $params = [
        "channelId"=> 2,
        "propertyId"=> $apto,
        "status"=> "new",
        'reservationId' => time() 
      ];
        
      $this->call('reservations-createtest','POST',$params);
      return $this->response;
    
    }
    
    public function setRatesDerived($apto) {
      
      $params = [
        "channelId"=> 1,
        "propertyId"=> $apto,
        "roomId"=> 12345678901,
        "rateId"=> 123456789991,
        "baseOccupancy"=> 6,
        "occupancy"=> [
            //With percentage
            ["persons"=>1,"percentage"=>"-25","round"=>"1"],
//          With Additional cost
            ["persons"=>3,"additional"=>"10","round"=>"1"],
        ],
      ];
        
      $this->call('rates-derived','POST',$params);
      return $this->response;
      
       
      
    }   
    
    function getBooking($params) {
      
      $this->call('reservations','GET',$params);
      return $this->response;
      
    }
    
    function getChannelManager($channelId,$propertyId,$roomId){
      
      $aptos = configZodomusAptos();
      foreach ($aptos as $cg => $data){
        foreach ($data->rooms as $room){
          if (
            $room->channel == $channelId
            && $room->propID == $propertyId
            && $room->roomID == $roomId
            )
            return $cg;
        }

      }
      
      return null;
    }
    
    
  public function calculateRoomToFastPayment($apto, $start, $finish,$roomID = null) {

    $room = new \App\Rooms();
    return $room->calculateRoomToFastPayment($apto, $start, $finish,$roomID);
    
  }
  
  function cancelRates($propertyId,$roomId,$rateId){
      $params = [
      "channelId" => 1,
      "propertyId" => $propertyId,
      "roomId" => $roomId,
      "rateId" => $rateId,
      "status" => "cancelled"
      ];
    
      $this->call('product','GET',$params);
      return $this->response;
  }
  
  
  function reservations_cc($channelId,$propertyId,$reservationId){
      $params = [
      "channelId" => $channelId,
      "propertyId" => $propertyId,
      "reservationId" => $reservationId,
      ];
    
      $this->call('reservations-cc','GET',$params,true);
      return $this->response;
  }
  
  function promotions($channelId,$propertyId){
    $params = [
      "channelId"  => $channelId,
      "propertyId" => $propertyId,
      "active"     => 1,
    ];
    
    $this->call('promotions','GET',$params);

    return $this->response;
  }
  
  function saveBooking($cg,$reserv){
    
    $roomID = $this->calculateRoomToFastPayment($cg, $reserv['start'], $reserv['end']);
    
    if ($roomID<0){
      $roomID = 33;
    }
            
    $nights = calcNights($reserv['start'], $reserv['end']);
    $book = new \App\Book();

    $rCustomer = $reserv['customer'];
    
    $phone = '';
    if ($rCustomer->phoneCountryCode && $rCustomer->phoneCountryCode>0) $phone .= ($rCustomer->phoneCountryCode);
    if ($rCustomer->phoneCityArea && $rCustomer->phoneCityArea>0) $phone .= ($rCustomer->phoneCityArea);
    if ($rCustomer->phone && $rCustomer->phone>0) $phone .= ($rCustomer->phone);
    $customer = new \App\Customers();
    $customer->user_id = 23;
    $customer->name = $rCustomer->firstName . ' ' . $rCustomer->lastName;
    $customer->email = $rCustomer->email;
    $customer->phone = $phone;
    $customer->DNI = "";
    $customer->email_notif = $rCustomer->email;
    $customer->send_notif = 1;
    $customer->country	 = $rCustomer->countryCode;
    $customer->city	 = $rCustomer->city;
    $customer->zipCode	 = $rCustomer->zipCode;
    
    if ($customer->zipCode>0){
      $customer->province = substr($customer->zipCode, 0,2);
    } else {
      if ($customer->country == 'es' || $customer->country == 'ES'){
        if (trim($rCustomer->city) != ''){
          $obj = \App\Provinces::where('province','LIKE', '%'.trim($rCustomer->city).'%')->first();
          if ($obj) {
            $customer->province = $obj->code;
          }
        }
      }
    }
    
    $customer->save();
    

    $comment = $this->oConfig->get_detailRate($reserv['rate_id']);
    $comment .= $this->oConfig->get_detailRate($reserv['rewrittenFromId']);
    //Create Book
    $book->user_id = 39;
    $book->customer_id = $customer->id;
    $book->room_id = $roomID;
    $book->start = $reserv['start'];
    $book->finish = $reserv['end'];
    $book->comment = $comment; //$reserv['mealPlan'];
    $book->type_book = 11;
    $book->nigths = $nights;
    $book->agency = $reserv['agency'];
    $book->pax = $reserv['numberOfGuests'];
    $book->real_pax = $reserv['numberOfGuests'];
    $book->PVPAgencia = $reserv['comision'];
    $book->total_price = $reserv['totalPrice'];
    $book->external_id = $reserv['reser_id'];
    $book->propertyId = $reserv['propertyId'];
    $book->external_roomId = $reserv['external_roomId'];
    $book->priceOTA = $reserv['totalPrice'];

    $book->save();
    
    
    $totales = $book->getPriceBook($book->start,$book->finish,$roomID);

    $book->cost_apto  = $totales['cost'];
    $book->extraPrice = $totales['extra_fixed'];
    $book->extraCost  = $totales['cost_extra_fixed'];
    $book->sup_limp   = $totales['limp'];
    $book->cost_limp  = $totales['cost_limp'];
    $book->cost_total = $book->get_costeTotal();
    $book->real_price  = $totales['pvp'] + $book->sup_limp + $book->extraPrice;
    $book->save();
    
    
    
    
    if ($reserv['rewrittenFromId'] == 17086950){
      $oBookExtra = new \App\BookExtraPrices();
      $oBookExtra->book_id = $book->id;
      $oBookExtra->extra_id = 10;
      $oBookExtra->qty = 1;
      $oBookExtra->price = 0;
      $oBookExtra->cost = 0;
      $oBookExtra->status ='';
      $oBookExtra->type = 'breakfast';
      $oBookExtra->fixed = 0;
      $oBookExtra->deleted = 0;
      $oBookExtra->save();
    }
        
    $book->sendAvailibility($roomID,$reserv['start'],$reserv['end']);    
        
    return $book->id;
  }
  
  function updBooking($cg,$reserv,$bookID){
    
    $book = \App\Book::find($bookID);
    
    $roomID = $this->calculateRoomToFastPayment($cg, $reserv['start'], $reserv['end'],$book->room_id);
    
    if ($roomID<0){
      $roomID = 33;
    }
            
    $nights = calcNights($reserv['start'], $reserv['end']);
    
    $comment = $this->oConfig->get_detailRate($reserv['rate_id']);
    $book->room_id = $roomID;
    $book->start = $reserv['start'];
    $book->finish = $reserv['end'];
    $book->comment = $book->comment.' - UPD:'.$comment; //$reserv['mealPlan'];
    $book->nigths = $nights;
    $book->pax = $reserv['numberOfGuests'];
    $book->real_pax = $reserv['numberOfGuests'];
    $book->PVPAgencia = $reserv['comision'];
    $book->total_price = $reserv['totalPrice'];
    $book->priceOTA = $reserv['totalPrice'];
    $book->save();
    
    
    $totales = $book->getPriceBook($book->start,$book->finish,$roomID);

    $book->cost_apto  = $totales['cost'];
    $book->extraPrice = $totales['extra_fixed'];
    $book->extraCost  = $totales['cost_extra_fixed'];
    $book->sup_limp   = $totales['limp'];
    $book->cost_limp  = $totales['cost_limp'];
    $book->cost_total = $book->cost_total + $totales['cost_extra_dynamic'];
    $book->real_price  = $totales['pvp'] + $book->sup_limp + $book->extraPrice+$totales['extra_dynamic'];
    $book->total_price += $totales['extra_dynamic'];
    $book->save();
            
    $book->sendAvailibility($roomID,$reserv['start'],$reserv['end']);
    
    return $book->id;
  }
 
  /*************************************************************/
  /*************    AUX FUNCTIONS             ******************/
  /*************************************************************/
  
  function createNewProperties(){
     $rooms = [];
      $aptosLst = configZodomusAptos();
      foreach ($aptosLst as $k=>$v){
        foreach ($v->rooms as $j){
          if($j->channel == 2){
            $rooms[] =  ["roomId" => $j->roomID,"roomName" => $j->name,"rates" => [$j->rateID],"quantity" => 10,"status" => 1];
          }
          
        }
      }
//      dd($rooms);
      
      
      $roomToAt = [
             "channelId" => 2,
             "propertyId" => $apto,
             "rooms" => $rooms
           ];
//    $return = $Zodomus->activateRoom($roomToAt);
  }
  
  function sendRatesGroup($apto,$rateId,$roomID,$channel_group){
//      $channel_group = 'ROSASJ';
  
      $priceDay = $minDay = [];
      $startTime = strtotime('2020-08-28');
      $finsh =  strtotime('2020-12-20');
      $day = 24*60*60;
      while ($startTime<$finsh){
        $priceDay[date('Y-m-d',$startTime)] = 999;
        $startTime += $day;
      }
      $oPrice = \App\DailyPrices::where('channel_group',$channel_group)
                ->where('date','>=',date('Y-m-d'))
                ->where('date','<=',date('Y-m-d',$finsh))
                ->get();
    
    
      foreach ($oPrice as $p){
        if (isset($priceDay[$p->date])){
          $priceDay[$p->date] = $p->price;
        }
      }
      
      $d1 = date('Y-m-d');
      $d2 = null;
      $to_send = [];
      $precio = null;
      foreach ($priceDay as $d=>$p){
        $d2 = $d;
        if (is_null($precio)) $precio = $p;
        if (is_null($d1))  $d1 = $d;
        
        
        if ($p!=$precio){
          $to_send[] =  [
              "dateFrom" => $d1,
              "dateTo" => $d2,
              "prices" =>   [ "price" => $precio ],
              
          ];
          $d1 = $d;
          $precio = $p;
        }
        
        
      }
      
       if ($d2!=$d1){
          $to_send[] =  [
              "dateFrom" => $d1,
              "dateTo" => $d2,
              "prices" =>   [ "price" => $precio ],
              
          ];
        }
      
      
     $weekDays = [ 
      "sun"=>true,
      "mon"=>true,
      "tue"=>true,
      "wed"=>true,
      "thu"=>true,
      "fri"=>true,
      "sat"=>true];
        
        
      foreach ($to_send as $v){
        if ($v['prices']['price'] == 999) continue;
        $param = [
                "channelId" =>  2,
                "propertyId" => $apto,
                "roomId" =>  $roomID,
                "dateFrom" => $v['dateFrom'],
                "dateTo" => $v['dateTo'],
                "currencyCode" =>  "EUR",
                "rateId" =>  $rateId,
                "weekDays" => $weekDays,
                "prices" =>  $v['prices'],
                "closed" =>  0,
                "minimumStay" => 1,
                "minimumStayArrival" => 1,
              ];
         
              $errorMsg = $this->setRates($param,$channel_group);
//var_dump($this->response,$param);
              if ($errorMsg){
                return $errorMsg;
              }
      }
//  dd($apto,$rateId,$roomID,$channel_group,$to_send);
      
      
  }
  
  function getCall($call,$params) {
    if ($params)  $this->call($call,'GET',$params);
    else  $this->call($call,'GET');
    return $this->response;
      
  }
}
