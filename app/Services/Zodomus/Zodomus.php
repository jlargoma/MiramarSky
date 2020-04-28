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
        "channelId"=> 2,
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
        "channelId"=> 2,
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
    public function setRates($params,$channel_group=null) {
      $params = $this->ZConfig->processPriceRates($params,$channel_group);
      $this->call('rates','POST',$params);
//      var_dump($this->response);
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
    
    
  public function calculateRoomToFastPayment($apto, $start, $finish,$roomID = null) {

    $roomSelected = null;
      
    $qry = \App\Rooms::
                    where('channel_group', $apto)
                    ->where('state', 1);
        
    if ($roomID) $qry->where('id',$roomID);

    $allRoomsBySize = $qry->orderBy('fast_payment','DESC')
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
    $book->real_pax = $reserv['numberOfGuests'];
    $book->PVPAgencia = $reserv['comision'];
    $book->total_price = $reserv['totalPrice'];
    $book->external_id = $reserv['reser_id'];
    $book->propertyId = $reserv['propertyId'];
    $book->external_roomId = $reserv['external_roomId'];
    $book->type_park = 0;
    $book->type_luxury = 0;
    
    
    //costes
    $room = \App\Rooms::find($roomID);
    $costes = $room->priceLimpieza($room->sizeApto);
    $book->cost_limp = isset($costes['cost_limp']) ? $costes['cost_limp'] : 0;
    

    $book->cost_lujo = 0;
    if ($room->luxury == 1){
      $book->type_luxury = 1;
      $book->cost_lujo = \App\Settings::priceLujo();
    }
    
    
    $book->cost_park = (\App\Settings::priceParking()*$nights) * $room->num_garage;
    $book->type_park = 1;

    $book->cost_apto = $room->getCostRoom($book->start,$book->finish,$book->pax);
    $book->extraCost  = \App\Extras::find(4)->cost;
    
    $book->cost_total = $book->get_costeTotal();
    
    //save
    $book->save();
    
    
    return $book->id;
  }
  
  function updBooking($cg,$reserv,$bookID){
    
    $book = \App\Book::find($bookID);
    
    $roomID = $this->calculateRoomToFastPayment($cg, $reserv['start'], $reserv['end'],$book->room_id);
    
    if ($roomID<0){
      $roomID = 33;
    }
            
    $nights = calcNights($reserv['start'], $reserv['end']);
    
    $comment = $this->ZConfig->get_detailRate($reserv['rate_id']);
    $book->room_id = $roomID;
    $book->start = $reserv['start'];
    $book->finish = $reserv['end'];
    $book->comment = $book->comment.' - UPD '.date('Y-m-d H:i').' -' .$comment; //$reserv['mealPlan'];
    $book->nigths = $nights;
    $book->pax = $reserv['numberOfGuests'];
    $book->real_pax = $reserv['numberOfGuests'];
    $book->PVPAgencia = $reserv['comision'];
    $book->total_price = $reserv['totalPrice'];
    $book->save();
    
    
    //costes
    $room = \App\Rooms::find($roomID);
    $costes = $room->priceLimpieza($room->sizeApto);
    $book->cost_limp = isset($costes['cost_limp']) ? $costes['cost_limp'] : 0;
    

    $book->cost_lujo = 0;
    if ($room->luxury == 1){
      $book->type_luxury = 1;
      $book->cost_lujo = \App\Settings::priceLujo();
    }
    
    
    $book->cost_park = (\App\Settings::priceParking()*$nights) * $room->num_garage;
    $book->type_park = 1;

    $book->cost_apto = $room->getCostRoom($book->start,$book->finish,$book->pax);
    $book->extraCost  = \App\Extras::find(4)->cost;
    
    $book->cost_total = $book->get_costeTotal();
    
    //save
    $book->save();
            
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
      $startTime = time();
      $finsh =  strtotime('+4 month');
      $day = 24*60*60;
      while ($startTime<$finsh){
        $priceDay[date('Y-m-d',$startTime)] = 700;
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
      
      
     $weekDays = [ 
      "sun"=>true,
      "mon"=>true,
      "tue"=>true,
      "wed"=>true,
      "thu"=>true,
      "fri"=>true,
      "sat"=>true];
        
        
      foreach ($to_send as $v){
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
              var_dump($this->response);
              if ($errorMsg){
                return $errorMsg;
              }
      }
  
      
      
  }
}
