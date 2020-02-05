<?php

namespace App\Services\Zodomus;
use App\Services\Zodomus\Config as ZConfig;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\DailyPrices;


class Zodomus{
   
  public $response;
  public $responseCode;
  public $rooms;
  protected   $token;
  protected   $URL;
  protected   $usr;
  protected   $psw;
  protected   $psw_card;
  protected   $ZConfig;
  
  
   public function __construct()
    {
      $this->URL = 'https://api.zodomus.com/';
      $this->usr = env('ZODOMUS_USR');
      $this->psw = env('ZODOMUS_PSW');
      $this->psw_card = env('ZODOMUS_PSW_CC');
//      $this->usr = 'mvwgA9k4Xlizvx3Znsod3vxXfsXv1a+v1LOQ88DNXmc=';
//      $this->psw = 'JFyoMMKjssKDOdZ+Nz+snuus8epX0pzxFmpgcP/SWlw=';
//      $this->psw_card = "8JvtVvGDUQVfn86nPdOyCwwD6A3EWfF6XaiH7x2Ktrk=";
      $this->ZConfig = new ZConfig();
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
      if(!$this->usr || !$this->psw) 
      { 
        $this->response = 'user required';
        return FALSE; 
      } 
      $credentials = ($this->usr.":".$this->psw);
      if ($cc) $credentials = ($this->usr.":".$this->psw_card);
       
      if ($method == "POST" || $method == "PUT"){
        
        $data_string = json_encode($data);   
//        echo $data_string;die;
        $ch = curl_init($this->URL.$endpoint);
        curl_setopt($ch, CURLOPT_USERPWD,$credentials);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
        curl_setopt($ch, CURLOPT_TIMEOUT , 10); //  CURLOPT_TIMEOUT => 10,
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',   
//            'Content-Length: ' . strlen($data_string)
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
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD,$credentials);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
        curl_setopt($ch, CURLOPT_TIMEOUT , 10); //  CURLOPT_TIMEOUT => 10,
        curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',        
        ));     
      }
      
      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
      curl_close($ch);
      $this->response = null;
      $this->responseCode = $httpCode;
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
    
    
    public function getInfo() {
      $params = [ "channelId"=> 1];
//      $this->call('reporting-misconduct-categories','GET',$params);
      $this->call('price-model','GET');
//      $this->call('account','GET');
//      $this->call('channels','GET');
      //EUR
      var_dump($this->response);
      var_dump($this->responseCode);
    }
    
    public function activateChannels($apto) {
      
      $params = [
        "channelId"=> 1,
        "propertyId"=> $apto,
        "priceModelId"=> 2
      ];
//      $this->call('property-status','POST',$params);
      $this->call('property-activation','POST',$params);
      return  ($this->response);
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
    
    public function createRoom() {
      
      $params = [
        "channelId"=> 1,
        "propertyId"=> "321000",
        "status"=> "New",
        "name"=> "Habitación 1"
      ];
      $this->call('room','POST',$params);
      //EUR
      var_dump($this->response);
      var_dump($this->responseCode);
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
        "channelId"=> 1,
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
    
    /**
     * Send Rates
     * @param type $params
     * @return Error Message
     */
    public function setRates($params) {
      $params = $this->ZConfig->processPriceRates($params);
//      var_dump($params);
      $this->call('rates','POST',$params);
      if (isset($this->response->status)){
        if (isset($this->response->status->returnCode)){
          if ($this->response->status->returnCode == 200){
            return null;
          } else {
            return $this->response->status->returnMessage;
          }
        }
      }
      return 'Algo ha salido mal. Intenta nuevamente';
    }
   
    
    public function getBookings($channelId,$apto) {
      
      
       $params = [
        "channelId"=> $channelId,
        "propertyId"=> $apto,
      ];
      
      $this->call('reservations-summary','GET',$params);
      return $this->response;
      
    }
    
    
    public function createTestReserv($apto) {
      
      
      $params = [
        "channelId"=> 1,
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
    
    
  public function calculateRoomToFastPayment($apto, $start, $finish) {

    $roomSelected = null;
    $allRoomsBySize = \App\Rooms::
                    where('channel_group', $apto)
                    ->where('state', 1)
                    ->where('fast_payment', 1)
                    ->orderBy('order_fast_payment', 'ASC')->get();

    foreach ($allRoomsBySize as $room) {
      $room_id = $room->id;
      if (\App\Book::availDate($start, $finish, $room_id)) {
        return $room_id;
      }
    }

    //search simple Rooms to Booking
    $oRoomsGroup = \App\Rooms::select('id')
                    ->where('channel_group', $apto)
                    ->where('state', 1)
                    ->orderBy('fast_payment', 'ASC')
                    ->orderBy('order_fast_payment', 'ASC')->first();
    if ($oRoomsGroup) {
      return $oRoomsGroup->id;
      return ['isFastPayment' => false, 'id' => $oRoomsGroup->id];
    }

    return -1;
//    return ['isFastPayment' => false, 'id' => -1];
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
  
  
  function reservations_cc($propertyId,$reservationId){
      $params = [
      "channelId" => 1,
      "propertyId" => $propertyId,
      "reservationId" => $reservationId,
      ];
    
      $this->call('reservations-cc','GET',$params,true);
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
    $customer = new \App\Customers();
    $customer->user_id = 23;
    $customer->name = $rCustomer->firstName . ' ' . $rCustomer->lastName;
    $customer->email = $rCustomer->email;
    $customer->phone = $rCustomer->phoneCountryCode . ' ' . $rCustomer->phoneCityArea . ' ' . $rCustomer->phone;
    $customer->DNI = "";
    $customer->save();

    $comment = $this->ZConfig->get_detailRate($reserv['rate_id']);
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
    $book->PVPAgencia = $reserv['comision'];
    $book->total_price = $reserv['totalPrice'];
    $book->external_id = $reserv['reser_id'];
    $book->propertyId = $reserv['propertyId'];
    $book->external_roomId = $reserv['external_roomId'];

    $book->save();
  }
}
